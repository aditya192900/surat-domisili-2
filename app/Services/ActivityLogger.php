<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Model;

class ActivityLogger
{
    /**
     * Catat aktivitas ke database.
     */
    public static function log(
        string $aksi,
        ?string $deskripsi = null,
        ?Model $model = null,
        ?array $dataLama = null,
        ?array $dataBaru = null
    ): void {
        $user = auth()->user();

        ActivityLog::create([
            'user_id'     => $user?->id,
            'user_name'   => $user?->name,
            'user_role'   => $user?->role,
            'aksi'        => $aksi,
            'model_type'  => $model ? get_class($model) : null,
            'model_id'    => $model?->id,
            'deskripsi'   => $deskripsi,
            'data_lama'   => $dataLama,
            'data_baru'   => $dataBaru,
            'ip_address'  => request()->ip(),
            'user_agent'  => substr((string) request()->userAgent(), 0, 255),
        ]);
    }
}