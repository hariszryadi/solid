<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\FrontEndController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserAdminController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [FrontEndController::class, 'index'])->name('index');
Route::get('/user/register', [FrontEndController::class, 'register'])->name('user.register');
Route::get('/user/login', [FrontEndController::class, 'login'])->name('user.login');
Route::get('/email/verify/{id}', [FrontEndController::class, 'verify'])->name('verification.verify');
Route::get('/verify-email-success', [FrondEndController::class, 'verify_success']);
Route::get('/verify-email-success', [FrondEndController::class, 'verify_failed']);
Route::post('/reset-password', [FrontEndController::class, 'reset_password'])->name('reset.password');

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::resource('category', CategoryController::class);
Route::resource('organization', OrganizationController::class);
Route::resource('account', AccountController::class);
Route::resource('transaction', TransactionController::class);
Route::resource('user-admin', UserAdminController::class);
Route::get('/report-daily', [ReportController::class, 'daily'])->name('report-daily');
Route::post('/report-daily-result', [ReportController::class, 'daily_result'])->name('report-daily-result');
Route::get('/report-monthly', [ReportController::class, 'monthly'])->name('report-monthly');
Route::post('/report-monthly-result', [ReportController::class, 'monthly_result'])->name('report-monthly-result');
