<?php

namespace App\Http\Controllers\Admin;

use App\Exports\MsrExport;
use App\Exports\MsrTransactionExport;
use App\Exports\DsrTransactionExport;
use App\Exports\WalletExport;
use App\Http\Controllers\Controller;
use App\Imports\WalletImport;
use App\Models\PosModel;
use App\Models\sponcer;
use App\Models\Sponsor;
use App\Models\Wallet;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class DsrController extends Controller
{
    public function dsr(Request $request)
    {
        $request->validate([
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date'],
            'search' => ['nullable', 'string', 'max:100'],
        ]);
        if ($request->filled('start_date') && $request->filled('end_date')
            && Carbon::parse($request->end_date)->lt(Carbon::parse($request->start_date))) {
            return back()->withErrors(['end_date' => 'The end date must be the same as or after the start date.'])->withInput();
        }

        $user_profile = auth()->user();
        $userId       = $user_profile->id;
        $query = Wallet::with('getPos')
            ->selectRaw('
                pos_id,
                DATE(transaction_date) as transaction_date,
                SUM(billing_amount) as total_billing_amount,
                COUNT(id) as total_transactions
            ')
            ->groupBy('pos_id', DB::raw('DATE(transaction_date)'));
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        if ($startDate && $endDate) {
            $query->whereBetween('transaction_date', [Carbon::parse($startDate)->startOfDay(), Carbon::parse($endDate)->endOfDay()]);
        } elseif ($startDate) {
            $query->whereDate('transaction_date', '>=', $startDate);
        } elseif ($endDate) {
            $query->whereDate('transaction_date', '<=', $endDate);
        } else {
            $query->whereDate('transaction_date', today());

            if ($query->count() == 0) {
                $previousDate = DB::table('wallets')
                    ->whereDate('insert_date', '<', now()->toDateString())
                    ->max('insert_date');

                if ($previousDate) {
                    $query->whereDate('insert_date', $previousDate);
                }
            }
        }
        if ($request->filled('search')) {
            $this->applyDsrPosSearch($query, $request->input('search'));
        }
        $summary = (clone $query)->get();

        $totalTransactions = $summary->sum('total_transactions');
        $totalBillingAmount = $summary->sum('total_billing_amount');
        $totalPos = $summary->pluck('pos_id')->unique()->count();
        $wallets = $query->with('user', 'getPos')->orderBy('transaction_date', 'desc')->orderBy('pos_id')
            ->simplePaginate(15)
            ->through(function ($wallet) {
                $chargeRate = (float) ($wallet->getPos->transaction_charge ?? 0);
                $wallet->total_transaction_charges =
                    (float) $wallet->total_billing_amount * $chargeRate / 100;

                return $wallet;
            });
        // dd($wallets);
        $wallets->appends($request->only(['search', 'start_date', 'end_date']));

        return view('admin.dsr.index', compact('totalBillingAmount', 'totalTransactions', 'totalPos', 'wallets', 'userId', 'user_profile'));
    }

    public function transaction_details(Request $request, $id)
    {
        // dd($request->all());
        $user_profile = auth()->user();
        $userId       = $user_profile->id;
        $id = decrypt($id);
        $pos = PosModel::findOrFail($id);
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $transaction_date = $request->transaction_date;
        $query =  Wallet::where('pos_id', $id)->with('user', 'userWallets');
        // if (!empty($start_date) && !empty($end_date)) {
        //     $startDate = Carbon::parse($request->start_date)->startOfDay();
        //     $endDate = Carbon::parse($request->end_date)->endOfDay();
        //     $query->whereBetween('transaction_date', [$startDate, $endDate]);
        // } else {
        $query->whereDate('transaction_date', $transaction_date);

        if ($query->count() == 0) {
            $previousDate = DB::table('wallets')
                ->whereDate('insert_date', '<', now()->toDateString())
                ->max('insert_date');

            if ($previousDate) {
                $query->whereDate('insert_date', $previousDate);
            }
        }
        // }

        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
            $query->where('mobilenumber', 'LIKE', "%{$searchTerm}%")->orWhereHas('user', function ($qry) use ($searchTerm) {
                $qry->where('name', 'LIKE', "%{$searchTerm}%");
            });
        }

        $wallets = $query->with('user', 'getPos')->orderBy('id', 'desc')
            ->simplePaginate(15);
        // dd($wallets);
        $wallets->appends($request->only(['search', 'start_date', 'end_date']));

        return view('admin.dsr.indivisualDsr', compact('wallets', 'userId', 'user_profile', 'id', 'pos', 'start_date', 'end_date', 'transaction_date'));
    }

    public function exportTransactionDetails(Request $request, $id)
    {
        $validated = $request->validate([
            'transaction_date' => ['required', 'date'],
        ]);

        $posId = decrypt($id);
        $pos = PosModel::findOrFail($posId);
        $transactionDate = Carbon::parse($validated['transaction_date'])->toDateString();
        $transactions = Wallet::query()
            ->where('pos_id', $pos->id)
            ->whereDate('transaction_date', $transactionDate)
            ->with(['user', 'getPos', 'userWallets'])
            ->orderByDesc('id')
            ->get();

        $filename = 'DSR_' . Str::slug($pos->user_id ?: (string) $pos->id, '_')
            . '_' . Carbon::parse($transactionDate)->format('d,m,Y') . '.xlsx';

        return Excel::download(new DsrTransactionExport($transactions), $filename);
    }

    public function update_dsr(Request $request)
    {
        try {
            // dd($request->all());
            $wallet = Wallet::where('id', $request->wallet_id)->first();
            $billing_amount = $request->billing_amount;
            $transaction_date = $request->transaction_date;
            if ($billing_amount) {
                $wallet->billing_amount = $billing_amount;
            }
            if ($transaction_date) {
                $wallet->transaction_date = date('Y-m-d', strtotime($transaction_date));
            }
            $res = $wallet->save();
            if ($res) {
                return response()->json([
                    "message" => "Update Successful",
                    "code" => 200
                ]);
            } else {
                return response()->json([
                    "message" => "Update Failed",
                    "code" => 500
                ], 200);
            }
        } catch (\Exception $e) {
            Log::error('Update DSR error: ' . $e->getMessage());
            return response()->json([
                "message" => $e->getMessage(),
                "code" => 500
            ], 200);
        }
    }

    public function delete_dsr(Request $request)
    {
        try {
            $wallet = Wallet::where('id', $request->wallet_id)->first();
            if (!$wallet) {
                return response()->json([
                    "message" => "Wallet record not found.",
                    "code" => 404
                ], 404);
            }

            $res = $wallet->delete();
            if ($res) {
                return response()->json([
                    "message" => "DSR deleted successfully.",
                    "code" => 200
                ], 200);
            } else {
                return response()->json([
                    "message" => "Failed",
                    "code" => 500
                ], 200);
            }
        } catch (\Exception $e) {
            Log::error('Update DSR error: ' . $e->getMessage());
            return response()->json([
                "message" => $e->getMessage(),
                "code" => 500
            ], 200);
        }
    }
    public function export(Request $request)
    {
        $validated = $request->validate([
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'search' => ['nullable', 'string', 'max:100'],
        ]);

        $startDate = $validated['start_date'] ?? null;
        $endDate = $validated['end_date'] ?? null;
        if (!$startDate && !$endDate) {
            $startDate = today()->toDateString();
            $endDate = $startDate;
        }

        $query = Wallet::query()->with(['user', 'getPos', 'userWallets']);
        if ($startDate && $endDate) {
            $query->whereBetween('transaction_date', [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay(),
            ]);
        } elseif ($startDate) {
            $query->whereDate('transaction_date', '>=', $startDate);
        } elseif ($endDate) {
            $query->whereDate('transaction_date', '<=', $endDate);
        }

        $posIds = collect();
        if (!empty($validated['search'])) {
            $searchTerm = $validated['search'];
            $this->applyDsrPosSearch($query, $searchTerm);

            $matchingPosIds = PosModel::query()
                ->where(function ($posQuery) use ($searchTerm) {
                    $posQuery->where('name', 'LIKE', "%{$searchTerm}%")
                        ->orWhere('mobilenumber', 'LIKE', "%{$searchTerm}%")
                        ->orWhere('user_id', 'LIKE', "%{$searchTerm}%")
                        ->orWhere('pos_code', 'LIKE', "%{$searchTerm}%")
                        ->orWhereHas('user', function ($posUserQuery) use ($searchTerm) {
                            $posUserQuery->where('user_id', 'LIKE', "%{$searchTerm}%")
                                ->orWhere('mobilenumber', 'LIKE', "%{$searchTerm}%");
                        });
                })
                ->distinct()
                ->pluck('user_id');
            $posIds = $matchingPosIds->filter()->unique()->values();
        }

        $transactions = $query->orderByDesc('transaction_date')->orderByDesc('id')->get();
        $posId = match ($posIds->count()) {
            0 => empty($validated['search']) ? 'All_POS' : 'Multiple_POS',
            1 => Str::slug($posIds->first(), '_'),
            default => 'Multiple_POS',
        };
        $formattedStartDate = $startDate ? Carbon::parse($startDate)->format('d,m,Y') : 'Start';
        $formattedEndDate = $endDate ? Carbon::parse($endDate)->format('d,m,Y') : 'End';
        $rangeLabel = $startDate === $endDate
            ? $formattedStartDate
            : $formattedStartDate . '_to_' . $formattedEndDate;
        $filename = 'DSR_' . $posId . '_' . $rangeLabel . '.xlsx';

        return Excel::download(new DsrTransactionExport($transactions), $filename);
    }

    private function applyDsrPosSearch($query, string $searchTerm): void
    {
        $query->whereHas('getPos', function ($posQuery) use ($searchTerm) {
            $posQuery->where('name', 'LIKE', "%{$searchTerm}%")
                ->orWhere('mobilenumber', 'LIKE', "%{$searchTerm}%")
                ->orWhere('user_id', 'LIKE', "%{$searchTerm}%")
                ->orWhere('pos_code', 'LIKE', "%{$searchTerm}%")
                ->orWhereHas('user', function ($posUserQuery) use ($searchTerm) {
                    $posUserQuery->where('user_id', 'LIKE', "%{$searchTerm}%")
                        ->orWhere('mobilenumber', 'LIKE', "%{$searchTerm}%");
                });
        });
    }

    public function userTransactionExport(Request $request)
    {
        // dd($request->all());
        $startDate = $request->has('start_date') && !empty($request->start_date) ? $request->start_date : null;
        $endDate = $request->has('end_date') && !empty($request->end_date) ? $request->end_date : null;
        $pos_id = $request->pos_id;
        return Excel::download(new WalletExport($startDate, $endDate, $pos_id), 'UserDailySalesReport_' . now()->format('d,m,y') . '.csv', \Maatwebsite\Excel\Excel::CSV);
    }
    public function import(Request $request)
    {
        try {
            $request->validate([
                'file' => 'required',
            ]);

            DB::beginTransaction();
            Excel::import(new WalletImport, $request->file('file'));
            DB::commit();
            return redirect()->back()->with('success', 'DSR File Uploaded Successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
    public function msr(Request $request)
    {
        $user_profile = auth()->user();
        $userId       = $user_profile->id;

        $pos = PosModel::with('user')->get()->map(function ($item) {
            $data = $item->toArray();
            $data['pos_id'] = $item->user ? $item->user->user_id : null;
            unset($data['user']);
            return $data;
        });
        $defaultMonth = Carbon::now()->subMonth()->format('Y-m'); // Previous month
        $selectedMonth = $request->input('month', $defaultMonth);
        $monthlySales = self::msrCalculation($request);
        $monthlySales->appends($request->only(['search', 'month', 'filter']));

        return view('admin.msr.index', compact('pos', 'monthlySales', 'selectedMonth', 'userId', 'user_profile'));
    }

    public function newMsr(Request $request)
    {
        $validated = $request->validate([
            'month' => ['nullable', 'date_format:Y-m'],
        ]);

        $selectedMonth = $validated['month'] ?? now()->format('Y-m');
        $monthStart = Carbon::createFromFormat('Y-m', $selectedMonth)->startOfMonth();
        $monthEnd = $monthStart->copy()->endOfMonth();
        $monthSales = static function ($query) use ($monthStart, $monthEnd) {
            $query->whereBetween('transaction_date', [$monthStart, $monthEnd]);
        };

        $posSales = PosModel::query()
            ->withSum(['wallet as total_billing_amount' => $monthSales], 'billing_amount')
            ->withCount(['wallet as total_transactions' => $monthSales])
            ->orderBy('name')
            ->simplePaginate(15)
            ->appends(['month' => $selectedMonth]);

        return view('admin.msr.new-index', compact('posSales', 'selectedMonth'));
    }

    public function newMsrDetails(Request $request, PosModel $pos)
    {
        $validated = $request->validate([
            'month' => ['nullable', 'date_format:Y-m'],
        ]);

        $selectedMonth = $validated['month'] ?? now()->format('Y-m');
        $monthStart = Carbon::createFromFormat('Y-m', $selectedMonth)->startOfMonth();
        $monthEnd = $monthStart->copy()->endOfMonth();
        $transactions = Wallet::query()
            ->where('pos_id', $pos->id)
            ->whereBetween('transaction_date', [$monthStart, $monthEnd])
            ->with('user')
            ->orderByDesc('transaction_date')
            ->orderByDesc('id')
            ->simplePaginate(15)
            ->appends(['month' => $selectedMonth]);

        return view('admin.msr.new-details', compact('pos', 'transactions', 'selectedMonth'));
    }

    public function exportNewMsrDetails(Request $request, PosModel $pos)
    {
        $validated = $request->validate([
            'month' => ['nullable', 'date_format:Y-m'],
        ]);

        $selectedMonth = $validated['month'] ?? now()->format('Y-m');
        $monthStart = Carbon::createFromFormat('Y-m', $selectedMonth)->startOfMonth();
        $monthEnd = $monthStart->copy()->endOfMonth();
        $transactions = Wallet::query()
            ->where('pos_id', $pos->id)
            ->whereBetween('transaction_date', [$monthStart, $monthEnd])
            ->with('user')
            ->orderByDesc('transaction_date')
            ->orderByDesc('id')
            ->get();

        $posIdentifier = $pos->user_id ?: $pos->id;
        $filename = 'MSR_' . Str::slug((string) $posIdentifier, '_') . '_'
            . Carbon::createFromFormat('Y-m', $selectedMonth)->format('m_Y') . '.xlsx';

        return Excel::download(new MsrTransactionExport($transactions), $filename);
    }

    public function msrCalculation($request)
    {
        $defaultMonth = Carbon::now()->subMonth()->format('Y-m'); // Previous month
        $selectedMonth = $request->input('month', $defaultMonth);

        $query = Wallet::select(
            'user_id',
            'mobilenumber',
            DB::raw('DATE_FORMAT(transaction_date, "%Y-%m") as transaction_month'),
            DB::raw('SUM(billing_amount) as total_billing_amount')
        )
            ->whereNotNull(['transaction_date', 'user_id'])
            ->whereRaw('DATE_FORMAT(transaction_date, "%Y-%m") = ?', [$selectedMonth])
            ->groupBy(['user_id', 'mobilenumber', DB::raw('DATE_FORMAT(transaction_date, "%Y-%m")')]);

        if ($request->filled('search')) {
            $posId = $request->input('search');
            $query->whereHas('getPos', function ($q) use ($posId) {
                $q->where('id', $posId);
            });
        }

        if ($request->filled('filter')) {
            $mobile = $request->input('filter');
            $query->where('mobilenumber', 'like', "%{$mobile}%");
        }

        $monthlySales = $query->orderBy('id', 'desc')->simplePaginate(15)->through(function ($item) use ($request) {
            $billing_amount = 0;

            Log::info($item->total_billing_amount);
            $check_sponsor = Sponsor::with('user')->where('sponsor_id', $item->user_id)->get();
            if (!$check_sponsor->isEmpty()) {
                $check_sponsor->each(function ($sponsor) use (&$billing_amount, &$item, $request) {
                    $month = $request->input('month', Carbon::now()->subMonth()->format('Y-m'));
                    $monthlyBilling = Wallet::select(
                        DB::raw('DATE_FORMAT(transaction_date, "%Y-%m") as transaction_month'),
                        DB::raw('SUM(billing_amount) as total_billing_amount')
                    )
                        ->where('user_id', $sponsor->user_id)
                        ->whereRaw('DATE_FORMAT(transaction_date, "%Y-%m") = ?', [$month])
                        ->groupBy(DB::raw('DATE_FORMAT(transaction_date, "%Y-%m")'))
                        ->get();
                    // dd($monthlyBilling );
                    foreach ($monthlyBilling as $monthData) {
                        $billing_amount += $monthData->total_billing_amount;
                    }
                    $check_level_sponsor = Sponsor::with('user')->where('sponsor_id', $sponsor->user_id)->get();
                    if (!$check_level_sponsor->isEmpty()) {
                        $check_level_sponsor->each(function ($level_sponsor) use (&$billing_amount, &$month, &$item, $request) {
                            $monthly_level_Billing = Wallet::select(
                                DB::raw('DATE_FORMAT(transaction_date, "%Y-%m") as transaction_month'),
                                DB::raw('SUM(billing_amount) as total_billing_amount')
                            )
                                ->where('user_id', $level_sponsor->user_id)
                                ->whereRaw('DATE_FORMAT(transaction_date, "%Y-%m") = ?', [$month])
                                ->groupBy(DB::raw('DATE_FORMAT(transaction_date, "%Y-%m")'))
                                ->get();
                            // dd($monthlyBilling );
                            foreach ($monthly_level_Billing as $monthData) {
                                $billing_amount += $monthData->total_billing_amount;
                            }
                        });
                    }
                });
                // $billing_amount += $item->total_billing_amount;
            }
            // Log::info($monthlySales);
            $item->sponsor_expenditure = $billing_amount;
            return $item;
        });
        return $monthlySales;
    }
    public function exportMsr(Request $request)
    {
        $defaultMonth = Carbon::now()->subMonth()->format('Y-m'); // Previous month
        $selectedMonth = $request->input('month', $defaultMonth);
        $query = Wallet::select(
            'user_id',
            'mobilenumber',
            DB::raw('DATE_FORMAT(transaction_date, "%Y-%m") as transaction_month'),
            DB::raw('SUM(billing_amount) as total_billing_amount')
        )
            ->whereNotNull(['transaction_date', 'user_id'])
            ->groupBy(['user_id', 'mobilenumber', DB::raw('DATE_FORMAT(transaction_date, "%Y-%m")')]);


        // if ($request->has('search') && !empty($request->search)) {
        //     $userId = $request->search;
        //     $query->where('pos_id', $userId);
        // }
        if ($request->has('month') && !empty($request->month)) {
            $selectedMonth = $request->month;
            $query->whereRaw('DATE_FORMAT(transaction_date, "%Y-%m") = ?', [$selectedMonth]);
        }
        if ($request->filled('search')) {
            $posId = $request->input('search');
            $query->whereHas('getPos', function ($q) use ($posId) {
                $q->where('id', $posId);
            });
        }

        if ($request->filled('filter')) {
            $mobile = $request->input('filter');
            $query->where('mobilenumber', 'like', "%{$mobile}%");
        }

        // $filteredData = $query->orderBy('id', 'desc')->get();
        $dataWithSponsorExpenditure = $query->orderBy('id', 'desc')->get()->map(function ($item) use ($request) {
            $billing_amount = 0;
            $user_billing_amount = Wallet::where('user_id', $item->user_id)->sum('billing_amount');
            if ($user_billing_amount >= 500) {
                // Log::info($item->total_billing_amount);
                $check_sponsor = Sponsor::with('user')->where('sponsor_id', $item->user_id)->get();
                if (!$check_sponsor->isEmpty()) {
                    $check_sponsor->each(function ($sponsor) use (&$billing_amount, &$item, $request) {
                        $month = $request->input('month', Carbon::now()->subMonth()->format('Y-m'));
                        $monthlyBilling = Wallet::select(
                            DB::raw('DATE_FORMAT(transaction_date, "%Y-%m") as transaction_month'),
                            DB::raw('SUM(billing_amount) as total_billing_amount')
                        )
                            ->where('user_id', $sponsor->user_id)
                            ->whereRaw('DATE_FORMAT(transaction_date, "%Y-%m") = ?', [$month])
                            ->groupBy(DB::raw('DATE_FORMAT(transaction_date, "%Y-%m")'))
                            ->get();
                        // dd($monthlyBilling );
                        foreach ($monthlyBilling as $monthData) {
                            $billing_amount += $monthData->total_billing_amount;
                        }
                        $check_level_sponsor = Sponsor::with('user')->where('sponsor_id', $sponsor->user_id)->get();
                        if (!$check_level_sponsor->isEmpty()) {
                            $check_level_sponsor->each(function ($level_sponsor) use (&$billing_amount, &$month, &$item, $request) {
                                $monthly_level_Billing = Wallet::select(
                                    DB::raw('DATE_FORMAT(transaction_date, "%Y-%m") as transaction_month'),
                                    DB::raw('SUM(billing_amount) as total_billing_amount')
                                )
                                    ->where('user_id', $level_sponsor->user_id)
                                    ->whereRaw('DATE_FORMAT(transaction_date, "%Y-%m") = ?', [$month])
                                    ->groupBy(DB::raw('DATE_FORMAT(transaction_date, "%Y-%m")'))
                                    ->get();
                                // dd($monthlyBilling );
                                foreach ($monthly_level_Billing as $monthData) {
                                    $billing_amount += $monthData->total_billing_amount;
                                }
                                Log::info('Billing amount - ' . $billing_amount);
                            });
                        }
                    });
                    // $billing_amount += $item->total_billing_amount;
                }
            }
            // Log::info($monthlySales);
            $item->sponsor_expenditure = $billing_amount;
            return $item;
        });

        // Log::info($dataWithSponsorExpenditure->toArray());
        return Excel::download(new MsrExport($dataWithSponsorExpenditure), 'MonthlySalesReport_' . now()->format('d,m,y') . '.csv');
    }
}
