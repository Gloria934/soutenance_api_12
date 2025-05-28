<?php

use App\Http\Controllers\API\AllergyController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\ClasseController;
use App\Http\Controllers\API\DciController;
use App\Http\Controllers\API\FormeController;
use App\Http\Controllers\API\SousCategoryController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\PharmaceuticalProductController;
use App\Http\Controllers\PrescriptionController;
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
Route::apiResource('pharmaceutical_products', PharmaceuticalProductController::class);
Route::post('pharmaceutical_products/{id}', [PharmaceuticalProductController::class, 'update']);

// Non traité

// Route::get('/prescriptions', [PrescriptionController::class, 'index']);
// Route::delete('/prescriptions/{id}', [PrescriptionController::class, 'destroy']);
// Route::delete('/prescriptions/paid', [PrescriptionController::class, 'deletePaid']);
// Route::put('/prescriptions/{id}/medicaments/{medicamentId}', [PrescriptionController::class, 'updateMedicament']);

// Non traité
// Route::post('/prescriptions', [PrescriptionController::class, 'store']);
// Route::get('/patients', [PrescriptionController::class, 'getPatients']);
// Route::get('/services', [PrescriptionController::class, 'getServices']);
Route::get('/pharmaceutical-products', [PharmaceuticalProductController::class, 'index']);

