<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataWargaRt extends Model
{
    use HasFactory;

    protected $table = 'data_warga_rt';

    protected $fillable = [
        'nik',
        'nama',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'alamat',
        'rt',
        'rw',
        'no_hp',
        'email',
        'status',
        'user_id',
        'created_by',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
    ];

    // Relasi: warga yang sudah daftar jadi user
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relasi: RT yang input data
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Helper: label jenis kelamin
    public function jenisKelaminLabel(): string
    {
        return $this->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan';
    }

    // Helper: tanggal lahir format Indonesia
    public function tanggalLahirFormat(): string
    {
        return $this->tanggal_lahir
            ? $this->tanggal_lahir->translatedFormat('d F Y')
            : '-';
    }

    // Helper: umur
    public function umur(): int
    {
        return $this->tanggal_lahir ? $this->tanggal_lahir->age : 0;
    }

    // Helper: NIK disamarkan (untuk tampil publik)
    public function nikMasked(): string
    {
        return substr($this->nik, 0, 6) . '******' . substr($this->nik, -4);
    }
}