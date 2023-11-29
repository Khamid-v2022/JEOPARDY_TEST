<?php 

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\admin\LoginController;
use App\Http\Controllers\admin\QuestionManageController;
use App\Http\Controllers\admin\UserManageController;

Route::namespace('admin')->prefix('admin')->group(function(){
    Route::middleware('guest:admin')->group(function(){
        Route::get('/login', [LoginController::class, 'index'])->name('admin-login-page');
        Route::post('/login', [LoginController::class, 'signin']);
    });

    Route::middleware(['auth.admin:admin'])->group(function(){
        Route::get('/', [QuestionManageController::class, 'index'])->name('question-management-page');
        Route::get('/question-management/load-question', [QuestionManageController::class, 'loadQuestions']);
        Route::post('/question-management/create-update-question', [QuestionManageController::class, 'createOrUpdateQuestion']);
        Route::post('/question-management/import-question', [QuestionManageController::class, 'importQuestion']);
        Route::delete('/question-management/delete-question/{id}', [QuestionManageController::class, 'deleteQuestion'])->where('id', '[0-9]+');

        Route::get('/users', [UserManageController::class, 'index'])->name('user-management-page');
        Route::get('/users/get-info/{id}', [UserManageController::class, 'getUserInfo'])->where('id', '[0-9]+');

        Route::get('/logout', [LoginController::class, 'signout'])->name('admin-logout');


        // Route::get('/question-management/structure-question', [QuestionManageController::class, 'structure_question']);
        // Route::get('/question-management/update_questions_remove_html', [QuestionManageController::class, 'update_questions_remove_html']);
        // Route::get('/question-management/remove_questions_have_html', [QuestionManageController::class, 'remove_questions_have_html']);
        // Route::get('/question-management/insert_question_info_to_answer_table', [QuestionManageController::class, 'insert_question_info_to_answer_table']);
        
    });
});
