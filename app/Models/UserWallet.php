<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserWallet extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'pos_id',
        'wallet_id',
        'invoice',
        'month',
        'used_amount',
        'wallet_amount',
        'remaining_amount',
        'reward_points',
        'used_points',
        'remaining_points',
        'pay_by',
        'trans_type',
        'transaction_date',
        'mobilenumber',
        'source',
    ];

    public function user_data()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getPos()
    {
        return $this->belongsTo(PosModel::class, 'pos_id');
    }

    public function wallet()
    {
        return $this->belongsTo(Wallet::class, 'wallet_id', 'id');
    }

    public static function getAdminWallet($request)
    {
        return self::adminPaybackQuery($request)
            ->paginate(50)
            ->through(fn ($wallet) => self::formatAdminPaybackRow($wallet));
    }

    public static function getAdminWalletExport($request)
    {
        return self::adminPaybackQuery($request)
            ->get()
            ->map(fn ($wallet) => self::formatAdminPaybackRow($wallet));
    }

    public static function getAdminPaybackHistory($request)
    {
        $query = self::adminPaybackQuery($request);
        self::applyHistoryFilters($query, $request);

        return $query->paginate(50)->through(function ($wallet) {
            $wallet = self::formatAdminPaybackRow($wallet);
            $wallet->history_type = 'payback';
            $wallet->trans_date = $wallet->transaction_date
                ? date('d-m-Y', strtotime($wallet->transaction_date))
                : '';

            return $wallet;
        });
    }

    public static function getAdminReferralHistory($request)
    {
        $monthNameSql = "CASE SUBSTR(transaction_date, 6, 2)
            WHEN '01' THEN 'Jan' WHEN '02' THEN 'Feb' WHEN '03' THEN 'Mar'
            WHEN '04' THEN 'Apr' WHEN '05' THEN 'May' WHEN '06' THEN 'Jun'
            WHEN '07' THEN 'Jul' WHEN '08' THEN 'Aug' WHEN '09' THEN 'Sep'
            WHEN '10' THEN 'Oct' WHEN '11' THEN 'Nov' WHEN '12' THEN 'Dec' END";

        $query = self::query()
            ->whereNull('source')
            ->whereNull('wallet_id')
            ->whereNull('used_amount')
            ->where('wallet_amount', '0')
            ->where('reward_points', '50')
            ->where('trans_type', 'credit')
            ->whereNotNull('transaction_date')
            ->where(function ($query) use ($monthNameSql) {
                $query->where('month', '25-Aug')
                    ->orWhere(function ($query) use ($monthNameSql) {
                        $query->whereRaw("LOWER(SUBSTR(month, 1, 3)) = LOWER(($monthNameSql))")
                            ->whereRaw("CAST(SUBSTR(month, 5) AS INTEGER) = CAST(SUBSTR(transaction_date, 9, 2) AS INTEGER)");
                    })
                    ->orWhere(function ($query) use ($monthNameSql) {
                        $query->whereRaw("LOWER(SUBSTR(month, LENGTH(month) - 2, 3)) = LOWER(($monthNameSql))")
                            ->whereRaw("CAST(SUBSTR(month, 1, INSTR(month, '-') - 1) AS INTEGER) = CAST(SUBSTR(transaction_date, 9, 2) AS INTEGER)");
                    });
            })
            ->with('user_data')
            ->orderByDesc('id');
        self::applyHistoryFilters($query, $request);

        return $query->paginate(50)->through(function ($wallet) {
            $wallet->history_type = 'referral';
            $wallet->trans_date = $wallet->transaction_date
                ? date('d-m-Y', strtotime($wallet->transaction_date))
                : '';

            return $wallet;
        });
    }

    private static function applyHistoryFilters($query, $request): void
    {
        if ($request->filled('history_user_id')) {
            $query->where('user_id', $request->input('history_user_id'));
        }

        if ($request->filled('transaction_month')) {
            $transactionMonth = \Illuminate\Support\Carbon::createFromFormat(
                '!Y-m',
                $request->input('transaction_month')
            );
            $query->whereMonth('transaction_date', $transactionMonth->month)
                ->whereYear('transaction_date', $transactionMonth->year);
        }

        if ($request->filled('user_search')) {
            $search = '%' . $request->input('user_search') . '%';
            $query->whereHas('user_data', function ($userQuery) use ($search) {
                $userQuery->where('user_id', 'LIKE', $search)
                    ->orWhere('mobilenumber', 'LIKE', $search);
            });
        }
    }

    private static function adminPaybackQuery($request)
    {
        $month = $request->input('month');
        $year = $request->input('year');
        $type = $request->input('type', $request->input('trans_type'));
        $payBy = $request->input('pay_by');

        $query = self::where(function ($query) {
            $query->whereIn('source', ['admin_import', 'legacy_admin_import'])
                ->orWhere(function ($query) {
                    $query->whereNull('source')
                        ->whereNull('wallet_id')
                        ->whereNull('used_amount')
                        ->whereNot(function ($query) {
                            $query->where('month', '25-Aug')
                                ->where('trans_type', 'credit')
                                ->where('wallet_amount', '0')
                                ->where('reward_points', '50');
                        })
                        ->where(function ($query) {
                            foreach ([
                                'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
                                'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec',
                            ] as $monthName) {
                                $query->orWhere('month', 'like', '__-' . $monthName);
                            }
                        });
                    $query->whereRaw('SUBSTR(month, 1, 2) = SUBSTR(transaction_date, 3, 2)');
                });
        })
            ->where(function ($query) {
                $query->whereRaw('COALESCE(CAST(wallet_amount AS DECIMAL(15, 4)), 0) <> 0')
                    ->orWhereRaw('COALESCE(CAST(reward_points AS DECIMAL(15, 4)), 0) <> 0');
            })
            ->whereNotNull('transaction_date')
            ->with('user_data')
            ->orderByDesc('id');

        if (is_numeric($month) && (int) $month >= 1 && (int) $month <= 12) {
            self::filterAdminPaybackMonth($query, (int) $month, is_numeric($year) ? (int) $year : null);
        } elseif (is_numeric($year)) {
            self::filterAdminPaybackMonth($query, null, (int) $year);
        }

        if ($type && strtolower(trim($type)) !== 'all') {
            $query->whereRaw('LOWER(TRIM(trans_type)) = ?', [strtolower(trim($type))]);
        }

        if ($payBy === 'wallet') {
            $query->whereRaw('CAST(wallet_amount AS DECIMAL(15, 4)) > 0');
        } elseif ($payBy === 'reward') {
            $query->whereRaw('CAST(reward_points AS DECIMAL(15, 4)) > 0');
        }

        return $query;
    }

    private static function filterAdminPaybackMonth($query, ?int $month, ?int $year): void
    {
        $months = [
            1 => ['Jan', 'January'],
            2 => ['Feb', 'February'],
            3 => ['Mar', 'March'],
            4 => ['Apr', 'April'],
            5 => ['May', 'May'],
            6 => ['Jun', 'June'],
            7 => ['Jul', 'July'],
            8 => ['Aug', 'August'],
            9 => ['Sep', 'September'],
            10 => ['Oct', 'October'],
            11 => ['Nov', 'November'],
            12 => ['Dec', 'December'],
        ];
        $selectedMonths = $month === null ? array_keys($months) : [$month];
        $shortYear = $year === null ? null : substr((string) $year, -2);
        $numericMonth = $month === null ? null : str_pad((string) $month, 2, '0', STR_PAD_LEFT);

        $query->where(function ($query) use ($months, $selectedMonths, $shortYear, $year, $numericMonth) {
            foreach ($selectedMonths as $monthNumber) {
                [$abbreviated, $full] = $months[$monthNumber];
                if ($shortYear === null) {
                    $patterns = [
                        $abbreviated . '-%',
                        '%-' . $abbreviated,
                        $full . ',%',
                        $full . ' %',
                        '%-' . $full,
                    ];
                } else {
                    $patterns = [
                        $abbreviated . '-' . $shortYear,
                        $full . ',' . $shortYear,
                        $full . ' ' . $year,
                        $year . '-' . $numericMonth . '%',
                    ];
                }

                foreach ($patterns as $pattern) {
                    $query->orWhere('month', 'like', $pattern);
                }

                if ($shortYear !== null) {
                    $query->orWhere(function ($query) use ($abbreviated, $shortYear, $year) {
                        $query->where('month', $shortYear . '-' . $abbreviated)
                            ->whereYear('transaction_date', $year);
                    });

                    $query->orWhere(function ($query) use ($abbreviated, $monthNumber, $year) {
                        $query->where('month', 'like', '__-' . $abbreviated)
                            ->whereMonth('transaction_date', $monthNumber)
                            ->whereYear('transaction_date', $year);
                    });
                }
            }
        });
    }

    private static function formatAdminPaybackRow(self $wallet): self
    {
        $walletAmount = (float) ($wallet->wallet_amount ?? 0);
        $rewardPoints = (float) ($wallet->reward_points ?? 0);

        $wallet->rounded_wallet_amount = ($walletAmount - floor($walletAmount)) > 0.3
            ? ceil($walletAmount)
            : floor($walletAmount);
        $wallet->rounded_reward_point = ($rewardPoints - floor($rewardPoints)) > 0.3
            ? ceil($rewardPoints)
            : floor($rewardPoints);
        $wallet->display_month = self::formatAdminPaybackMonth($wallet->month, $wallet->transaction_date);

        return $wallet;
    }

    private static function formatAdminPaybackMonth(?string $month, ?string $transactionDate): string
    {
        $month = trim((string) $month);
        if (
            preg_match('/^\d{1,2}-[A-Za-z]{3}$/', $month)
            && $transactionDate
        ) {
            $dayMonth = \DateTime::createFromFormat('!d-M', $month);
            $date = new \DateTimeImmutable($transactionDate);
            if (
                $dayMonth !== false
                && $dayMonth->format('m-d') === $date->format('m-d')
            ) {
                return $date->format('F,y');
            }
        }

        foreach (['!M-y', '!F,y', '!y-M', '!F Y', '!M Y', '!Y-m', '!m/Y', '!Y-m-d'] as $format) {
            $date = \DateTime::createFromFormat($format, $month);
            if ($date !== false) {
                return $date->format('F,y');
            }
        }

        return $month;
    }
}
