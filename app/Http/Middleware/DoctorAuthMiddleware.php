<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class DoctorAuthMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::guard('doctor')->check()) {
            return redirect()->route('doctor.login')->with('error', 'You must be logged in as a doctor.');
        }

        return $next($request);
    }
}
