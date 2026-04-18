<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Public\ArticleController;
use App\Http\Controllers\Public\ArticleReactionController;
use App\Http\Controllers\Public\ArticleCommentController;
use App\Http\Controllers\Public\CalculatorController;
use App\Http\Controllers\Public\ContactController;
use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\LawyerController;
use App\Http\Controllers\Public\ReserveController;
use App\Http\Controllers\Public\ServiceController;

use App\Http\Controllers\Client\DashboardController as ClientDashoboard;

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

    // articles.reactions.store
});

Route::get('/calculators', [CalculatorController::class, 'index'])->name('calculators.index');

Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'send'])->name('contact.send');

Route::prefix('reserve')->name('reserve.')->group(function () {
    Route::get('/', [ReserveController::class, 'index'])->name('index');
    Route::post('/', [ReserveController::class, 'store'])->name('store');
    // این روت رو زیر روت‌های قبلی رزرو اضافه کن
    Route::get('verify/{payment}', [ReserveController::class, 'verifyPayment'])->name('verify');
});

Route::get('/about', fn () => redirect()->route('lawyers.index'))->name('about');

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
// routes/web.php

Route::middleware(['auth'])->group(function () {

    // ری‌اکشن
    Route::post('/articles/reactions', [ArticleReactionController::class, 'store'])
        ->name('articles.reactions.store');

    // کامنت
    Route::post('/articles/comments', [ArticleCommentController::class, 'store'])
        ->name('articles.comments.store');

    Route::put('/articles/comments/{comment}', [ArticleCommentController::class, 'update'])
        ->name('articles.comments.update');

    Route::delete('/articles/comments/{comment}', [ArticleCommentController::class, 'destroy'])
        ->name('articles.comments.destroy');

});


Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register-show');
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

Route::prefix('auth')->name('auth.')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register-show');
    Route::post('/send-otp', [AuthController::class, 'sendOtp'])->name('send-otp');
    Route::post('/verify-otp', [AuthController::class, 'verifyOtp'])->name('verify-otp');
    Route::post('/register', [AuthController::class, 'register'])->name('register');

});

// ═══════════════════════════════════════════════════════════════
// DASHBOARD (Auth Required)
// ═══════════════════════════════════════════════════════════════
Route::middleware(['auth'])->group(function () {

    Route::prefix('dashboard')->name('dashboard.')->group(function () {
        Route::get('/',[ClientDashoboard::class ,'simple'])->name('index');
    });

    Route::prefix('client')->name('client.')->group(function () {
        Route::get('/', fn () => view('public.chat'))->name('chat.index');
    });

    Route::prefix('special')->name('special.')->group(function () {
        Route::get('/', fn () => view('client.special.dashboard'))->name('index');
        Route::get('/lawyers', fn () => view('client.special.lawyers'))->name('lawyers');
        Route::get('/chat', fn () => view('client.special.chat'))->name('chat');
    });

});

Route::middleware(['auth', 'role:lawyer'])->prefix('lawyer-panel')->name('lawyer.')->group(function () {
    Route::get('/', fn () => view('lawyer.dashboard'))->name('dashboard');
    Route::get('/plan', fn () => view('lawyer.plan'))->name('plan');
    Route::get('/chat', fn () => view('lawyer.chat'))->name('chat');
});
