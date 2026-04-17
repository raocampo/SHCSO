<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AccidentController;
use App\Http\Controllers\Api\AppointmentController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CatalogController;
use App\Http\Controllers\Api\CertificateController;
use App\Http\Controllers\Api\ClinicalEvolutionController;
use App\Http\Controllers\Api\EvaluationController;
use App\Http\Controllers\Api\ExamOrderController;
use App\Http\Controllers\Api\ReportController;
use App\Http\Controllers\Api\SettingsController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\VaccinationController;
use App\Http\Controllers\Api\WorkerController;

Route::get('/health', fn () => response()->json([
    'ok' => true,
    'message' => 'API SHCSO activa',
    'timestamp' => now()->toISOString(),
]));

Route::get('/auth/setup-status', [AuthController::class, 'setupStatus']);
Route::post('/auth/register-admin', [AuthController::class, 'registerAdmin']);
Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('/auth/reset-password', [AuthController::class, 'resetPassword']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/auth/me', [AuthController::class, 'me']);
    Route::put('/auth/me', [UserController::class, 'updateSelf']);
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
    Route::put('/users/{userId}/reset-password', [UserController::class, 'resetPassword'])
        ->middleware('role:ADMIN');

    Route::get('/catalog/companies', [CatalogController::class, 'listCompanies']);
    Route::post('/catalog/companies', [CatalogController::class, 'createCompany'])
        ->middleware('role:ADMIN,RECEPCION');
    Route::get('/catalog/job-positions', [CatalogController::class, 'listJobPositions']);
    Route::post('/catalog/job-positions', [CatalogController::class, 'createJobPosition'])
        ->middleware('role:ADMIN,RECEPCION');
    Route::get('/catalog/diagnoses', [CatalogController::class, 'listDiagnoses'])
        ->middleware('role:ADMIN,MEDICO_OCUPACIONAL,ENFERMERIA,RECEPCION,AUDITOR');
    Route::get('/catalog/medications', [CatalogController::class, 'listMedications'])
        ->middleware('role:ADMIN,MEDICO_OCUPACIONAL,ENFERMERIA,RECEPCION,AUDITOR');
    Route::post('/catalog/medications', [CatalogController::class, 'storeMedication'])
        ->middleware('role:ADMIN,MEDICO_OCUPACIONAL');
    Route::put('/catalog/medications/{medicationId}', [CatalogController::class, 'updateMedication'])
        ->middleware('role:ADMIN,MEDICO_OCUPACIONAL');

    Route::get('/workers', [WorkerController::class, 'index'])
        ->middleware('role:ADMIN,MEDICO_OCUPACIONAL,ENFERMERIA,RECEPCION,AUDITOR');
    Route::post('/workers', [WorkerController::class, 'store'])
        ->middleware('role:ADMIN,MEDICO_OCUPACIONAL,ENFERMERIA,RECEPCION');
    Route::put('/workers/{workerId}', [WorkerController::class, 'update'])
        ->middleware('role:ADMIN,MEDICO_OCUPACIONAL,ENFERMERIA,RECEPCION');
    Route::delete('/workers/{workerId}', [WorkerController::class, 'destroy'])
        ->middleware('role:ADMIN,MEDICO_OCUPACIONAL,ENFERMERIA,RECEPCION');
    Route::get('/workers/{workerId}', [WorkerController::class, 'show'])
        ->middleware('role:ADMIN,MEDICO_OCUPACIONAL,ENFERMERIA,RECEPCION,AUDITOR');
    Route::get('/workers/{workerId}/clinical-history', [WorkerController::class, 'clinicalHistory'])
        ->middleware('role:ADMIN,MEDICO_OCUPACIONAL,ENFERMERIA,RECEPCION,AUDITOR');
    Route::put('/workers/{workerId}/clinical-history', [WorkerController::class, 'upsertClinicalHistory'])
        ->middleware('role:ADMIN,MEDICO_OCUPACIONAL,ENFERMERIA,RECEPCION');
    Route::get('/workers/{workerId}/history', [WorkerController::class, 'history'])
        ->middleware('role:ADMIN,MEDICO_OCUPACIONAL,ENFERMERIA,RECEPCION,AUDITOR');
    Route::get('/workers/{workerId}/evolutions', [ClinicalEvolutionController::class, 'index'])
        ->middleware('role:ADMIN,MEDICO_OCUPACIONAL,ENFERMERIA,AUDITOR');
    Route::post('/workers/{workerId}/evolutions', [ClinicalEvolutionController::class, 'store'])
        ->middleware('role:ADMIN,MEDICO_OCUPACIONAL,ENFERMERIA');
    Route::get('/workers/{workerId}/evolutions/{evolutionId}', [ClinicalEvolutionController::class, 'show'])
        ->middleware('role:ADMIN,MEDICO_OCUPACIONAL,ENFERMERIA,AUDITOR');
    Route::put('/workers/{workerId}/evolutions/{evolutionId}', [ClinicalEvolutionController::class, 'update'])
        ->middleware('role:ADMIN,MEDICO_OCUPACIONAL,ENFERMERIA');
    Route::delete('/workers/{workerId}/evolutions/{evolutionId}', [ClinicalEvolutionController::class, 'destroy'])
        ->middleware('role:ADMIN,MEDICO_OCUPACIONAL');

    // Worker aggregate attachments
    Route::get('/workers/{workerId}/attachments', [WorkerController::class, 'allAttachments'])
        ->middleware('role:ADMIN,MEDICO_OCUPACIONAL,ENFERMERIA,AUDITOR');

    // Medical Exam Orders
    Route::get('/workers/{workerId}/exam-orders', [ExamOrderController::class, 'index'])
        ->middleware('role:ADMIN,MEDICO_OCUPACIONAL,ENFERMERIA,AUDITOR');
    Route::post('/workers/{workerId}/exam-orders', [ExamOrderController::class, 'store'])
        ->middleware('role:ADMIN,MEDICO_OCUPACIONAL,ENFERMERIA');
    Route::get('/workers/{workerId}/exam-orders/{orderId}', [ExamOrderController::class, 'show'])
        ->middleware('role:ADMIN,MEDICO_OCUPACIONAL,ENFERMERIA,AUDITOR');
    Route::put('/workers/{workerId}/exam-orders/{orderId}', [ExamOrderController::class, 'update'])
        ->middleware('role:ADMIN,MEDICO_OCUPACIONAL,ENFERMERIA');
    Route::delete('/workers/{workerId}/exam-orders/{orderId}', [ExamOrderController::class, 'destroy'])
        ->middleware('role:ADMIN,MEDICO_OCUPACIONAL');
    Route::get('/workers/{workerId}/exam-orders/{orderId}/pdf', [ExamOrderController::class, 'generatePdf'])
        ->middleware('role:ADMIN,MEDICO_OCUPACIONAL,ENFERMERIA');

    // Vaccinations
    Route::get('/workers/{workerId}/vaccinations', [VaccinationController::class, 'index'])
        ->middleware('role:ADMIN,MEDICO_OCUPACIONAL,ENFERMERIA,AUDITOR');
    Route::post('/workers/{workerId}/vaccinations', [VaccinationController::class, 'store'])
        ->middleware('role:ADMIN,MEDICO_OCUPACIONAL,ENFERMERIA');
    Route::put('/workers/{workerId}/vaccinations/{vaccinationId}', [VaccinationController::class, 'update'])
        ->middleware('role:ADMIN,MEDICO_OCUPACIONAL,ENFERMERIA');
    Route::delete('/workers/{workerId}/vaccinations/{vaccinationId}', [VaccinationController::class, 'destroy'])
        ->middleware('role:ADMIN,MEDICO_OCUPACIONAL');

    // Occupational Accidents (AT-01)
    Route::get('/workers/{workerId}/accidents', [AccidentController::class, 'index'])
        ->middleware('role:ADMIN,MEDICO_OCUPACIONAL,ENFERMERIA,AUDITOR');
    Route::post('/workers/{workerId}/accidents', [AccidentController::class, 'store'])
        ->middleware('role:ADMIN,MEDICO_OCUPACIONAL,ENFERMERIA');
    Route::put('/workers/{workerId}/accidents/{accidentId}', [AccidentController::class, 'update'])
        ->middleware('role:ADMIN,MEDICO_OCUPACIONAL,ENFERMERIA');
    Route::delete('/workers/{workerId}/accidents/{accidentId}', [AccidentController::class, 'destroy'])
        ->middleware('role:ADMIN,MEDICO_OCUPACIONAL');
    Route::get('/workers/{workerId}/accidents/{accidentId}/pdf', [AccidentController::class, 'generatePdf'])
        ->middleware('role:ADMIN,MEDICO_OCUPACIONAL,ENFERMERIA');

    Route::post('/evaluations', [EvaluationController::class, 'store'])
        ->middleware('role:ADMIN,MEDICO_OCUPACIONAL');
    Route::get('/evaluations', [EvaluationController::class, 'index'])
        ->middleware('role:ADMIN,MEDICO_OCUPACIONAL,ENFERMERIA,AUDITOR');
    Route::get('/evaluations/attachments/{attachmentId}/download', [EvaluationController::class, 'downloadAttachment'])
        ->middleware('role:ADMIN,MEDICO_OCUPACIONAL,ENFERMERIA,AUDITOR');
    Route::get('/evaluations/{evaluationId}', [EvaluationController::class, 'show'])
        ->middleware('role:ADMIN,MEDICO_OCUPACIONAL,ENFERMERIA,AUDITOR');
    Route::get('/evaluations/{evaluationId}/prescription-pdf', [EvaluationController::class, 'prescriptionPdf'])
        ->middleware('role:ADMIN,MEDICO_OCUPACIONAL,ENFERMERIA');
    Route::post('/evaluations/{evaluationId}/attachments', [EvaluationController::class, 'uploadAttachment'])
        ->middleware('role:ADMIN,MEDICO_OCUPACIONAL,ENFERMERIA');
    Route::get('/evaluations/{evaluationId}/attachments', [EvaluationController::class, 'listAttachments'])
        ->middleware('role:ADMIN,MEDICO_OCUPACIONAL,ENFERMERIA,AUDITOR');

    Route::get('/certificates/expiring', [CertificateController::class, 'expiring'])
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
    Route::get('/reports/export-excel', [ReportController::class, 'exportExcel'])
        ->middleware('role:ADMIN,MEDICO_OCUPACIONAL,AUDITOR');
    Route::get('/reports/company/{companyId}', [ReportController::class, 'companyDetail'])
        ->middleware('role:ADMIN,MEDICO_OCUPACIONAL,ENFERMERIA,AUDITOR');

    // Settings / configuration
    Route::get('/settings', [SettingsController::class, 'index']);
    Route::put('/settings', [SettingsController::class, 'update'])
        ->middleware('role:ADMIN,MEDICO_OCUPACIONAL');
    Route::post('/settings/upload-image/{type}', [SettingsController::class, 'uploadImage'])
        ->middleware('role:ADMIN,MEDICO_OCUPACIONAL');
    Route::delete('/settings/image/{type}', [SettingsController::class, 'deleteImage'])
        ->middleware('role:ADMIN,MEDICO_OCUPACIONAL');

    // Agenda de citas
    Route::get('/appointments/catalog', [AppointmentController::class, 'catalog']);
    Route::get('/appointments/today', [AppointmentController::class, 'today']);
    Route::get('/appointments/upcoming', [AppointmentController::class, 'upcoming']);
    Route::get('/appointments', [AppointmentController::class, 'index']);
    Route::post('/appointments', [AppointmentController::class, 'store']);
    Route::get('/appointments/{id}', [AppointmentController::class, 'show']);
    Route::put('/appointments/{id}', [AppointmentController::class, 'update']);
    Route::delete('/appointments/{id}', [AppointmentController::class, 'destroy'])
        ->middleware('role:ADMIN,MEDICO_OCUPACIONAL');
});
