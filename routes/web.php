<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\JeopardyTestController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\BillingController;
use App\Http\Controllers\UserSettingController;

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
    Route::delete('/my-tests/delete-test/{id}', [DashboardController::class, 'delete_test_record'])->where('id', '[0-9]+');
    // Route::get('/read-csv', [DashboardController::class, 'read_csv']);
    // Route::get('/structure-question', [DashboardController::class, 'structure_question']);
    
    Route::get('/jeopardy-test', [JeopardyTestController::class, 'index'])->name('pages-jeopardy-test');
    Route::get('/jeopardy-test/get-questions/{count}', [JeopardyTestController::class, 'get_questions'])->where('count', '[0-9]+');
    Route::post('/jeopardy-test/submit-response', [JeopardyTestController::class, 'submit_response']);

    Route::get('/jeopardy-test/view-detail/{id}', [JeopardyTestController::class, 'view_detail'])->where('id', '[0-9]+')->name('pages-view-detail');
    Route::post('/jeopardy-test/fix-answer', [JeopardyTestController::class, 'fix_answer']);

    Route::get('/pages-billing', [BillingController::class, 'index'])->name('pages-billing');
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('pages-checkout');
    // Route::post('/checkout/upgrade-account', [CheckoutController::class, 'upgrade_account']);
    Route::post('/checkout/upgrade-account', [CheckoutController::class, 'upgrade_account_with_subscription']);
    

    Route::get('/my-profile', [UserSettingController::class, 'index'])->name('my-profile');
    Route::post('/my-profile/update', [UserSettingController::class, 'update_profile']);
    Route::delete('/my-profile/delete', [UserSettingController::class, 'delete_profile']);
});

