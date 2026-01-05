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
    | Main Dashboard Route - Smart Redirect Based on Role
    |--------------------------------------------------------------------------
    | - Admins: Redirect to Admin Dashboard (with calendar, stats, etc.)
    | - Members: Redirect to Member Dashboard (their contributions)
    | - Others: Show generic dashboard
    */
    Route::get('/dashboard', function () {
        $user = auth()->user();
        
        if ($user && $user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif ($user && $user->role === 'member') {
            return redirect()->route('member.dashboard');
        }
        
        // Fallback for other roles or if no role
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

        // Approve user and link to member
        Route::post('/admin/users/{user}/approve', [AdminController::class, 'approveUser'])
            ->name('admin.approve-user');

        // Admin manages members
        Route::resource('members', MemberController::class);

        // Member contribution statement
        Route::get('members/{member}/statement', [MemberController::class, 'statement'])
            ->name('members.statement');

        // Import members from Excel
        Route::get('members-import', [MemberController::class, 'showImportForm'])
            ->name('members.import.form');
        Route::post('members-import', [MemberController::class, 'import'])
            ->name('members.import');
        Route::get('members-template', [MemberController::class, 'downloadTemplate'])
            ->name('members.template');

        // Admin manages contributions
        Route::resource('contributions', ContributionController::class);

        // Import historic contributions from CSV
        Route::get('contributions-import', [ContributionController::class, 'showImportForm'])
            ->name('contributions.import.form');
        Route::post('contributions-import', [ContributionController::class, 'import'])
            ->name('contributions.import');
        Route::get('contributions-template', [ContributionController::class, 'downloadTemplate'])
            ->name('contributions.template');

        // Expenses / financial overview
        Route::get('/expenses', [ExpenseController::class, 'index'])->name('expenses.index');
        Route::get('/expenses/create', [ExpenseController::class, 'create'])->name('expenses.create');
        Route::post('/expenses', [ExpenseController::class, 'store'])->name('expenses.store');

        // Financial records (upload name, initials, registration, months, deficit, expected amount, aging)
        Route::resource('financial-records', FinancialRecordController::class);

        // Reports
        Route::get('/reports/outstanding-balances', [\App\Http\Controllers\ReportController::class, 'outstandingBalances'])
            ->name('reports.outstanding-balances');
        Route::get('/reports/best-contributors', [\App\Http\Controllers\ReportController::class, 'bestContributors'])
            ->name('reports.best-contributors');

        // WhatsApp Reminders
        Route::get('/whatsapp/reminders', [\App\Http\Controllers\WhatsAppController::class, 'index'])
            ->name('whatsapp.index');
        Route::get('/whatsapp/send/{member}', [\App\Http\Controllers\WhatsAppController::class, 'generateLink'])
            ->name('whatsapp.send');
        Route::post('/whatsapp/send-bulk', [\App\Http\Controllers\WhatsAppController::class, 'sendBulkReminders'])
            ->name('whatsapp.send-bulk');

        // Payment Requests Management
        Route::get('/admin/payment-requests', [AdminController::class, 'paymentRequests'])
            ->name('admin.payment-requests');
        Route::post('/admin/payment-requests/{paymentRequest}/approve', [AdminController::class, 'approvePayment'])
            ->name('admin.payment-requests.approve');
        Route::post('/admin/payment-requests/{paymentRequest}/reject', [AdminController::class, 'rejectPayment'])
            ->name('admin.payment-requests.reject');

        // QPL Games Management
        Route::get('qpl-games/generate', [\App\Http\Controllers\QplGameController::class, 'generateForm'])
            ->name('qpl-games.generate.form');
        Route::post('qpl-games/generate', [\App\Http\Controllers\QplGameController::class, 'generate'])
            ->name('qpl-games.generate');
        Route::post('qpl-games/delete-generated', [\App\Http\Controllers\QplGameController::class, 'deleteGenerated'])
            ->name('qpl-games.delete-generated');
        Route::get('qpl-games/standings', [\App\Http\Controllers\QplGameController::class, 'standings'])
            ->name('qpl-games.standings');
        Route::resource('qpl-games', \App\Http\Controllers\QplGameController::class);

        // Calendar Activities Management (admin only - full CRUD)
        Route::resource('calendar-activities', \App\Http\Controllers\CalendarActivityController::class);
    });

    /*
    |--------------------------------------------------------------------------
    | Member Routes
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:member')->group(function () {

        Route::get('/member/dashboard', [MemberController::class, 'myDashboard'])
            ->name('member.dashboard');

        // Member views own contributions
        Route::get('/member/contributions', [ContributionController::class, 'myContributions'])
            ->name('member.contributions');

        // Member pays own contribution
        Route::get('/member/contributions/pay', [ContributionController::class, 'payForm'])
            ->name('member.contributions.pay.form');
        Route::post('/member/contributions/pay', [ContributionController::class, 'pay'])
            ->name('member.contributions.pay');

        // Member views calendar (read-only)
        Route::get('/member/calendar', [\App\Http\Controllers\CalendarActivityController::class, 'index'])
            ->name('member.calendar');

        // Member views QPL standings (read-only)
        Route::get('/member/qpl/standings', [\App\Http\Controllers\QplGameController::class, 'standings'])
            ->name('member.qpl.standings');
    });

    /*
    |--------------------------------------------------------------------------
    | Pending Approval Route (for users without member profile)
    |--------------------------------------------------------------------------
    | This route is accessible to all authenticated users
    */
    Route::get('/members/pending-approval', function () {
        return view('members.pending-approval');
    })->name('members.pending-approval');

});

/*
|--------------------------------------------------------------------------
| M-Pesa Daraja API Callback Routes (Public - No Auth Required)
|--------------------------------------------------------------------------
*/
Route::post('/api/mpesa/callback', [\App\Http\Controllers\MpesaCallbackController::class, 'stkCallback'])
    ->name('mpesa.callback');

/*
|--------------------------------------------------------------------------
| Daraja API Test Route (Admin Only)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/daraja/test', [\App\Http\Controllers\DarajaTestController::class, 'test'])
        ->name('daraja.test');
});
