<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use App\Services\AuditLogger;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    private function temporaryPassword(): string
    {
        return sprintf(
            'Tmp%s%s',
            random_int(1000, 9999),
            strtoupper(Str::random(4))
        );
    }

    public function index(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'q' => ['nullable', 'string', 'max:120'],
            'is_active' => ['nullable', 'boolean'],
            'page' => ['nullable', 'integer', 'min:1'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:200'],
            'limit' => ['nullable', 'integer', 'min:1', 'max:200'],
        ]);

        $query = trim((string) ($validated['q'] ?? ''));
        $perPage = (int) ($validated['per_page'] ?? $validated['limit'] ?? 50);
        $page = (int) ($validated['page'] ?? 1);

        $usersQuery = User::query()
            ->with('roles:id,name')
            ->orderByDesc('created_at');

        if ($query !== '') {
            $usersQuery->where(function ($builder) use ($query) {
                $builder
                    ->where('full_name', 'like', '%' . $query . '%')
                    ->orWhere('email', 'like', '%' . $query . '%');
            });
        }

        if (array_key_exists('is_active', $validated)) {
            $usersQuery->where('is_active', (bool) $validated['is_active']);
        }

        $total = (clone $usersQuery)->count();
        $users = $usersQuery
            ->forPage($page, $perPage)
            ->get();
        $totalPages = max(1, (int) ceil($total / $perPage));

        return response()->json([
            'ok' => true,
            'data' => $users->map(fn (User $user) => $this->serializeUser($user)),
            'meta' => [
                'page' => $page,
                'per_page' => $perPage,
                'total' => $total,
                'total_pages' => $totalPages,
                'has_next' => $page < $totalPages,
                'has_prev' => $page > 1,
            ],
        ]);
    }

    public function roles(): JsonResponse
    {
        return response()->json([
            'ok' => true,
            'data' => Role::query()
                ->orderBy('name')
                ->get(['id', 'name']),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'full_name' => ['required', 'string', 'min:3', 'max:120'],
            'email' => ['required', 'email', 'max:180', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'role_name' => ['required', 'string', Rule::exists('roles', 'name')],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $role = Role::query()->where('name', $validated['role_name'])->firstOrFail();

        $user = User::query()->create([
            'full_name' => $validated['full_name'],
            'email' => strtolower($validated['email']),
            'password' => $validated['password'],
            'is_active' => $validated['is_active'] ?? true,
        ]);

        $user->roles()->sync([$role->id]);
        $user->load('roles:id,name');

        AuditLogger::log(
            $request->user(),
            'CREATE_USER',
            'user',
            $user->id,
            ['email' => $user->email, 'role_name' => $role->name]
        );

        return response()->json([
            'ok' => true,
            'data' => $this->serializeUser($user),
        ], 201);
    }

    public function updateSelf(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user()->load('roles:id,name');

        $validated = $request->validate([
            'full_name'         => ['sometimes', 'string', 'min:3', 'max:120'],
            'professional_code' => ['sometimes', 'nullable', 'string', 'max:60'],
            'password'          => ['sometimes', 'nullable', 'string', 'min:8'],
        ]);

        if (array_key_exists('full_name', $validated)) {
            $user->full_name = $validated['full_name'];
        }
        if (array_key_exists('professional_code', $validated)) {
            $user->professional_code = $validated['professional_code'];
        }
        if (!empty($validated['password'])) {
            $user->password = $validated['password'];
        }
        $user->save();

        AuditLogger::log($request->user(), 'UPDATE_SELF_PROFILE', 'user', $user->id, []);

        return response()->json([
            'ok' => true,
            'data' => $this->serializeUser($user),
        ]);
    }

    public function update(Request $request, string $userId): JsonResponse
    {
        $user = User::query()->with('roles:id,name')->findOrFail($userId);

        $validated = $request->validate([
            'full_name' => ['sometimes', 'string', 'min:3', 'max:120'],
            'professional_code' => ['sometimes', 'nullable', 'string', 'max:60'],
            'email' => ['sometimes', 'email', 'max:180', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => ['sometimes', 'nullable', 'string', 'min:8'],
            'role_name' => ['sometimes', 'string', Rule::exists('roles', 'name')],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        if (
            array_key_exists('is_active', $validated)
            && !$validated['is_active']
            && $request->user()?->id === $user->id
        ) {
            return response()->json([
                'ok' => false,
                'message' => 'No puedes desactivar tu propio usuario.',
            ], 422);
        }

        if (array_key_exists('full_name', $validated)) {
            $user->full_name = $validated['full_name'];
        }
        if (array_key_exists('professional_code', $validated)) {
            $user->professional_code = $validated['professional_code'];
        }
        if (array_key_exists('email', $validated)) {
            $user->email = strtolower($validated['email']);
        }
        if (array_key_exists('is_active', $validated)) {
            $user->is_active = $validated['is_active'];
        }
        if (!empty($validated['password'])) {
            $user->password = $validated['password'];
        }
        $user->save();

        if (!empty($validated['role_name'])) {
            $role = Role::query()->where('name', $validated['role_name'])->firstOrFail();
            $user->roles()->sync([$role->id]);
        }

        $user->load('roles:id,name');

        AuditLogger::log(
            $request->user(),
            'UPDATE_USER',
            'user',
            $user->id,
            ['updated_fields' => array_keys($validated)]
        );

        return response()->json([
            'ok' => true,
            'data' => $this->serializeUser($user),
        ]);
    }

    public function updateStatus(Request $request, string $userId): JsonResponse
    {
        $validated = $request->validate([
            'is_active' => ['required', 'boolean'],
        ]);

        $user = User::query()->with('roles:id,name')->findOrFail($userId);
        $isActive = (bool) $validated['is_active'];

        if (!$isActive && $request->user()?->id === $user->id) {
            return response()->json([
                'ok' => false,
                'message' => 'No puedes desactivar tu propio usuario.',
            ], 422);
        }

        $user->is_active = $isActive;
        $user->save();

        AuditLogger::log(
            $request->user(),
            'UPDATE_USER_STATUS',
            'user',
            $user->id,
            ['is_active' => $isActive]
        );

        return response()->json([
            'ok' => true,
            'data' => $this->serializeUser($user),
        ]);
    }

    public function resetPassword(Request $request, string $userId): JsonResponse
    {
        $validated = $request->validate([
            'new_password' => ['nullable', 'string', 'min:8', 'max:120'],
        ]);

        $user = User::query()->with('roles:id,name')->findOrFail($userId);
        $generated = empty($validated['new_password']);
        $newPassword = $generated ? $this->temporaryPassword() : (string) $validated['new_password'];

        $user->password = $newPassword;
        $user->save();
        $user->tokens()->delete();

        AuditLogger::log(
            $request->user(),
            'ADMIN_RESET_PASSWORD',
            'user',
            $user->id,
            [
                'generated' => $generated,
                'target_email' => $user->email,
            ]
        );

        return response()->json([
            'ok' => true,
            'message' => 'Contrasena restablecida.',
            'data' => [
                ...$this->serializeUser($user),
                'temporary_password' => $generated ? $newPassword : null,
            ],
        ]);
    }

    private function serializeUser(User $user): array
    {
        return [
            'id' => $user->id,
            'full_name' => $user->full_name,
            'professional_code' => $user->professional_code,
            'email' => $user->email,
            'is_active' => (bool) $user->is_active,
            'roles' => $user->roles->pluck('name')->values(),
            'created_at' => $user->created_at?->toISOString(),
            'updated_at' => $user->updated_at?->toISOString(),
        ];
    }
}
