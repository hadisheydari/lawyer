<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\AuthLawyerController;
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
use App\Http\Controllers\Lawyer\DashboardController as LawyerDashboardController;
use App\Http\Controllers\Lawyer\CaseController as LawyerCaseController;
use App\Http\Controllers\Lawyer\ClientController as LawyerClientController;
use App\Http\Controllers\Lawyer\ConsultationController as LawyerConsultationController;
use App\Http\Controllers\Lawyer\ChatController as LawyerChatController;
use App\Http\Controllers\Lawyer\ArticleController as LawyerArticleController;
use App\Http\Controllers\Lawyer\CommentController as LawyerCommentController;
use App\Http\Controllers\Lawyer\PaymentController as LawyerPaymentController;
use App\Http\Controllers\Lawyer\SettingController as LawyerSettingController;
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
// ARTICLE COMMENTS & REACTIONS
// ═══════════════════════════════════════════════════════════════

Route::middleware('auth')->group(function () {
    Route::post('/articles/comments', [ArticleCommentController::class, 'store'])->name('articles.comments.store');
    Route::put('/articles/comments/{comment}', [ArticleCommentController::class, 'update'])->name('articles.comments.update');
    Route::delete('/articles/comments/{comment}', [ArticleCommentController::class, 'destroy'])->name('articles.comments.destroy');
    Route::post('/articles/reactions', [ArticleReactionController::class, 'store'])->name('articles.reactions.store');
});

// ═══════════════════════════════════════════════════════════════
// RESERVE
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
// AUTH — User
// ═══════════════════════════════════════════════════════════════

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
// AUTH — Lawyer + Lawyer Panel
// ═══════════════════════════════════════════════════════════════

Route::prefix('lawyer')->name('lawyer.')->group(function () {

    // لاگین
    Route::get('/login', [AuthLawyerController::class, 'showLogin'])->name('login');
    Route::post('/send-otp', [AuthLawyerController::class, 'sendOtp'])->name('send-otp');
    Route::post('/verify-otp', [AuthLawyerController::class, 'verifyOtp'])->name('verify-otp');

    // پنل (محافظت‌شده)
    Route::middleware('auth:lawyer')->group(function () {

        Route::post('/logout', [AuthLawyerController::class, 'logout'])->name('logout');

        // داشبورد
        Route::get('/dashboard', [LawyerDashboardController::class, 'index'])->name('dashboard');

        // پرونده‌ها
        Route::prefix('cases')->name('cases.')->group(function () {
            Route::get('/', [LawyerCaseController::class, 'index'])->name('index');
            Route::get('/create', [LawyerCaseController::class, 'create'])->name('create');
            Route::post('/', [LawyerCaseController::class, 'store'])->name('store');
            Route::get('/{case}', [LawyerCaseController::class, 'show'])->name('show');
            Route::get('/{case}/edit', [LawyerCaseController::class, 'edit'])->name('edit');
            Route::put('/{case}', [LawyerCaseController::class, 'update'])->name('update');
            Route::delete('/{case}', [LawyerCaseController::class, 'destroy'])->name('destroy');
            Route::post('/{case}/status-log', [LawyerCaseController::class, 'addStatusLog'])->name('status-log');
            Route::post('/{case}/installments', [LawyerCaseController::class, 'addInstallment'])->name('installments');
        });

        // موکلین
        Route::prefix('clients')->name('clients.')->group(function () {
            Route::get('/', [LawyerClientController::class, 'index'])->name('index');
            Route::get('/{client}', [LawyerClientController::class, 'show'])->name('show');
            Route::post('/{client}/upgrade', [LawyerClientController::class, 'upgrade'])->name('upgrade');
        });

        // مشاوره‌ها
        Route::prefix('consultations')->name('consultations.')->group(function () {
            Route::get('/', [LawyerConsultationController::class, 'index'])->name('index');
            Route::get('/{consultation}', [LawyerConsultationController::class, 'show'])->name('show');
            Route::post('/{consultation}/confirm', [LawyerConsultationController::class, 'confirm'])->name('confirm');
            Route::post('/{consultation}/reject', [LawyerConsultationController::class, 'reject'])->name('reject');
            Route::post('/{consultation}/complete', [LawyerConsultationController::class, 'complete'])->name('complete');
            Route::post('/{consultation}/cancel', [LawyerConsultationController::class, 'cancel'])->name('cancel');
            Route::post('/{consultation}/note', [LawyerConsultationController::class, 'addNote'])->name('note');
        });

        // چت
        Route::prefix('chat')->name('chat.')->group(function () {
            Route::get('/', [LawyerChatController::class, 'index'])->name('index');
            Route::get('/{id}', [LawyerChatController::class, 'show'])->name('show');
            Route::post('/{id}/send', [LawyerChatController::class, 'send'])->name('send');
            Route::post('/{id}/close', [LawyerChatController::class, 'close'])->name('close');
            Route::post('/{id}/reopen', [LawyerChatController::class, 'reopen'])->name('reopen');
        });

        // مقالات
        Route::prefix('articles')->name('articles.')->group(function () {
            Route::get('/', [LawyerArticleController::class, 'index'])->name('index');
            Route::get('/create', [LawyerArticleController::class, 'create'])->name('create');
            Route::post('/', [LawyerArticleController::class, 'store'])->name('store');
            Route::get('/{article}', [LawyerArticleController::class, 'show'])->name('show');
            Route::get('/{article}/edit', [LawyerArticleController::class, 'edit'])->name('edit');
            Route::put('/{article}', [LawyerArticleController::class, 'update'])->name('update');
            Route::delete('/{article}', [LawyerArticleController::class, 'destroy'])->name('destroy');
        });

        // نظرات
        Route::prefix('comments')->name('comments.')->group(function () {
            Route::get('/', [LawyerCommentController::class, 'index'])->name('index');
            Route::post('/{comment}/approve', [LawyerCommentController::class, 'approve'])->name('approve');
            Route::post('/{comment}/reject', [LawyerCommentController::class, 'reject'])->name('reject');
            Route::post('/bulk', [LawyerCommentController::class, 'bulkAction'])->name('bulk');
            Route::delete('/{comment}', [LawyerCommentController::class, 'destroy'])->name('destroy');
        });

        // پرداخت‌ها
        Route::prefix('payments')->name('payments.')->group(function () {
            Route::get('/', [LawyerPaymentController::class, 'index'])->name('index');
            Route::get('/{payment}', [LawyerPaymentController::class, 'show'])->name('show');
            Route::post('/installments/{installment}/mark-paid', [LawyerPaymentController::class, 'markInstallmentPaid'])->name('installment.mark-paid');
        });

        // تنظیمات
        Route::prefix('settings')->name('settings.')->group(function () {
            Route::get('/', [LawyerSettingController::class, 'index'])->name('index');
            Route::post('/profile', [LawyerSettingController::class, 'updateProfile'])->name('profile');
            Route::post('/schedule', [LawyerSettingController::class, 'updateSchedule'])->name('schedule');
            Route::post('/exception', [LawyerSettingController::class, 'addException'])->name('exception.add');
            Route::delete('/exception/{exception}', [LawyerSettingController::class, 'deleteException'])->name('exception.delete');
        });

        // alias تقویم و ساعات کاری → تنظیمات
        Route::get('/calendar', fn() => redirect()->route('lawyer.settings.index'))->name('calendar');
        Route::get('/schedule', fn() => redirect()->route('lawyer.settings.index'))->name('schedule');
    });
});

// ═══════════════════════════════════════════════════════════════
// CLIENT DASHBOARD
// ═══════════════════════════════════════════════════════════════

Route::get('/dashboard', [ClientDashboardController::class, 'index'])->middleware('auth')->name('dashboard');

Route::middleware(['auth'])->prefix('dashboard')->name('dashboard.')->group(function () {
    Route::get('/', [ClientDashboardController::class, 'index'])->name('index');
    Route::get('/profile', [ClientDashboardController::class, 'profile'])->name('profile');
});

// ═══════════════════════════════════════════════════════════════
// CLIENT ROUTES
// ═══════════════════════════════════════════════════════════════

Route::middleware(['auth'])->prefix('client')->name('client.')->group(function () {

    // پروفایل
    Route::get('/profile', [\App\Http\Controllers\Client\ProfileController::class, 'index'])->name('profile');
    Route::put('/profile', [\App\Http\Controllers\Client\ProfileController::class, 'update'])->name('profile.update');

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
        Route::post('/{installment}/pay', [\App\Http\Controllers\Client\InstallmentController::class, 'pay'])->name('pay');
        Route::get('/{payment}/verify', [\App\Http\Controllers\Client\InstallmentController::class, 'verify'])->name('verify');
    });
});