<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;

class AuthPasswordRecoveryApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_request_password_reset_token_for_existing_user(): void
    {
        $user = User::factory()->create([
            'email' => 'recuperacion@shcso.local',
            'is_active' => true,
        ]);

        $response = $this->postJson('/api/auth/forgot-password', [
            'email' => $user->email,
        ]);

        $response
            ->assertOk()
            ->assertJsonPath('ok', true);

        $this->assertDatabaseHas('password_reset_tokens', [
            'email' => $user->email,
        ]);
    }

    public function test_forgot_password_returns_generic_response_for_unknown_email(): void
    {
        $response = $this->postJson('/api/auth/forgot-password', [
            'email' => 'no-existe@shcso.local',
        ]);

        $response
            ->assertOk()
            ->assertJsonPath('ok', true);

        $this->assertSame(0, DB::table('password_reset_tokens')->count());
    }

    public function test_can_reset_password_with_valid_token(): void
    {
        $user = User::factory()->create([
            'email' => 'reset@shcso.local',
            'password' => 'PasswordViejo123',
        ]);
        $user->createToken('api-old-token');
        $token = Password::broker()->createToken($user);

        $response = $this->postJson('/api/auth/reset-password', [
            'email' => $user->email,
            'token' => $token,
            'password' => 'PasswordNuevo123',
            'password_confirmation' => 'PasswordNuevo123',
        ]);

        $response
            ->assertOk()
            ->assertJsonPath('ok', true);

        $user->refresh();
        $this->assertTrue(Hash::check('PasswordNuevo123', $user->password));
        $this->assertSame(0, $user->tokens()->count());
    }

    public function test_reset_password_fails_with_invalid_token(): void
    {
        $user = User::factory()->create([
            'email' => 'invalid-token@shcso.local',
        ]);

        $response = $this->postJson('/api/auth/reset-password', [
            'email' => $user->email,
            'token' => 'token-invalido',
            'password' => 'PasswordNuevo123',
            'password_confirmation' => 'PasswordNuevo123',
        ]);

        $response
            ->assertStatus(422)
            ->assertJsonPath('ok', false);
    }
}
