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
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email'    => ['required', 'string'],
            'password' => ['required', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.required'    => 'Email atau Nomor HP wajib diisi.',
            'password.required' => 'Kata sandi wajib diisi.',
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        $login = trim($this->input('email'));
        $password = $this->input('password');

        // 🎯 Deteksi: apakah input berupa email atau nomor HP
        $isEmail = filter_var($login, FILTER_VALIDATE_EMAIL) !== false;

        // Normalisasi nomor HP (hapus spasi, strip, kurung, plus, dll.)
        $phoneNormalized = preg_replace('/[^0-9]/', '', $login);

        // Konversi format nomor HP ke standar 08xxx
        // +62812xxx → 0812xxx
        // 62812xxx  → 0812xxx
        if (str_starts_with($phoneNormalized, '62')) {
            $phoneNormalized = '0' . substr($phoneNormalized, 2);
        }

        $credentials = null;

        if ($isEmail) {
            // Coba login via email
            $credentials = ['email' => $login, 'password' => $password];
        } else {
            // Coba login via phone (beberapa variasi format)
            $phoneVariants = array_unique([
                $phoneNormalized,
                $login, // original input
            ]);

            foreach ($phoneVariants as $phone) {
                if (Auth::attempt(['phone' => $phone, 'password' => $password], $this->boolean('remember'))) {
                    RateLimiter::clear($this->throttleKey());
                    return;
                }
            }

            // Fallback: kalau input bukan email tapi bukan juga nomor valid, coba sebagai email
            $credentials = ['email' => $login, 'password' => $password];
        }

        if (!Auth::attempt($credentials, $this->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }

        RateLimiter::clear($this->throttleKey());
    }

    public function ensureIsNotRateLimited(): void
    {
        if (!RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('email')) . '|' . $this->ip());
    }
}