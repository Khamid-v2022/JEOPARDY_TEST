<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MyTestResultController;
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

Route::get('/login', [AuthController::class, 'index'])->name('pages-login')->middleware('guest');
Route::post('/login', [AuthController::class, 'do_login']);
Route::get('/logout', [AuthController::class, 'do_logout'])->name('logout');

Route::post('/email-verify', [AuthController::class, 'email_verify_page'])->name('pages-email-verify');
Route::get('/resend-verify-email/{email}', [AuthController::class, 'resend_verify_email']);
Route::get('/email-verify-code/{unique_str}',  [AuthController::class, 'verify_email'])->name('verify_email');
Route::get('/failed-email-verify',  [AuthController::class, 'failed_email_verify_page'])->name('pages-failed-email-verify');



Route::get('/forgot-password', [AuthController::class, 'forgot_password_page'])->name('pages-forgot-password');
Route::post('/forgot-password', [AuthController::class, 'sendEmailToResetPassword']);
Route::get('/reset-password/{unique_str}',  [AuthController::class, 'reset_password_page'])->name('reset-password');
Route::post('/reset-password', [AuthController::class, 'reset_password']);



Route::get('/register', [AuthController::class, 'register_page'])->name('pages-register')->middleware('guest');
Route::post('/register', [AuthController::class, 'do_register']);

Route::group(['middleware' => ['user']], function() {
    Route::get('/', [DashboardController::class, 'index'])->name('pages-dashboard');
    Route::get('/get-daily-review', [DashboardController::class, 'get_daily_review']);

    Route::get('/my-tests', [MyTestResultController::class, 'index'])->name('pages-my-tests');
    Route::get('/my-tests/get-myscores', [MyTestResultController::class, 'get_scores_for_chart']);    
    Route::delete('/my-tests/delete-test/{id}', [MyTestResultController::class, 'delete_test_record'])->where('id', '[0-9]+');

    Route::get('/jeopardy-test', [JeopardyTestController::class, 'index'])->name('pages-jeopardy-test');
    Route::get('/jeopardy-test/get-questions/{count}', [JeopardyTestController::class, 'get_questions'])->where('count', '[0-9]+');
    Route::post('/jeopardy-test/submit-response', [JeopardyTestController::class, 'submit_response']);

    Route::get('/jeopardy-test/view-detail/{id}', [JeopardyTestController::class, 'view_detail'])->where('id', '[0-9]+')->name('pages-view-detail');
    Route::post('/jeopardy-test/fix-answer', [JeopardyTestController::class, 'fix_answer']);

    Route::get('/pages-billing', [BillingController::class, 'index'])->name('pages-billing');

    Route::get('/pricing', [CheckoutController::class, 'pricing_page'])->name('pages-pricing');

    Route::get('/checkout/{plan}', [CheckoutController::class, 'index'])->name('pages-checkout');

    // one time payment
    // Route::post('/checkout/upgrade-account', [CheckoutController::class, 'upgrade_account']);
    Route::post('/checkout/subscription', [CheckoutController::class, 'subscription']);
    Route::post('/checkout/upgrade-account-annually', [CheckoutController::class, 'upgrade_account_to_annually']);
    Route::post('/checkout/downgrade-account-monthly', [CheckoutController::class, 'downgrade_account_to_monthly']);
    
    
    Route::post('/checkout/cancel-subscription', [CheckoutController::class, 'cancel_subscription']);
    

    Route::get('/my-profile', [UserSettingController::class, 'index'])->name('my-profile');
    Route::post('/my-profile/update', [UserSettingController::class, 'update_profile']);
    Route::delete('/my-profile/delete', [UserSettingController::class, 'delete_profile']);
});

Route::fallback(function () {
    // return view('/pages/misc-error');
    return redirect(route('pages-register'));
});

