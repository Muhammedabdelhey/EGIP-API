<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CaregiverController;
use App\Http\Controllers\MemoryLibraryController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\TaskHistoryController;
use App\Http\Controllers\TaskSchedulerController;
use Illuminate\Support\Facades\Route;


Route::group([
    'middleware' => ['api'],
    'prefix' => 'auth',
    'controller' => AuthController::class
], function () {
    Route::post('/login',  'login');
    Route::post('/registercaregiver', [CaregiverController::class, 'addCaregiver']);
    Route::get('/logout', 'logout');
    Route::post('/refresh', 'refresh');
    Route::get('/user-profile', 'userProfile')->middleware("jwt.verify");
});

// Route::get('patientphoto/{id}', [PatientController::class, 'getPatientImage'])->middleware('api');
// Route::get('memoryphoto/{id}', [MemoryLibraryController::class, 'getMemoryImage'])->middleware('api');
//Route::get('historyphoto/{id}', [TaskHistoryController::class, 'getHistoryImage'])->middleware('api');


Route::group([
    'middleware' => ['api', 'jwt.verify'],
    'controller' => PatientController::class
], function () {
    Route::post('/patient', 'addPatient');
    Route::post('/patient/{patient_id}', 'updatePatient');
    Route::get('/patient/delete/{patient_id}', 'deletePatient');
    Route::get("/patient/{patient_id}", 'getPatient');
    Route::get('patient/notifications/{patient_id}', [NotificationController::class, 'getPaientNotifications']);
    Route::get('patientphoto/{id}', 'getPatientImage')->withoutMiddleware('jwt.verify');
});

Route::group([
    'middleware' => ['api', 'jwt.verify'],
    'controller' => MemoryLibraryController::class
], function () {
    Route::post('/memory', "addMemory");
    Route::post('/memory/{memory_id}', 'updateMemory');
    Route::get('/memory/delete/{memory_id}', 'deleteMemory');
    Route::get("/memory/{memory_id}", 'getMemory');
    Route::get("/memories/{patient_id}", 'getMemories');
    Route::get('memoryphoto/{id}', 'getMemoryImage')->withoutMiddleware('jwt.verify');
});

Route::group([
    'controller' => TaskSchedulerController::class,
    'middleware' => ['api', 'jwt.verify'],
], function () {
    Route::post('/task', 'createTask');
    Route::get('/task/{task_id}', 'getTask');
    Route::post('/task/{task_id}', 'updateTask');
    Route::get('/tasks/{patient_id}', 'getAllTasks');
    Route::get('/tasks/today/{patient_id}', 'getToDayTasks');
    Route::get('/task/delete/{task_id}', 'deleteTask');
});

Route::group([
    'controller' => TaskHistoryController::class,
    'middleware' => ['api', 'jwt.verify'],
], function () {
    Route::get('history/{patient_id}/{date}', 'getTasksHistroyByDate')->where('date', '[A-Za-z]+|\d{4}(?:-\d{2}){2}');
    Route::get('history/task/{task_id}', 'getTaskHistory');
    Route::get('history/patient/{patient_id}', 'getPatientHistroy');
    Route::post('/task/confirm', 'confirmTask');
    Route::get('historyphoto/{id}', 'getHistoryImage')->withoutMiddleware('jwt.verify');
});

Route::group([
    'controller' => CaregiverController::class,
    'middleware' => ['api', 'jwt.verify'/*, 'IsCaregiver'*/],
], function () {
    Route::get("/patients/{caregiver_id}", 'getCaregiverPatients');
    Route::get("/caregiver/{caregiver_id}", 'getCaregiver');
    Route::get('caregiver/notifications/{caregiver_id}', [NotificationController::class, 'getCaregiverNotifications']);
});
Route::post('notify', [NotificationController::class, 'addNotify']);

Route::any('{url}', function () {
    return responseJson(401, "", "this url not found check parmater");
})->where('url', '.*')->middleware('api');
