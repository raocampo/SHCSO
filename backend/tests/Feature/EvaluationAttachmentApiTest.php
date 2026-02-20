<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use App\Models\Worker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class EvaluationAttachmentApiTest extends TestCase
{
    use RefreshDatabase;

    private function authenticateAsAdmin(): User
    {
        $user = User::factory()->create();
        $role = Role::query()->firstOrCreate(['name' => 'ADMIN']);
        $user->roles()->syncWithoutDetaching([$role->id]);
        Sanctum::actingAs($user, ['*']);

        return $user;
    }

    private function createWorker(string $seed = '91'): Worker
    {
        return Worker::query()->create([
            'id' => (string) Str::uuid(),
            'history_number' => "HC-030303-{$seed}",
            'file_number' => "AR-030303-{$seed}",
            'document_type' => 'CEDULA',
            'document_number' => "09345678{$seed}",
            'first_name' => 'Andrea',
            'last_name' => 'Mena',
            'birth_date' => '1991-03-15',
            'sex' => 'F',
        ]);
    }

    private function createEvaluation(string $workerId): string
    {
        $response = $this->postJson('/api/evaluations', [
            'worker_id' => $workerId,
            'evaluation_type' => 'INGRESO',
            'consultation_reason' => 'Evaluacion ocupacional para adjuntos',
            'medical_aptitude' => 'APTO',
            'professional_name' => 'Dra. Test',
            'professional_code' => 'MED-0099',
            'diagnoses' => [
                [
                    'code' => 'Z00.0',
                    'diagnosis_type' => 'DEF',
                    'description' => 'Control general',
                ],
            ],
        ]);

        $response->assertCreated();

        return (string) $response->json('data.id');
    }

    public function test_can_upload_dicom_attachment_with_metadata(): void
    {
        Storage::fake('public');
        $this->authenticateAsAdmin();

        $worker = $this->createWorker('92');
        $evaluationId = $this->createEvaluation($worker->id);

        $file = UploadedFile::fake()->create(
            'rx_torax.dcm',
            2048,
            'application/dicom'
        );

        $response = $this->post(
            "/api/evaluations/{$evaluationId}/attachments",
            [
                'attachment_type' => 'DICOM',
                'exam_date' => '2026-02-20',
                'notes' => 'Radiografia de torax AP',
                'file' => $file,
            ],
            ['Accept' => 'application/json']
        );

        $response
            ->assertCreated()
            ->assertJsonPath('ok', true)
            ->assertJsonPath('data.attachment_type', 'DICOM')
            ->assertJsonPath('data.original_extension', 'dcm')
            ->assertJsonPath('data.notes', 'Radiografia de torax AP');

        $path = $response->json('data.file_path');
        $this->assertNotEmpty($path);
        Storage::disk('public')->assertExists($path);

        $this->assertDatabaseHas('evaluation_attachments', [
            'evaluation_id' => $evaluationId,
            'attachment_type' => 'DICOM',
            'original_extension' => 'dcm',
        ]);
    }

    public function test_can_download_uploaded_attachment(): void
    {
        Storage::fake('public');
        $this->authenticateAsAdmin();

        $worker = $this->createWorker('93');
        $evaluationId = $this->createEvaluation($worker->id);

        $uploadResponse = $this->post(
            "/api/evaluations/{$evaluationId}/attachments",
            [
                'attachment_type' => 'LAB_EXAM',
                'notes' => 'Biometria hematica',
                'file' => UploadedFile::fake()->create('biometria.pdf', 800, 'application/pdf'),
            ],
            ['Accept' => 'application/json']
        );

        $uploadResponse->assertCreated();
        $attachmentId = (string) $uploadResponse->json('data.id');

        $downloadResponse = $this->get("/api/evaluations/attachments/{$attachmentId}/download");

        $downloadResponse->assertOk();
        $this->assertStringContainsString(
            'biometria.pdf',
            (string) $downloadResponse->headers->get('content-disposition')
        );
    }
}
