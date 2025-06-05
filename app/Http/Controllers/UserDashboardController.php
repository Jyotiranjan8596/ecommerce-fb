<?php

namespace App\Http\Controllers;

use App\Models\PosModel;
use App\Models\Sponsor;
use App\Models\User;
use App\Models\UserWallet;
use App\Models\Wallet;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserDashboardController extends Controller
{
    public function index(Request $request)
    {
        $user_profile = auth()->user();
        $userId       = $user_profile->id;
        // dd($userId);
        $sponsors       = Sponsor::where('sponsor_id', $userId)->get();
        $sponsors_count = count($sponsors);
        // dd($sponsors_count);
        $transactionQuery = Wallet::where('user_id', $userId)->with('userWallets');
        // dd($transactionQuery);
        //     $monthlyPurchases = Wallet::where('user_id', $userId)
        //     ->select(
        //         DB::raw('SUM(billing_amount) as total_billing'),
        //         DB::raw('MONTH(transaction_date) as month')
        //     )
        //     ->groupBy(DB::raw('MONTH(transaction_date)'))
        //     ->get();

        //    dd($monthlyPurchases);

        $month = Carbon::now()->month;
        $year  = Carbon::now()->year;

        $monthlyPurchase = Wallet::where('user_id', $userId)
            ->with('userWallets')
            ->whereMonth('transaction_date', $month)
            ->whereYear('transaction_date', $year)
            ->sum('billing_amount');

        if ($request->has('from_date') && $request->has('to_date')) {
            $transactionQuery->whereBetween('transaction_date', [$request->from_date, $request->to_date]);
        } elseif ($request->has('from_date')) {
            $transactionQuery->where('transaction_date', '>=', $request->from_date);
        } elseif ($request->has('to_date')) {
            $transactionQuery->where('transaction_date', '<=', $request->to_date);
        } else {
            // Default to current month filter if no date range is provided
            $transactionQuery->whereMonth('transaction_date', now()->month)
                ->whereYear('transaction_date', now()->year);
        }

        // my code
        $walletList = $transactionQuery->orderBy('id', 'desc')->simplepaginate(50);

        $walletList->getCollection()->transform(function ($item) {
            $user_wallets = $item->userWallets;
            foreach ($user_wallets as $user_wallet) {
                $item->remaining_amount = $user_wallet->remaining_amount;
            }
            return $item;
        });

        // $walletList = $transactionQuery->orderBy('id', 'desc')->get()
        //     ->map(function ($item) {
        //         $user_wallets = $item->userWallets;
        //         foreach ($user_wallets as $user_wallet) {
        //             $item->remaining_amount = $user_wallet->remaining_amount;
        //         }
        //         return $item;
        //     })->paginate(15);

        // dd($walletList->all()[0]);
        // $userWallet = UserWallet::where('user_id', $userId)->get();
        // $totalUsedAmount = UserWallet::where('user_id', $userId)->sum('used_amount');
        $walletBalance = self::get_total_wallet_amount($userId);
        $total_payback = UserWallet::where('user_id', $userId)->where('trans_type', 'credit')->sum('wallet_amount');
        // if ($userWallet) {
        // } else {
        //     $walletBalance = 0;
        // }
        // $walletBalance = max($walletBalance, 0);

        return view('frontend.dashboard.index', compact('total_payback', 'sponsors_count', 'sponsors', 'user_profile', 'monthlyPurchase', 'walletBalance', 'walletList'));
    }

    public function get_total_wallet_amount($userId)
    {
        // dd($userId);
        // Fetch all wallet records for the user
        $userWallet = UserWallet::where('user_id', $userId)->get();

        // Calculate the total used amount
        $totalUsedAmount = $userWallet->sum('used_amount');

        // Calculate the total wallet amount
        $totalWalletAmount = $userWallet->sum('wallet_amount');

        // Calculate the wallet balance
        $walletBalance = $totalWalletAmount - $totalUsedAmount;

        // Ensure the wallet balance is non-negative
        return max($walletBalance, 0);
    }

    public function payment(Request $request)
    {
        try {
            // dd($request->all());
            $user           = User::find($request->user_id);
            $sponsors       = Sponsor::where('sponsor_id', $user->id)->get();
            $sponsors_count = count($sponsors);
            // Verify password
            // if (! $user || ! Hash::check($request->password, $user->password)) {
            //     return redirect()->back()->with('error', 'Incorrect password. Please try again.');
            // }
            $userId = Auth::user()->id;
            // dd($userId);
            $posId            = intval($request->input('pos_id'));
            $pos              = PosModel::find($posId);
            $randomNumber     = mt_rand(1, 99999);
            $invoiceNumber    = $randomNumber + 1;
            $invoice          = 'S' . str_pad($invoiceNumber, 6, '0', STR_PAD_LEFT);
            $amount           = $request->billing_amount;
            $mobilenumber     = $request->mobilenumber;
            $transaction_date = $request->transaction_date;
            $pay_by           = $request->pay_by;
            $alt_pay_by       = $request->alternative_pay_by;
            // dd($alt_pay_by);
            $transaction_charge = $pos ? $pos->transaction_charge : 0;
            $transaction_amount = $amount * ($transaction_charge / 100);
            $walletUsedAmount   = 0;

            $userWalletEntry                   = new UserWallet();
            $userWalletEntry->invoice          = $invoice;
            $userWalletEntry->user_id          = $userId;
            $userWalletEntry->mobilenumber     = $mobilenumber;
            $userWalletEntry->transaction_date = $transaction_date;
            $userWalletEntry->pay_by           = 'wallet';
            $userWalletEntry->trans_type       = 'debit';
            $userWalletEntry->pos_id           = $pos->id ?? null;
            $currentWalletBalance              = self::get_total_wallet_amount($userId);
            if ($sponsors_count >= 10) {
                if ($pay_by == 'wallet') {
                    $userWallet = UserWallet::where('user_id', $userId)->get();
                    // dd($userWallet);
                    $walletBalance = $userWallet ? $userWallet->sum('wallet_amount') - UserWallet::where('user_id', $userId)->sum('used_amount') : 0;

                    if ($walletBalance <= 0) {
                        return redirect()->back()->with('error', 'Wallet is empty. Payment cannot be processed.');
                    }

                    $walletUsedAmount = min($amount, $walletBalance);
                    $remainingAmount  = $amount - $walletUsedAmount;

                    $walletEntry                     = new Wallet();
                    $walletEntry->user_id            = $userId;
                    $walletEntry->invoice            = $invoice;
                    $walletEntry->mobilenumber       = $mobilenumber;
                    $walletEntry->transaction_date   = $transaction_date;
                    $walletEntry->billing_amount     = $amount;
                    $walletEntry->transaction_amount = $transaction_amount;

                    if ($walletBalance >= $amount) {
                        $walletEntry->amount_wallet = $amount;
                        $walletEntry->pay_by        = 'wallet';
                        $walletEntry->tran_type     = 'debit';
                        $walletEntry->amount        = 0;
                    } else {
                        $walletEntry->amount_wallet = $walletUsedAmount;
                        $walletEntry->pay_by        = $alt_pay_by;
                        $walletEntry->tran_type     = 'credit';
                        // $walletEntry->status = ;
                        $walletEntry->amount = $remainingAmount;
                    }

                    $walletEntry->pos_id      = $pos->id ?? null;
                    $walletEntry->insert_date = now();
                    $walletEntry->save();

                    $userWalletEntry->wallet_id   = $walletEntry->id;
                    $userWalletEntry->used_amount = $walletUsedAmount;
                    $userWalletEntry->save();
                } else {
                    if ($request->billing_amount > $request->paying_amount) {
                        $wallet_balance = $request->billing_amount - $request->paying_amount;
                    }
                    $remainingAmount             = $amount;
                    $dsrlist                     = new Wallet();
                    $dsrlist->invoice            = $invoice;
                    $dsrlist->user_id            = $userId;
                    $dsrlist->amount             = $request->paying_amount;
                    $dsrlist->pay_by             = $pay_by;
                    $dsrlist->mobilenumber       = $mobilenumber;
                    $dsrlist->transaction_date   = $transaction_date;
                    $dsrlist->tran_type          = 'credit';
                    $dsrlist->billing_amount     = $amount;
                    $dsrlist->amount_wallet      = 0;
                    $dsrlist->transaction_amount = $transaction_amount;
                    $dsrlist->pos_id             = $pos->id ?? null;
                    $dsrlist->insert_date        = now();
                    $dsrlist->save();

                    $userWalletEntry->wallet_id        = $dsrlist->id;
                    $userWalletEntry->used_amount      = 0;
                    $userWalletEntry->remaining_amount = $currentWalletBalance;
                    $userWalletEntry->save();
                }
                return response()->json(
                    [
                        'success' => true,
                        'message' => 'Payment verified successfully.',
                        'billing_amount' => $request->billing_amount,
                        'paying_amount' => $request->paying_amount
                    ]
                );
            } else {
                $remainingAmount = $amount;
                // $amount_wallet   = $amount * (5 / 100);

                if ($request->billing_amount > $request->paying_amount) {
                    $amount_wallet = $request->billing_amount - $request->paying_amount;
                    if ($currentWalletBalance <= $amount_wallet) {
                        $amount_wallet = $currentWalletBalance;
                    }
                } else {
                    $amount_wallet = 0;
                }


                $dsrlist                     = new Wallet();
                $dsrlist->invoice            = $invoice;
                $dsrlist->user_id            = $userId;
                $dsrlist->amount             = $request->paying_amount;
                $dsrlist->pay_by             = $pay_by;
                $dsrlist->mobilenumber       = $mobilenumber;
                $dsrlist->transaction_date   = $transaction_date;
                $dsrlist->tran_type          = 'credit';
                $dsrlist->billing_amount     = $amount;
                $dsrlist->amount_wallet      = $amount_wallet;
                $dsrlist->transaction_amount = $transaction_amount;
                $dsrlist->pos_id             = $pos->id ?? null;
                $dsrlist->insert_date        = now();
                $dsrlist->save();

                $userWalletEntry->wallet_id        = $dsrlist->id;
                $userWalletEntry->used_amount      = $amount_wallet;
                $userWalletEntry->remaining_amount = $currentWalletBalance - $amount_wallet;
                $userWalletEntry->save();

                return response()->json(
                    [
                        'success' => true,
                        'message' => 'Payment verified successfully.',
                        'billing_amount' => $request->billing_amount,
                        'paying_amount' => $request->paying_amount
                    ]
                );
            }
        } catch (\Exception $th) {
            Log::info($th->getMessage());
        }

        // return redirect()->back()->with('success', 'Payment successfully verified and processed.');
        // dd("comming");

    }

    // public function verifyPayment(Request $request)
    // {
    //     // dd($request->all());
    //     $user = User::find($request->user_id);

    //     if ($user && Hash::check($request->password, $user->password)) {
    //         Wallet::create([
    //             'user_id' => $request->user_id,
    //             'billing_amount' => $request->billing_amount,
    //             'amount' => $request->paying_amount,
    //             'amount_wallet' => $request->amount_wallet,
    //             'mobilenumber' => $request->mobilenumber,
    //             'pos_id' => $request->pos_id,
    //             'pay_by' => $request->pay_by,
    //             'transaction_date' => $request->transaction_date,
    //         ]);

    //         return redirect()->back()->with('success', 'Payment successfully verified and processed.');
    //     }

    //     return redirect()->back()->with('error', 'Incorrect password. Please try again.');
    // }

    public function addUser()
    {
        $user_id       = auth()->user()->user_id;
        $user_profile = auth()->user();
        $userId       = $user_profile->id;
        $states        = User::select('state')->where('role', 3)->whereNotNull('state')->where('state', '!=', '')->distinct()->pluck('state');
        $relation_user = User::select('relation_user')->where('role', 3)->whereNotNull('relation_user')->where('relation_user', '!=', '')->distinct()->pluck('relation_user');
        // dd($relation_user);
        return view('frontend.dashboard.adduser', compact('user_id', 'states', 'relation_user', 'userId', 'user_profile'));
    }

    public function storeUser(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'mobilenumber' => 'required|unique:users,mobilenumber|regex:/^[0-9]{10}$/',
        ]);
        $sponsor = User::where('user_id', $request->sponsor_id)->first();
        // dd($sponsor);
        $user_add                = new User;
        $user_add->name          = $request->name;
        $user_add->user_id       = mt_rand(1000000, 9999999);
        $user_add->email         = $request->email;
        $user_add->password      = Hash::make('123456');
        $user_add->mobilenumber  = $request->mobilenumber;
        $user_add->gender        = $request->gender;
        $user_add->sponsor_id    = $sponsor->id;
        $user_add->address       = $request->address;
        $user_add->city          = $request->city;
        $user_add->state         = $request->state;
        $user_add->zip           = $request->zip;
        $user_add->pan_no        = $request->pan_no;
        $user_add->ifsc          = $request->ifsc;
        $user_add->account_no    = $request->account_no;
        $user_add->nominee_name  = $request->nominee_name;
        $user_add->bank          = $request->bank;
        $user_add->relation_user = $request->relation_user;
        $user_add->role          = 3;
        $user_add->assignRole([$user_add->role]);

        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->getClientOriginalExtension();
            $request->image->move(public_path('images'), $imageName);
            $user_add->image = $imageName;
        }

        if ($user_add->save()) {
            $sponcer             = new Sponsor();
            $sponcer->user_id    = $user_add->id;
            $sponcer->sponsor_id = $request->hidden_user_id;

            if ($sponcer->save()) {
                flash()->addSuccess('User registered successfully.');
                return redirect()->route('user.add');
            }
        }
        flash()->addError('Whoops! User or Sponsor creation failed!');
        return redirect()->back();
    }
    public function wallet()
    {
        $user_profile   = auth()->user();
        $userId         = $user_profile->id;
        $sponsors       = Sponsor::where('sponsor_id', $userId)->get();
        $sponsors_count = count($sponsors);
        // dd($userId);
        $userWallet      = UserWallet::where('user_id', $userId)
        ->orderBy('id', 'desc')
        ->simplePaginate(10)
        ->through(function($item){
            // dd($item->toArray());
            if($item->used_amount > 0){
                $item->transaction_details = $item->getPos?$item->getPos->name:"N/A";
            }else{
                $item->transaction_details = "Admin";
            }
            return $item;
        });
        $totalUsedAmount = UserWallet::where('user_id', $userId)->sum('used_amount');

        $walletBalance = self::get_total_wallet_amount($userId);
        return view('frontend.dashboard.wallet', compact('userWallet', 'walletBalance', 'user_profile', 'sponsors_count'));
    }
    public function termCondition()
    {
        return view('frontend.dashboard.term_condition');
    }
    public function sponsorList()
    {
        $userId  = auth()->user()->id;
        $sponcer = Sponsor::where('sponsor_id', $userId)->get()->map(function($item){
            $item->created_on = Carbon::parse($item->created_at)->format('d-m-Y h:i:s A');
            return $item;
        });
        // dd($sponcer);
        return view('frontend.dashboard.sponser_list', compact('sponcer'));
    }
    public function posList(Request $request)
    {
        try {
            $query = PosModel::query();

            if ($request->has('search') && ! empty($request->search)) {
                $searchTerm = $request->search;
                $query->where('city', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('zip', 'LIKE', "%{$searchTerm}%");
            }

            $pos = $query->orderBy('id', 'desc')->paginate(50)->through(function ($item) {
                return [
                    'name' => $item->name ?? "N/A",
                    'mobilenumber' => $item->mobilenumber ?? "N/A",
                    'city' => $item->city ?? "N/A",
                    'address' => $item->address ?? "N/A",
                    'zip' => $item->zip??"N/A"
                ];
            });
            return response()->json([
                'code' => 200,
                'data' => $pos
            ]);
        } catch (\Exception $e) {
            Log::info($e->getMessage());
            return response()->json([
                'code' => 403,
            ]);
        }
    }

    public function pos_list_index()
    {
        return view('frontend.dashboard.pos_list');
    }

    public function editprofile()
    {
        $user_profile = auth()->user();
        $states       = User::select('state')->where('role', 3)->whereNotNull('state')->distinct()->pluck('state');
        return view('frontend.dashboard.editprofile', compact('user_profile', 'states'));
    }
    public function updateprofile(Request $request)
    {

        $update_profile = User::find(auth()->id());

        if ($request->mobilenumber !== $update_profile->mobilenumber) {
            $update_profile->requestMobileNumberUpdate($request->mobilenumber);

            flash()->addSuccess('Your mobile number update request has been submitted and is awaiting admin approval.');
            return redirect()->route('user.index');
        }

        $update_profile->email         = $request->email;
        $update_profile->address       = $request->address;
        $update_profile->city          = $request->city;
        $update_profile->state         = $request->state;
        $update_profile->zip           = $request->zip;
        $update_profile->nominee_name  = $request->nominee_name;
        $update_profile->relation_user = $request->relation_user;

        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->getClientOriginalExtension();
            $request->image->move(public_path('images'), $imageName);
            $update_profile->image = $imageName;
        }
        if ($update_profile->save()) {
            flash()->addSuccess('Profile Update successfully.');
            return redirect()->route('user.index');
        }
    }
    public function changepassword()
    {
        return view('frontend.dashboard.changepassword');
    }

    public function newPassword(Request $request)
    {
        $request->validate([
            'password' => 'required|min:6|confirmed',
        ]);
        $newpass           = Auth::user();
        $newpass->password = Hash::make($request->password);
        if ($newpass->save()) {
            flash()->addSuccess('Your password has been successfully updated.');
            return redirect()->route('user.index');
        }
    }
    // public function cart()
    // {
    //     $userId = Auth::id();
    //     $cartItems = Cart::with('product')->where('user_id', $userId)->get();
    //     $totalPrice = $cartItems->sum(fn($item) => $item->product->price);
    //     return view('frontend.dashboard.cart', compact('cartItems', 'totalPrice'));
    // }
}
