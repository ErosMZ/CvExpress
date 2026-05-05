<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| HOME
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| AUTH (registro / login)
|--------------------------------------------------------------------------
*/

Route::get('/register', [AuthController::class, 'showRegister']);
Route::post('/register', [AuthController::class, 'register']);

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

/*
|--------------------------------------------------------------------------
| VERIFICACIÓN DE EMAIL
|--------------------------------------------------------------------------
*/

// pantalla que ve el usuario si NO está verificado
Route::get('/email/verify', function () {
    return view('verify-email');
})->middleware('auth')->name('verification.notice');

// link del email (clic de verificación)
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();

    return redirect('/dashboard');
})->middleware(['auth', 'signed'])->name('verification.verify');

// reenviar email de verificación
Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();

    return back()->with('message', 'Email de verificación enviado');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

/*
|--------------------------------------------------------------------------
| DASHBOARD PROTEGIDO
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', function () {
    return "Estás logueado y verificado";
})->middleware(['auth', 'verified']);

/*
|--------------------------------------------------------------------------
| TEST MAIL (solo pruebas)
|--------------------------------------------------------------------------
*/

Route::get('/test-mail', function () {
    Mail::raw('Correo de prueba desde Laravel + SendGrid', function ($message) {
        $message->to('TU_CORREO_REAL@gmail.com')
                ->subject('Test SendGrid');
    });

    return 'Correo enviado';
});