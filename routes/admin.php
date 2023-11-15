<?php 

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\admin\LoginController;
use App\Http\Controllers\admin\QuestionManageController;

Route::namespace('admin')->prefix('admin')->group(function(){
    Route::middleware('guest:admin')->group(function(){
        Route::get('/login', [LoginController::class, 'index'])->name('admin-login-page');
        Route::post('/login', [LoginController::class, 'signin']);
    });

    Route::middleware('auth.admin:admin')->group(function(){
        Route::get('/', [QuestionManageController::class, 'index'])->name('question-management-page');
        Route::get('/logout', [LoginController::class, 'signout'])->name('admin-logout');
    });
});
