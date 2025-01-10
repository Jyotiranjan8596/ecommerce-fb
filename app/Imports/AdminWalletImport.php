<?php

namespace App\Imports;

use App\Models\User;
use App\Models\UserWallet;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;

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
        $monthColumnValue = $row['month']; // Assuming you're reading the row with 'month' column.
        $transaction_date_value = $row['transaction_date'];
        if (is_numeric($monthColumnValue)) {
            $formattedMonth = Date::excelToDateTimeObject($monthColumnValue)->format('M-y');
            
        } else {
            $formattedMonth = $monthColumnValue; // If it's already a string, retain it.
           
        }
        if(is_numeric($transaction_date_value)){
            $formattedDate = Date::excelToDateTimeObject($transaction_date_value)->format('Y-m-d');
        }else{
            $formattedDate = Date::excelToDateTimeObject($transaction_date_value)->format('Y-m-d');
        }
        // Find the user by user_id in the Users table
        $user = User::where('id', $row['user_id'])->first();
        // If the user does not exist, skip the record
        if (!$user) {
            return null; // Optionally handle missing users
        }

        return new UserWallet([
            'user_id' => $user->id, // Use the id from the Users table
            'month' => $formattedMonth,
            'wallet_amount' => $row['wallet_amount'],
            'trans_type' => $row['payment_mode'],
            'mobilenumber' => $row['mobile_number'],
            'transaction_date' => $formattedDate
        ]);
    }
}
