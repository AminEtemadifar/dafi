<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ComponentController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AdminDashboardController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will be
| assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Component routes for AJAX loading
Route::get('/component/{component}', [ComponentController::class, 'load']);

// API routes for form submissions
Route::post('/api/information', [ComponentController::class, 'storeInformation']);
Route::post('/api/verify-otp', [ComponentController::class, 'verifyOtp']);
Route::post('/api/verify-coupon', [ComponentController::class, 'verifyCoupon']);

// Payments
use App\Http\Controllers\PaymentController;
Route::post('/payment/start', [PaymentController::class, 'start'])->name('payment.start');
Route::get('/payment/callback', [PaymentController::class, 'callback'])->name('payment.callback');

// Admin auth
Route::get('/admin/login', [AdminAuthController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('admin.login.attempt');
Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

// Admin dashboard & actions
Route::middleware('auth:admin')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [\App\Http\Controllers\Admin\AdminDashboardController::class, 'index'])->name('dashboard');

    // Names management
    Route::resource('names', \App\Http\Controllers\Admin\AdminNamesController::class);
    Route::get('/submits/by-name', [\App\Http\Controllers\Admin\AdminNamesController::class, 'getMatchingSubmits'])->name('submits.byName');

    // Submits management
    Route::resource('submits', \App\Http\Controllers\Admin\AdminSubmitsController::class)->only(['index', 'show']);
    Route::patch('/submits/{submit}/status', [\App\Http\Controllers\Admin\AdminSubmitsController::class, 'updateStatus'])->name('submits.updateStatus');

    // Transactions management
    Route::resource('transactions', \App\Http\Controllers\Admin\AdminTransactionsController::class)->only(['index', 'show']);
    Route::patch('/transactions/{transaction}/status', [\App\Http\Controllers\Admin\AdminTransactionsController::class, 'updateStatus'])->name('transactions.updateStatus');
});

Route::get('login', function () {
    return redirect()->route('admin.login');
})->name('login');
