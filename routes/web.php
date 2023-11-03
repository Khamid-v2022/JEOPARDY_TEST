<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;

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


Route::get('login', [AuthController::class, 'index'])->name('login');
Route::post('login', [AuthController::class, 'do_login']);
Route::get('logout', [AuthController::class, 'do_logout'])->name('logout');

Route::get('register', [AuthController::class, 'register_page'])->name('register');
Route::post('register', [AuthController::class, 'do_register']);

Route::group(['middleware' => ['user']], function() {
    Route::get('/', [DashboardController::class, 'index'])->name('pages-dashboard');
});
