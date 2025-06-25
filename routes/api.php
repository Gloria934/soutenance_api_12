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
Route::post('verify_user_number', [AuthenticatedSessionController::class, 'verifyUserNumber']);
Route::apiResource('pharmaceutical_products', PharmaceuticalProductController::class);
Route::put('pharmaceutical_products/{id}', [PharmaceuticalProductController::class, 'update']);
// Route::delete('pharmaceutical_products/{id}', [PharmaceuticalProductController::class, 'delete']);

Route::put('ordonnance/{code_patient}', [PharmacyController::class, 'getOrdonnance']);

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


// en cours : Pour les notifications
Route::post('/save-device-token', [NotificationController::class, 'saveDeviceToken'])->middleware('auth:sanctum');



Route::get('/test-firebase', function () {
    try {
        $credentials = config('firebase.projects.default.credentials.file');
        if (!$credentials) {
            throw new \Exception('Firebase credentials not configured in config/firebase.php');
        }

        $credentialsPath = base_path($credentials);
        if (!file_exists($credentialsPath)) {
            throw new \Exception('Firebase credentials file does not exist at: ' . $credentialsPath);
        }

        if (!is_readable($credentialsPath)) {
            throw new \Exception('Firebase credentials file is not readable at: ' . $credentialsPath);
        }

        $firebase = (new Factory)
            ->withServiceAccount($credentialsPath)
            ->createAuth();
        return response()->json(['message' => 'Firebase initialized successfully']);
    } catch (\Exception $e) {
        \Illuminate\Support\Facades\Log::error('Firebase initialization failed: ' . $e->getMessage());
        return response()->json([
            'error' => 'Failed to initialize Firebase: ' . $e->getMessage(),
        ], 500);
    }
});

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
Route::get('/fetch-all-personnels', [PersonnelController::class, 'fetchAllPersonnel']);
Route::post('/scan', [PersonnelController::class, 'scanUser'], );
Route::apiResource('/patients', PatientController::class);

Route::apiResource('rendez-vous', RendezVousController::class);
Route::get('rendez-vous-utilisateur', [RendezVousController::class, 'getUserRdv']);

Route::get('/rendez-vous-a-valider', [RendezVousController::class, 'rendezVousAValider'])->middleware(['auth:api']);

Route::post('/save_ordonnance', [OrdonnanceController::class, 'store']);
Route::get('/ordonnances-utilisateur', [OrdonnanceController::class, 'getUserOrdonnances']);
Route::post('find-ordonnance', [OrdonnanceController::class, 'findUserOrdonnance']);
Route::post('invalider-ordonnance', [OrdonnanceController::class, 'invaliderOrdonnance']);



Route::post('/update_ordonnance', [OrdonnanceController::class, 'updateOrdonnance']);

Route::post('/update_consultation', [ConsultationController::class, 'updateConsultation']);
Route::get('accueil', [RendezVousController::class, 'accueil']);
Route::apiResource('/consultations', ConsultationController::class);
