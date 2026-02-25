<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Client\DashboardController as ClientDashboard;
use App\Http\Controllers\Lawyer\DashboardController as LawyerDashboard;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| صفحات عمومی
|--------------------------------------------------------------------------
*/
Route::get('/', fn() => view('public.home'))->name('home');
Route::get('/about', fn() => view('public.about'))->name('about');
Route::get('/contact', fn() => view('public.contact'))->name('contact');
Route::get('/services', fn() => view('public.services.index'))->name('services.index');
Route::get('/services/{slug}', fn($slug) => view('public.services.show', compact('slug')))->name('services.show');
Route::get('/articles', fn() => view('public.articles.index'))->name('articles.index');
Route::get('/articles/{slug}', fn($slug) => view('public.articles.show', compact('slug')))->name('articles.show');
Route::get('/calculators', fn() => view('public.calculators.index'))->name('calculators.index');
Route::get('/reserve', fn() => view('public.reserve'))->name('reserve.index');

/*
|--------------------------------------------------------------------------
| احراز هویت کاربران (OTP)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/auth/send-otp', [AuthController::class, 'sendOtp'])->name('auth.send-otp');
    Route::post('/auth/verify-otp', [AuthController::class, 'verifyOtp'])->name('auth.verify-otp');

    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// پاک کردن session OTP (برای "تغییر شماره")
Route::post('/auth/clear-session', function () {
    session()->forget(['otp_phone', 'otp_for_register', 'register_data']);
    return redirect()->route('login');
})->name('auth.clear-session');

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

/*
|--------------------------------------------------------------------------
| داشبورد مشترک — ریدایرکت
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->get('/dashboard', function () {
    return redirect()->route(
        auth()->user()->isSpecial()
            ? 'client.special.dashboard'
            : 'client.simple.dashboard'
    );
})->name('dashboard');

/*
|--------------------------------------------------------------------------
| پنل مشتری ساده
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'user.type:simple'])
    ->prefix('client')
    ->name('client.')
    ->group(function () {

        Route::get('/dashboard', [ClientDashboard::class, 'simple'])->name('simple.dashboard');
        Route::get('/profile', fn() => view('client.profile'))->name('profile');

        // مشاوره‌ها
        Route::prefix('consultations')->name('consultations.')->group(function () {
            Route::get('/', fn() => view('client.consultations.index'))->name('index');
            Route::get('/new', fn() => view('client.consultations.create'))->name('create');
            Route::post('/', 'App\Http\Controllers\Client\ConsultationController@store')->name('store');
        });

        // چت
        Route::prefix('chat')->name('chat.')->group(function () {
            Route::get('/', fn() => view('client.chat.index'))->name('index');
            Route::get('/{id}', 'App\Http\Controllers\Client\ChatController@show')->name('show');
            Route::post('/{id}/send', 'App\Http\Controllers\Client\ChatController@send')->name('send');
        });
    });

/*
|--------------------------------------------------------------------------
| پنل موکل ویژه
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'user.type:special'])
    ->prefix('client/special')
    ->name('client.')
    ->group(function () {

        Route::get('/dashboard', [ClientDashboard::class, 'special'])->name('special.dashboard');
        Route::get('/profile', fn() => view('client.profile'))->name('profile');

        // پرونده‌ها
        Route::prefix('cases')->name('cases.')->group(function () {
            Route::get('/', fn() => view('client.cases.index'))->name('index');
            Route::get('/{id}', fn($id) => view('client.cases.show', compact('id')))->name('show');
        });

        // اقساط
        Route::prefix('installments')->name('installments.')->group(function () {
            Route::get('/', fn() => view('client.installments.index'))->name('index');
            Route::post('/{id}/pay', 'App\Http\Controllers\Client\InstallmentController@pay')->name('pay');
        });

        // چت
        Route::prefix('chat')->name('chat.')->group(function () {
            Route::get('/', fn() => view('client.chat.index'))->name('index');
            Route::get('/{id}', 'App\Http\Controllers\Client\ChatController@show')->name('show');
            Route::post('/{id}/send', 'App\Http\Controllers\Client\ChatController@send')->name('send');
        });
    });

/*
|--------------------------------------------------------------------------
| پنل وکیل
|--------------------------------------------------------------------------
*/
Route::prefix('lawyer')
    ->name('lawyer.')
    ->group(function () {

        Route::get('/login', fn() => view('lawyer.auth.login'))->name('login');
        Route::post('/logout', [LawyerDashboard::class, 'logout'])->name('logout');

        Route::middleware('auth.lawyer')->group(function () {

            Route::get('/dashboard', [LawyerDashboard::class, 'index'])->name('dashboard');
            Route::get('/calendar', fn() => view('lawyer.calendar'))->name('calendar');
            Route::get('/schedule', fn() => view('lawyer.schedule'))->name('schedule');

            // پرونده‌ها
            Route::prefix('cases')->name('cases.')->group(function () {
                Route::get('/', fn() => view('lawyer.cases.index'))->name('index');
                Route::get('/create', fn() => view('lawyer.cases.create'))->name('create');
                Route::post('/', 'App\Http\Controllers\Lawyer\CaseController@store')->name('store');
                Route::get('/{id}', fn($id) => view('lawyer.cases.show', compact('id')))->name('show');
                Route::patch('/{id}/status', 'App\Http\Controllers\Lawyer\CaseController@updateStatus')->name('update-status');
            });

            // موکلین
            Route::prefix('clients')->name('clients.')->group(function () {
                Route::get('/', fn() => view('lawyer.clients.index'))->name('index');
                Route::get('/{id}', fn($id) => view('lawyer.clients.show', compact('id')))->name('show');
                Route::post('/{id}/upgrade', 'App\Http\Controllers\Lawyer\ClientController@upgrade')->name('upgrade');
            });

            // مشاوره‌ها
            Route::prefix('consultations')->name('consultations.')->group(function () {
                Route::get('/', fn() => view('lawyer.consultations.index'))->name('index');
                Route::patch('/{id}/confirm', 'App\Http\Controllers\Lawyer\ConsultationController@confirm')->name('confirm');
                Route::patch('/{id}/reject', 'App\Http\Controllers\Lawyer\ConsultationController@reject')->name('reject');
            });

            // چت
            Route::prefix('chat')->name('chat.')->group(function () {
                Route::get('/', fn() => view('lawyer.chat.index'))->name('index');
                Route::get('/{id}', fn($id) => view('lawyer.chat.show', compact('id')))->name('show');
            });

            // مقالات
            Route::prefix('articles')->name('articles.')->group(function () {
                Route::get('/', fn() => view('lawyer.articles.index'))->name('index');
                Route::get('/create', fn() => view('lawyer.articles.create'))->name('create');
                Route::get('/{id}/edit', fn($id) => view('lawyer.articles.edit', compact('id')))->name('edit');
            });

            // نظرات
            Route::prefix('comments')->name('comments.')->group(function () {
                Route::get('/', fn() => view('lawyer.comments.index'))->name('index');
            });

            // پرداخت‌ها
            Route::prefix('payments')->name('payments.')->group(function () {
                Route::get('/', fn() => view('lawyer.payments.index'))->name('index');
            });

            // تنظیمات
            Route::prefix('settings')->name('settings.')->group(function () {
                Route::get('/', fn() => view('lawyer.settings.index'))->name('index');
                Route::post('/', 'App\Http\Controllers\Lawyer\SettingController@update')->name('update');
            });
        });
    });
