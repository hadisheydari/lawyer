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
    Route::get('/dieh',    [CalculatorController::class, 'calcDieh'])->name('dieh');
    Route::get('/court',   [CalculatorController::class, 'calcCourt'])->name('court');
});

// ═══════════════════════════════════════════════════════════════
// AUTH ROUTES
// ═══════════════════════════════════════════════════════════════

Route::post('/clear-otp-session', [AuthController::class, 'clearOtpSession'])->name('auth.clear-session');

Route::middleware('guest')->group(function () {
    Route::get('/login',          [AuthController::class, 'showLogin'])->name('login');
    Route::get('/login/lawyer',   [AuthController::class, 'showLoginLawyer'])->name('login.show.lawyer');
    Route::get('/register',       [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register',      [AuthController::class, 'register'])->name('register.submit');
    Route::post('/send-otp',      [AuthController::class, 'sendOtp'])->name('auth.send-otp')->middleware('throttle:5,1');
    Route::post('/verify-otp',    [AuthController::class, 'verifyOtp'])->name('auth.verify-otp');
    Route::post('/login/lawyer',  [AuthController::class, 'loginLawyer'])->name('login.lawyer');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// ═══════════════════════════════════════════════════════════════
// CLIENT DASHBOARD (Auth Required)
// ═══════════════════════════════════════════════════════════════

Route::middleware(['auth'])->prefix('dashboard')->name('dashboard.')->group(function () {
    Route::get('/', [ClientDashboardController::class, 'index'])->name('index');
    Route::get('/profile', [ClientDashboardController::class, 'profile'])->name('profile');
});

Route::get('/dashboard', [ClientDashboardController::class, 'index'])
    ->middleware('auth')
    ->name('dashboard');

// ═══════════════════════════════════════════════════════════════
// CLIENT — Simple/Special User Routes
// ═══════════════════════════════════════════════════════════════

Route::middleware(['auth'])->prefix('client')->name('client.')->group(function () {

    Route::prefix('consultations')->name('consultations.')->group(function () {
        Route::get('/',              [\App\Http\Controllers\Client\ConsultationController::class, 'index'])->name('index');
        Route::get('/{consultation}',[\App\Http\Controllers\Client\ConsultationController::class, 'show'])->name('show');
    });

    Route::prefix('chat')->name('chat.')->group(function () {
        Route::get('/',            [\App\Http\Controllers\Client\ChatController::class, 'index'])->name('index');
        Route::get('/{id}',        [\App\Http\Controllers\Client\ChatController::class, 'show'])->name('show');
        Route::post('/{id}/send',  [\App\Http\Controllers\Client\ChatController::class, 'send'])->name('send');
        Route::post('/start',      [\App\Http\Controllers\Client\ChatController::class, 'store'])->name('store');
    });

    Route::prefix('cases')->name('cases.')->group(function () {
        Route::get('/',        [\App\Http\Controllers\Client\CaseController::class, 'index'])->name('index');
        Route::get('/{case}',  [\App\Http\Controllers\Client\CaseController::class, 'show'])->name('show');
    });

    Route::prefix('installments')->name('installments.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Client\InstallmentController::class, 'index'])->name('index');
    });

    Route::get('/profile',  [\App\Http\Controllers\Client\ProfileController::class, 'index'])->name('profile');
    Route::put('/profile',  [\App\Http\Controllers\Client\ProfileController::class, 'update'])->name('profile.update');
});

// ═══════════════════════════════════════════════════════════════
// LAWYER PANEL ROUTES
// ═══════════════════════════════════════════════════════════════

Route::middleware(['App\Http\Middleware\EnsureLawyerAuth'])
    ->prefix('lawyer')
    ->name('lawyer.')
    ->group(function () {

        // داشبورد
        Route::get('/dashboard', [\App\Http\Controllers\Lawyer\DashboardController::class, 'index'])->name('dashboard');

        // لاگ‌اوت وکیل (از طریق auth عمومی)
        Route::post('/logout', function () {
            \Illuminate\Support\Facades\Auth::guard('lawyer')->logout();
            request()->session()->invalidate();
            request()->session()->regenerateToken();
            return redirect()->route('login.show.lawyer');
        })->name('logout');

        // ─── مشاوره‌ها ───
        Route::prefix('consultations')->name('consultations.')->group(function () {
            Route::get('/',                            [\App\Http\Controllers\Lawyer\ConsultationController::class, 'index'])->name('index');
            Route::get('/{consultation}',              [\App\Http\Controllers\Lawyer\ConsultationController::class, 'show'])->name('show');
            Route::post('/{consultation}/confirm',     [\App\Http\Controllers\Lawyer\ConsultationController::class, 'confirm'])->name('confirm');
            Route::post('/{consultation}/reject',      [\App\Http\Controllers\Lawyer\ConsultationController::class, 'reject'])->name('reject');
            Route::post('/{consultation}/complete',    [\App\Http\Controllers\Lawyer\ConsultationController::class, 'complete'])->name('complete');
            Route::post('/{consultation}/cancel',      [\App\Http\Controllers\Lawyer\ConsultationController::class, 'cancel'])->name('cancel');
            Route::post('/{consultation}/note',        [\App\Http\Controllers\Lawyer\ConsultationController::class, 'addNote'])->name('note');
        });

        // ─── پرونده‌ها ───
        Route::prefix('cases')->name('cases.')->group(function () {
            Route::get('/',                               [\App\Http\Controllers\Lawyer\CaseController::class, 'index'])->name('index');
            Route::get('/create',                         [\App\Http\Controllers\Lawyer\CaseController::class, 'create'])->name('create');
            Route::post('/',                              [\App\Http\Controllers\Lawyer\CaseController::class, 'store'])->name('store');
            Route::get('/{case}',                         [\App\Http\Controllers\Lawyer\CaseController::class, 'show'])->name('show');
            Route::get('/{case}/edit',                    [\App\Http\Controllers\Lawyer\CaseController::class, 'edit'])->name('edit');
            Route::put('/{case}',                         [\App\Http\Controllers\Lawyer\CaseController::class, 'update'])->name('update');
            Route::delete('/{case}',                      [\App\Http\Controllers\Lawyer\CaseController::class, 'destroy'])->name('destroy');
            Route::post('/{case}/status-log',             [\App\Http\Controllers\Lawyer\CaseController::class, 'addStatusLog'])->name('status-log');
            Route::post('/{case}/installments',           [\App\Http\Controllers\Lawyer\CaseController::class, 'addInstallment'])->name('installments');
        });

        // ─── موکلین ───
        Route::prefix('clients')->name('clients.')->group(function () {
            Route::get('/',                    [\App\Http\Controllers\Lawyer\ClientController::class, 'index'])->name('index');
            Route::get('/{client}',            [\App\Http\Controllers\Lawyer\ClientController::class, 'show'])->name('show');
            Route::post('/{client}/upgrade',   [\App\Http\Controllers\Lawyer\ClientController::class, 'upgrade'])->name('upgrade');
        });

        // ─── چت ───
        Route::prefix('chat')->name('chat.')->group(function () {
            Route::get('/',             [\App\Http\Controllers\Lawyer\ChatController::class, 'index'])->name('index');
            Route::get('/{id}',         [\App\Http\Controllers\Lawyer\ChatController::class, 'show'])->name('show');
            Route::post('/{id}/send',   [\App\Http\Controllers\Lawyer\ChatController::class, 'send'])->name('send');
            Route::post('/{id}/close',  [\App\Http\Controllers\Lawyer\ChatController::class, 'close'])->name('close');
            Route::post('/{id}/reopen', [\App\Http\Controllers\Lawyer\ChatController::class, 'reopen'])->name('reopen');
        });

        // ─── مقالات ───
        Route::prefix('articles')->name('articles.')->group(function () {
            Route::get('/',               [\App\Http\Controllers\Lawyer\ArticleController::class, 'index'])->name('index');
            Route::get('/create',         [\App\Http\Controllers\Lawyer\ArticleController::class, 'create'])->name('create');
            Route::post('/',              [\App\Http\Controllers\Lawyer\ArticleController::class, 'store'])->name('store');
            Route::get('/{article}',      [\App\Http\Controllers\Lawyer\ArticleController::class, 'show'])->name('show');
            Route::get('/{article}/edit', [\App\Http\Controllers\Lawyer\ArticleController::class, 'edit'])->name('edit');
            Route::put('/{article}',      [\App\Http\Controllers\Lawyer\ArticleController::class, 'update'])->name('update');
            Route::delete('/{article}',   [\App\Http\Controllers\Lawyer\ArticleController::class, 'destroy'])->name('destroy');
        });

        // ─── نظرات ───
        Route::prefix('comments')->name('comments.')->group(function () {
            Route::get('/',                       [\App\Http\Controllers\Lawyer\CommentController::class, 'index'])->name('index');
            Route::post('/bulk',                  [\App\Http\Controllers\Lawyer\CommentController::class, 'bulkAction'])->name('bulk');
            Route::post('/{comment}/approve',     [\App\Http\Controllers\Lawyer\CommentController::class, 'approve'])->name('approve');
            Route::post('/{comment}/reject',      [\App\Http\Controllers\Lawyer\CommentController::class, 'reject'])->name('reject');
            Route::delete('/{comment}',           [\App\Http\Controllers\Lawyer\CommentController::class, 'destroy'])->name('destroy');
        });

        // ─── پرداخت‌ها ───
        Route::prefix('payments')->name('payments.')->group(function () {
            Route::get('/',                                         [\App\Http\Controllers\Lawyer\PaymentController::class, 'index'])->name('index');
            Route::get('/{payment}',                               [\App\Http\Controllers\Lawyer\PaymentController::class, 'show'])->name('show');
            Route::post('/installments/{installment}/mark-paid',   [\App\Http\Controllers\Lawyer\PaymentController::class, 'markInstallmentPaid'])->name('installment.paid');
        });

        // ─── تنظیمات ───
        Route::prefix('settings')->name('settings.')->group(function () {
            Route::get('/',                          [\App\Http\Controllers\Lawyer\SettingController::class, 'index'])->name('index');
            Route::put('/profile',                   [\App\Http\Controllers\Lawyer\SettingController::class, 'updateProfile'])->name('profile');
            Route::put('/schedule',                  [\App\Http\Controllers\Lawyer\SettingController::class, 'updateSchedule'])->name('schedule');
            Route::post('/exceptions',               [\App\Http\Controllers\Lawyer\SettingController::class, 'addException'])->name('exceptions.add');
            Route::delete('/exceptions/{exception}', [\App\Http\Controllers\Lawyer\SettingController::class, 'deleteException'])->name('exceptions.delete');
        });
    });