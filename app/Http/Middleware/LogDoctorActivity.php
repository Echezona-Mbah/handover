<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\DoctorActivity;
use Illuminate\Support\Facades\Auth;

class LogDoctorActivity
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        $doctor = Auth::guard('doctor')->user();
        if ($doctor) {
            $payload = $request->except(['password', 'password_confirmation', '_token']);

            $routeName = $request->route()?->getName();

                        // Skip noisy ajax routes
            if (in_array($routeName, ['get.units', 'get.sub.units'])) {
                return $response;
            }

            $actionText = match ($routeName) {
                'doctor.profile'        => 'Viewed own profile',
                'doctor.patient'        => 'Viewed patient list',
                'doctor.show_patient'   => 'Viewed patient details',
                'doctor.patients.plan'  => 'Viewed patient medication plan',
                'doctor.handover.show'  => 'Viewed handover notes',
                'doctor.medications.store' => 'Added medication',
                'doctor.add_patient'    => 'Opened add patient form',
                'doctor.store_patient'  => 'Created new patient',
                'doctor.invite.unit'    => 'Invited unit to patient',
                'doctor.discharge'      => 'Discharged / transferred patient',
                default                 => $routeName ?? 'doctor_action',
            };


            DoctorActivity::create([
                'doctor_id' => $doctor->id,
                'hospital_id' => $doctor->hospital_id,
                'action' => $actionText,
                'route' => $request->path(),
                'method' => $request->method(),
                'url' => $request->fullUrl(),
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'payload' => $payload,
            ]);
        }

        return $response;
    }
}
