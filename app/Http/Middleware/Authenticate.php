<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
         return $request->expectsJson() ? null : route('login');
    }
    // protected function redirectTo(Request $request): ?string
    // {      
    //     if (Auth::check()) {
    //         $role = Auth::user()->role;
    //         if ($role == 1) {
    //             return route('admin'); 
    //         } elseif ($role == 3) {
    //             return route('dashboard.index'); 
    //         }
    //     }
        
    //     return route('login');
    // }


    // /**
    //  * Handle an incoming request.
    //  *
    //  * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
    //  */
    // public function handle(Request $request, Closure $next): Response
    // {
    //     if(auth()->check() && auth()->user()->role == 1){
    //             return $next($request);
    //     }
    // }

}
