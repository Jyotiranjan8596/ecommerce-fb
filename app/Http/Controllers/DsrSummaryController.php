<?php

namespace App\Http\Controllers;

use App\Models\PosModel;
use App\Models\TransactionStatusChange;
use App\Models\Wallet;
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

        $summaries = $query->paginate(25)->withQueryString()->through(
            fn (object $summary) => $this->calculateSettlementAmounts($summary)
        );
        $this->attachLatestTransactionStatusActors($summaries->getCollection());

        return view('shared.dsr-summary-history', [
            'layout' => 'layouts.master',
            'summaries' => $summaries,
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

        $summaries = $query->paginate(25)->withQueryString()->through(
            fn (object $summary) => $this->calculateSettlementAmounts($summary)
        );
        $this->attachLatestTransactionStatusActors($summaries->getCollection());

        return view('shared.dsr-summary-history', [
            'layout' => 'pos.layouts.master',
            'summaries' => $summaries,
            'posList' => collect([$pos]),
            'selectedPosId' => $pos->id,
            'selectedDate' => $filters['date'] ?? '',
            'isAdmin' => false,
        ]);
    }

    private function dailySalesSummaryQuery(): Builder
    {
        $paymentToColumn = DB::getQueryGrammar()->wrap('payments.to');
        $summaryActivity = DB::table('payment_summaries')
            ->join('payments', 'payments.payment_summury_id', '=', 'payment_summaries.id')
            ->select([
                'payment_summaries.pos_id',
                'payment_summaries.date',
            ])
            ->selectRaw("MAX(CASE WHEN payments.remark = 'Auto Generated' THEN 1 ELSE 0 END) as has_generated_payment")
            ->selectRaw("SUM(CASE WHEN payments.remark = 'Auto Submitted' THEN payments.amount ELSE 0 END) as auto_submitted_amount")
            ->selectRaw("SUM(CASE WHEN (payments.remark IS NULL OR payments.remark != 'Auto Generated') AND {$paymentToColumn} = '666666' THEN payments.amount ELSE 0 END) as pos_payment_amount")
            ->selectRaw("MAX(CASE WHEN payment_summaries.status = 'approved' THEN 1 ELSE 0 END) as is_settled")
            ->selectRaw('MAX(payment_summaries.admin_credit) as admin_credit')
            ->groupBy('payment_summaries.pos_id', 'payment_summaries.date');

        return DB::table('wallets')
            ->join('pos_systems', 'pos_systems.id', '=', 'wallets.pos_id')
            ->leftJoinSub($summaryActivity, 'summary_activity', function ($join) {
                $join->on('summary_activity.pos_id', '=', 'wallets.pos_id')
                    ->whereRaw('summary_activity.date = DATE(wallets.transaction_date)');
            })
            ->whereNotNull('wallets.transaction_date')
            ->selectRaw('DATE(wallets.transaction_date) as date')
            ->addSelect([
                'pos_systems.id as pos_id',
                'pos_systems.name as pos_name',
                'pos_systems.user_id as pos_user_id',
                'pos_systems.mobilenumber as pos_mobile',
                'pos_systems.transaction_charge',
                'summary_activity.has_generated_payment',
                'summary_activity.auto_submitted_amount',
                'summary_activity.pos_payment_amount',
                'summary_activity.is_settled',
                'summary_activity.admin_credit',
            ])
            ->selectRaw('COUNT(wallets.id) as all_transaction_count')
            ->selectRaw('SUM(CASE WHEN wallets.status = 1 THEN 1 ELSE 0 END) as total_transaction')
            ->selectRaw('SUM(CASE WHEN wallets.status = 1 THEN 0 ELSE 1 END) as pending_transaction_count')
            ->selectRaw('COALESCE(SUM(CASE WHEN wallets.status = 1 THEN wallets.billing_amount ELSE 0 END), 0) as total_billing_amount')
            ->selectRaw('COALESCE(SUM(CASE WHEN wallets.status = 1 THEN wallets.amount ELSE 0 END), 0) as by_cash')
            ->selectRaw(
                "COALESCE(SUM(CASE WHEN wallets.status = 1 AND wallets.pay_by = 'wallet' THEN wallets.amount_wallet ELSE 0 END), 0) as by_wallet"
            )
            ->selectRaw(
                "COALESCE(SUM(CASE WHEN wallets.status = 1 AND wallets.pay_by = 'reward' THEN wallets.reward_amount ELSE 0 END), 0) as by_reward"
            )
            ->groupByRaw(
                'DATE(wallets.transaction_date), wallets.pos_id, pos_systems.id, pos_systems.name, pos_systems.user_id, pos_systems.mobilenumber, pos_systems.transaction_charge, summary_activity.has_generated_payment, summary_activity.auto_submitted_amount, summary_activity.pos_payment_amount, summary_activity.is_settled, summary_activity.admin_credit'
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

    private function attachLatestTransactionStatusActors($summaries): void
    {
        if ($summaries->isEmpty()) {
            return;
        }

        $latestIds = DB::table('transaction_status_changes as history')
            ->join('wallets', function ($join) {
                $join->on('wallets.id', '=', 'history.subject_id')
                    ->where('history.subject_type', Wallet::class);
            })
            ->whereIn('history.event_type', ['created', 'status_changed'])
            ->where(function (Builder $query) use ($summaries) {
                foreach ($summaries as $summary) {
                    $query->orWhere(function (Builder $pair) use ($summary) {
                        $pair->where('wallets.pos_id', $summary->pos_id)
                            ->whereDate('wallets.transaction_date', $summary->date);
                    });
                }
            })
            ->selectRaw('MAX(history.id) as id')
            ->groupBy('wallets.pos_id')
            ->groupByRaw('DATE(wallets.transaction_date)');

        $changes = DB::table('transaction_status_changes as changes')
            ->joinSub($latestIds, 'latest', fn ($join) => $join->on('latest.id', '=', 'changes.id'))
            ->join('wallets', function ($join) {
                $join->on('wallets.id', '=', 'changes.subject_id')
                    ->where('changes.subject_type', Wallet::class);
            })
            ->select([
                'wallets.pos_id',
                'changes.actor_type',
                'changes.actor_name',
                'changes.created_at',
            ])
            ->selectRaw('DATE(wallets.transaction_date) as transaction_date')
            ->get()
            ->keyBy(fn ($change) => $change->pos_id . '|' . $change->transaction_date);

        foreach ($summaries as $summary) {
            $change = $changes->get($summary->pos_id . '|' . $summary->date);
            $summary->latest_transaction_status_actor = $change
                ? match ($change->actor_type) {
                    'admin' => 'Admin',
                    'pos' => 'POS',
                    'customer' => 'Customer',
                    'system' => 'Automatic system',
                    default => 'User',
                } . ($change->actor_name ? ' (' . $change->actor_name . ')' : '')
                : null;
            $summary->latest_transaction_status_at = $change?->created_at;
        }
    }
}
