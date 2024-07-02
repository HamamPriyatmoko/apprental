<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
// routes/api.php

Route::middleware('auth:sanctum')->post('/profile/picture', [ProfileController::class, 'updateProfilePicture']);

// Rute untuk logout
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);
Route::get('/product', [ProductController::class, 'index']);