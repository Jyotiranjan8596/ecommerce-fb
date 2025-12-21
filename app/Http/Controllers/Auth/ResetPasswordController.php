<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\OtpVerification;
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
                'mobilenumber' => 'required|exists:users,mobilenumber'
            ]);

            if (!$request->filled('otp')) {
                $request->validate([
                    'mobilenumber' => 'required|digits:10|exists:users,mobilenumber',
                ]);

                $otp = rand(100000, 999999);
                $otpSent = OtpVerification::storeOtp($otp, $request->mobilenumber);
                if ($otpSent) {
                    return back()
                        ->with('otp_sent', true)
                        ->with('mobilenumber', $request->mobilenumber)
                        ->with('success', 'OTP sent successfully.');
                } else {
                    return back()
                        ->with('otp_sent', false)
                        ->with('mobilenumber', $request->mobilenumber)
                        ->with('success', 'Something went error!');
                }
            }
            $request->validate([
                'mobilenumber' => 'required',
                'otp'          => 'required|digits:6',
                'password'     => 'required|min:8|confirmed',
            ]);
            $get_otp = OtpVerification::get_otp($request->mobilenumber, $request->otp);
            if (!$get_otp) {
                return back()->withErrors(['otp' => 'Invalid or expired OTP.']);
            }

            $update_password = User::updatePassword($request->mobilenumber, $request->password, $get_otp->id);
            if ($update_password) {
                return redirect()->route('frontend.index')->with('success', 'Password reset successfully.');
            }
        } catch (\Exception $e) {
            Log::info($e->getMessage());
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
