<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Pengaturan extends Model
{
    use HasFactory;

    protected $table = 'pengaturan';

    protected $fillable = [
        'key',
        'value',
        'tipe',
        'label',
        'grup',
    ];

    /**
     * Ambil nilai setting berdasarkan key.
     * Pakai cache biar cepat.
     */
    public static function ambil(string $key, $default = null)
    {
        $data = Cache::rememberForever('pengaturan_all', function () {
            return self::pluck('value', 'key')->toArray();
        });

        return $data[$key] ?? $default;
    }

    /**
     * Simpan/update nilai setting.
     */
    public static function set(string $key, $value): void
    {
        self::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );

        Cache::forget('pengaturan_all');
    }

    /**
     * Bersihkan cache pengaturan.
     */
    public static function clearCache(): void
    {
        Cache::forget('pengaturan_all');
    }

    /**
     * Ambil semua setting berdasarkan grup.
     */
    public static function byGrup(string $grup)
    {
        return self::where('grup', $grup)->get();
    }
}