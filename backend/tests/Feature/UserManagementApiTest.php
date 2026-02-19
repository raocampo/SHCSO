<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UserManagementApiTest extends TestCase
{
    use RefreshDatabase;

    private function authenticate(string $roleName): User
    {
        $user = User::factory()->create();
        $role = Role::query()->firstOrCreate(['name' => $roleName]);
        $user->roles()->syncWithoutDetaching([$role->id]);
        Sanctum::actingAs($user, ['*']);

        return $user;
    }

    public function test_admin_can_create_and_update_user(): void
    {
        $this->authenticate('ADMIN');
        Role::query()->firstOrCreate(['name' => 'ENFERMERIA']);

        $createResponse = $this->postJson('/api/users', [
            'full_name' => 'Maria Delgado',
            'email' => 'maria@shcso.local',
            'password' => 'PasswordSeguro123',
            'role_name' => 'ENFERMERIA',
            'is_active' => true,
        ]);

        $createResponse
            ->assertCreated()
            ->assertJsonPath('ok', true)
            ->assertJsonPath('data.email', 'maria@shcso.local')
            ->assertJsonPath('data.roles.0', 'ENFERMERIA');

        $userId = $createResponse->json('data.id');

        $updateResponse = $this->putJson("/api/users/{$userId}", [
            'full_name' => 'Maria Fernanda Delgado',
            'is_active' => false,
        ]);

        $updateResponse
            ->assertOk()
            ->assertJsonPath('ok', true)
            ->assertJsonPath('data.full_name', 'Maria Fernanda Delgado')
            ->assertJsonPath('data.is_active', false);
    }

    public function test_non_admin_cannot_manage_users(): void
    {
        $this->authenticate('ENFERMERIA');

        $this->getJson('/api/users')
            ->assertForbidden()
            ->assertJsonPath('ok', false);

        $this->postJson('/api/users', [
            'full_name' => 'Invitado',
            'email' => 'invitado@shcso.local',
            'password' => 'PasswordSeguro123',
            'role_name' => 'ENFERMERIA',
        ])->assertForbidden();
    }

    public function test_users_index_returns_pagination_meta(): void
    {
        $this->authenticate('ADMIN');
        $role = Role::query()->firstOrCreate(['name' => 'ENFERMERIA']);

        for ($i = 1; $i <= 5; $i++) {
            $user = User::factory()->create([
                'email' => "user{$i}@shcso.local",
            ]);
            $user->roles()->syncWithoutDetaching([$role->id]);
        }

        $response = $this->getJson('/api/users?limit=2&page=2');

        $response
            ->assertOk()
            ->assertJsonPath('ok', true)
            ->assertJsonPath('meta.page', 2)
            ->assertJsonPath('meta.per_page', 2)
            ->assertJsonPath('meta.total', 6)
            ->assertJsonPath('meta.total_pages', 3)
            ->assertJsonPath('meta.has_next', true)
            ->assertJsonPath('meta.has_prev', true)
            ->assertJsonCount(2, 'data');
    }
}
