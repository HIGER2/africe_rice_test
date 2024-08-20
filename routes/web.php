<?php

use App\Exports\EmployeeInformationExport;
use App\Http\Controllers\WebController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Maatwebsite\Excel\Facades\Excel;

Route::get('/login', function () {

    if (Auth::guard('employees')->check()) {
        return   redirect('/');
    }
    return view('welcome');
});


Route::get('/destroy/{id}', [WebController::class, 'destroy'])->name('employee.destroy');



Route::get('/form-status/{action}', [WebController::class, 'showStatus'])->name('form.status');
Route::post('/form-action/{id}/{action}', [WebController::class, 'handleAction'])->name('form.action');
Route::get('/form-action/{id}/{action}/confirm', [WebController::class, 'confirmAction'])->name('form.confirm');

Route::get('/export-request', function () {
    return Excel::download(new EmployeeInformationExport, 'request_staff.xlsx');
})->name('request.export');

Route::post('/login', [WebController::class, 'login'])->name('login');

Route::get('/logout', [WebController::class, 'logout'])->name('logout');
Route::post('/save', [WebController::class, 'save'])->name('save');
Route::get('/', [WebController::class, 'index'])->name('home');
Route::get('/setting', [WebController::class, 'setting'])->name('setting');
Route::post('/setting', [WebController::class, 'settingIndex'])->name('setting');
Route::get('/liste', [WebController::class, 'showInformations'])->name('liste');
Route::get('/request-approuve', [WebController::class, 'requestApprouved'])->name('request.approve');


Route::get('/service-email/{id?}', [WebController::class, 'serviceEmail'])->name('service.email.get');
Route::post('/service-email', [WebController::class, 'serviceEmailSave'])->name('service.email');
Route::delete('/service-destroy/{id}', [WebController::class, 'destroyEmail'])->name('service.email.destroy');

// ->middleware('auth');
Route::post('/payment-confirm/{request_id}', [WebController::class, 'paymentConfirm'])->name('payment.confirm');

// Route::middleware(['auth'])->group(function () {
// });


// Route::get('/', function () {
//     return view('home');
// })->name('home');