<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\API\PatientController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\ProfileController;
use App\Http\Controllers\Auth\FirebaseLoginController;
use App\Http\Controllers\Auth\FirebaseRegisterController;
use App\Http\Controllers\Auth\FirebaseEmailVerificationNotificationController;
use App\Http\Controllers\Auth\FirebasePasswordResetLinkController;
use App\Http\Controllers\Auth\FirebaseVerifyEmailController;
use App\Http\Controllers\Auth\FirebaseNewPasswordController;






// routes/api.php
Route::post('/email/verification-notification', [FirebaseEmailVerificationNotificationController::class, 'store'])
    ->middleware(['auth:sanctum', 'throttle:6,1']);

Route::get('/verify-email/{token}', [FirebaseVerifyEmailController::class, '__invoke'])
    ->name('verification.verify');

Route::post('/forgot-password', [FirebasePasswordResetLinkController::class, 'store'])
    ->middleware('guest');

Route::post('/reset-password', [FirebaseNewPasswordController::class, 'store'])
    ->middleware('guest');

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
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

    

    Route::get('/roles', [UserController::class, 'roles']); // Liste des rôles
    Route::post('/users', [UserController::class, 'store']); // Création d'un utilisateur
    Route::get('/users/export/excel', [UserController::class, 'exportExcel']);
    Route::get('/users/export/pdf', [UserController::class, 'exportPdf']);

    Route::get('/me', [UserController::class, 'me']);
});


//  Route publique
Route::post('/login', [FirebaseLoginController::class, 'login']);


Route::post('/register', [FirebaseRegisterController::class, 'register']);


//  Routes protégées par authentification



// Fichier : routes/api.php
Route::middleware(['firebase.auth'])->group(function () {
    //Route::get('/me', [ProfileController::class, 'me']);
    // ... autres routes protégées
});




Route::middleware('auth:sanctum')->group(function () {
    
    // Accessible par tous les utilisateurs connectés
    //Route::get('/me', fn () => Auth::user());

    // 👤 Routes réservées aux patients
    /*Route::middleware('role:patient')->group(function () {
        Route::post('/payer', [PaiementController::class, 'payer']);
        Route::get('/historique', [PaiementController::class, 'historique']);
    });*/

    // 🛡️ Routes réservées aux admins
   /* Route::middleware('role:admin')->group(function () {
        Route::get('/admin/dashboard', [AdminController::class, 'dashboard']);
        Route::post('/admin/valider-paiement', [AdminController::class, 'validerPaiement']);
    });*/
});







   


 
    











