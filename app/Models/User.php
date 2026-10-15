<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'nik',
        'email',
        'password',
        'phone',
        'role',
        'rt',
        'rw',
        'alamat',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'nip',
        'jabatan',
        'foto',
        'ttd_path',
        'stempel_path',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'tanggal_lahir' => 'date',
            'password' => 'hashed',
        ];
    }

    public function suratDomisili()
    {
        return $this->hasMany(SuratDomisili::class);
    }

    public function isWarga(): bool { return $this->role === 'warga'; }
    public function isRt(): bool { return $this->role === 'rt'; }
    public function isKelurahan(): bool { return $this->role === 'kelurahan'; }

    public function fotoUrl(): ?string
    {
        return $this->foto ? route('profile.foto', $this) : null;
    }

    public function inisial(): string
    {
        $kata = explode(' ', trim($this->name));
        $inisial = '';
        foreach (array_slice($kata, 0, 2) as $k) {
            $inisial .= strtoupper(substr($k, 0, 1));
        }
        return $inisial ?: '?';
    }
}