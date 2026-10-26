<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengumuman extends Model
{
    use HasFactory;

    protected $table = 'pengumuman';

    protected $fillable = [
        'judul',
        'konten',
        'kategori',
        'tanggal_mulai',
        'tanggal_selesai',
        'is_published',
        'created_by',
    ];

    protected $casts = [
        'tanggal_mulai'    => 'date',
        'tanggal_selesai'  => 'date',
        'is_published'     => 'boolean',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Scope: hanya yang published
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    // Scope: pengumuman aktif (belum berakhir atau tanpa tanggal selesai)
    public function scopeAktif($query)
    {
        return $query->where('tanggal_mulai', '<=', now())
                     ->where(function ($q) {
                         $q->whereNull('tanggal_selesai')
                           ->orWhere('tanggal_selesai', '>=', now());
                     });
    }

    // Badge warna sesuai kategori
    public function badgeKategori(): array
    {
        return match ($this->kategori) {
            'penting'  => ['bg' => '#fee2e2', 'color' => '#991b1b', 'icon' => '🔴', 'label' => 'Penting'],
            'kegiatan' => ['bg' => '#dbeafe', 'color' => '#1e40af', 'icon' => '📅', 'label' => 'Kegiatan'],
            default    => ['bg' => '#fef3c7', 'color' => '#92400e', 'icon' => 'ℹ️', 'label' => 'Info'],
        };
    }
}