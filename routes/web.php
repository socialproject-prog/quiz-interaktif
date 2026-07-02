<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\QuestionController;
use App\Http\Controllers\Admin\QuizController;
use App\Http\Controllers\Admin\ResultController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PresenterController;
use App\Http\Controllers\PublicQuizController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PublicQuizController::class, 'home'])->name('home');
Route::post('/join', [PublicQuizController::class, 'join'])->name('quiz.join');
Route::get('/quiz/{code}', [PublicQuizController::class, 'play'])->name('quiz.play');
Route::post('/quiz/{code}/answer/{question}', [PublicQuizController::class, 'answer'])->name('quiz.answer');
Route::get('/quiz/{code}/result', [PublicQuizController::class, 'result'])->name('quiz.result');
Route::post('/quiz/leave', [PublicQuizController::class, 'leave'])->name('quiz.leave');

Route::get('/presenter/{code}', [PresenterController::class, 'show'])->name('presenter.show');
Route::get('/presenter/{code}/data', [PresenterController::class, 'data'])->name('presenter.data');

Route::middleware('guest')->group(function () {
    Route::get('/admin/login', [AuthController::class, 'showLogin'])->name('admin.login');
    Route::post('/admin/login', [AuthController::class, 'login'])->name('admin.login.submit');
});

Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/', DashboardController::class)->name('dashboard');

    Route::resource('quizzes', QuizController::class);
    Route::post('/quizzes/{quiz}/toggle', [QuizController::class, 'toggle'])->name('quizzes.toggle');
    Route::post('/quizzes/{quiz}/regenerate-code', [QuizController::class, 'regenerateCode'])->name('quizzes.regenerate-code');

    Route::get('/quizzes/{quiz}/questions/create', [QuestionController::class, 'create'])->name('questions.create');
    Route::post('/quizzes/{quiz}/questions', [QuestionController::class, 'store'])->name('questions.store');
    Route::get('/quizzes/{quiz}/questions/{question}/edit', [QuestionController::class, 'edit'])->name('questions.edit');
    Route::put('/quizzes/{quiz}/questions/{question}', [QuestionController::class, 'update'])->name('questions.update');
    Route::delete('/quizzes/{quiz}/questions/{question}', [QuestionController::class, 'destroy'])->name('questions.destroy');

    Route::get('/quizzes/{quiz}/results', [ResultController::class, 'show'])->name('results.show');
    Route::get('/quizzes/{quiz}/results/{participant}', [ResultController::class, 'participant'])->name('results.participant');
    Route::delete('/quizzes/{quiz}/results', [ResultController::class, 'reset'])->name('results.reset');
});
