<?php

namespace App\Http\Controllers\Admin;

use App\Exports\AdminWalletExport;
use App\Http\Controllers\Controller;
use App\Imports\AdminUsedWalletImport;
use App\Imports\AdminWalletImport;
use App\Models\UserWallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class WalletController extends Controller
{
    public function wallet()
    {
        return view('admin.wallet.index');
        // return view('admin.wallet.index', compact('walletBalance', 'userId', 'user_profile'));
    }

    public function uploadPage()
    {
        return view('admin.wallet.upload');
    }

    public function downloadUploadTemplate()
    {
        $headers = [
            'MONTH',
            'MOBILE NUMBER',
            'USER ID',
            'SPONSOR ID',
            'TOTAL BILLING AMOUNT',
            'REWARD POINT',
            'WALLET AMOUNT',
            'TRANSACTION DATE',
            'PAYMENT MODE',
        ];

        return response()->streamDownload(function () use ($headers) {
            $output = fopen('php://output', 'w');
            fputcsv($output, $headers);
            fclose($output);
        }, 'Payback_Upload_Template.csv', [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    public function wallet_load(Request $request)
    {
        $request->validate([
            'month' => ['nullable', 'integer', 'between:1,12'],
            'year' => ['nullable', 'integer', 'between:2000,2100'],
            'type' => ['nullable', 'string', 'in:all,credit,debit'],
            'trans_type' => ['nullable', 'string', 'in:all,credit,debit'],
            'pay_by' => ['nullable', 'string', 'in:wallet,reward'],
        ]);

        $user_profile = auth()->user();
        $userId       = $user_profile->id;
        $walletBalance = UserWallet::getAdminWallet($request);
        $pagination = $walletBalance->links()->render();
        return response()->json([
            'data' => $walletBalance,
            'pagination' => $pagination
        ]);
    }
    public function exportWallet(Request $request)
    {
        $request->validate([
            'month' => ['nullable', 'integer', 'between:1,12'],
            'year' => ['nullable', 'integer', 'between:2000,2100'],
            'type' => ['nullable', 'string', 'in:all,credit,debit'],
            'trans_type' => ['nullable', 'string', 'in:all,credit,debit'],
            'pay_by' => ['nullable', 'string', 'in:wallet,reward'],
        ]);

        // dd($request->all());
        $data = UserWallet::getAdminWalletExport($request);
        // dd($data->toArray());
        return Excel::download(new AdminWalletExport($data), 'AdminWalletData_' . now()->format('d,m,y') . '.xlsx');
    }
    public function uploadWallet(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt|extensions:csv',
        ]);

        try {
            Excel::import(new AdminWalletImport, $request->file('file'));
            return redirect()->back()->with('success', 'Wallet data imported successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error importing wallet data: ' . $e->getMessage());
        }
    }
    public function uploadUsedWallet(Request $request)
    {
        $request->validate([
            'used_file' => 'required|mimes:xlsx|max:2048', // Limit file size for better control
        ]);

        try {
            Excel::import(new AdminUsedWalletImport, $request->file('used_file'));
            return redirect()->back()->with('success', 'Wallet data imported successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error importing wallet data: ' . $e->getMessage());
        }
    }
}
