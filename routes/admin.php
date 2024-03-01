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

        Route::get('/feature-task-management', [QuestionManageController::class, 'featuredTasksPage'])->name('feature-task-page');
        Route::post('/feature-task-management/import-featured-task', [QuestionManageController::class, 'importFeaturedTask']);
        Route::delete('/feature-task-management/delete-featured-task/{id}', [QuestionManageController::class, 'deleteFeaturedTask'])->where('id', '[0-9]+');
        Route::post('/feature-task-management/update-task-title', [QuestionManageController::class, 'updateTaskTitle']);

        // Detail page to add/delete questions
        Route::get('/feature-question-management/task/{id}', [QuestionManageController::class, 'featuredQuestionsPage'])->where('id', '[0-9]+')->name('feature-questions-page');
        Route::delete('/feature-question-management/delete-featured-question/{id}', [QuestionManageController::class, 'deleteFeatureQuestion'])->where('id', '[0-9]+');
        Route::post('/feature-question-management/update-question', [QuestionManageController::class, 'updateFeatureQustion']);
        

        
        
        // Route::get('/feature-question-management/load_feature_tasks', [QuestionManageController::class, 'loadFeatureTasks']);
        

        Route::get('/users', [UserManageController::class, 'index'])->name('user-management-page');
        Route::get('/users/get-info/{id}', [UserManageController::class, 'getUserInfo'])->where('id', '[0-9]+');
        Route::get('/user/test-data/{id}', [UserManageController::class, 'viewUserTestInfo'])->where('id', '[0-9]+');
        Route::get('/user/test-data/get-user-scores/{id}', [UserManageController::class, 'getUserScoreForChart'])->where('id', '[0-9]+');
        Route::get('/user/test-data/view-detail/{id}', [UserManageController::class, 'viewTestDetail'])->where('id', '[0-9]+')->name('user-test-detail');

        

        Route::get('/logout', [LoginController::class, 'signout'])->name('admin-logout');


        // Route::get('/question-management/structure-question', [QuestionManageController::class, 'structure_question']);
        // Route::get('/question-management/update_questions_remove_html', [QuestionManageController::class, 'update_questions_remove_html']);
        // Route::get('/question-management/remove_questions_have_html', [QuestionManageController::class, 'remove_questions_have_html']);
        // Route::get('/question-management/insert_question_info_to_answer_table', [QuestionManageController::class, 'insert_question_info_to_answer_table']);
        
    });
});
