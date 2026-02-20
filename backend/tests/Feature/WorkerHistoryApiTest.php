<?php

namespace Tests\Feature;

use App\Models\MedicalCertificate;
use App\Models\OccupationalEvaluation;
use App\Models\Role;
use App\Models\User;
use App\Models\Worker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class WorkerHistoryApiTest extends TestCase
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

    private function createWorker(string $seed = '01'): Worker
    {
        return Worker::query()->create([
            'id' => (string) Str::uuid(),
            'history_number' => "HC-010101-{$seed}",
            'file_number' => "AR-010101-{$seed}",
            'document_type' => 'CEDULA',
            'document_number' => "17234567{$seed}",
            'first_name' => 'Ana',
            'last_name' => 'Lopez',
            'birth_date' => '1992-01-10',
            'sex' => 'F',
        ]);
    }

    public function test_can_update_worker(): void
    {
        $this->authenticateAsAdmin();
        $worker = $this->createWorker();

        $response = $this->putJson("/api/workers/{$worker->id}", [
            'first_name' => 'Ana Maria',
            'last_name' => 'Lopez Torres',
            'phone' => '0999988877',
            'blood_type' => 'O+',
            'laterality' => 'DIESTRO',
        ]);

        $response
            ->assertOk()
            ->assertJsonPath('ok', true)
            ->assertJsonPath('data.first_name', 'Ana Maria')
            ->assertJsonPath('data.last_name', 'Lopez Torres');

        $this->assertDatabaseHas('workers', [
            'id' => $worker->id,
            'first_name' => 'Ana Maria',
            'phone' => '0999988877',
        ]);
    }

    public function test_can_delete_worker(): void
    {
        $this->authenticateAsAdmin();
        $worker = $this->createWorker();

        $response = $this->deleteJson("/api/workers/{$worker->id}");

        $response
            ->assertOk()
            ->assertJsonPath('ok', true);

        $this->assertDatabaseMissing('workers', [
            'id' => $worker->id,
        ]);
    }

    public function test_can_get_worker_history(): void
    {
        $user = $this->authenticateAsAdmin();
        $worker = $this->createWorker();

        $evaluation = OccupationalEvaluation::query()->create([
            'id' => (string) Str::uuid(),
            'worker_id' => $worker->id,
            'evaluator_user_id' => $user->id,
            'evaluation_type' => 'INGRESO',
            'attention_date' => now()->toDateString(),
            'consultation_reason' => 'Evaluacion inicial',
            'vital_signs' => ['pa' => '120/80'],
            'medical_aptitude' => 'APTO',
            'professional_name' => 'Dra. Test',
            'professional_code' => 'MED-0001',
        ]);

        $certificate = MedicalCertificate::query()->create([
            'id' => (string) Str::uuid(),
            'certificate_code' => 'CERT-TEST-100',
            'evaluation_id' => $evaluation->id,
            'worker_id' => $worker->id,
            'issue_date' => now()->toDateString(),
            'medical_aptitude' => 'APTO',
            'professional_name' => 'Dra. Test',
            'professional_code' => 'MED-0001',
            'created_by' => $user->id,
        ]);

        $response = $this->getJson("/api/workers/{$worker->id}/history");

        $response
            ->assertOk()
            ->assertJsonPath('ok', true)
            ->assertJsonPath('data.worker.id', $worker->id)
            ->assertJsonPath('data.clinical_history', null)
            ->assertJsonCount(2, 'data.clinical_timeline')
            ->assertJsonCount(1, 'data.evaluations')
            ->assertJsonCount(1, 'data.certificates')
            ->assertJsonPath('data.evaluations.0.id', $evaluation->id)
            ->assertJsonPath('data.certificates.0.id', $certificate->id);
    }

    public function test_can_upsert_worker_clinical_history(): void
    {
        $this->authenticateAsAdmin();
        $worker = $this->createWorker();

        $response = $this->putJson("/api/workers/{$worker->id}/clinical-history", [
            'personal_background' => 'Antecedentes personales relevantes',
            'family_background' => 'Padre con hipertension',
            'allergies' => 'Penicilina',
            'current_medication' => 'Losartan',
            'pathological_history' => 'Hipertension arterial',
            'surgical_history' => 'Apendicectomia 2018',
            'occupational_history' => 'Operario en planta industrial',
            'lifestyle_notes' => 'Actividad fisica 3 veces por semana',
            'longitudinal_notes' => 'Control trimestral por medicina ocupacional',
        ]);

        $response
            ->assertOk()
            ->assertJsonPath('ok', true)
            ->assertJsonPath('data.worker_id', $worker->id)
            ->assertJsonPath('data.allergies', 'Penicilina');

        $this->assertDatabaseHas('worker_clinical_histories', [
            'worker_id' => $worker->id,
            'allergies' => 'Penicilina',
        ]);

        $historyResponse = $this->getJson("/api/workers/{$worker->id}/history");

        $historyResponse
            ->assertOk()
            ->assertJsonPath('ok', true)
            ->assertJsonPath('data.clinical_history.worker_id', $worker->id)
            ->assertJsonPath('data.clinical_history.pathological_history', 'Hipertension arterial');

        $clinicalResponse = $this->getJson("/api/workers/{$worker->id}/clinical-history");

        $clinicalResponse
            ->assertOk()
            ->assertJsonPath('ok', true)
            ->assertJsonPath('data.worker_id', $worker->id)
            ->assertJsonPath('data.longitudinal_notes', 'Control trimestral por medicina ocupacional');
    }

    public function test_workers_index_returns_pagination_meta(): void
    {
        $this->authenticateAsAdmin();

        foreach (['11', '12', '13', '14', '15'] as $seed) {
            $this->createWorker($seed);
        }

        $response = $this->getJson('/api/workers?limit=2&page=2');

        $response
            ->assertOk()
            ->assertJsonPath('ok', true)
            ->assertJsonPath('meta.page', 2)
            ->assertJsonPath('meta.per_page', 2)
            ->assertJsonPath('meta.total', 5)
            ->assertJsonPath('meta.total_pages', 3)
            ->assertJsonPath('meta.has_next', true)
            ->assertJsonPath('meta.has_prev', true)
            ->assertJsonCount(2, 'data');
    }
}
