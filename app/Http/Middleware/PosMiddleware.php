<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class PosMiddleware
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
        if (Auth::check() && Auth::user()->role == 4) {
            return $next($request);
        }
        return redirect()->back();
    }
}
