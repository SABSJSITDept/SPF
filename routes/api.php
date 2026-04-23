<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SpfRegistrationController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\ReportController;

Route::post('/register', [SpfRegistrationController::class, 'store']);

Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/sub-categories/{category_id}', [CategoryController::class, 'getSubCategories']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Admin Login
Route::post('/admin/login', [AdminAuthController::class, 'login']);

// Protect Admin Routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/admin/logout', [AdminAuthController::class, 'logout']);
    Route::get('/admin/users', [SpfRegistrationController::class, 'getUsers']); // Fetch all users

    // Member action API
    Route::post('/member-action', [SpfRegistrationController::class, 'memberAction']);

    //---------------Reports----------------
    
    Route::get('/admin/fetchusers', [ReportController::class, 'getFilteredUsers']);
    Route::get('/admin/export', [ReportController::class, 'exportToExcel']);
    Route::get('/filters', [ReportController::class, 'getFilters']);
    
});
Route::get('/admin/export-users', [ReportController::class, 'export']);
