<?php

namespace App\Services;

use App\Models\UserWallet;

class UserWalletBalanceService
{
    /**
     * @return array{wallet_balance: float, rewardBalance: float}
     */
    public function getBalancesForUser(int $userId, ?int $additionalUserId = null): array
    {
        $walletTotals = UserWallet::query()
            ->where(function ($query) use ($userId, $additionalUserId) {
                $query->where('user_id', $userId);

                if ($additionalUserId !== null) {
                    $query->orWhere('user_id', $additionalUserId);
                }
            })
            ->selectRaw('
                COALESCE(SUM(wallet_amount), 0) as total_wallet_amount,
                COALESCE(SUM(used_amount), 0) as total_used_amount,
                COALESCE(SUM(reward_points), 0) as total_reward_points,
                COALESCE(SUM(used_points), 0) as total_used_points
            ')
            ->first();

        $walletBalance = (float) $walletTotals->total_wallet_amount - (float) $walletTotals->total_used_amount;
        $rewardBalance = (float) $walletTotals->total_reward_points - (float) $walletTotals->total_used_points;

        return [
            'wallet_balance' => max($walletBalance, 0),
            'rewardBalance' => max($rewardBalance, 0),
        ];
    }
}
