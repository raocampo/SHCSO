<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CatalogController;
use App\Http\Controllers\Api\CertificateController;
use App\Http\Controllers\Api\EvaluationController;
use App\Http\Controllers\Api\ReportController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\WorkerController;

Route::get('/health', fn () => response()->json([
    'ok' => true,
    'message' => 'API SHCSO activa',
    'timestamp' => now()->toISOString(),
]));

Route::post('/auth/register-admin', [AuthController::class, 'registerAdmin']);
Route::post('/auth/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/auth/me', [AuthController::class, 'me']);
    Route::post('/auth/logout', [AuthController::class, 'logout']);

    Route::get('/users', [UserController::class, 'index'])
        ->middleware('role:ADMIN');
    Route::get('/users/roles', [UserController::class, 'roles'])
        ->middleware('role:ADMIN');
    Route::post('/users', [UserController::class, 'store'])
        ->middleware('role:ADMIN');
    Route::put('/users/{userId}', [UserController::class, 'update'])
        ->middleware('role:ADMIN');
    Route::put('/users/{userId}/status', [UserController::class, 'updateStatus'])
        ->middleware('role:ADMIN');

    Route::get('/catalog/companies', [CatalogController::class, 'listCompanies']);
    Route::post('/catalog/companies', [CatalogController::class, 'createCompany'])
        ->middleware('role:ADMIN,RECEPCION');
    Route::get('/catalog/job-positions', [CatalogController::class, 'listJobPositions']);
    Route::post('/catalog/job-positions', [CatalogController::class, 'createJobPosition'])
        ->middleware('role:ADMIN,RECEPCION');

    Route::get('/workers', [WorkerController::class, 'index'])
        ->middleware('role:ADMIN,MEDICO_OCUPACIONAL,ENFERMERIA,RECEPCION,AUDITOR');
    Route::post('/workers', [WorkerController::class, 'store'])
        ->middleware('role:ADMIN,MEDICO_OCUPACIONAL,ENFERMERIA,RECEPCION');
    Route::put('/workers/{workerId}', [WorkerController::class, 'update'])
        ->middleware('role:ADMIN,MEDICO_OCUPACIONAL,ENFERMERIA,RECEPCION');
    Route::get('/workers/{workerId}', [WorkerController::class, 'show'])
        ->middleware('role:ADMIN,MEDICO_OCUPACIONAL,ENFERMERIA,RECEPCION,AUDITOR');
    Route::get('/workers/{workerId}/history', [WorkerController::class, 'history'])
        ->middleware('role:ADMIN,MEDICO_OCUPACIONAL,ENFERMERIA,RECEPCION,AUDITOR');

    Route::post('/evaluations', [EvaluationController::class, 'store'])
        ->middleware('role:ADMIN,MEDICO_OCUPACIONAL');
    Route::get('/evaluations', [EvaluationController::class, 'index'])
        ->middleware('role:ADMIN,MEDICO_OCUPACIONAL,ENFERMERIA,AUDITOR');
    Route::get('/evaluations/{evaluationId}', [EvaluationController::class, 'show'])
        ->middleware('role:ADMIN,MEDICO_OCUPACIONAL,ENFERMERIA,AUDITOR');
    Route::post('/evaluations/{evaluationId}/attachments', [EvaluationController::class, 'uploadAttachment'])
        ->middleware('role:ADMIN,MEDICO_OCUPACIONAL,ENFERMERIA');
    Route::get('/evaluations/{evaluationId}/attachments', [EvaluationController::class, 'listAttachments'])
        ->middleware('role:ADMIN,MEDICO_OCUPACIONAL,ENFERMERIA,AUDITOR');

    Route::post('/certificates/from-evaluation/{evaluationId}', [CertificateController::class, 'storeFromEvaluation'])
        ->middleware('role:ADMIN,MEDICO_OCUPACIONAL');
    Route::get('/certificates', [CertificateController::class, 'index'])
        ->middleware('role:ADMIN,MEDICO_OCUPACIONAL,ENFERMERIA,AUDITOR');
    Route::get('/certificates/{certificateId}', [CertificateController::class, 'show'])
        ->middleware('role:ADMIN,MEDICO_OCUPACIONAL,ENFERMERIA,AUDITOR');
    Route::post('/certificates/{certificateId}/generate-pdf', [CertificateController::class, 'generatePdf'])
        ->middleware('role:ADMIN,MEDICO_OCUPACIONAL');
    Route::get('/certificates/{certificateId}/download-pdf', [CertificateController::class, 'downloadPdf'])
        ->middleware('role:ADMIN,MEDICO_OCUPACIONAL,ENFERMERIA,AUDITOR');

    Route::get('/reports/dashboard', [ReportController::class, 'dashboard'])
        ->middleware('role:ADMIN,MEDICO_OCUPACIONAL,ENFERMERIA,AUDITOR');
    Route::get('/reports/aptitude-by-company', [ReportController::class, 'aptitudeByCompany'])
        ->middleware('role:ADMIN,MEDICO_OCUPACIONAL,ENFERMERIA,AUDITOR');
    Route::get('/reports/top-diagnoses', [ReportController::class, 'topDiagnoses'])
        ->middleware('role:ADMIN,MEDICO_OCUPACIONAL,ENFERMERIA,AUDITOR');
    Route::get('/reports/monthly-activity', [ReportController::class, 'monthlyActivity'])
        ->middleware('role:ADMIN,MEDICO_OCUPACIONAL,ENFERMERIA,AUDITOR');
});
