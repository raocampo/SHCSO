<?php

namespace Tests\Feature;

use App\Models\DiagnosisCatalog;
use App\Models\Role;
use App\Models\User;
use App\Models\Worker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class MedicalConsultationApiTest extends TestCase
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
            'history_number' => "HC-120000-{$seed}",
            'file_number' => "AR-120000-{$seed}",
            'document_type' => 'CEDULA',
            'document_number' => "17123456{$seed}",
            'first_name' => 'Marco',
            'last_name' => 'Suarez',
            'birth_date' => '1991-06-15',
            'sex' => 'M',
        ]);
    }

    public function test_can_create_medical_consultation_with_prescriptions(): void
    {
        $user = $this->authenticateAsAdmin();
        $worker = $this->createWorker();

        $payload = [
            'worker_id' => $worker->id,
            'evaluation_type' => 'PERIODICO',
            'attention_date' => now()->toDateString(),
            'consultation_reason' => 'Paciente refiere cefalea y fatiga en jornada laboral.',
            'current_problem' => 'Sintomas de 3 dias de evolucion.',
            'vital_signs' => [
                'blood_pressure' => '120/80',
                'temperature_c' => 36.6,
                'heart_rate' => 72,
                'respiratory_rate' => 16,
                'weight_kg' => 70,
                'height_cm' => 170,
            ],
            'physical_exam' => [
                'soap_o' => 'Paciente consciente, sin signos de dificultad respiratoria.',
            ],
            'medical_aptitude' => 'APTO_OBSERVACION',
            'recommendations' => 'Control en 30 dias y medidas ergonomicas.',
            'professional_name' => 'Dr. Miguel Rojas',
            'professional_code' => 'MED-9090',
            'diagnoses' => [
                ['code' => 'R51', 'description' => 'Cefalea', 'diagnosis_type' => 'DEF', 'notes' => 'Dolor frontal moderado'],
            ],
            'prescriptions' => [
                [
                    'medication' => 'Paracetamol',
                    'dosage' => '500 mg',
                    'frequency' => 'Cada 8 horas',
                    'duration' => '5 dias',
                    'indications' => 'Tomar despues de alimentos.',
                ],
                [
                    'medication' => 'Ibuprofeno',
                    'dosage' => '400 mg',
                    'frequency' => 'Cada 12 horas',
                    'duration' => '3 dias',
                    'indications' => 'No usar con estomago vacio.',
                ],
            ],
        ];

        $createResponse = $this->postJson('/api/evaluations', $payload);

        $createResponse
            ->assertCreated()
            ->assertJsonPath('ok', true);

        $evaluationId = (string) $createResponse->json('data.id');
        $this->assertNotSame('', $evaluationId);

        $this->assertDatabaseHas('occupational_evaluations', [
            'id' => $evaluationId,
            'worker_id' => $worker->id,
            'evaluator_user_id' => $user->id,
            'medical_aptitude' => 'APTO_OBSERVACION',
        ]);

        $this->assertDatabaseHas('evaluation_prescriptions', [
            'evaluation_id' => $evaluationId,
            'medication' => 'Paracetamol',
            'dosage' => '500 mg',
        ]);

        $showResponse = $this->getJson("/api/evaluations/{$evaluationId}");

        $showResponse
            ->assertOk()
            ->assertJsonPath('ok', true)
            ->assertJsonCount(2, 'data.prescriptions')
            ->assertJsonCount(1, 'data.diagnoses')
            ->assertJsonFragment(['medication' => 'Ibuprofeno']);
    }

    public function test_can_search_diagnosis_catalog(): void
    {
        $this->authenticateAsAdmin();

        DiagnosisCatalog::query()->updateOrCreate(
            ['code' => 'M54.5'],
            ['description' => 'Lumbalgia']
        );
        DiagnosisCatalog::query()->updateOrCreate(
            ['code' => 'J06.9'],
            ['description' => 'Infeccion aguda de vias respiratorias superiores']
        );

        $response = $this->getJson('/api/catalog/diagnoses?q=lumb&limit=5');

        $response
            ->assertOk()
            ->assertJsonPath('ok', true)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.code', 'M54.5');
    }
}

