<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        /**
         * Blade Directive: @setting('nama_kelurahan')
         * Ambil dari tabel pengaturan (DB). Fallback ke config/kelurahan.php kalau kosong.
         */
        Blade::directive('setting', function ($expression) {
            return "<?php echo e(setting({$expression})); ?>";
        });

        /**
         * Alias @kelurahan (untuk backward-compatible)
         */
        Blade::directive('kelurahan', function ($expression) {
            return "<?php echo e(setting({$expression})); ?>";
        });
    }
}