<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use App\Models\Worker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CertificateFlowApiTest extends TestCase
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

    private function createWorker(string $seed): Worker
    {
        return Worker::query()->create([
            'id' => (string) Str::uuid(),
            'history_number' => "HC-020202-{$seed}",
            'file_number' => "AR-020202-{$seed}",
            'document_type' => 'CEDULA',
            'document_number' => "09234567{$seed}",
            'first_name' => 'Carlos',
            'last_name' => 'Ramirez',
            'birth_date' => '1990-02-10',
            'sex' => 'M',
        ]);
    }

    public function test_can_complete_evaluation_certificate_pdf_flow(): void
    {
        Storage::fake('public');
        $this->authenticateAsAdmin();
        $worker = $this->createWorker('81');

        $evaluationResponse = $this->postJson('/api/evaluations', [
            'worker_id' => $worker->id,
            'evaluation_type' => 'INGRESO',
            'consultation_reason' => 'Evaluacion integral de ingreso',
            'medical_aptitude' => 'APTO',
            'professional_name' => 'Dra. Maria Lopez',
            'professional_code' => 'MED-12345',
            'vital_signs' => ['pa' => '120/80', 'fc' => 70],
            'diagnoses' => [
                [
                    'code' => 'Z00.0',
                    'diagnosis_type' => 'DEF',
                    'description' => 'Examen general',
                    'notes' => 'Sin novedad',
                ],
            ],
        ]);

        $evaluationResponse
            ->assertCreated()
            ->assertJsonPath('ok', true);

        $evaluationId = $evaluationResponse->json('data.id');
        $this->assertNotEmpty($evaluationId);

        $certificateResponse = $this->postJson("/api/certificates/from-evaluation/{$evaluationId}", [
            'observations' => 'Apto para labores.',
            'recommendations' => 'Control anual.',
        ]);

        $certificateResponse
            ->assertCreated()
            ->assertJsonPath('ok', true);

        $certificateId = $certificateResponse->json('data.id');
        $this->assertNotEmpty($certificateId);

        $generateResponse = $this->postJson("/api/certificates/{$certificateId}/generate-pdf");

        $generateResponse
            ->assertOk()
            ->assertJsonPath('ok', true);

        $pdfPath = $generateResponse->json('data.pdf_path');
        $this->assertNotEmpty($pdfPath);
        Storage::disk('public')->assertExists($pdfPath);

        $showResponse = $this->getJson("/api/certificates/{$certificateId}");

        $showResponse
            ->assertOk()
            ->assertJsonPath('ok', true)
            ->assertJsonPath('data.id', $certificateId);

        $this->assertNotNull($showResponse->json('data.pdf_url'));

        $downloadResponse = $this->get("/api/certificates/{$certificateId}/download-pdf");

        $downloadResponse->assertOk();
        $this->assertStringContainsString(
            '.pdf',
            (string) $downloadResponse->headers->get('content-disposition')
        );
    }
}
