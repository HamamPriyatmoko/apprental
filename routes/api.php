<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminAuthController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Rute untuk registrasi dan login user
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Rute untuk logout user
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);

// Rute untuk mengupdate gambar profil
Route::middleware('auth:sanctum')->post('/profile/picture', [ProfileController::class, 'updateProfilePicture']);

// Rute untuk registrasi dan login admin
Route::post('admin/register', [AdminAuthController::class, 'register']);
Route::post('admin/login', [AdminAuthController::class, 'login']);

// Rute untuk logout admin
Route::middleware('auth:sanctum')->post('/admin/logout', [AdminAuthController::class, 'logout']);

// Rute untuk produk
Route::resource('product', ProductController::class)->only([
    'index', 'show', 'store', 'update', 'destroy'
]);
Route::post('upload', [ProductController::class, 'upload']);
Route::delete('/product/{id}', [ProductController::class, 'destroy']);
