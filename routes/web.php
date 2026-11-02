<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BerandaController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return redirect()->route('backend.login');
});

Route::get('backend/beranda', [BerandaController::class, 'berandaBackend'])
    ->name('backend.beranda')->middleware('auth');

Route::get('backend/login', [LoginController::class, 'loginBackend'])
    ->name('backend.login');

Route::post('backend/login', [LoginController::class, 'authenticateBackend'])
    ->name('backend.login');

Route::post('backend/logout', [LoginController::class, 'logoutBackend'])
    ->name('backend.logout');

// Resource route untuk User dengan alias backend
Route::resource('backend/user', UserController::class, ['as' => 'backend'])
    ->middleware('auth');

    