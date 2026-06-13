<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\TeacherController;
use Illuminate\Support\Facades\Route;

// 1. PUBLIC ROUTES
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// 2. AUTHENTICATED ROUTES
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    // Student Dashboard & Placement
    Route::get('/student/dashboard', [StudentController::class, 'dashboard']);
    Route::post('/student/placement', [StudentController::class, 'submitPlacement']);

    // Modules
    Route::get('/modules', [ModuleController::class, 'index']);
    Route::get('/modules/{id}', [ModuleController::class, 'show']);

    // Word Wall
    Route::get('/word-wall', [StudentController::class, 'getWordWall']);
    Route::post('/word-wall', [StudentController::class, 'addWordWall']);
    Route::put('/word-wall/{id}', [StudentController::class, 'updateWordWall']);
    Route::delete('/word-wall/{id}', [StudentController::class, 'deleteWordWall']);

    // Journals
    Route::get('/journals', [StudentController::class, 'getJournals']);
    Route::post('/journals', [StudentController::class, 'addJournal']);

    // Leaderboards
    Route::get('/leaderboard', [StudentController::class, 'getLeaderboard']);

    // Quizzes
    Route::get('/quizzes/{id}', [QuizController::class, 'show']);
    Route::post('/quizzes/{id}/submit', [QuizController::class, 'submit']);

    // 3. TEACHER PORTAL ROUTES (Protected)
    Route::middleware('teacher')->group(function () {
        // Module management
        Route::post('/modules', [ModuleController::class, 'store']);
        Route::put('/modules/{id}', [ModuleController::class, 'update']);
        Route::delete('/modules/{id}', [ModuleController::class, 'destroy']);
        Route::post('/modules/{id}/toggle-publish', [ModuleController::class, 'togglePublish']);
        Route::put('/modules/{id}/level-content', [ModuleController::class, 'updateLevelContent']);

        // Vocab management
        Route::post('/vocab-words', [TeacherController::class, 'storeVocab']);
        Route::put('/vocab-words/{id}', [TeacherController::class, 'updateVocab']);
        Route::delete('/vocab-words/{id}', [TeacherController::class, 'destroyVocab']);

        // Quiz Question management
        Route::post('/quizzes/{quiz_id}/questions', [TeacherController::class, 'storeQuestion']);
        Route::put('/questions/{id}', [TeacherController::class, 'updateQuestion']);
        Route::delete('/questions/{id}', [TeacherController::class, 'destroyQuestion']);

        // Student Tracking & Analytics
        Route::get('/teacher/students', [TeacherController::class, 'getStudents']);
        Route::get('/teacher/students/{id}', [TeacherController::class, 'getStudentDetail']);
        Route::get('/teacher/analytics', [TeacherController::class, 'getAnalytics']);
    });
});
