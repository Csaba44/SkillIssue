<?php

use App\Http\Controllers\BadgeController;
use App\Http\Controllers\UserAuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



// User needs to be logged in to access the routes
Route::middleware('auth:sanctum')->group(function () {
  Route::get('/user', function (Request $request) {
    $user = $request->user()->load('badges');

    return response()->json([
      'user' => $user,
      'rank' => $user->rank,
      'level' => $user->level,
    ]);
  });

  // Auth
  Route::post('/logout', [UserAuthController::class, 'logout']);

  // Badges
  Route::get('/badges', [BadgeController::class, 'index']);
});

// Public routes
Route::post('/register', [UserAuthController::class, 'register']);
Route::post('/login', [UserAuthController::class, 'login']);
