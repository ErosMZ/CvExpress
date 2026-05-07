<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

use App\Http\Controllers\AuthController;

use App\Http\Controllers\Admin\TemplateController;
use App\Http\Controllers\Admin\CategoryController;

/*
|--------------------------------------------------------------------------
| HOME
|--------------------------------------------------------------------------
*/

Route::get('/', function () {

    return view('index');

})->name('home');

/*
|--------------------------------------------------------------------------
| AUTH
|--------------------------------------------------------------------------
*/

Route::get('/register', [AuthController::class, 'showRegister'])
    ->name('register');

Route::post('/register', [AuthController::class, 'register'])
    ->name('register.post');

Route::get('/login', [AuthController::class, 'showLogin'])
    ->name('login');

Route::post('/login', [AuthController::class, 'login'])
    ->name('login.post');

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

/*
|--------------------------------------------------------------------------
| EMAIL VERIFICATION
|--------------------------------------------------------------------------
*/

Route::get('/email/verify', function () {

    return view('verify-email');

})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {

    $request->fulfill();

    return redirect()->route('dashboard');

})->middleware([
    'auth',
    'signed'
])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {

    $request->user()->sendEmailVerificationNotification();

    return back()->with('message', 'Email de verificación enviado');

})->middleware([
    'auth',
    'throttle:6,1'
])->name('verification.send');

/*
|--------------------------------------------------------------------------
| USER DASHBOARD
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', function () {

    return view('dashboard');

})->middleware([
    'auth',
    'verified'
])->name('dashboard');

/*
|--------------------------------------------------------------------------
| ADMIN PANEL
|--------------------------------------------------------------------------
*/

Route::middleware([
    'auth',
    'verified',
    'admin'
])

->prefix('admin')

->group(function () {

    /*
    |--------------------------------------------------------------------------
    | ADMIN HOME
    |--------------------------------------------------------------------------
    */

    Route::get('/', function () {

        return view('admin.dashboard');

    })->name('admin');

    /*
    |--------------------------------------------------------------------------
    | TEMPLATES CRUD
    |--------------------------------------------------------------------------
    */

    Route::resource(
        'templates',
        TemplateController::class
    );

    /*
    |--------------------------------------------------------------------------
    | CATEGORIES CRUD
    |--------------------------------------------------------------------------
    */

    Route::resource(
        'categories',
        CategoryController::class
    );

});

    /*
    |--------------------------------------------------------------------------
    | USERS (PRÓXIMAMENTE)
    |--------------------------------------------------------------------------
    */

    Route::get('/users', function () {

        return "Panel de usuarios próximamente";

    })->name('users.index');

/*
|--------------------------------------------------------------------------
| TEST MAIL
|--------------------------------------------------------------------------
*/

Route::get('/test-mail', function () {

    Mail::raw('Correo de prueba desde Laravel + SendGrid', function ($message) {

        $message->to('TU_CORREO_REAL@gmail.com')
                ->subject('Test SendGrid');

    });

    return 'Correo enviado';

});

/*
|--------------------------------------------------------------------------
| STATIC PAGES
|--------------------------------------------------------------------------
*/

Route::view('/about', 'about')
    ->name('about');

Route::view('/contact', 'contact')
    ->name('contact');

Route::view('/privacy', 'privacy')
    ->name('privacy');

Route::view('/terms', 'terms')
    ->name('terms');