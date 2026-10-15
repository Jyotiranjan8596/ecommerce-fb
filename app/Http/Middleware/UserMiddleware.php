<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class UserMiddleware
{
    public function handle($request, Closure $next)
    {
        try {
            if (!DB::connection()->getPdo()) {
                DB::reconnect();
            }
        } catch (\Exception $e) {
            DB::reconnect();
        }
        if (Auth::check() && Auth::user()->role == 3 && (int) Auth::user()->status === 1) {
            Auth::user()->loadCount(['referrals as referrals_count' => function ($query) {
                $query->where('role', 3);
            }]);

            return $next($request);
        }
        if (Auth::check() && Auth::user()->role == 3) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->with('error', 'Your account is suspended. Contact the administrator.');
        }

        return redirect()->back();
    }
}
