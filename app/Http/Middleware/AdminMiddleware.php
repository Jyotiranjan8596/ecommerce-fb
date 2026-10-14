<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
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

        if (Auth::guest()) {
            return redirect()->guest(route('login'));
        }

        if ((int) Auth::user()->role !== 1) {
            return redirect()->route('frontend.index');
        }

        return $next($request);
    }
}
