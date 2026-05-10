<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

use App\Http\Controllers\AuthController;

use App\Http\Controllers\Admin\TemplateController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\TemplatesController;

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
| PLANTILLAS (usuario)
|--------------------------------------------------------------------------
*/

Route::get('/plantillas', [TemplatesController::class, 'index'])->name('templates.list');
Route::get('/plantillas/{template:slug}', [TemplatesController::class, 'show'])->name('templates.preview');
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::put('/dashboard/profile', [DashboardController::class, 'updateProfile'])->name('dashboard.profile.update');
    Route::get('/dashboard/cv/download', [DashboardController::class, 'downloadCv'])->name('dashboard.cv.download');
});

// web.php
Route::middleware('auth')->group(function () {
    Route::put('/dashboard/cvweb/{cvWebId}/section/{section}', 
        [CvWebController::class, 'updateSection']
    )->name('dashboard.cvweb.update');
});
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

    /*
    |--------------------------------------------------------------------------
    | USERS MANAGEMENT
    |--------------------------------------------------------------------------
    */

    Route::get('/users', [UserController::class, 'index'])->name('admin.users.index');
    Route::post('/users', [UserController::class, 'store'])->name('admin.users.store');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('admin.users.destroy');
    Route::patch('/users/{user}/toggle-admin', [UserController::class, 'toggleAdmin'])->name('admin.users.toggle-admin');
    Route::patch('/users/{user}/verify', [UserController::class, 'verify'])->name('admin.users.verify');
    Route::post('/users/{user}/resend-verification', [UserController::class, 'resendVerification'])->name('admin.users.resend-verification');

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