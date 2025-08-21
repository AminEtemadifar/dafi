<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ComponentController;

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
