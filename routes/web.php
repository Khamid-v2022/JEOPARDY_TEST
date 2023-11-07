<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\JeopardyTestController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\BillingController;

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


Route::get('login', [AuthController::class, 'index'])->name('pages-login');
Route::post('login', [AuthController::class, 'do_login']);
Route::get('logout', [AuthController::class, 'do_logout'])->name('logout');

Route::get('register', [AuthController::class, 'register_page'])->name('pages-register');
Route::post('register', [AuthController::class, 'do_register']);

Route::group(['middleware' => ['user']], function() {
    Route::get('/', [DashboardController::class, 'index'])->name('pages-dashboard');
    Route::get('/read-csv', [DashboardController::class, 'read_csv']);
    Route::get('/structure-question', [DashboardController::class, 'structure_question']);
    
    Route::get('/jeopardy-test', [JeopardyTestController::class, 'index'])->name('pages-jeopardy-test');
    Route::get('/jeopardy-test/get-questions', [JeopardyTestController::class, 'get_questions']);
    Route::post('/jeopardy-test/submit-response', [JeopardyTestController::class, 'submit_response']);

    Route::get('/jeopardy-test/view-detail/{id}', [JeopardyTestController::class, 'view_detail'])->where('id', '[0-9]+')->name('pages-view-detail');

    Route::get('/pages-billing', [BillingController::class, 'index'])->name('pages-billing');
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('pages-checkout');
    Route::post('/checkout/upgrade-account', [CheckoutController::class, 'upgrade_account']);
});

