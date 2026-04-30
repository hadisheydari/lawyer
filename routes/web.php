<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Client\DashboardController as ClientDashboardController;
use App\Http\Controllers\Public\ArticleCommentController;
use App\Http\Controllers\Public\ArticleController;
use App\Http\Controllers\Public\ArticleReactionController;
use App\Http\Controllers\Public\CalculatorController;
use App\Http\Controllers\Public\ContactController;
use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\LawyerController;
use App\Http\Controllers\Public\ReserveController;
use App\Http\Controllers\Public\ServiceController;
use Illuminate\Support\Facades\Route;

// ═══════════════════════════════════════════════════════════════
// PUBLIC ROUTES
// ═══════════════════════════════════════════════════════════════

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::prefix('lawyers')->name('lawyers.')->group(function () {
    Route::get('/', [LawyerController::class, 'index'])->name('index');
    Route::get('/{slug}', [LawyerController::class, 'show'])->name('show');
});

Route::prefix('services')->name('services.')->group(function () {
    Route::get('/', [ServiceController::class, 'index'])->name('index');
    Route::get('/{slug}', [ServiceController::class, 'show'])->name('show');
});

Route::prefix('articles')->name('articles.')->group(function () {
    Route::get('/', [ArticleController::class, 'index'])->name('index');
    Route::get('/{slug}', [ArticleController::class, 'show'])->name('show');
});

Route::get('/calculators', [CalculatorController::class, 'index'])->name('calculators.index');

Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'send'])->name('contact.send');

Route::get('/about', [LawyerController::class, 'index'])->name('about');

// ═══════════════════════════════════════════════════════════════
// Article Comments & Reactions (auth required)
// ═══════════════════════════════════════════════════════════════

Route::middleware('auth')->group(function () {
    Route::post('/articles/comments', [ArticleCommentController::class, 'store'])
        ->name('articles.comments.store');
    Route::put('/articles/comments/{comment}', [ArticleCommentController::class, 'update'])
        ->name('articles.comments.update');
    Route::delete('/articles/comments/{comment}', [ArticleCommentController::class, 'destroy'])
        ->name('articles.comments.destroy');

    Route::post('/articles/reactions', [ArticleReactionController::class, 'store'])
        ->name('articles.reactions.store');
});

// ═══════════════════════════════════════════════════════════════
// RESERVE ROUTES
// ═══════════════════════════════════════════════════════════════

Route::prefix('reserve')->name('reserve.')->group(function () {
    Route::get('/', [ReserveController::class, 'index'])->name('index');
    Route::post('/', [ReserveController::class, 'store'])->name('store');
    Route::get('/slots', [ReserveController::class, 'getAvailableSlots'])->name('slots');
    Route::get('/verify/{payment}', [ReserveController::class, 'verifyPayment'])->name('verify');
});

// ═══════════════════════════════════════════════════════════════
// API — محاسبات
// ═══════════════════════════════════════════════════════════════

Route::prefix('api/calculators')->name('api.calc.')->group(function () {
    Route::get('/mahrieh', [CalculatorController::class, 'calcMahrieh'])->name('mahrieh');
    Route::get('/dieh', [CalculatorController::class, 'calcDieh'])->name('dieh');
    Route::get('/court', [CalculatorController::class, 'calcCourt'])->name('court');
});

// ═══════════════════════════════════════════════════════════════
// AUTH ROUTES
// ═══════════════════════════════════════════════════════════════

// ✅ clear-otp-session باید خارج از guest middleware باشد تا در هر حالتی کار کند
Route::post('/clear-otp-session', [AuthController::class, 'clearOtpSession'])->name('auth.clear-session');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.submit');
    Route::post('/send-otp', [AuthController::class, 'sendOtp'])->name('auth.send-otp')->middleware('throttle:5,1');
    Route::post('/verify-otp', [AuthController::class, 'verifyOtp'])->name('auth.verify-otp');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// ═══════════════════════════════════════════════════════════════
// CLIENT DASHBOARD (Auth Required)
// ═══════════════════════════════════════════════════════════════

Route::middleware(['auth'])->prefix('dashboard')->name('dashboard.')->group(function () {
    Route::get('/', [ClientDashboardController::class, 'index'])->name('index');
    Route::get('/profile', [ClientDashboardController::class, 'profile'])->name('profile');
});

// alias بدون prefix برای redirect بعد از لاگین
Route::get('/dashboard', [ClientDashboardController::class, 'index'])
    ->middleware('auth')
    ->name('dashboard');

// ═══════════════════════════════════════════════════════════════
// CLIENT — Simple User Routes
// ═══════════════════════════════════════════════════════════════

Route::middleware(['auth'])->prefix('client')->name('client.')->group(function () {

    // مشاوره‌ها (کاربر ساده)
    Route::prefix('consultations')->name('consultations.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Client\ConsultationController::class, 'index'])->name('index');
        Route::get('/{consultation}', [\App\Http\Controllers\Client\ConsultationController::class, 'show'])->name('show');
    });

    // چت
    Route::prefix('chat')->name('chat.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Client\ChatController::class, 'index'])->name('index');
        Route::get('/{id}', [\App\Http\Controllers\Client\ChatController::class, 'show'])->name('show');
        Route::post('/{id}/send', [\App\Http\Controllers\Client\ChatController::class, 'send'])->name('send');
        Route::post('/start', [\App\Http\Controllers\Client\ChatController::class, 'store'])->name('store');
    });

    // پرونده‌ها (کاربر ویژه)
    Route::prefix('cases')->name('cases.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Client\CaseController::class, 'index'])->name('index');
        Route::get('/{case}', [\App\Http\Controllers\Client\CaseController::class, 'show'])->name('show');
    });

    // اقساط (کاربر ویژه)
    Route::prefix('installments')->name('installments.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Client\InstallmentController::class, 'index'])->name('index');
    });

    // Profile
    Route::get('/profile', [\App\Http\Controllers\Client\ProfileController::class, 'index'])->name('profile');
    Route::put('/profile', [\App\Http\Controllers\Client\ProfileController::class, 'update'])->name('profile.update');
});

// ═══════════════════════════════════════════════════════════════
// LAWYER PANEL ROUTES
// ═══════════════════════════════════════════════════════════════

Route::middleware(['App\Http\Middleware\EnsureLawyerAuth'])
    ->prefix('lawyer')
    ->name('lawyer.')
    ->group(function () {

    Route::get('/dashboard', [\App\Http\Controllers\Lawyer\DashboardController::class, 'index'])->name('dashboard');
    Route::post('/logout', [\App\Http\Controllers\Lawyer\DashboardController::class, 'logout'])->name('logout');

    // پرونده‌ها
    Route::prefix('cases')->name('cases.')->group(function () {
        Route::get('/', fn() => abort(501))->name('index');
        Route::get('/{id}', fn() => abort(501))->name('show');
    });

    // موکلین
    Route::prefix('clients')->name('clients.')->group(function () {
        Route::get('/', fn() => abort(501))->name('index');
    });

    // مشاوره‌ها
    Route::prefix('consultations')->name('consultations.')->group(function () {
        Route::get('/', fn() => abort(501))->name('index');
    });

    // چت
    Route::prefix('chat')->name('chat.')->group(function () {
        Route::get('/', fn() => abort(501))->name('index');
    });

    // مقالات
    Route::prefix('articles')->name('articles.')->group(function () {
        Route::get('/', fn() => abort(501))->name('index');
    });

    // نظرات
    Route::prefix('comments')->name('comments.')->group(function () {
        Route::get('/', fn() => abort(501))->name('index');
    });

    // پرداخت‌ها
    Route::prefix('payments')->name('payments.')->group(function () {
        Route::get('/', fn() => abort(501))->name('index');
    });

    // تنظیمات
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/', fn() => abort(501))->name('index');
    });

    // تقویم و ساعات کاری
    Route::get('/calendar', fn() => abort(501))->name('calendar');
    Route::get('/schedule', fn() => abort(501))->name('schedule');
});

// صفحه لاگین وکیل
Route::get('/lawyer/login', fn() => abort(501))->name('lawyer.login');