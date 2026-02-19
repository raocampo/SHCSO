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
}
