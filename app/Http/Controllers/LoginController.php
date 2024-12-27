<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    public function register()
    {
        return view('frontend.register');
    }
    public function login()
    {
        return view('frontend.login');
    }
    public function store(Request $request)
    {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password], true)) {
            if (Auth::user()->role == 3) {             
                return redirect()->route('dashboard.index')->with('success', 'Login successfully');
            }
             Auth::logout();
        } else {
            return redirect()->back()->with('error', 'Invalid Credentials');
        }
    }

    public function storeRegister(Request $request)
    {

        $validate = $request->validate([
            "name" => "required",
            "email" => "required",
            "password" => "required|string|min:6",
            "mobilenumber" => "required",
        ]);

        $store = new User;       
        $store->name = $request->name;
        $store->email = $request->email;
        $store->password = Hash::make($request->password);
        $store->mobilenumber = $request->mobilenumber;
        $store->role = 3;
        $store->assignRole([$store->role]);
        $store->save();
        return redirect()->route('frontend.index')->with('success', 'Register Successfully');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        Session::flush();

        return redirect()->route('frontend.index');
    }
}
