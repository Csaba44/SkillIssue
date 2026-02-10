<?php

use App\Http\Controllers\BadgeController;
use App\Http\Controllers\CorrectAnswerController;
use App\Http\Controllers\GameMatchController;
use App\Http\Controllers\PracticeSessionController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\QuestionReportController;
use App\Http\Controllers\SingleQuestionController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\UserAuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserReportController;
use App\Http\Controllers\VerifyAnswerController;
use App\Http\Middleware\EnsurePracticeSessionTokenIsValid;
use App\Http\Middleware\EnsureQuestionTokenIsValid;
use App\Models\PracticeSession;
use Illuminate\Support\Facades\Route;


Route::middleware("guest")->group(function () {
    Route::post('/register', [UserAuthController::class, 'register']);
    Route::post('/login', [UserAuthController::class, 'login']);
    Route::get("/test", function () {
        return response()->json(["message" => "Service healthy"]);
    });
});

Route::middleware("auth:sanctum")->group(function () {
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
    Route::apiResource('/subjects', SubjectController::class); // Admin only
    Route::get('/subjects/{subject}/random/{count}', [SubjectController::class, 'random']);

    // Questions
    Route::post('/questions/get-one', SingleQuestionController::class)->middleware(EnsurePracticeSessionTokenIsValid::class);
    Route::apiResource('/questions', QuestionController::class); // Admin only

    Route::post('/questions/{question}/answer', [QuestionController::class, 'storeAnswers']); // Admin only;
    Route::delete('/questions/{question}/answer', [QuestionController::class, 'deleteAnswers']); // Admin only;

    Route::post('/questions/correct-answer', CorrectAnswerController::class)->middleware([EnsureQuestionTokenIsValid::class, EnsurePracticeSessionTokenIsValid::class]);

    // Answers
    Route::post('/answers/verify/{answer}', VerifyAnswerController::class)->middleware([EnsureQuestionTokenIsValid::class, EnsurePracticeSessionTokenIsValid::class]);

    // Practice sessions
    Route::apiResource('/practice-sessions', PracticeSessionController::class);
});
