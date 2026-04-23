<?php

use App\Http\Controllers\AllUsersController;
use App\Http\Controllers\BadgeController;
use App\Http\Controllers\BanController;
use App\Http\Controllers\CorrectAnswerController;
use App\Http\Controllers\GameMatchController;
use App\Http\Controllers\internal\CorrectQuestionController;
use App\Http\Controllers\internal\GameMatchController as InternalGameMatchController;
use App\Http\Controllers\internal\SingleQuestionController as InternalSingleQuestionController;
use App\Http\Controllers\internal\SocketAuthController as InternalSocketAuthController;
use App\Http\Controllers\internal\VerifyAnswerController as InternalVerifyAnswerController;
use App\Http\Controllers\PracticeSessionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\QuestionReportController;
use App\Http\Controllers\SingleQuestionController;
use App\Http\Controllers\SocketAuthController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\UserAuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserReportController;
use App\Http\Controllers\VerifyAnswerController;
use App\Http\Middleware\EnsurePracticeSessionTokenIsValid;
use App\Http\Middleware\EnsureQuestionTokenIsValid;
use App\Http\Middleware\EnsureRankedQuestionTokenIsValid;
use App\Http\Middleware\EnsureRankedTokenIsValid;
use App\Http\Middleware\EnsureReportQuestionTokenIsValid;
use App\Http\Middleware\EnsureServiceTokenIsValid;
use App\Models\PracticeSession;
use Illuminate\Support\Facades\Route;

// Healthcheck
Route::get("/health", function () {
    return response()->json(["message" => "Service healthy"]);
});

Route::middleware("guest")->group(function () {
    Route::post('/register', [UserAuthController::class, 'register']);
    Route::post('/login', [UserAuthController::class, 'login']);
});



/* INTERNAL SERVICE2SERVICE ROUTES */

// Authentication for websocket
Route::get('/socket-auth', InternalSocketAuthController::class)->middleware(['web', 'auth:sanctum']);

Route::prefix('/internal')->middleware(EnsureServiceTokenIsValid::class)->group(function () {
    Route::post('/game-matches', [InternalGameMatchController::class, 'store']);
    Route::post('/questions/get-one', InternalSingleQuestionController::class)->middleware(EnsureRankedTokenIsValid::class);
    Route::post('/answers/verify/{id}', InternalVerifyAnswerController::class)->middleware([EnsureRankedTokenIsValid::class, EnsureRankedQuestionTokenIsValid::class]);
});

// Profiles
Route::get('/profiles/{user}', [ProfileController::class, 'show']);


Route::middleware("auth:sanctum")->group(function () {
    Route::post('/logout', [UserAuthController::class, 'logout']);
    
    Route::get('/users/all', AllUsersController::class); // admin

    Route::apiResource('/badges', BadgeController::class);
    Route::apiResource('/users', UserController::class)->only(['index', 'update', 'destroy']);

    // Reports authorization rules:
    //          For non-admin users: POST
    //          For admin users: GET, POST, PUT, DELETE
    Route::apiResource('/user-reports', UserReportController::class)->except(['store']);;

    Route::apiResource('/user-reports', UserReportController::class)
        ->only(['store'])
        ->middleware(EnsureRankedTokenIsValid::class);

    Route::apiResource('/question-reports', QuestionReportController::class)
        ->only(['store'])
        ->middleware(EnsureReportQuestionTokenIsValid::class);

    Route::apiResource('/question-reports', QuestionReportController::class)
        ->except(['store']);

    // Delete -> only for admins
    Route::apiResource('/game-matches', GameMatchController::class)->only(['index', 'show', 'destroy']);

    // Subjects
    Route::apiResource('/subjects', SubjectController::class); // Admin only
    Route::get('/subjects/{subject}/random/{count}', [SubjectController::class, 'random']);



    // Practice sessions
    Route::apiResource('/practice-sessions', PracticeSessionController::class);




    // Questions
    Route::post('/questions/get-one', SingleQuestionController::class)->middleware(EnsurePracticeSessionTokenIsValid::class);
    Route::apiResource('/questions', QuestionController::class); // Admin only

    Route::post('/questions/{question}/answer', [QuestionController::class, 'storeAnswers']); // Admin only;
    Route::delete('/questions/{question}/answer', [QuestionController::class, 'deleteAnswers']); // Admin only;

    Route::post('/questions/correct-answer', CorrectAnswerController::class)->middleware([EnsureQuestionTokenIsValid::class, EnsurePracticeSessionTokenIsValid::class]);

    // Answers
    Route::post('/answers/verify/{answer}', VerifyAnswerController::class)->middleware([EnsureQuestionTokenIsValid::class, EnsurePracticeSessionTokenIsValid::class]);

    Route::apiResource('/ban', BanController::class); // Admin only
});
