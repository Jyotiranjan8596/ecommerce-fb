<?php

namespace App\Http\Controllers;

use App\Models\PosModel;
use Illuminate\Http\Request;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DsrSummaryController extends Controller
{
    public function adminIndex(Request $request): View
    {
        $filters = $request->validate([
            'date' => ['nullable', 'date'],
            'pos_search' => ['nullable', 'string', 'max:100'],
        ]);

        $query = $this->dailySalesSummaryQuery();

        if (! empty($filters['date'])) {
            $query->whereDate('wallets.transaction_date', $filters['date']);
        }

        if (! empty($filters['pos_search'])) {
            $posSearch = trim($filters['pos_search']);
            $query->where(function (Builder $query) use ($posSearch) {
                $query->where('pos_systems.user_id', 'LIKE', "%{$posSearch}%")
                    ->orWhere('pos_systems.mobilenumber', 'LIKE', "%{$posSearch}%");
            });
        }

        return view('shared.dsr-summary-history', [
            'layout' => 'layouts.master',
            'summaries' => $query->paginate(25)->withQueryString()->through(
                fn (object $summary) => $this->calculateSettlementAmounts($summary)
            ),
            'posList' => PosModel::orderBy('name')->get(['id', 'name', 'user_id', 'mobilenumber']),
            'selectedPosSearch' => $filters['pos_search'] ?? '',
            'selectedDate' => $filters['date'] ?? '',
            'isAdmin' => true,
        ]);
    }

    public function posIndex(Request $request): View
    {
        $filters = $request->validate([
            'date' => ['nullable', 'date'],
        ]);

        $pos = PosModel::where('user_id', auth()->user()->user_id)->firstOrFail();
        $query = $this->dailySalesSummaryQuery()->where('wallets.pos_id', $pos->id);

        if (! empty($filters['date'])) {
            $query->whereDate('wallets.transaction_date', $filters['date']);
        }

        return view('shared.dsr-summary-history', [
            'layout' => 'pos.layouts.master',
            'summaries' => $query->paginate(25)->withQueryString()->through(
                fn (object $summary) => $this->calculateSettlementAmounts($summary)
            ),
            'posList' => collect([$pos]),
            'selectedPosId' => $pos->id,
            'selectedDate' => $filters['date'] ?? '',
            'isAdmin' => false,
        ]);
    }

    private function dailySalesSummaryQuery(): Builder
    {
        $generatedSummaries = DB::table('payment_summaries')
            ->join('payments', 'payments.payment_summury_id', '=', 'payment_summaries.id')
            ->where('payments.remark', 'Auto Generated')
            ->select('payment_summaries.pos_id', 'payment_summaries.date')
            ->groupBy('payment_summaries.pos_id', 'payment_summaries.date');

        return DB::table('wallets')
            ->join('pos_systems', 'pos_systems.id', '=', 'wallets.pos_id')
            ->leftJoinSub($generatedSummaries, 'generated_summaries', function ($join) {
                $join->on('generated_summaries.pos_id', '=', 'wallets.pos_id')
                    ->whereRaw('generated_summaries.date = DATE(wallets.transaction_date)');
            })
            ->whereNotNull('wallets.transaction_date')
            ->where('wallets.status', 1)
            ->selectRaw('DATE(wallets.transaction_date) as date')
            ->addSelect([
                'pos_systems.id as pos_id',
                'pos_systems.name as pos_name',
                'pos_systems.user_id as pos_user_id',
                'pos_systems.mobilenumber as pos_mobile',
                'pos_systems.transaction_charge',
            ])
            ->selectRaw('COUNT(wallets.id) as total_transaction')
            ->selectRaw('COALESCE(SUM(wallets.billing_amount), 0) as total_billing_amount')
            ->selectRaw('COALESCE(SUM(wallets.amount), 0) as by_cash')
            ->selectRaw(
                "COALESCE(SUM(CASE WHEN wallets.pay_by = 'wallet' THEN wallets.amount_wallet ELSE 0 END), 0) as by_wallet"
            )
            ->selectRaw(
                "COALESCE(SUM(CASE WHEN wallets.pay_by = 'reward' THEN wallets.reward_amount ELSE 0 END), 0) as by_reward"
            )
            ->selectRaw(
                'MAX(CASE WHEN generated_summaries.pos_id IS NULL THEN 0 ELSE 1 END) as has_generated_payment'
            )
            ->groupByRaw(
                'DATE(wallets.transaction_date), wallets.pos_id, pos_systems.id, pos_systems.name, pos_systems.user_id, pos_systems.mobilenumber, pos_systems.transaction_charge'
            )
            ->orderByDesc('date')
            ->orderByDesc('pos_systems.name')
            ->orderByDesc('pos_systems.id');
    }

    private function calculateSettlementAmounts(object $summary): object
    {
        $settlementCharge = (float) $summary->total_billing_amount
            * ((float) $summary->transaction_charge / 100);
        $walletAndReward = (float) $summary->by_wallet + (float) $summary->by_reward;

        $summary->pos_credit = round(max(0, $walletAndReward - $settlementCharge), 2);
        $summary->pos_debit = round(max(0, $settlementCharge - $walletAndReward), 2);
        $summary->has_generated_payment = (bool) $summary->has_generated_payment;

        return $summary;
    }
}
