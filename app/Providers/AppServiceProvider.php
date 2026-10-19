<?php

namespace App\Providers;

use App\Http\Middleware\DentistMiddleware;
use App\Http\Middleware\NurseAuth;
use App\Models\Dentists;
use App\Models\Doctor;
use App\Models\Medication;
use App\Models\News;
use App\Models\NurseHandover;
use App\Models\NursingReport;
use App\Models\NursingRoster;
use App\Models\NursingRosterAssignment;
use App\Models\NursingStatistic;
use App\Models\Patient;
use App\Models\ShiftSchedule;
use App\Models\Ward;
use App\Observers\HospitalActivityObserver;
use App\Observers\NursingRosterActivityObserver;
use App\Observers\NursingRosterAssignmentObserver;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $activityModels = [
            Dentists::class,
            Doctor::class,
            Medication::class,
            News::class,
            NursingReport::class,
            NursingStatistic::class,
            NurseHandover::class,
            Patient::class,
            ShiftSchedule::class,
            Ward::class,
        ];

        foreach ($activityModels as $model) {
            $model::observe(HospitalActivityObserver::class);
        }

        NursingRoster::observe(NursingRosterActivityObserver::class);
        NursingRosterAssignment::observe(NursingRosterAssignmentObserver::class);

        // View::composer('parklane.doctor.*', function ($view) {
        //     if (Auth::guard('doctor')->check()) {
        //         $doctor = Auth::guard('doctor')->user();

        //         $notifications = PatientUnitInvite::with('patient')
        //             ->where('doctor_unit_id', $doctor->doctor_unit_id)
        //             ->when($doctor->doctor_sub_unit_id, function ($q) use ($doctor) {
        //                 $q->where('doctor_sub_unit_id', $doctor->doctor_sub_unit_id);
        //             }, function ($q) {
        //                 $q->whereNull('doctor_sub_unit_id');
        //             })
        //             ->whereNull('seen_at')
        //             ->latest()
        //             ->take(10)
        //             ->get();

        //         $view->with('notifications', $notifications);
        //     }
        // });

        $this->routes(function () {
            Route::middleware('web')
                ->group(base_path('routes/web.php'));

            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));
        });

        Route::aliasMiddleware('authNurse', NurseAuth::class);
        Route::aliasMiddleware('dentist', DentistMiddleware::class);

    }
}
