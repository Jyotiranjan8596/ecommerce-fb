<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class MasterUsersImport implements ToModel, WithHeadingRow, SkipsEmptyRows
{
    public function shouldImportRow(array $row)
    {
        set_time_limit(240);
        return empty($row['my_id']) &&
            empty($row['phone']) &&
            empty($row['user_email']) &&
            empty($row['user_name']) &&
            empty($row['user_lname']) &&
            empty($row['gender']) &&
            empty($row['address']) &&
            empty($row['city']) &&
            empty($row['state']) &&
            empty($row['country']) &&
            empty($row['zip']) &&
            empty($row['pan']) &&
            empty($row['bank_nm']) &&
            empty($row['account']) &&
            empty($row['ifsc']) &&
            empty($row['nom_name']) &&
            empty($row['nom_rel']) &&
            empty($row['wife']);
    }
    public $count = 0;
    public function model(array $row)
    {
        $existingUserData = User::where('mobilenumber', $row['phone'])->first();
        // ->where('email', $row['user_email']);

        if ($existingUserData) {
            $this->count++;
        }
        $userCount = User::where('email', $row['user_email'])
            ->orWhere('mobilenumber', $row['phone'])
            ->count();      
        if ($this->shouldImportRow($row) != true && $existingUserData == null) {
            // if (self::shouldImportRow($row) != true) {
            return User::create([
                'user_id' => strlen((string) $row['my_id'])>8 ?$row['phone']: $row['my_id'],
                'mobilenumber' => strlen((string) $row['phone'])<10 ?$row['my_id']: $row['phone'],
                'email' => $row['user_email'],
                'name' => $row['user_name'] . ' ' . $row['user_lname'],
                'gender' => $row['gender'],
                'address' => $row['address'],
                'password' => Hash::make('123456'), 
                'city' => $row['city'],
                'state' => $row['state'],
                'country' => $row['country'],
                'zip' => $row['zip'],
                'pan_no' => $row['pan'],
                'bank' => $row['bank_nm'],
                'account_no' => $row['account'],
                'ifsc' => $row['ifsc'],
                'nominee_name' => $row['nom_name'],
                'relation_user' => $row['nom_rel'],
                'wife' => $row['wife'],
            ]);
        }
    }
}
