<?php

use App\Http\Controllers\Api\Admin\LawyerApplicationController as AdminLawyerApplicationController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\LawyerApplicationController;
use App\Http\Controllers\Api\LawyerController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/user', function (Illuminate\Http\Request $request) {
        return response()->json($request->user());
    });

    Route::post('/lawyer/apply', [LawyerApplicationController::class, 'apply'])
        ->middleware('role:user');

    Route::prefix('admin')->middleware('role:admin')->group(function () {

        // Get pending lawyer applications
        Route::get('/lawyer-applications', [AdminLawyerApplicationController::class, 'index']);

        // Approve application
        Route::put('/lawyer-applications/{id}/approve', [AdminLawyerApplicationController::class, 'approve']);

        // Reject application
        Route::put('/lawyer-applications/{id}/reject', [AdminLawyerApplicationController::class, 'reject']);

    });

    // // Admin Routes
    // Route::get('/admin/test', function () {
    //     return response()->json([
    //         'message' => 'Admin access granted',
    //     ]);
    // })->middleware('role:admin');

    // Check application status
    Route::get('/lawyer/application-status', [LawyerController::class, 'applicationStatus']);

    // Lawyer profile - approved lawyers only
    Route::get('/lawyer/profile', [LawyerController::class, 'profile'])
        ->middleware('role:lawyer');

});
