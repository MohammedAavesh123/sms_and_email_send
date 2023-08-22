<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::post('/store', [App\Http\Controllers\HomeController::class, 'store'])->name('store');
Route::get('/send-email', [App\Http\Controllers\HomeController::class, 'sendMail'])->name('sendmail');
Route::get('/send-sms', [App\Http\Controllers\HomeController::class, 'sendsms_view']);
Route::post('/send-sms-form', [App\Http\Controllers\HomeController::class, 'sendsms_form'])->name('sendSms');
