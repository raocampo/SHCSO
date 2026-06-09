<?php

namespace Tests\Feature;

use App\Models\CiiuActivity;
use App\Models\JobPosition;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CatalogClassificationApiTest extends TestCase
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

    public function test_company_uses_ciiu_activity_catalog(): void
    {
        $this->authenticateAsAdmin();

        CiiuActivity::query()->create([
            'code' => 'Q8621.01',
            'description' => 'Actividades de consulta medica general',
            'level' => 6,
        ]);

        $catalogResponse = $this->getJson('/api/catalog/ciiu-activities?q=8621&level=6');

        $catalogResponse
            ->assertOk()
            ->assertJsonPath('ok', true)
            ->assertJsonPath('data.0.code', 'Q8621.01');

        $createResponse = $this->postJson('/api/catalog/companies', [
            'ruc' => '1799999999001',
            'ciiu' => 'Q8621.01',
            'business_name' => 'Clinica Laboral Demo',
            'work_center' => 'Matriz',
            'address' => 'Quito',
        ]);

        $createResponse
            ->assertCreated()
            ->assertJsonPath('ok', true)
            ->assertJsonPath('data.ciiu', 'Q8621.01')
            ->assertJsonPath('data.ciiu_activity.description', 'Actividades de consulta medica general');
    }

    public function test_worker_job_positions_use_ciuo_catalog_not_ciiu(): void
    {
        $this->authenticateAsAdmin();

        JobPosition::query()->create([
            'ciuo_code' => '0110.01.01',
            'ciuo_level' => 8,
            'name' => 'General del Ejercito',
            'description' => 'General del Ejercito (Fuerza Terrestre)',
        ]);

        JobPosition::query()->create([
            'ciuo_code' => 'A0111.11',
            'ciiu_code' => 'A0111.11',
            'ciiu_level' => 6,
            'name' => 'Cultivo de trigo',
            'description' => 'Actividad economica CIIU heredada',
        ]);

        $response = $this->getJson('/api/catalog/job-positions?q=general&level=8&limit=10');

        $response
            ->assertOk()
            ->assertJsonPath('ok', true)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.ciuo_code', '0110.01.01')
            ->assertJsonPath('data.0.ciuo_level', 8)
            ->assertJsonPath('data.0.ciiu_code', null);
    }
}
