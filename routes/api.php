<?php

use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EligibilityController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\PublicLedgerController;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware('throttle:6,1')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::post('/eligibility/cycle', [EligibilityController::class, 'cycle']);

    Route::post('/wallets', [WalletController::class, 'store']);
    Route::delete('/wallets/{wallet}', [WalletController::class, 'destroy']);

    Route::post('/projects', [ProjectController::class, 'store']);
    Route::put('/projects/{project}', [ProjectController::class, 'update']);
    Route::delete('/projects/{project}', [ProjectController::class, 'destroy']);

    Route::get('/wallets/trashed', [WalletController::class, 'trashed']);
    Route::post('/wallets/{id}/restore', [WalletController::class, 'restore']);

    Route::get('/projects/trashed', [ProjectController::class, 'trashed']);
    Route::post('/projects/{id}/restore', [ProjectController::class, 'restore']);

    Route::delete('/wallets/{id}/force', [WalletController::class, 'forceDelete']);
    Route::delete('/projects/{id}/force', [ProjectController::class, 'forceDelete']);
});
Route::get('/public/ledger/{shareId}', [PublicLedgerController::class, 'show']);

Route::get('/email/verify/{id}/{hash}', function (Request $request, $id, $hash) {
    $user = User::findOrFail($id);

    if (! hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
        return response('<h2 style="font-family:sans-serif">❌ Invalid verification link.</h2>', 403);
    }

    if (! $user->hasVerifiedEmail()) {
        $user->markEmailAsVerified();
        event(new Verified($user));
    }

    return response('<h2 style="font-family:sans-serif">✅ Email verified! You can close this tab and return to the app.</h2>');
})->middleware('signed')->name('verification.verify');

