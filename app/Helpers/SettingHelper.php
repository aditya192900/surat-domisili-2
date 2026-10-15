<?php

use App\Models\Pengaturan;

if (!function_exists('setting')) {
    /**
     * Ambil nilai pengaturan.
     *
     * @param  string $key
     * @param  mixed  $default
     * @return mixed
     */
    function setting(string $key, $default = null)
    {
        return Pengaturan::ambil($key, $default);
    }
}

if (!function_exists('setting_set')) {
    /**
     * Simpan nilai pengaturan.
     */
    function setting_set(string $key, $value): void
    {
        Pengaturan::set($key, $value);
    }
}