<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthSetupStatusApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_setup_status_requires_bootstrap_when_no_admin_exists(): void
    {
        $response = $this->getJson('/api/auth/setup-status');

        $response
            ->assertOk()
            ->assertJsonPath('ok', true)
            ->assertJsonPath('data.admin_exists', false)
            ->assertJsonPath('data.bootstrap_required', true)
            ->assertJsonPath('data.users_count', 0);
    }

    public function test_setup_status_changes_after_registering_first_admin(): void
    {
        $createResponse = $this->postJson('/api/auth/register-admin', [
            'full_name' => 'Primer Admin',
            'email' => 'admin@shcso.local',
            'password' => 'PasswordSeguro123',
        ]);

        $createResponse
            ->assertCreated()
            ->assertJsonPath('ok', true);

        $statusResponse = $this->getJson('/api/auth/setup-status');

        $statusResponse
            ->assertOk()
            ->assertJsonPath('ok', true)
            ->assertJsonPath('data.admin_exists', true)
            ->assertJsonPath('data.bootstrap_required', false)
            ->assertJsonPath('data.users_count', 1);
    }
}

