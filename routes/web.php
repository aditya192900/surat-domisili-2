<?php

use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\BackupController;
use App\Http\Controllers\KelolaRtController;
use App\Http\Controllers\PengaturanController;
use App\Http\Controllers\PengumumanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SuratDomisiliController;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

// ==========================================
// HALAMAN PUBLIK
// ==========================================

Route::get('/', function () {
    // 🚀 Cache statistik (isinya array scalar — aman di-cache)
    $statistik = Cache::remember('landing_statistik', 300, function () {
        $total = \App\Models\SuratDomisili::count();
        $selesai = \App\Models\SuratDomisili::where('status', 'selesai')->count();
        $bulanIni = \App\Models\SuratDomisili::whereMonth('created_at', now()->month)
                        ->whereYear('created_at', now()->year)
                        ->count();

        $result = DB::table('surat_domisili')
            ->where('status', 'selesai')
            ->whereNotNull('created_at')
            ->whereNotNull('kelurahan_processed_at')
            ->selectRaw('AVG(TIMESTAMPDIFF(HOUR, created_at, kelurahan_processed_at)) as rata')
            ->first();

        return [
            'total'     => $total,
            'selesai'   => $selesai,
            'bulan_ini' => $bulanIni,
            'rata_rata' => $result && $result->rata ? round($result->rata, 1) : 0,
        ];
    });

    // ⚠️ Pengumuman: query langsung (Collection Eloquent tidak aman di-cache)
    $pengumuman = \App\Models\Pengumuman::published()
        ->aktif()
        ->latest('tanggal_mulai')
        ->take(3)
        ->get();

    return view('landing', compact('statistik', 'pengumuman'));
})->name('landing');

Route::get('/lacak', function (\Illuminate\Http\Request $request) {
    $surat = null;
    $dicari = false;

    if ($request->filled('nomor')) {
        $dicari = true;
        $nomor = trim($request->nomor);
        $surat = \App\Models\SuratDomisili::with('warga')
            ->where('nomor_pengajuan', $nomor)
            ->first();
    }

    return view('lacak', compact('surat', 'dicari'));
})->name('lacak');

Route::post('/cek-nik', [\App\Http\Controllers\CekNikController::class, 'cek'])->name('cek-nik');

Route::get('/verifikasi/{nomor}', [SuratDomisiliController::class, 'verifikasiPublik'])
    ->name('verifikasi.publik');

Route::get('/pengumuman/{pengumuman}', [PengumumanController::class, 'show'])
    ->name('pengumuman.show');

// ==========================================
// ROUTE BAWAAN BREEZE (AUTH)
// ==========================================
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/profile/{user}/foto', [ProfileController::class, 'foto'])->name('profile.foto');
    Route::delete('/profile/foto', [ProfileController::class, 'hapusFoto'])->name('profile.foto.hapus');

    Route::get('/profile/{user}/ttd', [ProfileController::class, 'ttd'])->name('profile.ttd');
    Route::delete('/profile/ttd', [ProfileController::class, 'hapusTtd'])->name('profile.ttd.hapus');

    Route::get('/profile/{user}/stempel', [ProfileController::class, 'stempel'])->name('profile.stempel');
    Route::delete('/profile/stempel', [ProfileController::class, 'hapusStempel'])->name('profile.stempel.hapus');

    Route::get('/logo-kelurahan', [PengaturanController::class, 'logo'])->name('kelurahan.logo');
});

require __DIR__.'/auth.php';

// ==========================================
// ROUTE SETELAH LOGIN
// ==========================================
Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', function () {
        $role = auth()->user()->role;
        return match ($role) {
            'warga'     => redirect()->route('warga.dashboard'),
            'rt'        => redirect()->route('rt.surat.index'),
            'kelurahan' => redirect()->route('kelurahan.dashboard'),
            default     => abort(403, 'Role tidak dikenali.'),
        };
    })->name('dashboard');

    Route::middleware('role:kelurahan,admin')
        ->get('/admin/logs', [ActivityLogController::class, 'index'])
        ->name('admin.logs');

    Route::get('/surat/{surat}/file/{jenis}', [SuratDomisiliController::class, 'lihatFile'])
        ->name('surat.file');

    Route::get('/surat/{surat}/cetak', [SuratDomisiliController::class, 'cetakSurat'])
        ->name('surat.cetak');

    Route::get('/surat/{surat}', [SuratDomisiliController::class, 'show'])
        ->name('surat.show');

    // --- WARGA ---
    Route::middleware('role:warga')->prefix('warga')->name('warga.')->group(function () {
        Route::get('/dashboard', [SuratDomisiliController::class, 'dashboardWarga'])->name('dashboard');
        Route::get('/surat', [SuratDomisiliController::class, 'indexWarga'])->name('surat.index');
        Route::get('/surat/create', [SuratDomisiliController::class, 'create'])->name('surat.create');
        Route::post('/surat', [SuratDomisiliController::class, 'store'])->name('surat.store');
                Route::get('/surat/{surat}/reapply', [SuratDomisiliController::class, 'reapply'])->name('surat.reapply');
        Route::get('/surat/{surat}/pengantar', [SuratDomisiliController::class, 'cetakSuratPengantar'])->name('surat.pengantar');
    });

    // --- RT ---
    Route::middleware('role:rt')->prefix('rt')->name('rt.')->group(function () {
        Route::get('/surat', [SuratDomisiliController::class, 'indexRt'])->name('surat.index');
        Route::get('/riwayat', [SuratDomisiliController::class, 'riwayatRt'])->name('surat.riwayat');
                Route::get('/riwayat/pdf', [SuratDomisiliController::class, 'cetakPdfRt'])->name('surat.riwayat.pdf');
        Route::get('/surat/{surat}/pengantar', [SuratDomisiliController::class, 'cetakSuratPengantar'])->name('surat.pengantar');
                Route::get('/riwayat/export', [SuratDomisiliController::class, 'exportExcelRt'])->name('surat.riwayat.export');
        Route::post('/surat/{surat}/verifikasi', [SuratDomisiliController::class, 'verifikasiRt'])->name('surat.verifikasi');
        Route::post('/surat/{surat}/batalkan-verifikasi', [SuratDomisiliController::class, 'batalkanVerifikasiRt'])->name('surat.batalkan-verifikasi');

        Route::get('/data-warga', [\App\Http\Controllers\DataWargaRtController::class, 'index'])->name('data-warga.index');
        Route::get('/data-warga/create', [\App\Http\Controllers\DataWargaRtController::class, 'create'])->name('data-warga.create');
        Route::post('/data-warga', [\App\Http\Controllers\DataWargaRtController::class, 'store'])->name('data-warga.store');
        Route::get('/data-warga/{dataWarga}', [\App\Http\Controllers\DataWargaRtController::class, 'show'])->name('data-warga.show');
        Route::get('/data-warga/{dataWarga}/edit', [\App\Http\Controllers\DataWargaRtController::class, 'edit'])->name('data-warga.edit');
        Route::put('/data-warga/{dataWarga}', [\App\Http\Controllers\DataWargaRtController::class, 'update'])->name('data-warga.update');
        Route::delete('/data-warga/{dataWarga}', [\App\Http\Controllers\DataWargaRtController::class, 'destroy'])->name('data-warga.destroy');

        Route::post('/data-warga/{dataWarga}/reset-password', [\App\Http\Controllers\DataWargaRtController::class, 'resetPassword'])->name('data-warga.reset-password');
    });

    // --- KELURAHAN ---
    Route::middleware('role:kelurahan')->prefix('kelurahan')->name('kelurahan.')->group(function () {
        Route::get('/dashboard', [SuratDomisiliController::class, 'dashboardKelurahan'])->name('dashboard');
        Route::get('/surat', [SuratDomisiliController::class, 'indexKelurahan'])->name('surat.index');
        Route::get('/riwayat', [SuratDomisiliController::class, 'riwayatKelurahan'])->name('surat.riwayat');
        Route::get('/riwayat/export', [SuratDomisiliController::class, 'exportExcel'])->name('surat.export');
        Route::get('/riwayat/rekap-pdf', [SuratDomisiliController::class, 'rekapPdf'])->name('surat.rekap');
        Route::post('/surat/{surat}/proses', [SuratDomisiliController::class, 'prosesKelurahan'])->name('surat.proses');

        Route::get('/pengaturan', [PengaturanController::class, 'index'])->name('pengaturan.index');
        Route::post('/pengaturan', [PengaturanController::class, 'update'])->name('pengaturan.update');
        Route::delete('/pengaturan/logo', [PengaturanController::class, 'hapusLogo'])->name('pengaturan.logo.hapus');

        // 🆕 BACKUP DATABASE
        Route::get('/backup', [BackupController::class, 'index'])->name('backup.index');
        Route::post('/backup', [BackupController::class, 'create'])->name('backup.create');
        Route::get('/backup/download/{filename}', [BackupController::class, 'download'])->name('backup.download');
        Route::delete('/backup/{filename}', [BackupController::class, 'destroy'])->name('backup.destroy');

        Route::resource('kelola-rt', KelolaRtController::class);
        Route::resource('pengumuman', PengumumanController::class)->except(['show']);
    });
});