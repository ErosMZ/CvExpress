<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CvParseController;
use App\Http\Controllers\DashboardController;

use App\Http\Controllers\Admin\TemplateController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\PlanController;
use App\Http\Controllers\Admin\SiteController as AdminSiteController;
use App\Http\Controllers\TemplatesController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CvPdfController;
use App\Http\Controllers\PublicSiteController;

/*
|--------------------------------------------------------------------------
| WEBS PUBLICADAS POR SUBDOMINIO
|--------------------------------------------------------------------------
| Debe ir ANTES que las rutas sin dominio (p. ej. "/"), porque Laravel
| resuelve por orden de registro y una ruta sin restricción de dominio
| encajaría con cualquier host, incluidos los subdominios.
|
| Dominio base configurable vía PUBLIC_SITES_DOMAIN (ver config/services.php):
| "localhost" en local (prueba con http://tunombre.localhost:8000),
| "cvxpress.es" en producción una vez el dominio esté registrado y el DNS
| comodín (*.cvxpress.es) apunte al servidor.
*/

Route::domain('{subdomain}.' . config('services.public_sites.domain'))->group(function () {
    Route::get('/{path?}', [PublicSiteController::class, 'show'])
        ->where('path', '.*')
        ->name('public-site.show');
});

/*
|--------------------------------------------------------------------------
| HOME
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    $featuredTemplates = \App\Models\Template::with('category')
        ->where('is_active', true)
        ->latest()
        ->get();
    // "Planes anuales" del index es solo CV Web con hosting — nada de PDF
    // ni del plan de solo-descarga (ese se ofrece dentro de cada plantilla).
    $plans = \App\Models\Plan::where('is_active', true)->where('category', 'template')->orderBy('sort_order')->get();
    return view('index', compact('featuredTemplates', 'plans'));
})->name('home');

/*
|--------------------------------------------------------------------------
| PLANTILLAS (usuario)
|--------------------------------------------------------------------------
*/

Route::get('/plantillas', [TemplatesController::class, 'index'])->name('templates.list');
Route::get('/plantillas/{template:slug}', [TemplatesController::class, 'show'])->name('templates.preview');

/*
|--------------------------------------------------------------------------
| CHECKOUT
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    // Antes que /checkout/{plan:slug} para que "descarga" no se confunda con un slug de plan.
    Route::get('/checkout/descarga/{template:slug}',  [CheckoutController::class, 'showDownload'])->name('checkout.download.show');
    Route::post('/checkout/descarga/{template:slug}', [CheckoutController::class, 'processDownload'])->name('checkout.download.process');

    Route::get('/checkout/{plan:slug}',  [CheckoutController::class, 'show'])->name('checkout.show');
    Route::post('/checkout/{plan:slug}', [CheckoutController::class, 'process'])->name('checkout.process');
    Route::get('/invoice/{purchase}',    [CheckoutController::class, 'invoice'])->name('checkout.invoice');
    Route::get('/invoice/{purchase}/pdf',[CheckoutController::class, 'downloadPdf'])->name('checkout.pdf');
});
Route::middleware('auth')->group(function () {
    Route::get('/cv-pdf/editor', [CvPdfController::class, 'editor'])->name('cv-pdf.editor');
    Route::post('/cv-pdf/improve-profile', [CvPdfController::class, 'improveProfile'])->name('cv-pdf.improve-profile');
    Route::post('/cv-pdf/buy/{plan}', [CvPdfController::class, 'buyPdfAccess'])->name('cv-pdf.buy');
    Route::post('/cv-pdf/download', [CvPdfController::class, 'download'])->name('cv-pdf.download');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard',                              [DashboardController::class, 'index'])->name('dashboard');
    Route::put('/dashboard/profile',                     [DashboardController::class, 'updateProfile'])->name('dashboard.profile.update');
    Route::get('/dashboard/cv/download',                 [DashboardController::class, 'downloadCv'])->name('dashboard.cv.download');
    Route::post('/dashboard/plan/{plan}/activate',       [DashboardController::class, 'activatePlan'])->name('dashboard.plan.activate');
    Route::post('/dashboard/cv-data',                        [DashboardController::class, 'updateCvData'])->name('dashboard.cv-data.update');
    Route::delete('/dashboard/cv-data',                      [DashboardController::class, 'clearCvData'])->name('dashboard.cv-data.clear');
    Route::patch('/dashboard/purchase/{purchase}/hosting', [DashboardController::class, 'updateHosting'])->name('dashboard.purchase.hosting');
    Route::delete('/dashboard/purchase/{purchase}/cancel', [DashboardController::class, 'cancelPlan'])->name('dashboard.purchase.cancel');
    Route::post('/dashboard/purchase/{purchase}/template/{template}', [DashboardController::class, 'selectTemplate'])->name('dashboard.template.select');

    // Foto de perfil
    Route::post('/dashboard/photo', [DashboardController::class, 'uploadPhoto'])->name('dashboard.photo.upload');

    // AI CV parsing
    Route::post('/dashboard/cv/parse',              [CvParseController::class, 'store'])->name('dashboard.cv.parse');
    Route::get('/dashboard/cv/parse/{cvParse}/status', [CvParseController::class, 'status'])->name('dashboard.cv.parse.status');
});

// Route::middleware('auth')->group(function () {
//     Route::put('/dashboard/cvweb/{cvWebId}/section/{section}',
//         [CvWebController::class, 'updateSection']
//     )->name('dashboard.cvweb.update');
// });
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

Route::get('/email/verify/{id}/{hash}', function (Request $request, $id, $hash) {

    $user = \App\Models\User::findOrFail($id);

    abort_unless($request->hasValidSignature(), 403);
    abort_unless(hash_equals((string) sha1($user->getEmailForVerification()), (string) $hash), 403);

    if (!$user->hasVerifiedEmail()) {
        $user->markEmailAsVerified();
        event(new \Illuminate\Auth\Events\Verified($user));
    }

    return view('email-verified');

})->middleware(['signed'])->name('verification.verify');

Route::get('/email/verification-status', function () {
    return response()->json(['verified' => auth()->user()?->hasVerifiedEmail() ?? false]);
})->middleware('auth');

Route::post('/email/verification-notification', function (Request $request) {

    try {
        $request->user()->sendEmailVerificationNotification();
    } catch (\Throwable $e) {
        \Illuminate\Support\Facades\Log::error('Reenvío de verificación falló', [
            'user_id' => $request->user()->id,
            'error'   => $e->getMessage(),
        ]);
        return back()->with('error', 'Ahora mismo no podemos enviar el correo. Inténtalo de nuevo más tarde.');
    }

    return back()->with('message', 'Email de verificación enviado');

})->middleware([
    'auth',
    'throttle:6,1'
])->name('verification.send');

/*
|--------------------------------------------------------------------------
| USER DASHBOARD  (handled by DashboardController above)
|--------------------------------------------------------------------------
*/

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

    Route::delete('/templates-bulk', [TemplateController::class, 'bulkDestroy'])->name('templates.bulk-destroy');

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

    /*
    |--------------------------------------------------------------------------
    | PLANS CRUD
    |--------------------------------------------------------------------------
    */

    Route::get('/planes',                  [PlanController::class, 'index'])->name('admin.plans.index');
    Route::post('/planes',                 [PlanController::class, 'store'])->name('admin.plans.store');
    Route::put('/planes/{plan}',           [PlanController::class, 'update'])->name('admin.plans.update');
    Route::delete('/planes/{plan}',        [PlanController::class, 'destroy'])->name('admin.plans.destroy');

    /*
    |--------------------------------------------------------------------------
    | WEBS PUBLICADAS (subdominios)
    |--------------------------------------------------------------------------
    */

    Route::get('/sites', [AdminSiteController::class, 'index'])->name('admin.sites.index');
    Route::delete('/sites/{purchase}', [AdminSiteController::class, 'destroy'])->name('admin.sites.destroy');

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

Route::get('/test-mail', function (Request $request) {

    abort_unless(app()->environment('local'), 404);

    $to = $request->query('to');
    abort_unless(filter_var($to, FILTER_VALIDATE_EMAIL), 422, 'Pasa ?to=correo@valido.com');

    try {
        Mail::raw('Correo de prueba desde Laravel', function ($message) use ($to) {
            $message->to($to)->subject('Test mail CvXpress');
        });
    } catch (\Throwable $e) {
        return response('Fallo al enviar: ' . $e->getMessage(), 500);
    }

    return 'Correo enviado (revisa la bandeja o storage/logs/laravel.log si usas el mailer failover)';

});

/*
|--------------------------------------------------------------------------
| /app → panel de producto (app-landing)
|--------------------------------------------------------------------------
*/
Route::get('/app', function () {
    $templates  = \App\Models\Template::with('category')->where('is_active', true)->latest()->get();
    $categories = \App\Models\Category::where('is_active', true)->orderBy('name')->get();
    // CV Web (con hosting o solo descarga) — nunca los planes de PDF aquí.
    $plans      = \App\Models\Plan::where('is_active', true)->whereIn('category', ['template', 'web_download'])->orderBy('sort_order')->get();
    $featured   = $templates->where('is_featured', true)->first() ?? $templates->first();
    return view('app-landing', compact('templates', 'categories', 'plans', 'featured'));
})->name('app-landing');

/*
|--------------------------------------------------------------------------
| CV WEB — editor dedicado
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/cv-web', [DashboardController::class, 'cvWeb'])->name('cv-web.editor');
    Route::post('/cv-web/template/{purchase}/{template}', [DashboardController::class, 'cvWebSelectTemplate'])
         ->name('cv-web.template.select');
    Route::post('/cv-web/download-zip', [DashboardController::class, 'cvWebDownloadZip'])
         ->name('cv-web.download-zip');
    Route::post('/cv-web/publish', [DashboardController::class, 'cvWebPublish'])
         ->name('cv-web.publish');
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