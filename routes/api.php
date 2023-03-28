<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CaregiverController;
use App\Http\Controllers\MemoryLibraryController;
use App\Http\Controllers\PatientController;
use Illuminate\Support\Facades\Route;


Route::group([
    'middleware' => ['api', 'checkpassword'],
    'prefix' => 'auth',
    'controller' => AuthController::class
], function () {
    Route::post('/login',  'login');
    Route::post('/registercaregiver', [CaregiverController::class, 'addCaregiver']);
    Route::get('/logout', 'logout');
    Route::post('/refresh', 'refresh');
    Route::get('/user-profile', 'userProfile')->middleware("jwt.verify");
});

Route::get('patientphoto/{id}', [PatientController::class, 'getPatientImage'])->middleware('api');
Route::get('memoryphoto/{id}', [MemoryLibraryController::class, 'getMemoryImage'])->middleware('api');

Route::group([
    'middleware' => ['api', 'checkpassword', 'jwt.verify'],
    'controller' => PatientController::class
], function () {
    Route::post('/patient', 'addPatient');
    Route::put('/patient/{patient_id}', 'updatePatient');
    Route::delete('/patient/{patient_id}', 'deletePatient');
    Route::get("/patient/{patient_id}", 'getPatient');
    Route::get("/patients/{caregiver_id}", 'getPatients');
});

Route::group([
    'middleware' => ['api', 'checkpassword', 'jwt.verify'],
    'controller' => MemoryLibraryController::class
], function () {
    Route::post('/memory',"addMemory");
    Route::put('/memory/{memory_id}', 'updateMemory');
    Route::delete('/memory/{memory_id}', 'deleteMemory');
    Route::get("/memory/{memory_id}", 'getMemory');
    Route::get("/memories/{patient_id}", 'getMemories');
});
Route::any('{url}', function(){
    return responseJson(404,"","this url not found check parmater");
})->where('url', '.*')->middleware('api');