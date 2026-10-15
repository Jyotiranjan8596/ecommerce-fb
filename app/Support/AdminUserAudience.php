<?php

namespace App\Support;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

class AdminUserAudience
{
    public static function query(string $category): Builder
    {
        $query = User::query();
        $primeCustomerIds = User::query()
            ->select('sponsor_id')
            ->where('role', 3)
            ->whereNotNull('sponsor_id')
            ->groupBy('sponsor_id')
            ->havingRaw('COUNT(*) >= ?', [User::PRIME_REFERRAL_THRESHOLD]);

        return match ($category) {
            'pos' => $query->where('role', 4),
            'prime' => $query->where('role', 3)->whereIn('id', $primeCustomerIds),
            'non_prime' => $query->where('role', 3)->whereNotIn('id', $primeCustomerIds),
            default => $query->whereIn('role', [3, 4]),
        };
    }

    public static function label(string $category): string
    {
        return match ($category) {
            'pos' => 'POS users',
            'prime' => 'Prime customers',
            'non_prime' => 'non-Prime customers',
            default => 'POS users and customers',
        };
    }
}
