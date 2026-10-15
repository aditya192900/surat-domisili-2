<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Jumlah percobaan maksimal sebelum diblokir.
     */
    public const MAX_ATTEMPTS = 5;

    /**
     * Waktu blokir (detik). 600 detik = 10 menit.
     */
    public const LOCKOUT_SECONDS = 600;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email'    => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.required'    => 'Email wajib diisi.',
            'email.email'       => 'Format email tidak valid.',
            'password.required' => 'Kata sandi wajib diisi.',
        ];
    }

    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        if (! Auth::attempt($this->only('email', 'password'), $this->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey(), self::LOCKOUT_SECONDS);

            $sisa = self::MAX_ATTEMPTS - RateLimiter::attempts($this->throttleKey());

            if ($sisa > 0) {
                $pesan = "Email atau kata sandi salah. Sisa percobaan: {$sisa}x lagi sebelum akun diblokir sementara.";
            } else {
                $pesan = 'Terlalu banyak percobaan login gagal. Akun Anda diblokir sementara selama 10 menit. Silakan coba lagi nanti.';
            }

            throw ValidationException::withMessages([
                'email' => $pesan,
            ]);
        }

        RateLimiter::clear($this->throttleKey());
    }

    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), self::MAX_ATTEMPTS)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        if ($seconds >= 60) {
            $menit = ceil($seconds / 60);
            $waktu = "{$menit} menit";
        } else {
            $waktu = "{$seconds} detik";
        }

        throw ValidationException::withMessages([
            'email' => "Terlalu banyak percobaan login gagal. Akun Anda diblokir selama {$waktu}. Silakan coba lagi nanti.",
        ]);
    }

    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('email')).'|'.$this->ip());
    }
}