<?php

namespace App\Imports;

use App\Models\User;
use App\Models\UserWallet;
use Carbon\Carbon;
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
        if (empty($row['transaction_date'])) {
            throw new \InvalidArgumentException('Each payback row must include a transaction date.');
        }

        $rewardPointsKey = array_key_exists('reward_point', $row)
            ? 'reward_point'
            : (array_key_exists('reward_points', $row) ? 'reward_points' : null);

        if ($rewardPointsKey === null) {
            throw new \InvalidArgumentException('The CSV must include a REWARD POINT or REWARD POINTS column.');
        }

        $transactionDate = $this->parseTransactionDate($row['transaction_date']);
        $uploadedMonth = $row['month'] ?? null;
        if (is_numeric($uploadedMonth)) {
            $month = Date::excelToDateTimeObject($uploadedMonth)->format('M-y');
        } elseif (is_string($uploadedMonth) && trim($uploadedMonth) !== '') {
            $month = trim($uploadedMonth);
        } else {
            $month = Carbon::parse($transactionDate)->format('M-y');
        }

        // Find the user by user_id in the Users table
        $user = User::where('user_id', $row['user_id'])->first();
        // If the user does not exist, skip the record
        if (!$user) {
            return null; // Optionally handle missing users
        }

        return new UserWallet([
            'user_id' => $user->id, // Use the id from the Users table
            'month' => $month,
            'wallet_amount' => $row['wallet_amount'],
            'reward_points' => $row[$rewardPointsKey],
            'trans_type' => $row['payment_mode'],
            'mobilenumber' => $row['mobile_number'],
            'transaction_date' => $transactionDate,
            'source' => 'admin_import',
        ]);
    }

    private function parseTransactionDate($value): string
    {
        if (is_numeric($value)) {
            return Date::excelToDateTimeObject($value)->format('Y-m-d');
        }

        $value = trim((string) $value);
        if (preg_match('/^(\d{1,2})[-\/.](\d{1,2})[-\/.](\d{2}|\d{4})$/', $value, $matches)) {
            $yearFormat = strlen($matches[3]) === 2 ? 'y' : 'Y';
            $date = Carbon::createFromFormat(
                '!d-m-' . $yearFormat,
                $matches[1] . '-' . $matches[2] . '-' . $matches[3]
            );
            $errors = Carbon::getLastErrors();

            if ($date === false || ($errors !== false && ($errors['warning_count'] > 0 || $errors['error_count'] > 0))) {
                throw new \InvalidArgumentException('Transaction dates must be valid and use DD-MM-YY format.');
            }

            return $date->format('Y-m-d');
        }

        return Carbon::parse($value)->format('Y-m-d');
    }
}
