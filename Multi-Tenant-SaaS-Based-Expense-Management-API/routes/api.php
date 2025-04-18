<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ExpenseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Authentication
Route::post('register', [AuthController::class, 'register'])->middleware('adminsOnly');
Route::post('login', [AuthController::class, 'login']);

#### Expense Management
Route::get('expenses', [ExpenseController::class, 'index']);
Route::post('expenses', [ExpenseController::class, 'register'])->middleware(['auth:sanctum', 'authUser']);    // Create (restricted to logged-in user’s company)
Route::put('expenses/{id}', [ExpenseController::class, 'update'])->middleware(['auth:sanctum', 'admin_or_manager']);  // Update (Managers & Admins only)
Route::delete('expenses/{id}', [ExpenseController::class, 'update'])->middleware(['auth:sanctum', 'admin:Admin']);

#### User Management
Route::middleware(['auth:sanctum', 'admin:Admin'])->group(function(){
    Route::get('users', [AuthController::class, 'index']);
    Route::post('users', [AuthController::class, 'register']);
    Route::put('users/{id}', [AuthController::class, 'update']); // Update user role (Admins only)
});

// Route::get('users', [AuthController::class, 'index']);