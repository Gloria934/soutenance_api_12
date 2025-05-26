<?php

use App\Http\Controllers\API\AllergyController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\ClasseController;
use App\Http\Controllers\API\DciController;
use App\Http\Controllers\API\FormeController;
use App\Http\Controllers\API\SousCategoryController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

require __DIR__ . '/auth.php';

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResource('categories', CategoryController::class);
Route::apiResource('sous_categories', SousCategoryController::class);
Route::apiResource('dcis', DciController::class);
Route::apiResource('classes', ClasseController::class);
Route::apiResource('formes', FormeController::class);
Route::apiResource('allergies', AllergyController::class);
Route::post('verify_user_number', [AuthenticatedSessionController::class, 'verifyUserNumber']);


