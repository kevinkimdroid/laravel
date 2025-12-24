<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\ContributionController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application.
| These routes are loaded by the RouteServiceProvider within a group
| which contains the "web" middleware group.
|
*/

// Landing page
Route::get('/', function () {
    return view('welcome'); // Show landing page with menu
})->name('home');

Route::get('members/{member}/statement', [MemberController::class, 'statement'])
    ->name('members.statement');


// Members Management
Route::resource('members', MemberController::class);

// Contributions Management
Route::resource('contributions', ContributionController::class);
