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
        $user_profile = auth()->user();
        $userId       = $user_profile->id;
        $walletBalance = UserWallet::whereNotNull('wallet_amount')->orderBy('id', 'desc')->simplePaginate(15)->through(function ($wallet) {
            // dd($wallet->wallet_amount);
            $wallet->rounded_wallet_amount = ($wallet->wallet_amount - floor($wallet->wallet_amount)) > 0.3
                ? ceil($wallet->wallet_amount)
                : floor($wallet->wallet_amount);
            return $wallet;
        });
        // dd($walletBalance);
        //  dd($walletBalance);
        return view('admin.wallet.index', compact('walletBalance', 'userId', 'user_profile'));
    }
    public function exportWallet()
    {
        return Excel::download(new AdminWalletExport, 'admin_wallet_data.xlsx');
    }
    public function uploadWallet(Request $request)
    {
        $request->validate([
            'file' => 'required', // Limit file size for better control
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
