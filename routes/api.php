<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CaregiverController;
use App\Http\Controllers\MemoryLibraryController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\TaskHistoryController;
use App\Http\Controllers\TaskSchedulerController;
use App\Http\Middleware\CheckPatient;
use App\Http\Middleware\CheckPatientCaregivers;
use App\Models\MemoryLibrary;
use App\Models\TaskScheduler;
use Illuminate\Support\Facades\Route;


Route::group([
    'middleware' => ['api'],
    'prefix' => 'auth',
    'controller' => AuthController::class
], function () {
    Route::post('/login',  'login');
    Route::post('/registercaregiver', [CaregiverController::class, 'addCaregiver']);
    Route::get('/logout', 'logout')->middleware("jwt.verify");;
    Route::post('/refresh', 'refresh');
    Route::get('/user-profile', 'userProfile')->middleware("jwt.verify");
});

Route::group([
    'controller' => CaregiverController::class,
    'middleware' => ['api', 'jwt.verify', 'IsCaregiver'],
], function () {
    Route::get("/patients/{caregiver_id}", 'getCaregiverPatients');
    Route::get("/caregiver/{caregiver_id}", 'getCaregiver');
    Route::get('caregiver/notifications/{caregiver_id}', [NotificationController::class, 'getCaregiverNotifications']);
});

Route::group([
    'middleware' => ['api', 'jwt.verify', 'IsCaregiverPatient'],
    'controller' => PatientController::class
], function () {
    Route::post('/patient', 'addPatient')
        ->withoutMiddleware('IsCaregiverPatient')->middleware('IsCaregiver');
    Route::post('/patient/{patient_id}', 'updatePatient');
    Route::get('/patient/delete/{patient_id}', 'deletePatient');
    Route::get("/patient/{patient_id}", 'getPatient');
    Route::get('patient/notifications/{patient_id}', [NotificationController::class, 'getPaientNotifications'])
        ->middleware('or:' . CheckPatientCaregivers::class . ',' . CheckPatient::class)->withoutMiddleware('IsCaregiverPatient');
    Route::get('patientphoto/{id}', 'getPatientImage')->withoutMiddleware('jwt.verify');
});

Route::group([
    'middleware' => [
        'api', 'jwt.verify',
        'getPaientId:' . MemoryLibrary::class . ',' . 'memory_id',
        'or:' . CheckPatientCaregivers::class . ',' . CheckPatient::class,
    ],
    'controller' => MemoryLibraryController::class
], function () {
    Route::post('/memory/{memory_id}', 'updateMemory');
    Route::get('/memory/delete/{memory_id}', 'deleteMemory');
    Route::get("/memory/{memory_id}", 'getMemory');
    Route::post('/memory', "addMemory")->withoutMiddleware('getPaientId:' . MemoryLibrary::class . ',' . 'memory_id');
    Route::get("/memories/{patient_id}", 'getMemories')->withoutMiddleware('getPaientId:' . MemoryLibrary::class . ',' . 'memory_id');
    Route::get('memoryphoto/{id}', 'getMemoryImage')->withoutMiddleware('jwt.verify');
});


Route::group([
    'controller' => TaskSchedulerController::class,
    'middleware' => ['api', 'jwt.verify'],
], function () {
    Route::post('/task', 'createTask')->middleware('IsCaregiverPatient');
    Route::get('/task/{task_id}', 'getTask')
        ->middleware([
            'getPaientId:' . TaskScheduler::class . ',' . 'task_id',
            'or:' . CheckPatientCaregivers::class . ',' . CheckPatient::class
        ]);
    Route::group([
        'middleware' => ['or:' . CheckPatientCaregivers::class . ',' . CheckPatient::class]
    ], function () {
        Route::get('/tasks/{patient_id}', 'getAllTasks');
        Route::get('/tasks/today/{patient_id}', 'getToDayTasks');
    });
    Route::group([
        'middleware' => ['getPaientId:' . TaskScheduler::class . ',' . 'task_id', 'IsCaregiverPatient']
    ], function () {
        Route::get('/task/delete/{task_id}', 'deleteTask');
        Route::post('/task/{task_id}', 'updateTask');
    });
});

Route::group([
    'controller' => TaskHistoryController::class,
    'middleware' => ['api', 'jwt.verify'],
], function () {
    Route::get('history/{patient_id}/{date}', 'getTasksHistroyByDate')->where('date', '[A-Za-z]+|\d{4}(?:-\d{2}){2}')
        ->middleware('or:' . CheckPatientCaregivers::class . ',' . CheckPatient::class);
    Route::get('history/patient/{patient_id}', 'getPatientHistroy')
        ->middleware('or:' . CheckPatientCaregivers::class . ',' . CheckPatient::class);
    Route::get('history/task/{task_id}', 'getTaskHistory')->middleware([
        'getPaientId:' . TaskScheduler::class . ',' . 'task_id',
        'or:' . CheckPatientCaregivers::class . ',' . CheckPatient::class
    ]);
    Route::post('confirm-task', 'confirmTask')->middleware([
        'getPaientId:' . TaskScheduler::class . ',' . 'task_id', 'IsPatient'
    ]);
    Route::get('historyphoto/{id}', 'getHistoryImage')->withoutMiddleware('jwt.verify');
});


Route::post('notify', [NotificationController::class, 'addNotify']);

Route::any('{url}', function () {
    return responseJson(401, "", "this url not found check parmater");
})->where('url', '.*')->middleware('api');
