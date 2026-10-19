<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DentistMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Adjust based on how you identify dentists (e.g., role, is_dentist, type, etc.)
        if (!Auth::guard('dentist')->check()) {
            return $next($request);
        }

        // Redirect or abort if not dentist
        return redirect()->route('dentist.login')->with('error', 'Access denied. Only dentists allowed.');
    }
}
