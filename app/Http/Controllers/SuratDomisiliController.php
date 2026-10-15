<?php

namespace App\Http\Controllers;

use App\Exports\SuratDomisiliExport;
use App\Models\SuratDomisili;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
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

        $statistik = [
            'total'   => SuratDomisili::where('user_id', $userId)->count(),
            'proses'  => SuratDomisili::where('user_id', $userId)
                ->whereIn('status', ['diajukan_ke_rt', 'diverifikasi_rt', 'diproses_kelurahan'])
                ->count(),
            'selesai' => SuratDomisili::where('user_id', $userId)
                ->where('status', 'selesai')
                ->count(),
            'ditolak' => SuratDomisili::where('user_id', $userId)
                ->whereIn('status', ['ditolak_rt', 'ditolak_kelurahan'])
                ->count(),
        ];

        $terbaru = SuratDomisili::where('user_id', $userId)
            ->latest()
            ->take(3)
            ->get();

        $sedangDiproses = SuratDomisili::where('user_id', $userId)
            ->whereIn('status', ['diajukan_ke_rt', 'diverifikasi_rt', 'diproses_kelurahan'])
            ->count();

        return view('warga.dashboard', compact('statistik', 'terbaru', 'sedangDiproses'));
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

        $surat->load('warga', 'rtVerifier', 'kelurahanProcessor');

        return view('surat.detail', compact('surat'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'jenis_surat' => 'required|string|in:Surat Keterangan Domisili,Surat Keterangan Tidak Mampu (SKTM),Surat Keterangan Usaha (SKU)',
            'keperluan'   => 'nullable|string|max:255',
            'ktp'         => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'kk'          => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'foto'        => 'required|file|mimes:jpg,jpeg,png|max:2048',
        ], [
            'foto.required' => 'Pas foto wajib diupload.',
            'foto.mimes'    => 'Pas foto harus berformat JPG atau PNG.',
        ]);

        return DB::transaction(function () use ($request) {
            $ktp  = $request->file('ktp')->store('private/ktp');
            $kk   = $request->file('kk')->store('private/kk');
            $foto = $request->file('foto')->store('private/foto');

            $keperluan = $request->jenis_surat;
            if ($request->filled('keperluan')) {
                $keperluan .= ' — ' . $request->keperluan;
            }

            $surat = SuratDomisili::create([
                'nomor_pengajuan' => 'SD-' . now()->format('Ymd') . '-' . strtoupper(Str::random(6)),
                'user_id'         => auth()->id(),
                'keperluan'       => $keperluan,
                'ktp_path'        => $ktp,
                'kk_path'         => $kk,
                'foto_path'       => $foto,
                'status'          => 'diajukan_ke_rt',
            ]);

            ActivityLogger::log(
                'ajukan_surat',
                "Warga mengajukan surat {$surat->nomor_pengajuan}",
                $surat,
                null,
                ['status' => 'diajukan_ke_rt']
            );

            return redirect()->route('warga.surat.index')
                ->with('success', "Pengajuan {$surat->nomor_pengajuan} berhasil dikirim ke RT.");
        });
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

        $baseQuery = SuratDomisili::whereHas('warga', function ($q) use ($rt) {
            $q->where('rt', $rt);
        });

        $statistik = [
            'diverifikasi_rt'    => (clone $baseQuery)->where('status', 'diverifikasi_rt')->count(),
            'ditolak_rt'         => (clone $baseQuery)->where('status', 'ditolak_rt')->count(),
            'diproses_kelurahan' => (clone $baseQuery)->where('status', 'diproses_kelurahan')->count(),
            'selesai'            => (clone $baseQuery)->where('status', 'selesai')->count(),
            'ditolak_kelurahan'  => (clone $baseQuery)->where('status', 'ditolak_kelurahan')->count(),
        ];

        return view('rt.riwayat', compact('data', 'statistik'));
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
                'status'         => 'diverifikasi_rt',
                'rt_verified_by' => auth()->id(),
                'rt_verified_at' => now(),
                'catatan_rt'     => $request->catatan,
            ]);
        } else {
            $surat->update([
                'status'         => 'ditolak_rt',
                'rt_verified_by' => auth()->id(),
                'rt_verified_at' => now(),
                'catatan_rt'     => $request->catatan,
            ]);
        }

        ActivityLogger::log(
            'verifikasi_rt',
            "RT {$request->aksi} surat {$surat->nomor_pengajuan}",
            $surat,
            $dataLama,
            $dataBaru
        );

        return back()->with('success', 'Verifikasi RT berhasil disimpan.');
    }

    public function cetakSuratPengantar(SuratDomisili $surat)
    {
        $user = auth()->user();

        if ($user->role !== 'rt') {
            abort(403, 'Hanya RT yang boleh mencetak surat pengantar.');
        }

        if ($user->rt !== $surat->warga->rt) {
            abort(403, 'Anda tidak berhak mencetak surat pengantar ini.');
        }

        if (!$surat->rt_verified_at || $surat->status === 'ditolak_rt') {
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
            "RT mencetak Surat Pengantar untuk {$surat->nomor_pengajuan}",
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
            ->get();

        $statistik = [
            'baru'     => SuratDomisili::where('status', 'diverifikasi_rt')->count(),
            'diproses' => SuratDomisili::where('status', 'diproses_kelurahan')->count(),
            'selesai'  => SuratDomisili::where('status', 'selesai')->count(),
            'ditolak'  => SuratDomisili::whereIn('status', ['ditolak_rt', 'ditolak_kelurahan'])->count(),
        ];

        return view('kelurahan.index', compact('data', 'suratBaru', 'statistik'));
    }

    public function dashboardKelurahan()
    {
        $statistik = [
            'total'              => SuratDomisili::count(),
            'diajukan_ke_rt'     => SuratDomisili::where('status', 'diajukan_ke_rt')->count(),
            'diverifikasi_rt'    => SuratDomisili::where('status', 'diverifikasi_rt')->count(),
            'diproses_kelurahan' => SuratDomisili::where('status', 'diproses_kelurahan')->count(),
            'selesai'            => SuratDomisili::where('status', 'selesai')->count(),
            'ditolak_rt'         => SuratDomisili::where('status', 'ditolak_rt')->count(),
            'ditolak_kelurahan'  => SuratDomisili::where('status', 'ditolak_kelurahan')->count(),
        ];

        $suratSelesai = SuratDomisili::where('status', 'selesai')
            ->whereNotNull('created_at')
            ->whereNotNull('kelurahan_processed_at')
            ->get();

        $totalJam = 0;
        $jumlahSurat = 0;
        foreach ($suratSelesai as $s) {
            $totalJam += $s->created_at->diffInHours($s->kelurahan_processed_at);
            $jumlahSurat++;
        }
        $rataRataJam = $jumlahSurat > 0 ? round($totalJam / $jumlahSurat, 1) : 0;

        $terbaru = SuratDomisili::with('warga')
            ->latest()
            ->take(10)
            ->get();

        return view('kelurahan.dashboard', compact('statistik', 'rataRataJam', 'terbaru'));
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

        $statistik = [
            'total'              => SuratDomisili::count(),
            'diverifikasi_rt'    => SuratDomisili::where('status', 'diverifikasi_rt')->count(),
            'diproses_kelurahan' => SuratDomisili::where('status', 'diproses_kelurahan')->count(),
            'selesai'            => SuratDomisili::where('status', 'selesai')->count(),
            'ditolak_rt'         => SuratDomisili::where('status', 'ditolak_rt')->count(),
            'ditolak_kelurahan'  => SuratDomisili::where('status', 'ditolak_kelurahan')->count(),
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

        $surat->status                 = $statusMap[$request->aksi];
        $surat->nomor_surat            = $request->nomor_surat ?? $surat->nomor_surat;
        $surat->catatan_kelurahan      = $request->catatan;
        $surat->kelurahan_processed_by = auth()->id();
        $surat->kelurahan_processed_at = now();

        if ($request->aksi === 'tolak') {
            $surat->notif_ditolak_rt_read_at = null;
        }

        $surat->save();

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

        return response()->file($fullPath);
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