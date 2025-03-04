<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login(Request $request)
    {
        $request->validate([
            'mobile_or_user_id' => 'required',
            'password' => 'required',
        ]);
        if (filter_var($request->mobile_or_user_id, FILTER_VALIDATE_EMAIL)) {
            // dd("comming");
            $admin = \App\Models\User::where('email', $request->mobile_or_user_id)->where('role', 1)->first();
            if ($admin) {
                $credentials = ['email' => $request->mobile_or_user_id, 'password' => $request->password];
                if (Auth::attempt($credentials)) {
                    return redirect('admin')->with('success', 'Admin Login successfully');
                } else {
                    return redirect()->back()->with('error', 'Invalid Admin Credentials');
                }
            }
            return redirect()->back()->with('error', 'Unauthorized access: Only admin can login with email.');
        }
        $credentials = ['password' => $request->password];

        $isMobileNumber = is_numeric($request->mobile_or_user_id) && strlen($request->mobile_or_user_id) == 10;

        if ($isMobileNumber) {
            $credentials['mobilenumber'] = $request->mobile_or_user_id;
        } else {
            $credentials['user_id'] = $request->mobile_or_user_id;
        }
        $existingUser = \App\Models\User::where(function ($query) use ($request, $isMobileNumber) {
            if ($isMobileNumber) {
                $query->where('mobilenumber', $request->mobile_or_user_id);
            } else {
                $query->where('user_id', $request->mobile_or_user_id);
            }
        })->first();

        if ($existingUser) {
            if (Auth::check() && Auth::user()->id == $existingUser->id) {
                return redirect()->back()->with('error', 'This user is already logged in.');
            }
        }
        if (Auth::attempt($credentials)) {
            $user = auth()->user();
            if ($user->role == 4) {
                return redirect('pos')->with('success', 'POS Login successfully');
            } elseif ($user->role == 3) {
                return redirect('user')->with('success', 'Customer Login successfully');
            }
            Auth::logout();
            return redirect()->back()->with('error', 'Unauthorized access for this login method.');
        }

        return redirect()->back()->with('error', 'Invalid Credentials');
    }
}
