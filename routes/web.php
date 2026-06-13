<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PinLoginController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AbsenceController;
use App\Http\Controllers\PlanningController;
use App\Http\Controllers\ReportController;
/*
|--------------------------------------------------------------------------
| AUTH (PIN LOGIN)
|--------------------------------------------------------------------------
*/
Route::middleware('session.guest')->group(function () {

    Route::get('/', [PinLoginController::class, 'show'])
        ->name('pin.show');

    Route::post('/pin-login', [PinLoginController::class, 'store'])
        ->name('pin.store');
});
/*
|--------------------------------------------------------------------------
| FAKE LOGIN ROUTE (FIX Laravel auth error)
|--------------------------------------------------------------------------
*/
Route::get('/login', function () {
    return redirect('/');
})->name('login');


/*
|--------------------------------------------------------------------------
| LOGOUT
|--------------------------------------------------------------------------
*/
Route::post('/logout', [PinLoginController::class, 'destroy'])
    ->middleware('session.auth')
    ->name('logout');


/*
|--------------------------------------------------------------------------
| ADMIN AREA
|--------------------------------------------------------------------------
*/
Route::middleware(['session.auth', 'role:admin'])
    ->prefix('admin')
    ->group(function () {

        Route::get('/dashboard', [AdminDashboardController::class, 'index'])
            ->name('admin.dashboard');

        Route::resource('employees', EmployeeController::class)
            ->except(['show']);

        Route::get('/attendance', [AttendanceController::class, 'index'])
            ->name('attendance.index');

        Route::post('/attendance', [AttendanceController::class, 'store'])
            ->name('attendance.store');

        Route::get('/absences', [AbsenceController::class, 'index'])
            ->name('absences.index');

        Route::post('/absences', [AbsenceController::class, 'store'])
            ->name('absences.store');

        Route::delete('/absences/{absence}', [AbsenceController::class, 'destroy'])
            ->name('absences.destroy');

        Route::get('/planning', [PlanningController::class, 'index'])
            ->name('planning.index');

        Route::post('/planning/bulk-save', [PlanningController::class, 'bulkSave'])
            ->name('planning.bulk-save');

        Route::get('/reports', [ReportController::class, 'index'])
            ->name('reports.index');
    });


/*
|--------------------------------------------------------------------------
| EMPLOYEE AREA
|--------------------------------------------------------------------------
*/
Route::middleware(['session.auth', 'role:employee'])
    ->prefix('employee')
    ->name('employee.')
    ->group(function () {

        Route::get('/attendance', [PinLoginController::class, 'employeeAttendance'])
            ->name('attendance');

        Route::post('/check-in', [PinLoginController::class, 'checkIn'])
            ->name('check-in');

        Route::post('/check-out', [PinLoginController::class, 'checkOut'])
            ->name('check-out');
    });
