<?php

use App\Http\Controllers\BadgeController;
use App\Http\Controllers\GameMatchController;
use App\Http\Controllers\QuestionReportController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\UserAuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserReportController;
use Illuminate\Support\Facades\Route;


Route::middleware("guest")->group(function () {
    Route::post('/register', [UserAuthController::class, 'register']);
    Route::post('/login', [UserAuthController::class, 'login']);
});

Route::middleware("auth:sanctum")->group(function () {
    /*Route::get('/user', function (Request $request) {
        return $request->user();
    });*/

    Route::post('/logout', [UserAuthController::class, 'logout']);

    Route::apiResource('/badges', BadgeController::class);
    Route::apiResource('/users', UserController::class)->only(['index', 'update', 'destroy']);

    // Reports authorization rules:
    //          For non-admin users: POST
    //          For admin users: GET, POST, PUT, DELETE
    Route::apiResource('/user-reports', UserReportController::class);
    Route::apiResource('/question-reports', QuestionReportController::class);

    // Delete -> only for admins
    Route::apiResource('/game-matches', GameMatchController::class)->only(['index', 'show', 'destroy']);

    // Subjects
    Route::apiResource('subjects', SubjectController::class); // Admin only
    Route::get('subjects/{subject}/random/{count}', [SubjectController::class, 'random']);
});
