<?php

namespace App\Imports;

use App\Models\User;
use App\Models\UserWallet;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class AdminWalletImport implements ToModel, WithHeadingRow
{
    /**
     * Map each row to a model.
     *
     * @param array $row
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // dd($row);
        // Find the user by user_id in the Users table
        $user = User::where('user_id', $row['user_id'])->first();

        // If the user does not exist, skip the record
        if (!$user) {
            return null; // Optionally handle missing users
        }

        return new UserWallet([
            'user_id' => $user->id, // Use the id from the Users table
            'month' => $row['month'],
            'wallet_amount' => $row['wallet_amount'],
            'trans_type' => $row['payment_mode'],
            'mobilenumber' => $row['mobile_number'],
            
        ]);
    }
}
