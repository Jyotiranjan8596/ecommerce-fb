<?php

namespace App\Imports;

use App\Models\User;
use App\Models\UserWallet;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class AdminUsedWalletImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // dd($row);
        $monthColumnValue = $row['date'];
        $mobile_no = $row['mobile_no']; // Assuming you're reading the row with 'month' column.
        if (is_numeric($monthColumnValue)) {
            $formattedMonth = Date::excelToDateTimeObject($monthColumnValue)->format('M-y');
        } else {
            $formattedMonth = $monthColumnValue; // If it's already a string, retain it.
        }
        // Find the user by user_id in the Users table
        $user = User::where('mobilenumber', $mobile_no)->first();
        // If the user does not exist, skip the record
        if (!$user) {
            return null; // Optionally handle missing users
        }
        if ($row['paid_by_1'] == "FB Walet" || $row['paid_by_2'] == "FB Wallet") {
            if ($row['paid_by_1'] == "FB Walet") {
                return new UserWallet([
                    'user_id' => $user->id, // Use the id from the Users table
                    'month' => $formattedMonth,
                    'used_amount' => $row['amount_paid_by_1'],
                    'trans_type' => 'debit',
                    'mobilenumber' => $mobile_no,
                ]);
            } elseif ($row['paid_by_2'] == "FB Wallet") {
                return new UserWallet([
                    'user_id' => $user->id, // Use the id from the Users table
                    'month' => $formattedMonth,
                    'used_amount' => $row['amount_paid_by_2'],
                    'trans_type' => 'debit',
                    'mobilenumber' => $mobile_no,
                ]);
            }
        }
    }
}
