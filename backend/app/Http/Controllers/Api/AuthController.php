<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use App\Services\AuditLogger;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    private function passwordResetUiUrl(string $email, string $token): string
    {
        $baseUrl = rtrim((string) config('app.url', 'http://127.0.0.1:8000'), '/');
        $query = http_build_query([
            'email' => $email,
            'reset_token' => $token,
        ]);

        return "{$baseUrl}/sistema?{$query}";
    }

    private function passwordRecoveryMessage(
        string $fullName,
        string $email,
        string $token,
        int $expireMinutes,
        string $resetUrl
    ): string
    {
        return implode(PHP_EOL, [
            "Hola {$fullName},",
            '',
            'Recibimos una solicitud para recuperar tu contrasena en SHCSO.',
            "Correo: {$email}",
            "Token de recuperacion: {$token}",
            "Enlace directo de recuperacion: {$resetUrl}",
            "Vigencia: {$expireMinutes} minutos",
            '',
            'Ingresa al modulo de acceso de SHCSO y usa la opcion "Ya tengo token" para establecer una nueva contrasena.',
            '',
            'Si no solicitaste este cambio, ignora este mensaje.',
        ]);
    }

    public function setupStatus(): JsonResponse
    {
        $adminExists = User::query()
            ->whereHas('roles', fn ($query) => $query->where('name', 'ADMIN'))
            ->exists();

        return response()->json([
            'ok' => true,
            'data' => [
                'admin_exists' => $adminExists,
                'bootstrap_required' => !$adminExists,
                'users_count' => User::query()->count(),
            ],
        ]);
    }

    public function registerAdmin(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'full_name' => ['required', 'string', 'min:3', 'max:120'],
            'email' => ['required', 'email', 'max:180', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        $adminExists = User::query()
            ->whereHas('roles', fn ($query) => $query->where('name', 'ADMIN'))
            ->exists();

        if ($adminExists) {
            return response()->json([
                'ok' => false,
                'message' => 'Ya existe un usuario ADMIN.',
            ], 409);
        }

        $user = User::create([
            'full_name' => $validated['full_name'],
            'email' => strtolower($validated['email']),
            'password' => Hash::make($validated['password']),
            'is_active' => true,
        ]);

        $adminRoleId = Role::query()->firstOrCreate(['name' => 'ADMIN'])->id;
        $user->roles()->attach($adminRoleId);

        AuditLogger::log($user, 'CREATE_ADMIN', 'user', $user->id, [
            'email' => $user->email,
        ]);

        return response()->json([
            'ok' => true,
            'message' => 'Administrador creado correctamente.',
            'data' => $user->only(['id', 'full_name', 'email', 'created_at']),
        ], 201);
    }

    public function login(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        /** @var User|null $user */
        $user = User::query()
            ->with('roles:id,name')
            ->where('email', strtolower($validated['email']))
            ->first();

        if (!$user || !Hash::check($validated['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Credenciales invalidas.'],
            ]);
        }

        if (!$user->is_active) {
            return response()->json([
                'ok' => false,
                'message' => 'Usuario inactivo.',
            ], 403);
        }

        $token = $user->createToken('api-token', $user->roles->pluck('name')->all())->plainTextToken;

        AuditLogger::log($user, 'LOGIN', 'auth', $user->id);

        return response()->json([
            'ok' => true,
            'data' => [
                'token' => $token,
                'user' => [
                    'id' => $user->id,
                    'full_name' => $user->full_name,
                    'email' => $user->email,
                    'roles' => $user->roles->pluck('name')->values(),
                ],
            ],
        ]);
    }

    public function forgotPassword(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email', 'max:180'],
        ]);

        $email = strtolower(trim((string) $validated['email']));
        $genericMessage = 'Si el correo existe y esta activo, se envio un token de recuperacion.';

        /** @var User|null $user */
        $user = User::query()
            ->where('email', $email)
            ->first();

        if (!$user || !$user->is_active) {
            return response()->json([
                'ok' => true,
                'message' => $genericMessage,
            ]);
        }

        $token = Password::broker()->createToken($user);
        $expireMinutes = (int) config('auth.passwords.users.expire', 60);
        $resetUrl = $this->passwordResetUiUrl($user->email, $token);

        Mail::raw(
            $this->passwordRecoveryMessage($user->full_name, $user->email, $token, $expireMinutes, $resetUrl),
            function ($message) use ($user) {
                $message
                    ->to($user->email, $user->full_name)
                    ->subject('SHCSO - Recuperacion de contrasena');
            }
        );

        AuditLogger::log(
            $user,
            'REQUEST_PASSWORD_RESET',
            'user',
            $user->id,
            ['email' => $user->email]
        );

        $response = [
            'ok' => true,
            'message' => $genericMessage,
        ];

        if ((bool) config('app.debug')) {
            $response['data'] = [
                'email' => $user->email,
                'reset_token' => $token,
                'reset_url' => $resetUrl,
                'expires_in_minutes' => $expireMinutes,
            ];
        }

        return response()->json($response);
    }

    public function resetPassword(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email', 'max:180'],
            'token' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $status = Password::broker()->reset(
            [
                'email' => strtolower(trim((string) $validated['email'])),
                'token' => $validated['token'],
                'password' => $validated['password'],
                'password_confirmation' => $validated['password_confirmation'] ?? '',
            ],
            function (User $user, string $password): void {
                $user->forceFill([
                    'password' => $password,
                    'remember_token' => Str::random(60),
                ])->save();

                $user->tokens()->delete();
                event(new PasswordReset($user));

                AuditLogger::log(
                    $user,
                    'RESET_PASSWORD',
                    'user',
                    $user->id
                );
            }
        );

        if ($status !== Password::PASSWORD_RESET) {
            return response()->json([
                'ok' => false,
                'message' => __($status),
            ], 422);
        }

        return response()->json([
            'ok' => true,
            'message' => 'Contrasena actualizada correctamente.',
        ]);
    }

    public function me(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user()->load('roles:id,name');

        return response()->json([
            'ok' => true,
            'data' => [
                'id' => $user->id,
                'full_name' => $user->full_name,
                'email' => $user->email,
                'roles' => $user->roles->pluck('name')->values(),
            ],
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();
        $user->currentAccessToken()?->delete();

        AuditLogger::log($user, 'LOGOUT', 'auth', $user->id);

        return response()->json([
            'ok' => true,
            'message' => 'Sesion cerrada.',
        ]);
    }
}
