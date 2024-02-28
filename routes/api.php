<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TransactionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout']);
Route::post('/refresh', [AuthController::class, 'refresh']);
Route::get('/account-profile', [AuthController::class, 'accountProfile']);
Route::get('/category', [AuthController::class, 'category']);
Route::get('/organization', [AuthController::class, 'organization']);

Route::get('email/verify/{id}', [AuthController::class, 'verify'])->name('verification.verify');
Route::get('email/resend/{id}', [AuthController::class, 'resend'])->name('verification.resend');

Route::post('/transaction/store', [TransactionController::class, 'store']);
Route::post('/transaction/report', [TransactionController::class, 'report']);
