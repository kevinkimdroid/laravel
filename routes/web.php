<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ContributionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\FinancialRecordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\VerifyEmailController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('login');
});

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');

    // User registration (uses Breeze controller and users table)
    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store'])->name('register.store');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Authenticated User Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Profile
    |--------------------------------------------------------------------------
    */
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    /*
    |--------------------------------------------------------------------------
    | Simple Dashboard Route
    |--------------------------------------------------------------------------
    | For now, always show the default dashboard view.
    | You can later customize it per role without redirects.
    */
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | Email verification routes (needed by Breeze views)
    |--------------------------------------------------------------------------
    */
    Route::get('verify-email', EmailVerificationPromptController::class)
        ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');

    // Password update (used by profile/security views)
    Route::put('password', [PasswordController::class, 'update'])
        ->name('password.update');

    /*
    |--------------------------------------------------------------------------
    | Admin Routes
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:admin')->group(function () {

        Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])
            ->name('admin.dashboard');

        // Admin manages members
        Route::resource('members', MemberController::class);

        // Member contribution statement
        Route::get('members/{member}/statement', [MemberController::class, 'statement'])
            ->name('members.statement');

        // Admin manages contributions
        Route::resource('contributions', ContributionController::class);

        // Import historic contributions from CSV
        Route::get('contributions-import', [ContributionController::class, 'showImportForm'])
            ->name('contributions.import.form');
        Route::post('contributions-import', [ContributionController::class, 'import'])
            ->name('contributions.import');

        // Expenses / financial overview
        Route::get('/expenses', [ExpenseController::class, 'index'])->name('expenses.index');
        Route::get('/expenses/create', [ExpenseController::class, 'create'])->name('expenses.create');
        Route::post('/expenses', [ExpenseController::class, 'store'])->name('expenses.store');

        // Financial records (upload name, initials, registration, months, deficit, expected amount, aging)
        Route::resource('financial-records', FinancialRecordController::class);
    });

    /*
    |--------------------------------------------------------------------------
    | Member Routes
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:member')->group(function () {

        Route::get('/member/dashboard', function () {
            return redirect()->route('member.contributions');
        })->name('member.dashboard');

        // Member views own contributions
        Route::get('/member/contributions', [ContributionController::class, 'myContributions'])
            ->name('member.contributions');

        // Member pays own contribution
        Route::get('/member/contributions/pay', [ContributionController::class, 'payForm'])
            ->name('member.contributions.pay.form');
        Route::post('/member/contributions/pay', [ContributionController::class, 'pay'])
            ->name('member.contributions.pay');
    });

});
