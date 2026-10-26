<?php

namespace App\Http\Controllers;

use App\Exports\SuratDomisiliExport;
use App\Models\SuratDomisili;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class SuratDomisiliController extends Controller
{
    // ==========================================
    // BAGIAN WARGA
    // ==========================================

    public function indexWarga(Request $request)
    {
        $query = SuratDomisili::where('user_id', auth()->id());

        if ($request->filled('kategori')) {
            $kategori = $request->kategori;
            switch ($kategori) {
                case 'proses':
                    $query->whereIn('status', ['diajukan_ke_rt', 'diverifikasi_rt', 'diproses_kelurahan']);
                    break;
                case 'selesai':
                    $query->where('status', 'selesai');
                    break;
                case 'ditolak':
                    $query->whereIn('status', ['ditolak_rt', 'ditolak_kelurahan']);
                    break;
            }
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('jenis')) {
            $jenis = $request->jenis;
            $query->where(function ($q) use ($jenis) {
                if ($jenis === 'domisili') {
                    $q->where('keperluan', 'like', '%Domisili%');
                } elseif ($jenis === 'sktm') {
                    $q->where(function ($sub) {
                        $sub->where('keperluan', 'like', '%SKTM%')
                            ->orWhere('keperluan', 'like', '%Tidak Mampu%');
                    });
                } elseif ($jenis === 'sku') {
                    $q->where(function ($sub) {
                        $sub->where('keperluan', 'like', '%SKU%')
                            ->orWhere('keperluan', 'like', '%Usaha%');
                    });
                }
            });
        }

        if ($request->filled('cari')) {
            $cari = $request->cari;
            $query->where(function ($q) use ($cari) {
                $q->where('nomor_pengajuan', 'like', "%{$cari}%")
                  ->orWhere('nomor_surat', 'like', "%{$cari}%")
                  ->orWhere('keperluan', 'like', "%{$cari}%");
            });
        }

        $data = $query->latest()->paginate(10)->withQueryString();

        return view('warga.index', compact('data'));
    }

    public function dashboardWarga()
    {
        $userId = auth()->id();

        $perStatus = DB::table('surat_domisili')
            ->where('user_id', $userId)
            ->selectRaw('status, COUNT(*) as jumlah')
            ->groupBy('status')
            ->pluck('jumlah', 'status')
            ->toArray();

        $statistik = [
            'total'   => array_sum($perStatus),
            'proses'  => ($perStatus['diajukan_ke_rt'] ?? 0) + ($perStatus['diverifikasi_rt'] ?? 0) + ($perStatus['diproses_kelurahan'] ?? 0),
            'selesai' => $perStatus['selesai'] ?? 0,
            'ditolak' => ($perStatus['ditolak_rt'] ?? 0) + ($perStatus['ditolak_kelurahan'] ?? 0),
        ];

        $sedangDiproses = $statistik['proses'];

        $terbaru = SuratDomisili::where('user_id', $userId)
            ->latest()
            ->take(3)
            ->get();

        $siapDownload = SuratDomisili::where('user_id', $userId)
            ->whereNotNull('rt_verified_at')
            ->whereIn('status', ['diverifikasi_rt', 'diproses_kelurahan', 'selesai'])
            ->latest('rt_verified_at')
            ->take(3)
            ->get();

        $totalSiapDownload = SuratDomisili::where('user_id', $userId)
            ->whereNotNull('rt_verified_at')
            ->whereIn('status', ['diverifikasi_rt', 'diproses_kelurahan', 'selesai'])
            ->count();

        return view('warga.dashboard', compact('statistik', 'terbaru', 'sedangDiproses', 'siapDownload', 'totalSiapDownload'));
    }

    public function create()
    {
        return view('warga.create');
    }

    public function show(SuratDomisili $surat)
    {
        $user = auth()->user();

        $bolehAkses = false;

        if ($user->id === $surat->user_id) {
            $bolehAkses = true;
        } elseif ($user->role === 'rt' && $user->rt === $surat->warga->rt) {
            $bolehAkses = true;
        } elseif (in_array($user->role, ['kelurahan', 'admin'], true)) {
            $bolehAkses = true;
        }

        if (!$bolehAkses) {
            abort(403, 'Anda tidak berhak melihat surat ini.');
        }

        if (
            $user->role === 'rt'
            && $surat->status === 'ditolak_kelurahan'
            && is_null($surat->notif_ditolak_rt_read_at)
        ) {
            $surat->notif_ditolak_rt_read_at = now();
            $surat->save();
        }

        if (
            in_array($user->role, ['kelurahan', 'admin'], true)
            && $surat->status === 'diverifikasi_rt'
            && is_null($surat->notif_diverifikasi_rt_read_at)
        ) {
            $surat->notif_diverifikasi_rt_read_at = now();
            $surat->save();
        }

        $surat->load('warga', 'rtVerifier', 'kelurahanProcessor');

        return view('surat.detail', compact('surat'));
    }

           public function store(Request $request)
    {
        $request->validate([
            'jenis_surat'     => 'required|string|in:Surat Keterangan Domisili,Surat Keterangan Tidak Mampu (SKTM),Pengantar Nikah,Surat Pernyataan Ahli Waris,Surat Keterangan Belum Menikah,Surat Keterangan Belum Memiliki Rumah',
            'keperluan'       => 'nullable|string|max:255',
            'ktp'             => 'nullable',
            'ktp.*'           => 'file|mimes:jpg,jpeg,png,pdf|max:2048',
            'kk'              => 'nullable',
            'kk.*'            => 'file|mimes:jpg,jpeg,png,pdf|max:2048',
            'foto'            => 'nullable',
            'foto.*'          => 'file|mimes:jpg,jpeg,png,pdf|max:2048',
            'reapply_from_id' => 'nullable|exists:surat_domisili,id',
        ]);

        return DB::transaction(function () use ($request) {
            $saveFiles = function ($fileInput, $folder) {
                $paths = [];
                if ($fileInput) {
                    $files = is_array($fileInput) ? $fileInput : [$fileInput];
                    foreach ($files as $file) {
                        $paths[] = $file->store('private/' . $folder);
                    }
                }
                return $paths;
            };

            $suratLama = null;
            if ($request->filled('reapply_from_id')) {
                $suratLama = SuratDomisili::find($request->reapply_from_id);
                if ($suratLama && $suratLama->user_id !== auth()->id()) $suratLama = null;
            }

            $ktpPaths = $request->hasFile('ktp') ? $saveFiles($request->file('ktp'), 'ktp') : ($suratLama ? (json_decode($suratLama->ktp_path, true) ?? [$suratLama->ktp_path]) : []);
            $kkPaths  = $request->hasFile('kk') ? $saveFiles($request->file('kk'), 'kk') : ($suratLama ? (json_decode($suratLama->kk_path, true) ?? [$suratLama->kk_path]) : []);
            $fotoPaths = $request->hasFile('foto') ? $saveFiles($request->file('foto'), 'foto') : ($suratLama ? (json_decode($suratLama->foto_path, true) ?? [$suratLama->foto_path]) : []);

            // VALIDASI KHUSUS BERDASARKAN JENIS SURAT
                       if ($request->jenis_surat === 'Pengantar Nikah') {
                if (count($ktpPaths) < 3 || count($kkPaths) < 3 || count($fotoPaths) < 3) {
                    return back()->withInput()->with('error', 'Untuk Pengantar Nikah, wajib mengupload 3 KTP, 3 KK, Pas Foto, Surat Sehat, dan Bukti PBB.');
                }
            } elseif ($request->jenis_surat === 'Surat Pernyataan Ahli Waris') {
                if (count($ktpPaths) < 3 || count($kkPaths) < 1 || count($fotoPaths) < 2) {
                    return back()->withInput()->with('error', 'Untuk Ahli Waris, wajib mengupload 3 KTP (Ahli Waris + 2 Saksi), 1 KK, Akta Kematian, dan Bukti Lunas PBB.');
                }
            } else {
                if (empty($ktpPaths) || empty($kkPaths) || empty($fotoPaths)) {
                    return back()->withInput()->with('error', 'Semua dokumen utama wajib diisi.');
                }
            }

            $keperluan = $request->jenis_surat;
            if ($request->filled('keperluan')) $keperluan .= ' — ' . $request->keperluan;

            $surat = SuratDomisili::create([
                'nomor_pengajuan' => 'SD-' . now()->format('Ymd') . '-' . strtoupper(Str::random(6)),
                'user_id'         => auth()->id(),
                'reapply_from_id' => $suratLama ? $suratLama->id : null,
                'keperluan'       => $keperluan,
                'ktp_path'        => json_encode($ktpPaths),
                'kk_path'         => json_encode($kkPaths),
                'foto_path'       => json_encode($fotoPaths),
                'status'          => 'diajukan_ke_rt',
            ]);

            ActivityLogger::log('ajukan_surat', "Warga mengajukan surat {$surat->nomor_pengajuan}", $surat, null, ['status' => 'diajukan_ke_rt']);
            return redirect()->route('warga.surat.index')->with('success', "Pengajuan berhasil dikirim ke RT.");
        });
    }
    public function reapply(SuratDomisili $surat)
    {
        if ($surat->user_id !== auth()->id()) {
            abort(403, 'Anda tidak berhak mengajukan ulang surat ini.');
        }

        if (!in_array($surat->status, ['ditolak_rt', 'ditolak_kelurahan'])) {
            return redirect()->route('warga.surat.index')
                ->with('error', 'Hanya surat yang ditolak yang bisa diajukan ulang.');
        }

        $sudahReapply = SuratDomisili::where('reapply_from_id', $surat->id)
            ->whereNotIn('status', ['ditolak_rt', 'ditolak_kelurahan'])
            ->exists();

        if ($sudahReapply) {
            return redirect()->route('warga.surat.index')
                ->with('error', 'Anda sudah mengajukan ulang surat ini. Silakan cek "Surat Saya".');
        }

        $oldJenis = '';
        foreach ([
            'Surat Keterangan Domisili',
            'Surat Keterangan Tidak Mampu (SKTM)',
            'Surat Keterangan Usaha (SKU)',
        ] as $jenis) {
            if (str_contains($surat->keperluan, $jenis)) {
                $oldJenis = $jenis;
                break;
            }
        }

        $keterangan = '';
        if (str_contains($surat->keperluan, ' — ')) {
            $keterangan = trim(explode(' — ', $surat->keperluan, 2)[1] ?? '');
        }

        return view('warga.create', [
            'suratLama'     => $surat,
            'oldJenisSurat' => $oldJenis,
            'oldKeperluan'  => $keterangan,
        ]);
    }

    // ==========================================
    // BAGIAN RT
    // ==========================================

    public function indexRt()
    {
        $rt = auth()->user()->rt;

        $data = SuratDomisili::whereHas('warga', function ($q) use ($rt) {
                $q->where('rt', $rt);
            })
            ->where('status', 'diajukan_ke_rt')
            ->latest()
            ->paginate(10);

        $jumlahSuratBaru = SuratDomisili::whereHas('warga', function ($q) use ($rt) {
                $q->where('rt', $rt);
            })
            ->where('status', 'diajukan_ke_rt')
            ->count();

        $ditolakKelurahan = SuratDomisili::with('warga')
            ->whereHas('warga', function ($q) use ($rt) {
                $q->where('rt', $rt);
            })
            ->where('status', 'ditolak_kelurahan')
            ->whereNull('notif_ditolak_rt_read_at')
            ->latest('kelurahan_processed_at')
            ->get();

        return view('rt.index', compact('data', 'ditolakKelurahan', 'jumlahSuratBaru'));
    }

    public function riwayatRt(Request $request)
    {
        $rt = auth()->user()->rt;

        $query = SuratDomisili::with('warga', 'kelurahanProcessor')
            ->whereHas('warga', function ($q) use ($rt) {
                $q->where('rt', $rt);
            })
            ->whereIn('status', [
                'diverifikasi_rt',
                'ditolak_rt',
                'diproses_kelurahan',
                'selesai',
                'ditolak_kelurahan',
            ]);

        if ($request->filled('kategori')) {
            $kategori = $request->kategori;
            switch ($kategori) {
                case 'diproses':
                    $query->whereIn('status', ['diverifikasi_rt', 'diproses_kelurahan']);
                    break;
                case 'selesai':
                    $query->where('status', 'selesai');
                    break;
                case 'ditolak':
                    $query->whereIn('status', ['ditolak_rt', 'ditolak_kelurahan']);
                    break;
            }
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('jenis')) {
            $jenis = $request->jenis;
            $query->where(function ($q) use ($jenis) {
                if ($jenis === 'domisili') {
                    $q->where('keperluan', 'like', '%Domisili%');
                } elseif ($jenis === 'sktm') {
                    $q->where(function ($sub) {
                        $sub->where('keperluan', 'like', '%SKTM%')
                            ->orWhere('keperluan', 'like', '%Tidak Mampu%');
                    });
                } elseif ($jenis === 'sku') {
                    $q->where(function ($sub) {
                        $sub->where('keperluan', 'like', '%SKU%')
                            ->orWhere('keperluan', 'like', '%Usaha%');
                    });
                }
            });
        }

        if ($request->filled('cari')) {
            $cari = $request->cari;
            $query->where(function ($q) use ($cari) {
                $q->where('nomor_pengajuan', 'like', "%{$cari}%")
                  ->orWhereHas('warga', function ($w) use ($cari) {
                      $w->where('name', 'like', "%{$cari}%");
                  });
            });
        }

        $data = $query->latest('rt_verified_at')->paginate(15)->withQueryString();

        $perStatus = DB::table('surat_domisili')
            ->join('users', 'surat_domisili.user_id', '=', 'users.id')
            ->where('users.rt', $rt)
            ->selectRaw('surat_domisili.status, COUNT(*) as jumlah')
            ->groupBy('surat_domisili.status')
            ->pluck('jumlah', 'status')
            ->toArray();

        $statistik = [
            'diverifikasi_rt'    => $perStatus['diverifikasi_rt'] ?? 0,
            'ditolak_rt'         => $perStatus['ditolak_rt'] ?? 0,
            'diproses_kelurahan' => $perStatus['diproses_kelurahan'] ?? 0,
            'selesai'            => $perStatus['selesai'] ?? 0,
            'ditolak_kelurahan'  => $perStatus['ditolak_kelurahan'] ?? 0,
        ];

        return view('rt.riwayat', compact('data', 'statistik'));
    }

    public function exportExcelRt(Request $request)
    {
        $rt = auth()->user()->rt;

        $filter = [
            'rt'     => $rt,
            'status' => $request->status,
            'cari'   => $request->cari,
        ];

        $filename = 'Laporan-RT-' . $rt . '-' . now()->format('Ymd-His') . '.xlsx';

        ActivityLogger::log(
            'export_excel_rt',
            "RT {$rt} mengekspor laporan Excel",
            null
        );

        return Excel::download(new SuratDomisiliExport($filter), $filename);
    }
    // ==========================================
    // CETAK PDF RIWAYAT RT
    // ==========================================
    public function cetakPdfRt(Request $request)
    {
        $rt = auth()->user()->rt;

        $query = SuratDomisili::with('warga', 'kelurahanProcessor')
            ->whereHas('warga', function ($q) use ($rt) {
                $q->where('rt', $rt);
            })
            ->whereIn('status', [
                'diverifikasi_rt',
                'ditolak_rt',
                'diproses_kelurahan',
                'selesai',
                'ditolak_kelurahan',
            ]);

        // Samakan filter dengan halaman web
        if ($request->filled('kategori')) {
            $kategori = $request->kategori;
            switch ($kategori) {
                case 'diproses':
                    $query->whereIn('status', ['diverifikasi_rt', 'diproses_kelurahan']);
                    break;
                case 'selesai':
                    $query->where('status', 'selesai');
                    break;
                case 'ditolak':
                    $query->whereIn('status', ['ditolak_rt', 'ditolak_kelurahan']);
                    break;
            }
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('jenis')) {
            $jenis = $request->jenis;
            $query->where(function ($q) use ($jenis) {
                if ($jenis === 'domisili') {
                    $q->where('keperluan', 'like', '%Domisili%');
                } elseif ($jenis === 'sktm') {
                    $q->where(function ($sub) {
                        $sub->where('keperluan', 'like', '%SKTM%')
                            ->orWhere('keperluan', 'like', '%Tidak Mampu%');
                    });
                } elseif ($jenis === 'sku') {
                    $q->where(function ($sub) {
                        $sub->where('keperluan', 'like', '%SKU%')
                            ->orWhere('keperluan', 'like', '%Usaha%');
                    });
                }
            });
        }

        if ($request->filled('cari')) {
            $cari = $request->cari;
            $query->where(function ($q) use ($cari) {
                $q->where('nomor_pengajuan', 'like', "%{$cari}%")
                  ->orWhereHas('warga', function ($w) use ($cari) {
                      $w->where('name', 'like', "%{$cari}%");
                  });
            });
        }

        $data = $query->latest('rt_verified_at')->get();

        // Ambil data user RT untuk kop surat
        $rtUser = auth()->user();
        $namaKelurahan = setting('nama_kelurahan', 'Kelurahan Sungai Asam');
        $namaLurah = setting('nama_lurah', 'Lurah');

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.riwayat-rt', [
            'data'          => $data,
            'rtUser'        => $rtUser,
            'namaKelurahan' => $namaKelurahan,
            'namaLurah'     => $namaLurah,
            'filter'        => $request->all(),
        ]);

        $pdf->setPaper('A4', 'landscape');

        ActivityLogger::log(
            'cetak_pdf_rt',
            "RT {$rt} mencetak laporan PDF riwayat surat",
            null
        );

        $filename = 'Laporan-Riwayat-RT-' . $rt . '-' . now()->format('Ymd-His') . '.pdf';
        return $pdf->download($filename);
    }
    public function verifikasiRt(Request $request, SuratDomisili $surat)
    {
        $this->authorizeRt($surat);

        $request->validate([
            'aksi'    => 'required|in:setuju,tolak',
            'catatan' => 'required_if:aksi,tolak|nullable|string|max:500',
        ], [
            'catatan.required_if' => 'Alasan penolakan wajib diisi.',
        ]);

        $dataLama = ['status' => $surat->status];
        $dataBaru = ['status' => $request->aksi === 'setuju' ? 'diverifikasi_rt' : 'ditolak_rt'];

        if ($request->aksi === 'setuju') {
            $surat->update([
                'status'                        => 'diverifikasi_rt',
                'rt_verified_by'                => auth()->id(),
                'rt_verified_at'                => now(),
                'catatan_rt'                    => $request->catatan,
                'notif_diverifikasi_rt_read_at' => null,
            ]);
        } else {
            $surat->update([
                'status'         => 'ditolak_rt',
                'rt_verified_by' => auth()->id(),
                'rt_verified_at' => now(),
                'catatan_rt'     => $request->catatan,
            ]);
        }

        Cache::forget('landing_statistik');
        Cache::forget('kelurahan_statistik');

        ActivityLogger::log(
            'verifikasi_rt',
            "RT {$request->aksi} surat {$surat->nomor_pengajuan}",
            $surat,
            $dataLama,
            $dataBaru
        );

        return back()->with('success', 'Verifikasi RT berhasil disimpan.');
    }

    public function batalkanVerifikasiRt(Request $request, SuratDomisili $surat)
    {
        $this->authorizeRt($surat);

        if (!in_array($surat->status, ['diverifikasi_rt', 'ditolak_rt'])) {
            return back()->with('error', 'Verifikasi tidak bisa dibatalkan karena surat sudah diproses kelurahan.');
        }

        $statusLama = $surat->status;
        $alasan = $request->filled('alasan_batal') ? trim($request->alasan_batal) : 'Tidak ada alasan';

        $surat->update([
            'status'         => 'diajukan_ke_rt',
            'rt_verified_by' => null,
            'rt_verified_at' => null,
            'catatan_rt'     => null,
        ]);

        Cache::forget('landing_statistik');
        Cache::forget('kelurahan_statistik');

        ActivityLogger::log(
            'batalkan_verifikasi_rt',
            "RT membatalkan verifikasi surat {$surat->nomor_pengajuan} (dari status: {$statusLama}). Alasan: {$alasan}",
            $surat,
            ['status' => $statusLama],
            ['status' => 'diajukan_ke_rt']
        );

        return back()->with('success', "Verifikasi surat {$surat->nomor_pengajuan} berhasil dibatalkan.");
    }

    public function cetakSuratPengantar(SuratDomisili $surat)
    {
        $user = auth()->user();

        $bolehAkses = false;

        if ($user->role === 'rt' && $user->rt === $surat->warga->rt) {
            $bolehAkses = true;
        }

        if ($user->id === $surat->user_id) {
            $bolehAkses = true;
        }

        if (!$bolehAkses) {
            abort(403, 'Anda tidak berhak mengunduh surat pengantar ini.');
        }

        if (!$surat->rt_verified_at || in_array($surat->status, ['ditolak_rt', 'ditolak_kelurahan'])) {
            abort(403, 'Surat belum diverifikasi atau sudah ditolak.');
        }

        $fotoBase64 = null;
        if ($surat->foto_path) {
            $fotoPath = storage_path('app/private/' . $surat->foto_path);
            if (file_exists($fotoPath)) {
                $mime = mime_content_type($fotoPath);
                if (in_array($mime, ['image/jpeg', 'image/jpg', 'image/png'])) {
                    $fotoBase64 = 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($fotoPath));
                }
            }
        }

        $rtUser = \App\Models\User::find($surat->rt_verified_by);

        if (!$rtUser || !$rtUser->ttd_path) {
            if ($user->role === 'rt' && $user->ttd_path) {
                $rtUser = $user;
            }
        }

        if (!$rtUser || !$rtUser->ttd_path) {
            $rtFallback = \App\Models\User::where('role', 'rt')
                ->where('rt', $surat->warga->rt)
                ->whereNotNull('ttd_path')
                ->first();

            if ($rtFallback) {
                $rtUser = $rtFallback;
            }
        }

        $verifyUrl = route('verifikasi.publik', ['nomor' => $surat->nomor_pengajuan]);

        $qrCode = \SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')
            ->size(200)
            ->margin(1)
            ->errorCorrection('H')
            ->generate($verifyUrl);

        $qrBase64 = 'data:image/svg+xml;base64,' . base64_encode($qrCode);

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.surat-pengantar-rt', [
            'surat'      => $surat->load('warga', 'rtVerifier'),
            'fotoBase64' => $fotoBase64,
            'rtUser'     => $rtUser,
            'qrBase64'   => $qrBase64,
            'verifyUrl'  => $verifyUrl,
        ]);

        $pdf->setPaper('A4', 'portrait');

        ActivityLogger::log(
            'cetak_surat_pengantar',
            "Surat Pengantar diunduh oleh {$user->role}: {$surat->nomor_pengajuan}",
            $surat
        );

        $filename = 'Surat-Pengantar-RT-' . $surat->nomor_pengajuan . '.pdf';
        return $pdf->download($filename);
    }

    // ==========================================
    // BAGIAN KELURAHAN
    // ==========================================

    public function indexKelurahan(Request $request)
    {
        $query = SuratDomisili::with('warga', 'rtVerifier')
            ->whereIn('status', ['diverifikasi_rt', 'diproses_kelurahan', 'selesai']);

        if ($request->filled('cari')) {
            $cari = $request->cari;
            $query->where(function ($q) use ($cari) {
                $q->where('nomor_pengajuan', 'like', "%{$cari}%")
                  ->orWhere('nomor_surat', 'like', "%{$cari}%")
                  ->orWhereHas('warga', function ($w) use ($cari) {
                      $w->where('name', 'like', "%{$cari}%");
                  });
            });
        }

        if ($request->filled('jenis')) {
            $jenis = $request->jenis;
            $query->where(function ($q) use ($jenis) {
                if ($jenis === 'domisili') {
                    $q->where('keperluan', 'like', '%Domisili%');
                } elseif ($jenis === 'sktm') {
                    $q->where(function ($sub) {
                        $sub->where('keperluan', 'like', '%SKTM%')
                            ->orWhere('keperluan', 'like', '%Tidak Mampu%');
                    });
                } elseif ($jenis === 'sku') {
                    $q->where(function ($sub) {
                        $sub->where('keperluan', 'like', '%SKU%')
                            ->orWhere('keperluan', 'like', '%Usaha%');
                    });
                }
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $data = $query->latest()->paginate(10)->withQueryString();

        $suratBaru = SuratDomisili::with('warga')
            ->where('status', 'diverifikasi_rt')
            ->latest('rt_verified_at')
            ->take(10)
            ->get();

        $notifBaruDariRt = SuratDomisili::with('warga', 'rtVerifier')
            ->where('status', 'diverifikasi_rt')
            ->whereNull('notif_diverifikasi_rt_read_at')
            ->latest('rt_verified_at')
            ->get();

        $perStatus = DB::table('surat_domisili')
            ->selectRaw('status, COUNT(*) as jumlah')
            ->groupBy('status')
            ->pluck('jumlah', 'status')
            ->toArray();

        $statistik = [
            'baru'     => $perStatus['diverifikasi_rt'] ?? 0,
            'diproses' => $perStatus['diproses_kelurahan'] ?? 0,
            'selesai'  => $perStatus['selesai'] ?? 0,
            'ditolak'  => ($perStatus['ditolak_rt'] ?? 0) + ($perStatus['ditolak_kelurahan'] ?? 0),
        ];

        return view('kelurahan.index', compact('data', 'suratBaru', 'statistik', 'notifBaruDariRt'));
    }

    public function dashboardKelurahan()
    {
        $statistik = Cache::remember('kelurahan_statistik', 180, function () {
            $perStatus = DB::table('surat_domisili')
                ->selectRaw('status, COUNT(*) as jumlah')
                ->groupBy('status')
                ->pluck('jumlah', 'status')
                ->toArray();

            $rataResult = DB::table('surat_domisili')
                ->where('status', 'selesai')
                ->whereNotNull('created_at')
                ->whereNotNull('kelurahan_processed_at')
                ->selectRaw('AVG(TIMESTAMPDIFF(HOUR, created_at, kelurahan_processed_at)) as rata')
                ->first();

            return [
                'total'              => array_sum($perStatus),
                'diajukan_ke_rt'     => $perStatus['diajukan_ke_rt'] ?? 0,
                'diverifikasi_rt'    => $perStatus['diverifikasi_rt'] ?? 0,
                'diproses_kelurahan' => $perStatus['diproses_kelurahan'] ?? 0,
                'selesai'            => $perStatus['selesai'] ?? 0,
                'ditolak_rt'         => $perStatus['ditolak_rt'] ?? 0,
                'ditolak_kelurahan'  => $perStatus['ditolak_kelurahan'] ?? 0,
                'rata_rata'          => $rataResult && $rataResult->rata ? round($rataResult->rata, 1) : 0,
            ];
        });

        $rataRataJam = $statistik['rata_rata'];

        $terbaru = SuratDomisili::with(['warga:id,name,nik,foto,rt,rw'])
            ->latest()
            ->take(10)
            ->get();

        $notifBaruDariRt = SuratDomisili::with('warga', 'rtVerifier')
            ->where('status', 'diverifikasi_rt')
            ->whereNull('notif_diverifikasi_rt_read_at')
            ->latest('rt_verified_at')
            ->get();

        return view('kelurahan.dashboard', compact('statistik', 'rataRataJam', 'terbaru', 'notifBaruDariRt'));
    }

    public function riwayatKelurahan(Request $request)
    {
        $query = SuratDomisili::with('warga', 'rtVerifier', 'kelurahanProcessor')
            ->whereIn('status', [
                'diverifikasi_rt',
                'diproses_kelurahan',
                'selesai',
                'ditolak_rt',
                'ditolak_kelurahan',
            ]);

        if ($request->filled('kategori')) {
            $kategori = $request->kategori;
            switch ($kategori) {
                case 'diproses':
                    $query->whereIn('status', ['diverifikasi_rt', 'diproses_kelurahan']);
                    break;
                case 'selesai':
                    $query->where('status', 'selesai');
                    break;
                case 'ditolak':
                    $query->whereIn('status', ['ditolak_rt', 'ditolak_kelurahan']);
                    break;
            }
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('dari') && $request->filled('sampai')) {
            $query->whereBetween('created_at', [
                $request->dari . ' 00:00:00',
                $request->sampai . ' 23:59:59',
            ]);
        }

        if ($request->filled('cari')) {
            $cari = $request->cari;
            $query->where(function ($q) use ($cari) {
                $q->where('nomor_pengajuan', 'like', "%{$cari}%")
                  ->orWhere('nomor_surat', 'like', "%{$cari}%")
                  ->orWhereHas('warga', function ($w) use ($cari) {
                      $w->where('name', 'like', "%{$cari}%");
                  });
            });
        }

        $data = $query->latest()->paginate(15)->withQueryString();

        $perStatus = DB::table('surat_domisili')
            ->selectRaw('status, COUNT(*) as jumlah')
            ->groupBy('status')
            ->pluck('jumlah', 'status')
            ->toArray();

        $statistik = [
            'total'              => array_sum($perStatus),
            'diverifikasi_rt'    => $perStatus['diverifikasi_rt'] ?? 0,
            'diproses_kelurahan' => $perStatus['diproses_kelurahan'] ?? 0,
            'selesai'            => $perStatus['selesai'] ?? 0,
            'ditolak_rt'         => $perStatus['ditolak_rt'] ?? 0,
            'ditolak_kelurahan'  => $perStatus['ditolak_kelurahan'] ?? 0,
        ];

        return view('kelurahan.riwayat', compact('data', 'statistik'));
    }

    public function exportExcel(Request $request)
    {
        $filter = [
            'status' => $request->status,
            'dari'   => $request->dari,
            'sampai' => $request->sampai,
            'cari'   => $request->cari,
        ];

        $filename = 'Laporan-Surat-' . now()->format('Ymd-His') . '.xlsx';

        return Excel::download(new SuratDomisiliExport($filter), $filename);
    }

    public function rekapPdf(Request $request)
    {
        $periode = $request->periode ?? 'bulan_ini';
        $sertakanFoto = $request->boolean('foto', false);

        switch ($periode) {
            case 'hari_ini':
                $dari   = now()->startOfDay();
                $sampai = now()->endOfDay();
                $label  = 'Hari Ini (' . now()->translatedFormat('d F Y') . ')';
                break;
            case 'minggu_ini':
                $dari   = now()->startOfWeek();
                $sampai = now()->endOfWeek();
                $label  = 'Minggu Ini (' . $dari->translatedFormat('d M') . ' - ' . $sampai->translatedFormat('d M Y') . ')';
                break;
            case 'bulan_lalu':
                $dari   = now()->subMonth()->startOfMonth();
                $sampai = now()->subMonth()->endOfMonth();
                $label  = 'Bulan ' . now()->subMonth()->translatedFormat('F Y');
                break;
            case 'custom':
                $dari   = \Carbon\Carbon::parse($request->dari)->startOfDay();
                $sampai = \Carbon\Carbon::parse($request->sampai)->endOfDay();
                $label  = $dari->translatedFormat('d M Y') . ' - ' . $sampai->translatedFormat('d M Y');
                break;
            case 'bulan_ini':
            default:
                $dari   = now()->startOfMonth();
                $sampai = now()->endOfMonth();
                $label  = 'Bulan ' . now()->translatedFormat('F Y');
                break;
        }

        $data = SuratDomisili::with('warga')
            ->whereBetween('created_at', [$dari, $sampai])
            ->latest()
            ->get();

        $statistik = [
            'total'    => $data->count(),
            'selesai'  => $data->where('status', 'selesai')->count(),
            'diproses' => $data->whereIn('status', ['diajukan_ke_rt', 'diverifikasi_rt', 'diproses_kelurahan'])->count(),
            'ditolak'  => $data->whereIn('status', ['ditolak_rt', 'ditolak_kelurahan'])->count(),
        ];

        $fotoData = [];
        if ($sertakanFoto) {
            foreach ($data as $s) {
                $fotoData[$s->id] = [
                    'ktp' => $this->fileToBase64($s->ktp_path),
                    'kk'  => $this->fileToBase64($s->kk_path),
                ];
            }
        }

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.rekap', [
            'data'         => $data,
            'statistik'    => $statistik,
            'label'        => $label,
            'dari'         => $dari,
            'sampai'       => $sampai,
            'sertakanFoto' => $sertakanFoto,
            'fotoData'     => $fotoData,
        ]);

        $pdf->setPaper('A4', 'landscape');

        ActivityLogger::log(
            'rekap_pdf',
            "Kelurahan mencetak rekap PDF periode {$label}" . ($sertakanFoto ? ' (dengan foto)' : ''),
            null
        );

        $filename = 'Rekap-Surat-' . $dari->format('Ymd') . '-' . $sampai->format('Ymd') . '.pdf';
        return $pdf->download($filename);
    }

    private function fileToBase64(?string $relativePath): ?string
    {
        if (!$relativePath) {
            return null;
        }

        $fullPath = storage_path('app/private/' . $relativePath);

        if (!file_exists($fullPath)) {
            return null;
        }

        $mime = mime_content_type($fullPath);
        if (!in_array($mime, ['image/jpeg', 'image/jpg', 'image/png'], true)) {
            return null;
        }

        if (filesize($fullPath) > 3 * 1024 * 1024) {
            return null;
        }

        return 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($fullPath));
    }

    public function prosesKelurahan(Request $request, SuratDomisili $surat)
    {
        $request->validate([
            'aksi'        => 'required|in:proses,selesai,tolak',
            'nomor_surat' => 'nullable|string|max:50',
            'catatan'     => 'required_if:aksi,tolak|nullable|string|max:500',
        ], [
            'catatan.required_if' => 'Alasan penolakan wajib diisi.',
        ]);

        $statusMap = [
            'proses'  => 'diproses_kelurahan',
            'selesai' => 'selesai',
            'tolak'   => 'ditolak_kelurahan',
        ];

        $dataLama = ['status' => $surat->status];

        $surat->status                        = $statusMap[$request->aksi];
        $surat->nomor_surat                   = $request->nomor_surat ?? $surat->nomor_surat;
        $surat->catatan_kelurahan             = $request->catatan;
        $surat->kelurahan_processed_by        = auth()->id();
        $surat->kelurahan_processed_at        = now();
        $surat->notif_diverifikasi_rt_read_at = now();

        if ($request->aksi === 'tolak') {
            $surat->notif_ditolak_rt_read_at = null;
        }

        $surat->save();

        Cache::forget('landing_statistik');
        Cache::forget('kelurahan_statistik');

        ActivityLogger::log(
            'proses_kelurahan',
            "Kelurahan {$request->aksi} surat {$surat->nomor_pengajuan}",
            $surat,
            $dataLama,
            ['status' => $statusMap[$request->aksi]]
        );

        return back()->with('success', 'Status surat berhasil diperbarui.');
    }

    public function lihatFile(SuratDomisili $surat, string $jenis)
    {
        $user = auth()->user();

        $path = match ($jenis) {
            'ktp'  => $surat->ktp_path,
            'kk'   => $surat->kk_path,
            'foto' => $surat->foto_path,
            default => null,
        };

        if (!$path) {
            abort(404, 'File tidak ditemukan.');
        }

        $bolehAkses = false;

        if ($user->id === $surat->user_id) {
            $bolehAkses = true;
        } elseif ($user->role === 'rt' && $user->rt === $surat->warga->rt) {
            $bolehAkses = true;
        } elseif (in_array($user->role, ['kelurahan', 'admin'], true)) {
            $bolehAkses = true;
        }

        if (!$bolehAkses) {
            abort(403, 'Anda tidak berhak melihat file ini.');
        }

        $fullPath = storage_path('app/private/' . $path);

        if (!file_exists($fullPath)) {
            abort(404, 'File tidak ada di server.');
        }

        return response()->file($fullPath, [
            'Cache-Control' => 'private, max-age=86400',
        ]);
    }

    public function cetakSurat(SuratDomisili $surat)
    {
        if ($surat->status !== 'selesai') {
            abort(403, 'Surat belum selesai diproses.');
        }

        if (!in_array(auth()->user()->role, ['kelurahan', 'admin'], true)) {
            abort(403, 'Hanya kelurahan yang boleh mencetak surat.');
        }

        if (!$surat->nomor_surat) {
            $surat->update([
                'nomor_surat' => '470/' . str_pad($surat->id, 4, '0', STR_PAD_LEFT)
                                 . '/' . now()->format('Y'),
            ]);
        }

        $verifyUrl = route('verifikasi.publik', ['nomor' => $surat->nomor_pengajuan]);

        $qrCode = \SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')
            ->size(200)
            ->margin(1)
            ->errorCorrection('H')
            ->generate($verifyUrl);

        $qrBase64 = 'data:image/svg+xml;base64,' . base64_encode($qrCode);

        $keperluan = strtolower($surat->keperluan);

        if (str_contains($keperluan, 'sktm') || str_contains($keperluan, 'tidak mampu')) {
            $view = 'pdf.sktm';
            $jenisLabel = 'SKTM';
        } elseif (str_contains($keperluan, 'sku') || str_contains($keperluan, 'usaha')) {
            $view = 'pdf.sku';
            $jenisLabel = 'SKU';
        } else {
            $view = 'pdf.domisili';
            $jenisLabel = 'Domisili';
        }

        $lurah = \App\Models\User::find($surat->kelurahan_processed_by);
        if (!$lurah) {
            $lurah = \App\Models\User::where('role', 'kelurahan')->first();
        }

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView($view, [
            'surat'     => $surat->load('warga', 'kelurahanProcessor'),
            'qrBase64'  => $qrBase64,
            'verifyUrl' => $verifyUrl,
            'lurah'     => $lurah,
        ]);

        $pdf->setPaper('A4', 'portrait');

        ActivityLogger::log(
            'cetak_pdf',
            "Kelurahan mencetak PDF surat {$jenisLabel} {$surat->nomor_pengajuan}",
            $surat
        );

        $filename = 'Surat-' . $jenisLabel . '-' . $surat->nomor_pengajuan . '.pdf';
        return $pdf->download($filename);
    }

    public function verifikasiPublik(string $nomor)
    {
        $surat = SuratDomisili::with('warga', 'rtVerifier', 'kelurahanProcessor')
            ->where('nomor_pengajuan', $nomor)
            ->first();

        return view('verifikasi.show', compact('surat', 'nomor'));
    }

    private function authorizeRt(SuratDomisili $surat): void
    {
        if (auth()->user()->rt !== $surat->warga->rt) {
            abort(403, 'Anda tidak berhak memproses surat ini.');
        }
    }
}