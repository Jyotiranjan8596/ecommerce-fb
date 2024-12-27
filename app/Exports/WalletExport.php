<?php

namespace App\Exports;

use App\Models\Wallet;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class WalletExport implements FromCollection, WithHeadings, WithMapping
{
    protected $startDate;
    protected $endDate;
    protected $serialNumber = 1;

    public function __construct($startDate = null, $endDate = null)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function collection()
    {
        $query = Wallet::with('user', 'getPos');
    
        if ($this->startDate && $this->endDate) {
            $query->whereBetween('transaction_date', [$this->startDate, $this->endDate]);
        }
    
        return $query->get();
    }
    

    public function map($wallet): array
    {
        return [
            $this->serialNumber++,
            $wallet->pos_id,
            $wallet->transaction_date,
            $wallet->invoice,                     
            // $wallet->user ? $wallet->user->user_id : 'N/A',
            $wallet->user ? $wallet->user->mobilenumber : 'N/A',                      
            $wallet->user ? $wallet->user->name : 'N/A',
            $wallet->user ? $wallet->user->address : 'N/A',
            $wallet->billing_amount ?? 0,
            $wallet->pay_by,
            $wallet->amount ?? 0,           
            $wallet->tran_type,
            $wallet->pay_by_wallet,           
            $wallet->amount_wallet !== null ? $wallet->amount_wallet : 0,
            number_format($wallet->transaction_amount, 2) ?? 0,
            $wallet->insert_date
        ];
    }

    public function headings(): array
    {
        return [
            'Sl. No.', 
            'BRANCH',          
            'DATE',
            'VOUCHER NUMBER',            
            // 'USER ID',
            'MOBILE NUMBER',            
            'CUSTOMER NAME',
            'ADDRESS',
            'AMOUNT',
            'PAID BY',
            'AMOUNT PAID BY CASH/UPI',           
            'TRANSACTION TYPE',
            'PAY BY WALLET',            
            'AMOUNT WALLET',
            'TRANSACTION AMOUNT',
            'INSERT DATE'
        ];
    }
}
