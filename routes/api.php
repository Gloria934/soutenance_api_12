<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\PatientController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\UserProfileController;

use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\NewPasswordController;


Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});


//routes qui utilisent les contrôleurs situés dans le dossier auth

Route::prefix('auth')->group(function () {
    Route::post('/register', [RegisteredUserController::class, 'store']);
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy']);

    Route::post('/forgot-password', [PasswordResetLinkController::class, 'store']);
    Route::post('/reset-password', [NewPasswordController::class, 'store']);

    Route::get('/verify-email/{id}/{hash}', VerifyEmailController::class)->middleware('signed');
    Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('auth:sanctum');

    
});



//Routes qui concernent les actions liées à un patient
Route::prefix('patient')->group(function () {
    Route::get('/patients/by-code/{code}', [PatientController::class, 'findByCode'])
        ->middleware(['auth:sanctum', 'permission:view patient']);
        
    Route::get('/mes-ordonnances', [PatientController::class, 'mesOrdonnances']);
});


//Routes qui concernent les actions liées aux utilisateurs de l'application

Route::middleware('auth:sanctum')->prefix('users')->group(function () {
    Route::get('/', [UserController::class, 'index']);               // Liste des utilisateurs avec leurs rôles, pagination et fonction de recherche
    Route::get('{id}', [UserController::class, 'show']);             // Voir un user
    Route::put('{id}', [UserController::class, 'update']);           // Modifier un user
    Route::delete('{id}', [UserController::class, 'destroy']);       // Supprimer un user
    Route::post('{id}/role', [UserController::class, 'assignRole']); // Attribuer un rôle

    Route::put('/user/profile', [UserProfileController::class, 'update']);

    Route::get('/roles', [UserController::class, 'roles']); // Liste des rôles
    Route::post('/users', [UserController::class, 'store']); // Création d'un utilisateur
    Route::get('/users/export/excel', [UserController::class, 'exportExcel']);
    Route::get('/users/export/pdf', [UserController::class, 'exportPdf']);

    Route::get('/me', [UserController::class, 'me']);
});




Route::get('/email/verify/{id}', [EmailVerificationNotificationController::class, 'verify'])
    ->name('verification.verify')
    ->middleware(['signed', 'throttle:6,1']); // La route pour vérifier l'email



    Route::prefix('auth')->group(function () {
        Route::middleware(['auth:sanctum'])->group(function () {
            // Envoi du mail de vérification
            Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
                ->middleware('throttle:6,1');
    
            // Vérification de l’email (lien signé)
            Route::get('/email/verify/{id}/{hash}', [VerifyEmailController::class, 'verify'])
                ->middleware(['signed'])
                ->name('verification.verify');
        });
    });











