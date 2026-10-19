<?php

use App\Http\Controllers\Admin\DentistController;
use App\Http\Controllers\Admin\DepartmentController;
use App\Http\Controllers\Admin\DoctorController;
use App\Http\Controllers\Admin\NewsController;
use App\Http\Controllers\Admin\NuresController;
use App\Http\Controllers\Admin\NurseNotificationController as AdminNurseNotificationController;
use App\Http\Controllers\Admin\PatientController;
use App\Http\Controllers\Admin\ProfilesController;

use App\Http\Controllers\Admin\SubadminController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Dentist\AvailabilityController as DentistAvailabilityController;
use App\Http\Controllers\Dentist\DashboardController as DentistDashboardController;
use App\Http\Controllers\Dentist\PatientController as DentistPatientController;
use App\Http\Controllers\Dentist\ProfileController as DentistProfileController;
use App\Http\Controllers\Doctor\AvailabilityController;
use App\Http\Controllers\Doctor\DashboardController;
use App\Http\Controllers\Doctor\DoctorController as DoctorDoctorController;
use App\Http\Controllers\Doctor\DoctorForgotPasswordController;
use App\Http\Controllers\Doctor\DoctorPasswordSetupController;
use App\Http\Controllers\Doctor\NewsController as DoctorNewsController;
use App\Http\Controllers\Doctor\PatientController as DoctorPatientController;
use App\Http\Controllers\Doctor\ProfileController;
use App\Http\Controllers\Nurse\DashboardController as NurseDashboardController;
use App\Http\Controllers\Nurse\NewsController as NurseNewsController;
use App\Http\Controllers\Nurse\NurseController;
use App\Http\Controllers\Nurse\NotificationController as NurseNotificationController;
use App\Http\Controllers\Nurse\PatientController as NursePatientController;


use App\Http\Controllers\Admin\NursingDigitalController;
use App\Http\Controllers\Nurse\NursingReportController;
use App\Http\Controllers\Nurse\NursingRosterController;
use App\Http\Controllers\Nurse\NursingStatisticController;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
// use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    $messages = DB::table('handover_notes')->pluck('alerts');
    return view('welcome', compact('messages'));
});
Route::get('/about', function () {
    $messages = DB::table('handover_notes')->pluck('alerts');

    return view('about', compact('messages'));
});
Route::get('/service', function () {
    $messages = DB::table('handover_notes')->pluck('alerts');

    return view('service', compact('messages'));
});
Route::get('/contact', function () {
    $messages = DB::table('handover_notes')->pluck('alerts');

    return view('contact', compact('messages'));
});
Route::get('/blog', function () {
    $messages = DB::table('handover_notes')->pluck('alerts');
    return view('blog', compact('messages'));
});
Route::get('/blog-detail', function () {
    $messages = DB::table('handover_notes')->pluck('alerts');
    return view('blog-detail', compact('messages'));
});
Route::get('/team', function () {
    $messages = DB::table('handover_notes')->pluck('alerts');
    return view('team', compact('messages'));
});
Route::get('/testimonial', function () {
    $messages = DB::table('handover_notes')->pluck('alerts');
    return view('testimonial', compact('messages'));
});
Route::get('/appointment', function () {
    $messages = DB::table('handover_notes')->pluck('alerts');
    return view('appointment', compact('messages'));
});
Route::get('/clear-cache', [DashboardController::class, 'clearCache']);


Route::get('/dashboard', function () {
    $user = Auth::user();

    $doctorsQuery = DB::table('doctors');
    if (!$user->is_super_admin) {
        $doctorsQuery->where('doctor_departments', $user->doctor_department_id);
    }
    $doctors = $doctorsQuery->count();

    $patients = null;
    $nurses   = null;
    $dentists = null;
    $admins   = null;

    if ($user->is_super_admin) {
        $patients = DB::table('patients')->count();
        $nurses   = DB::table('nurses')->count();
        $dentists = DB::table('dentists')->count();
        $admins   = DB::table('users')->count();
    }

    return view('parklane.dashboard', compact('patients', 'doctors', 'nurses', 'dentists', 'admins'));
})->middleware(['auth', 'verified'])->name('dashboard');


Route::fallback(function () {
    return redirect('/not-found');
});


Route::get('/not-found', function () {
    return view('error.404'); 
})->name('custom.404');


Route::middleware('auth')->group(function () {

        Route::get('/add_admin_user', [SubadminController::class, 'create'])->name('add_admin_user');
        Route::post('/add_admin_user', [SubadminController::class, 'store'])->name('store_admin_user');
        Route::get('/admin_user_list', [SubadminController::class, 'index'])->name('admin_user_list');
        Route::delete('/admin_user/{user}', [SubadminController::class, 'destroy'])->name('admin_user.destroy');


    Route::get('/adminprofile', [ProfilesController::class, 'edit'])->name('profile.edit');
    Route::patch('/adminprofile', [ProfilesController::class, 'update'])->name('profile.update');
    Route::delete('/adminprofile', [ProfilesController::class, 'destroy'])->name('profile.destroy');
    // DOctor
    Route::get('/add_doctor', [DoctorController::class, 'create'])->name('add_doctor');
    Route::post('/add_doctor', [DoctorController::class, 'store'])->name('store_doctor');
    Route::get('/doctor_list', [DoctorController::class, 'showDoctorList'])->name('doctor_list');
    Route::get('/show_doctor/{id}', [DoctorController::class, 'show'])->name('doctors.show');
    Route::delete('/doctors/{doctor}', [DoctorController::class, 'destroy'])->name('doctors.destroy');
    Route::get('/doctors/{doctor}/shifts', [DoctorController::class, 'createDoctorShift'])->name('doctors.shifts');
    Route::put('/doctors/{doctor}/shifts', [DoctorController::class, 'storeDoctorShift'])->name('storeDoctorShift.shifts');
    Route::get('/doctors/shifts', [DoctorController::class, 'doctorShift'])->name('doctorShift');
    Route::delete('/doctors_shift/{shift}', [DoctorController::class, 'destroyShift'])->name('destroyShift.shift.destroy');
    Route::get('/doctors/shifts/{id}/edit', [DoctorController::class, 'editDoctorShift'])->name('editDoctorShift');
    Route::put('/doctors/shifts/{id}', [DoctorController::class, 'updateDoctorShift'])->name('updateDoctorShift');
    Route::post('/doctors/message-unit', [DoctorController::class, 'messageUnit'])->name('message.unit');
    // Route::get('/get-units-doctor/{department_id}', [DoctorController::class, 'getUnits']);
    // Route::get('/get-sub-units-doctor/{unit_id}', [DoctorController::class, 'getSubUnits']);



    Route::post('/doctor/update-department', [DoctorController::class, 'updateDepartment'])
     ->name('doctor.update.department');


    Route::get('/get-units/{department_id}', [DoctorController::class, 'getUnits'])->name('get.units');
    Route::get('/get-sub-units/{unit_id}', [DoctorController::class, 'getSubUnits'])->name('get.sub.units');


    //PATIENT
    Route::get('/add_patient', [PatientController::class, 'create'])->name('add_patient');
    Route::post('/add_patient', [PatientController::class, 'store'])->name('Storeadd_patient');
    Route::get('/patient_list', [PatientController::class, 'showDoctorList'])->name('patient_list');
    Route::get('/show_patient/{id}', [PatientController::class, 'show'])->name('show_patient');
    Route::get('/edit_patients/{id}', [PatientController::class, 'edit'])->name('edit_patients');
    Route::put('/patient/update/{id}', [PatientController::class, 'patientUpdate'])->name('patientUpdate');
    Route::delete('/patient/{patient}', [PatientController::class, 'destroy'])->name('patient.destroy');
    Route::get('/edit_patients/{id}', [PatientController::class, 'edit'])->name('edit_patients');
    Route::get('/patients/{patientId}/assign', [PatientController::class, 'assignToDoctor'])->name('admin.patients.assign');
    Route::post('/patients/{patientId}/assign', [PatientController::class, 'saveAssignment'])->name('admin.patients.saveAssignment');

    //NURSE
    Route::get('/add_nurse', [NuresController::class, 'createNurse'])->name('add_nurse');
    Route::post('/add_nurse', [NuresController::class, 'storeNurse'])->name('storeNurse.add_nurse');
    Route::get('/nurse_list', [NuresController::class, 'showNurseList'])->name('nurse_list');
    Route::get('/show_nurse/{id}', [NuresController::class, 'showNures'])->name('nurse.showew');
    Route::delete('/show_nurse/{nurse}', [NuresController::class, 'destroyNurse'])->name('nurse.destroy');
    Route::get('/nursing/notifications/create', [AdminNurseNotificationController::class, 'create'])->name('admin.nurse-notifications.create');
    Route::get('/nursing/notifications/acknowledgements', [AdminNurseNotificationController::class, 'acknowledgements'])->name('admin.nurse-notifications.acknowledgements');
    Route::post('/nursing/notifications', [AdminNurseNotificationController::class, 'store'])->name('admin.nurse-notifications.store');
    Route::get('/nursing/reports', [NursingDigitalController::class, 'reports'])->name('admin.nursing.reports');
Route::get('/nursing/reports/{report}', [NursingDigitalController::class, 'showReport'])->name('admin.nursing.reports.show');
Route::post('/nursing/reports/{report}/review', [NursingDigitalController::class, 'reviewReport'])->name('admin.nursing.reports.review');

Route::get('/nursing/statistics', [NursingDigitalController::class, 'statistics'])->name('admin.nursing.statistics');
Route::post('/nursing/statistics/{statistic}/verify', [NursingDigitalController::class, 'verifyStatistic'])->name('admin.nursing.statistics.verify');

Route::get('/nursing/rosters', [NursingDigitalController::class, 'rosters'])->name('admin.nursing.rosters');
Route::get('/nursing/rosters/{roster}', [NursingDigitalController::class, 'showRoster'])->name('admin.nursing.rosters.show');
Route::post('/nursing/rosters/{roster}/approve', [NursingDigitalController::class, 'approveRoster'])->name('admin.nursing.rosters.approve');

    // Dentist
    Route::get('/add_dentist', [DentistController::class, 'create'])->name('add_dentist');
    Route::post('/add_dentist', [DentistController::class, 'store'])->name('store_doctor');
    Route::get('/dentist_list', [DentistController::class, 'showDentistList'])->name('dentist_list');
    Route::get('/show_dentist/{id}', [DentistController::class, 'show'])->name('dentists.show');
    Route::delete('/dentists/{dentist}', [DentistController::class, 'destroy'])->name('dentists.destroy');
    Route::get('/dentists/{dentist}/shifts', [DentistController::class, 'createDentistShift'])->name('dentists.shifts');
    Route::put('/dentists/{dentist}/shifts', [DentistController::class, 'storeDentistShift'])->name('storeDoctorShift.shifts');
    Route::get('/dentists/shifts', [DentistController::class, 'dentistShift'])->name('dentistShift');
    Route::delete('/dentists_shift/{shift}', [DentistController::class, 'destroydentistShift'])->name('destroydentistShift.shift.destroy');
    Route::get('/dentists/shifts/{id}/edit', [DentistController::class, 'editDentistShift'])->name('editDentistShift');
    Route::put('/dentists/shifts/{id}', [DentistController::class, 'updateDentistShift'])->name('updateDentistShift');
    Route::get('/patients/{patientId}/assignDentist', [PatientController::class, 'assignToDentist'])->name('admin.patients.assignDentist');
    Route::post('/patients/{patientId}/assignDentist', [PatientController::class, 'saveAssignmentDentist'])->name('admin.patients.saveAssignmentDentist');


    Route::get('/add_news', [NewsController::class, 'create'])->name('add_news');
    Route::post('/news', [NewsController::class, 'store'])->name('news.store');
    Route::get('/newss', [NewsController::class, 'show'])->name('newss');
    Route::get('/news/{id}', [NewsController::class, 'showSingle'])->name('news.show');



    Route::get('/departments',                   [DepartmentController::class, 'index'])->name('departments.index');
    Route::post('/departments',                  [DepartmentController::class, 'storeDepartment'])->name('departments.store');
    Route::delete('/departments/{id}',           [DepartmentController::class, 'destroyDepartment'])->name('departments.destroy');

    Route::post('/units',                        [DepartmentController::class, 'storeUnit'])->name('units.store');
    Route::delete('/units/{id}',                 [DepartmentController::class, 'destroyUnit'])->name('units.destroy');

    Route::post('/sub-units',                    [DepartmentController::class, 'storeSubUnit'])->name('subunits.store');
    Route::delete('/sub-units/{id}',             [DepartmentController::class, 'destroySubUnit'])->name('subunits.destroy');

    Route::put('/departments/{id}', [DepartmentController::class, 'updateDepartment'])->name('departments.update');
    Route::put('/units/{id}', [DepartmentController::class, 'updateUnit'])->name('units.update');
    Route::put('/sub-units/{id}', [DepartmentController::class, 'updateSubUnit'])->name('subunits.update');

    Route::post('/wards', [DepartmentController::class, 'storeWard'])->name('wards.store');
    Route::put('/wards/{id}', [DepartmentController::class, 'updateWard'])->name('wards.update');
    Route::delete('/wards/{id}', [DepartmentController::class, 'destroyWard'])->name('wards.destroy');















    // Route::post('login', [AuthenticatedSessionController::class, 'doctorLogin']);

    // Route::post('/doctor/login', [DoctorAuthController::class, 'doctorLogin'])->name('doctor.login');
    // Route::get('/doctor/dashboard', [DoctorDashboardController::class, 'index'])->name('doctor.dashboard')->middleware('auth:doctor');
    // Route::get('/hospital2/dashboard', [Hospital2Controller::class, 'index'])->name('hospital2.dashboard')->middleware('auth:doctor');

});


Route::get('doctor/login', [DashboardController::class, 'create'])->name('doctor.login');
Route::post('doctor/login', [DashboardController::class, 'doctorLogin'])->name('doctor.login.submit');
Route::get('/doctor/password-setup/{token}', [DoctorPasswordSetupController::class, 'show'])->name('doctor.password.setup');
Route::post('/doctor/password-setup/{token}', [DoctorPasswordSetupController::class, 'store'])->name('doctor.password.setup.submit');


Route::get('/doctor/forgot-password', [DoctorForgotPasswordController::class, 'showForm'])
    ->name('doctor.password.forgot');

Route::post('/doctor/forgot-password', [DoctorForgotPasswordController::class, 'sendResetLink'])
    ->name('doctor.password.forgot.send');

Route::get('/doctor/reset-password/{token}', [DoctorForgotPasswordController::class, 'showResetForm'])
    ->name('doctor.password.reset');

Route::post('/doctor/reset-password', [DoctorForgotPasswordController::class, 'resetPassword'])
    ->name('doctor.password.reset.submit');

//  Route::get('/news', [DoctorNewsController::class, 'show'])->name('news');
//    Route::get('/news/{id}', [DoctorNewsController::class, 'showSingle'])->name('news.show');
Route::middleware(['doctor.auth', 'doctor.activity'])->group(function () {
     Route::get('/doctor/news', [DoctorNewsController::class, 'show'])->name('doctor.news');
   Route::get('/doctor/news/{id}', [DoctorNewsController::class, 'showSingle'])->name('news.show');


    Route::get('/doctor/dashboard', [DashboardController::class, 'index'])->name('doctor.dashboard');
    Route::get('/doctor/profile', [ProfileController::class, 'index'])->name('doctor.profile');
    Route::put('/doctor/profile', [ProfileController::class, 'updateProfile'])->name('updateProfile');
    Route::get('/doctor/available', [AvailabilityController::class, 'index'])->name('doctor.available');
    Route::put('/doctor/available', [AvailabilityController::class, 'update'])->name('updateAvailable');

    // Doctor Patient
    Route::get('/doctor/add_patient', [DoctorPatientController::class, 'addPatients'])->name('doctor.add_patient');
    Route::post('/doctor/add_patient/store', [DoctorPatientController::class, 'storeDoctorAddPatients'])->name('doctor.store_patient');
    Route::get('/doctor/patient', [DoctorPatientController::class, 'myPatients'])->name('doctor.patient');
    Route::get('/doctor/patient/plan/{patient}', [DoctorPatientController::class, 'store'])->name('doctor.patients.plan');
    Route::post('/doctor/patient/medication/store', [DoctorPatientController::class, 'storeMedication'])->name('doctor.medications.store');

    Route::get('/doctor/patient/medication/{id}', [DoctorPatientController::class, 'editMedication'])->name('doctor.edit_medication');
    Route::put('/doctor/patient/medication/update/{id}', [DoctorPatientController::class, 'updateMedication'])->name('doctor.update_medication');
    Route::get('/doctor/patient/edit/{id}', [DoctorPatientController::class, 'edit'])->name('doctor.edit_patients');
    Route::put('/doctor/patient/update/{id}', [DoctorPatientController::class, 'patientUpdate'])->name('doctor.patientUpdate');
    Route::get('/doctor/show_patient/{id}', [DoctorPatientController::class, 'show'])->name('doctor.show_patient');
    Route::delete('/medications/{id}', [DoctorPatientController::class, 'destroy'])->name('medications.destroy');

    Route::post('/doctor/handover/store', [DoctorPatientController::class, 'storeHandover'])->name('doctor.handover.store');
    Route::get('/doctor/handover/show/{patient_id}', [DoctorPatientController::class, 'showHandover'])->name('doctor.handover.show'); 
    Route::get('/doctor/handover/edit/{id}', [DoctorPatientController::class, 'editHandoverNote'])->name('doctor.handover.edit');
    Route::put('/doctor/handover/update/{id}', [DoctorPatientController::class, 'updateHandoverNote'])->name('doctor.handover.update');
    Route::post('/lab-orders/store', [DoctorPatientController::class, 'store'])->name('lab-orders.store');

    Route::get('/doctor/news', [DoctorNewsController::class, 'show'])->name('doctor.news');
    Route::get('/doctor/news/{id}', [DoctorNewsController::class, 'showSingle'])->name('doctor.show');


    Route::post('/doctor/invite-unit', [DoctorPatientController::class, 'inviteUnit'])->name('doctor.invite.unit');
    Route::get('/get-units-doctor/{department_id}', [DoctorPatientController::class, 'getUnits'])->name('get.units');
    Route::get('/get-sub-units-doctor/{unit_id}', [DoctorPatientController::class, 'getSubUnits'])->name('get.sub.units');


    Route::post('/doctor/patient/discharge', [DoctorPatientController::class, 'discharge'])
    ->name('doctor.patient.discharge');
    Route::post('/doctor/patient/{patient}/accept-transfer', [DoctorPatientController::class, 'acceptTransfer'])->name('doctor.accept_transfer');
    Route::post('/doctor/patient/{patient}/reject-transfer', [DoctorPatientController::class, 'rejectTransfer'])->name('doctor.reject_transfer');
    Route::post('/doctor/notifications/seen', [DoctorPatientController::class, 'markNotificationsSeen'])
    ->name('doctor.notifications.seen');


    Route::post('/doctor/invites/{invite}/accept', [DoctorPatientController::class, 'acceptInvite'])->name('doctor.invite.accept');
    Route::post('/doctor/invites/{invite}/reject', [DoctorPatientController::class, 'rejectInvite'])->name('doctor.invite.reject');


    Route::get('/doctor/password/change',  [DoctorPasswordSetupController::class, 'showss'])
         ->name('doctor.password.change');
    Route::post('/doctor/password/change', [DoctorPasswordSetupController::class, 'storess'])
         ->name('doctor.password.change.store');

           Route::get('/doctor/unit/doctors', [DoctorDoctorController::class, 'unitDoctors'])
         ->name('doctor.unit.doctors');


    Route::post('/doctor/logout', [DashboardController::class, 'logout'])
    ->name('doctor.logout');




});


Route::get('nurse/login', [NurseDashboardController::class, 'createNurse'])->name('nurse.login');
Route::post('nurse/login', [NurseDashboardController::class, 'nurseLogin'])->name('nurse.login.submit');

Route::middleware(['authNurse'])->group(function () {
    Route::get('/nurse/dashboard', [NurseDashboardController::class, 'indexNurse'])->name('nurse.dashboard');
    Route::get('/nurse/notifications', [NurseNotificationController::class, 'index'])->name('nurse.notifications.index');
    Route::post('/nurse/notifications/{notification}/read', [NurseNotificationController::class, 'markAsRead'])->name('nurse.notifications.read');
    Route::post('/nurse/notifications/{notification}/acknowledge', [NurseNotificationController::class, 'acknowledge'])->name('nurse.notifications.acknowledge');
    Route::get('/nurse/profile', [NurseController::class, 'editProfile'])->name('nurse.profile');
    Route::put('/nurse/profile', [NurseController::class, 'updateProfile'])->name('nurse.profile.update');
    Route::get('/nurse/available', [NurseController::class, 'index'])->name('nurse.available');
    Route::put('/nurse/available', [NurseController::class, 'update'])->name('nurseAvailable');
    Route::post('/nurse/handover', [NursePatientController::class, 'storeHandover'])->name('nurse.handover');
    Route::get('/nurse/handovershow/{patient_id}', [NursePatientController::class, 'showHandover'])->name('nurse.handovershow'); 
    Route::get('/nurse/handoveredit/{id}', [NursePatientController::class, 'editHandoverNote'])->name('nurse.handoveredit');
    Route::put('/nurse/handoverupdate/{id}', [NursePatientController::class, 'updateHandoverNote'])->name('nurse.handoverupdate');
    Route::get('/nurse/patient', [NursePatientController::class, 'myPatients'])->name('nurse.patient');
    Route::get('/nurse/patients/create', [NursePatientController::class, 'createPatient'])->name('nurse.patients.create');
    Route::post('/nurse/patients', [NursePatientController::class, 'storePatient'])->name('nurse.patients.store');
    Route::get('/nurse/show_patient/{id}', [NursePatientController::class, 'show'])->name('nurse.show_patient');

    Route::get('/nurse/patient/plan/{patient}', [NursePatientController::class, 'store'])->name('nurse.patients.plan');
    Route::post('/nurse/patient/medication/store', [NursePatientController::class, 'storeMedication'])->name('nurse.medicationsstore');
    Route::get('/nurse/patient/medication/{id}', [NursePatientController::class, 'editMedication'])->name('nurse.edit_medication');
    Route::put('/nurse/patient/medication/update/{id}', [NursePatientController::class, 'updateMedication'])->name('nurse.update_medication');
    Route::delete('nurse/medications/{id}', [NursePatientController::class, 'NursePatientdestroy'])->name('nursemedications.destroy');


    Route::get('/nurse/news', [NurseNewsController::class, 'show'])->name('nurse.news');
    Route::get('/nurse/news/{id}', [NurseNewsController::class, 'showSingle'])->name('nurse.show');

        Route::get('/nurse/reports', [NursingReportController::class, 'index'])->name('nurse.reports.index');
    Route::get('/nurse/reports/create', [NursingReportController::class, 'create'])->name('nurse.reports.create');
    Route::post('/nurse/reports', [NursingReportController::class, 'store'])->name('nurse.reports.store');
    Route::get('/nurse/reports/{report}', [NursingReportController::class, 'show'])->name('nurse.reports.show');

    Route::get('/nurse/statistics', [NursingStatisticController::class, 'index'])->name('nurse.statistics.index');
    Route::get('/nurse/statistics/create', [NursingStatisticController::class, 'create'])->name('nurse.statistics.create');
    Route::post('/nurse/statistics', [NursingStatisticController::class, 'store'])->name('nurse.statistics.store');
    Route::get('/nurse/statistics/{statistic}', [NursingStatisticController::class, 'show'])->name('nurse.statistics.show');

    Route::get('/nurse/rosters', [NursingRosterController::class, 'index'])->name('nurse.rosters.index');
    Route::get('/nurse/rosters/create', [NursingRosterController::class, 'create'])->name('nurse.rosters.create');
    Route::post('/nurse/rosters', [NursingRosterController::class, 'store'])->name('nurse.rosters.store');
    Route::get('/nurse/rosters/{roster}', [NursingRosterController::class, 'show'])->name('nurse.rosters.show');
    Route::post('/nurse/rosters/{roster}/submit', [NursingRosterController::class, 'submit'])->name('nurse.rosters.submit');




});



Route::get('dentist/login', [DentistDashboardController::class, 'create'])->name('dentist.login');
Route::post('dentist/login', [DentistDashboardController::class, 'dentistLogin'])->name('dentist.login.submit');

Route::middleware(['auth:dentist'])->group(function () {
    // dd('sdddddddsd');
    Route::get('/dentist/dashboard', [DentistDashboardController::class, 'index'])->name('dentist.dashboard');
    Route::get('/dentist/profile', [DentistProfileController::class, 'index'])->name('dentist.profile');
    Route::put('/dentist/profile', [DentistProfileController::class, 'updateProfile'])->name('updateProfile');
    Route::get('/dentist/available', [DentistAvailabilityController::class, 'index'])->name('dentist.available');
    Route::put('/dentist/available', [DentistAvailabilityController::class, 'update'])->name('updateAvailable');

    // Patienint
    Route::get('/dentist/add_patient', [DentistPatientController::class, 'addPatients'])->name('dentist.add_patient');
    Route::post('/dentist/add_patient/store', [DentistPatientController::class, 'storeDentistAddPatients'])->name('dentist.store_patient');

    Route::get('/dentist/patient', [DentistPatientController::class, 'myPatients'])->name('dentist.patient');
    Route::get('/dentist/patient/plan/{patient}', [DentistPatientController::class, 'store'])->name('dentist.patients.plan');
    Route::post('/dentist/patient/medication/store', [DentistPatientController::class, 'storeMedication'])->name('medications.store');
    Route::get('/dentist/patient/medication/{id}', [DentistPatientController::class, 'editMedication'])->name('edit_medication');
    Route::put('/dentist/patient/medication/update/{id}', [DentistPatientController::class, 'updateMedication'])->name('update_medication');
    Route::get('/dentist/patient/edit/{id}', [DentistPatientController::class, 'edit'])->name('dentist.edit_patients');
    Route::put('/dentist/patient/update/{id}', [DentistPatientController::class, 'patientUpdate'])->name('dentist.patientUpdate');
    Route::get('/dentist/show_patient/{id}', [DentistPatientController::class, 'show'])->name('dentist.show_patient');
    Route::delete('/medications/{id}', [DentistPatientController::class, 'destroy'])->name('medications.destroy');

    
    Route::post('/handover/store', [DentistPatientController::class, 'storeHandover'])->name('handover.store');
    Route::get('/handover/show/{patient_id}', [DentistPatientController::class, 'showHandover'])->name('handover.show'); 
    Route::get('/handover/edit/{id}', [DentistPatientController::class, 'editHandoverNote'])->name('handover.edit');
    Route::put('/handover/update/{id}', [DentistPatientController::class, 'updateHandoverNote'])->name('handover.update');
    Route::post('/lab-orders/store', [DentistPatientController::class, 'store'])->name('lab-orders.store');

    
    Route::get('/dentist/news', [NurseNewsController::class, 'show'])->name('dentist.news');
    Route::get('/dentist/news/{id}', [NurseNewsController::class, 'showSingle'])->name('dentist.show');


});





require __DIR__.'/auth.php';
