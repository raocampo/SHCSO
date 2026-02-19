<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use App\Services\AuditLogger;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
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
