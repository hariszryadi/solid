<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\FrontEndController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrganizationController;
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

Route::get('/verify-email-success', function () {
    return view('email.verify-email-success');
});

Route::get('/verify-email-failed', function () {
    return view('email.verify-email-failed');
});

Route::get('/user/login', function () {
    return view('frontend.login');
})->name('user.login');

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::resource('category', CategoryController::class);
Route::resource('organization', OrganizationController::class);
Route::resource('account', AccountController::class);
Route::resource('user-admin', UserAdminController::class);
