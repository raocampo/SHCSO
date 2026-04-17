<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\SystemSetting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    private const TEXT_KEYS = [
        'institution_name', 'institution_subtitle', 'institution_city',
        'footer_note', 'signature_name', 'signature_title',
        'professional_code', 'professional_title',
    ];

    private const IMAGE_TYPES = ['logo', 'signature', 'seal'];

    public function index(): JsonResponse
    {
        $all  = SystemSetting::getAll();
        $data = [];

        foreach (self::TEXT_KEYS as $key) {
            $data[$key] = $all[$key] ?? null;
        }

        // Image paths → return public URL (not full disk path)
        foreach (['logo_path', 'signature_path', 'seal_path'] as $imgKey) {
            $path       = $all[$imgKey] ?? null;
            $data[$imgKey]     = $path;
            $data[str_replace('_path', '_url', $imgKey)] = $path
                ? asset($path)
                : null;
        }

        return response()->json(['ok' => true, 'data' => $data]);
    }

    public function update(Request $request): JsonResponse
    {
        $validated = $request->validate(array_fill_keys(
            self::TEXT_KEYS,
            ['nullable', 'string', 'max:500']
        ));

        SystemSetting::setMany($validated);

        AuditLog::create([
            'user_id'     => $request->user()->id,
            'action'      => 'UPDATE_SYSTEM_SETTINGS',
            'entity_type' => 'SystemSetting',
            'entity_id'   => 'all',
            'description' => 'Configuración del sistema actualizada',
            'ip_address'  => $request->ip(),
        ]);

        return response()->json(['ok' => true, 'message' => 'Configuración guardada correctamente.']);
    }

    public function uploadImage(Request $request, string $type): JsonResponse
    {
        if (!in_array($type, self::IMAGE_TYPES, true)) {
            return response()->json(['ok' => false, 'message' => 'Tipo de imagen no válido.'], 422);
        }

        $request->validate([
            'image' => ['required', 'file', 'mimes:jpg,jpeg,png,gif,svg,webp', 'max:2048'],
        ]);

        $settingKey = $type . '_path';

        // Delete old file if exists
        $oldPath = SystemSetting::getValue($settingKey);
        if ($oldPath && file_exists(public_path($oldPath))) {
            @unlink(public_path($oldPath));
        }

        $file     = $request->file('image');
        $filename = $type . '_' . now()->format('YmdHis') . '.' . $file->getClientOriginalExtension();
        $destDir  = public_path('storage/settings');
        if (!is_dir($destDir)) {
            mkdir($destDir, 0775, true);
        }
        $file->move($destDir, $filename);
        $relativePath = 'storage/settings/' . $filename;

        SystemSetting::set($settingKey, $relativePath);

        AuditLog::create([
            'user_id'     => $request->user()->id,
            'action'      => 'UPLOAD_SETTING_IMAGE',
            'entity_type' => 'SystemSetting',
            'entity_id'   => $settingKey,
            'description' => "Imagen '{$type}' actualizada: {$relativePath}",
            'ip_address'  => $request->ip(),
        ]);

        return response()->json([
            'ok'   => true,
            'path' => $relativePath,
            'url'  => asset($relativePath),
        ]);
    }

    public function deleteImage(Request $request, string $type): JsonResponse
    {
        if (!in_array($type, self::IMAGE_TYPES, true)) {
            return response()->json(['ok' => false, 'message' => 'Tipo de imagen no válido.'], 422);
        }

        $settingKey = $type . '_path';
        $oldPath    = SystemSetting::getValue($settingKey);

        if ($oldPath && file_exists(public_path($oldPath))) {
            @unlink(public_path($oldPath));
        }

        SystemSetting::set($settingKey, null);

        return response()->json(['ok' => true]);
    }
}
