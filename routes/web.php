<?php

use App\Http\Controllers\WebController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/login', function () {

    if (Auth::guard('employees')->check()) {
        return   redirect('/');
    }
    return view('welcome');
});


Route::get('/destroy/{id}', [WebController::class, 'destroy'])->name('employee.destroy');



Route::get('/form-status/{action}', [WebController::class, 'showStatus'])->name('form.status');
Route::get('/form-action/{id}/{action}', [WebController::class, 'handleAction'])->name('form.action');
Route::post('/login', [WebController::class, 'login'])->name('login');
Route::post('/logout', [WebController::class, 'logout'])->name('logout');
Route::post('/save', [WebController::class, 'save'])->name('save');
Route::get('/', [WebController::class, 'index'])->name('home');
Route::get('/setting', [WebController::class, 'setting'])->name('setting');
Route::post('/setting', [WebController::class, 'settingIndex'])->name('setting');
    // ->middleware('auth');




// Route::get('/', function () {
//     return view('home');
// })->name('home');