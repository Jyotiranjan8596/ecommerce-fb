<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    // protected $redirectTo = RouteServiceProvider::HOME;

    public function index()
    {
        return view('auth.passwords.reset');
    }

    public function resetPassword(Request $request)
    {
        // dd($request->all());
        try {
            $request->validate([
                'mobilenumber' => 'required|exists:users,mobilenumber',
                'password' => 'required|confirmed|min:8',
            ]);

            $user = User::where('mobilenumber', $request->mobilenumber)->first();

            if ($user) {
                // Update password
                $user->password = Hash::make($request->password);
                $user->save();

                return redirect()->route('login')->with('success', 'Reset Successfull');
            }

            return redirect()->back()->with('error', 'User Not Found!');
        } catch (\Exception $e) {
            Log::info($e->getMessage());
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
