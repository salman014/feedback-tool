<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FeedbackController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->group(function () {
    // Route to get user information
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Route for feedback (example)
    Route::post('/add-feedback', [FeedbackController::class, 'addFeedback']);
    Route::get('/feedback-listing', [FeedbackController::class, 'feedbackListing']);
    Route::get('/feedback-detail', [FeedbackController::class, 'feedbackDetail']);
    Route::post('/add-feedback-comment', [FeedbackController::class, 'addFeedbackComment']);
    Route::get('/mention-users', [FeedbackController::class, 'mentionUsers']);

    // Route for logout
    Route::post('/logout', [AuthController::class, 'logout']);
});

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);