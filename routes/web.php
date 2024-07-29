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


Route::post('/login', [WebController::class, 'login'])->name('login');
Route::post('/logout', [WebController::class, 'logout'])->name('logout');
Route::post('/save', [WebController::class, 'save'])->name('save');
Route::get('/', [WebController::class, 'index'])
    ->name('home');
    // ->middleware('auth');




// Route::get('/', function () {
//     return view('home');
// })->name('home');