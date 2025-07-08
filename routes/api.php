<?php

use App\Http\Controllers\API\AllergyController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\ClasseController;
use App\Http\Controllers\API\DciController;
use App\Http\Controllers\API\FormeController;
use App\Http\Controllers\API\SousCategoryController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\ConsultationController;
use App\Http\Controllers\DeviceTokenController;
use App\Http\Controllers\OrdonnanceController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\PersonnelController;
use App\Http\Controllers\PharmaceuticalProductController;
use App\Http\Controllers\PharmacyController;
use App\Http\Controllers\PrescriptionController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\RendezVousController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\SimpleNotificationController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Kreait\Firebase\Factory;

// use Illuminate\Support\Facades\Log;


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
Route::apiResource('services', ServiceController::class);
Route::post('services/{id}/restore', [ServiceController::class, 'restore']);
//
Route::get('rendez-vous-pour-service-precis', [ServiceController::class, 'rendezVousPourServicePrecis']);//
Route::post('editer-rendez-vous', [ServiceController::class, 'editerRendezVous']);//
//

Route::post('verify_user_number', [AuthenticatedSessionController::class, 'verifyUserNumber']);
Route::apiResource('pharmaceutical_products', PharmaceuticalProductController::class);
Route::put('pharmaceutical_products/{id}', [PharmaceuticalProductController::class, 'update']);
// Route::delete('pharmaceutical_products/{id}', [PharmaceuticalProductController::class, 'delete']);
Route::apiResource('ordonnance', OrdonnanceController::class);
Route::put('ordonnance/{ordonnance}', [PharmacyController::class, 'getOrdonnances']);
Route::post('find-user-with-code-patient', [PatientController::class, 'findUserWithCodePatient']);
Route::post('edit-patient', [RegisteredUserController::class, 'editPatientInfo']);


Route::get('/pharmaceutical-products', [PharmaceuticalProductController::class, 'index']);


// en cours : Pour les notifications
Route::post('/save-device-token', [NotificationController::class, 'saveDeviceToken'])->middleware('auth:sanctum');

// Route::get('count', [RegisteredUserController::class, 'countAdmin']);
Route::post('update-device-token', [DeviceTokenController::class, 'updateDeviceToken']);
Route::apiResource('roles', RoleController::class);
Route::apiResource('personnels', PersonnelController::class);
Route::post('/personnels/{id}/role', [PersonnelController::class, 'updateRole']);

Route::get('/notifications', [SimpleNotificationController::class, 'index']);
Route::post('/notifications/{id}/accept', [SimpleNotificationController::class, 'accept']);
Route::post('/notifications/{id}/reject', [SimpleNotificationController::class, 'reject']);
Route::delete('/notifications/{id}', [SimpleNotificationController::class, 'destroy']);

Route::post('/user/{id}', [RegisteredUserController::class, 'checkRole'], );
Route::get('/nextId', [RegisteredUserController::class, 'nextId']);
Route::get('/fetch-all-personnels', [PersonnelController::class, 'fetchAllPersonnel']);
Route::post('/scan', [PersonnelController::class, 'scanUser'], );
Route::apiResource('/patients', PatientController::class);


Route::apiResource('rendez-vous', RendezVousController::class);
Route::post('rendez-vous-direct', [RendezVousController::class, 'storeDirect']);
Route::get('rendez-vous-utilisateur', [RendezVousController::class, 'getUserRdv']);
// Route suivante pour faire un enregistrement rapide d'un utilisateur par un personnel à l'accueil.
// Données : nom_visiteur, prenom_visiteur, code_patient, service_id, date_rdv ...............
Route::post('rendez-vous-rapide', [RendezVousController::class, 'rdvRapide']);


Route::get('/rendez-vous-a-valider', [RendezVousController::class, 'rendezVousAValider'])->middleware(['auth:api']);

Route::post('/save_ordonnance', [OrdonnanceController::class, 'store']);
Route::get('/ordonnances-utilisateur', [OrdonnanceController::class, 'getUserOrdonnances']);
Route::post('find-ordonnance', [OrdonnanceController::class, 'findUserOrdonnance']);
Route::post('invalider-ordonnance', [OrdonnanceController::class, 'invaliderOrdonnance']);

Route::post('/update_ordonnance', [OrdonnanceController::class, 'updateOrdonnance']);

Route::post('/update_consultation', [ConsultationController::class, 'updateConsultation']);
Route::get('accueil', [RendezVousController::class, 'accueil']);
Route::apiResource('/consultations', ConsultationController::class);
