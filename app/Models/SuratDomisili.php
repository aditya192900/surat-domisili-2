<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuratDomisili extends Model
{
    use HasFactory;

    protected $table = 'surat_domisili';

    protected $fillable = [
        'nomor_pengajuan',
        'user_id',
        'reapply_from_id',
        'keperluan',
        'ktp_path',
        'kk_path',
        'foto_path',
        'status',
        'catatan_rt',
        'rt_verified_by',
        'rt_verified_at',
        'catatan_kelurahan',
        'notif_ditolak_rt_read_at',
        'notif_diverifikasi_rt_read_at',
        'kelurahan_processed_by',
        'kelurahan_processed_at',
        'nomor_surat',
        'file_surat_path',
    ];

    protected $casts = [
        'rt_verified_at' => 'datetime',
        'kelurahan_processed_at' => 'datetime',
        'notif_ditolak_rt_read_at' => 'datetime',
        'notif_diverifikasi_rt_read_at' => 'datetime',
    ];

    public function warga()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function rtVerifier()
    {
        return $this->belongsTo(User::class, 'rt_verified_by');
    }

    public function kelurahanProcessor()
    {
        return $this->belongsTo(User::class, 'kelurahan_processed_by');
    }

    // 🆕 Relasi ke surat yang diajukan ulang
    public function reapplyFrom()
    {
        return $this->belongsTo(SuratDomisili::class, 'reapply_from_id');
    }

    public function reapplies()
    {
        return $this->hasMany(SuratDomisili::class, 'reapply_from_id');
    }
}