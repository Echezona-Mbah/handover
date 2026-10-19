<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class NurseAuth
{
    public function handle(Request $request, Closure $next): Response
    {
        // dd('Middleware hit!');
    
        if (!Auth::guard('nurse')->check()) {
            return redirect()->route('nurse.login')->with('error', 'Please login as a nurse to access this page.');
        }
    
        return $next($request);
    }
    
}
