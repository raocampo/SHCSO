<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SystemSetting extends Model
{
    protected $primaryKey = 'key';
    public    $incrementing = false;
    protected $keyType      = 'string';

    protected $fillable = ['key', 'value'];

    public static function getValue(string $key, ?string $default = null): ?string
    {
        return Cache::remember("setting:{$key}", 300, function () use ($key, $default) {
            return static::where('key', $key)->value('value') ?? $default;
        });
    }

    public static function getAll(): array
    {
        return Cache::remember('settings:all', 300, function () {
            return static::all()->pluck('value', 'key')->toArray();
        });
    }

    public static function set(string $key, ?string $value): void
    {
        static::updateOrCreate(['key' => $key], ['value' => $value]);
        Cache::forget("setting:{$key}");
        Cache::forget('settings:all');
    }

    public static function setMany(array $data): void
    {
        foreach ($data as $key => $value) {
            static::updateOrCreate(['key' => $key], ['value' => $value]);
            Cache::forget("setting:{$key}");
        }
        Cache::forget('settings:all');
    }

    public static function institutionConfig(): array
    {
        $s = static::getAll();
        return [
            'name'                   => $s['institution_name']           ?? config('shcso.institution.name'),
            'subtitle'               => $s['institution_subtitle']       ?? config('shcso.institution.subtitle'),
            'city'                   => $s['institution_city']           ?? config('shcso.institution.city'),
            'ruc'                    => $s['institution_ruc']            ?? null,
            'representative'         => $s['institution_representative'] ?? null,
            'phone'                  => $s['institution_phone']          ?? null,
            'address'                => $s['institution_address']        ?? null,
            'email'                  => $s['institution_email']          ?? null,
            'footer_note'            => $s['footer_note']                ?? config('shcso.pdf_certificate.footer_note'),
            'signature_name'         => $s['signature_name']             ?? config('shcso.pdf_certificate.signature_name'),
            'signature_title'        => $s['signature_title']            ?? config('shcso.pdf_certificate.signature_title'),
            'professional_code'      => $s['professional_code']          ?? null,
            'professional_title'     => $s['professional_title']         ?? null,
            'logo_path'              => $s['logo_path']                  ? public_path($s['logo_path']) : null,
            'signature_path'         => $s['signature_path']             ? public_path($s['signature_path']) : null,
            'seal_path'              => $s['seal_path']                  ? public_path($s['seal_path']) : null,
        ];
    }
}
