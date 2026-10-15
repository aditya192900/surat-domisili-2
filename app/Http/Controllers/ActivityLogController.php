<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $query = ActivityLog::with('user')->latest();

        // Filter pencarian
        if ($request->filled('cari')) {
            $cari = $request->cari;
            $query->where(function ($q) use ($cari) {
                $q->where('user_name', 'like', "%{$cari}%")
                  ->orWhere('aksi', 'like', "%{$cari}%")
                  ->orWhere('deskripsi', 'like', "%{$cari}%");
            });
        }

        // Filter aksi
        if ($request->filled('aksi')) {
            $query->where('aksi', $request->aksi);
        }

        // Filter role
        if ($request->filled('role')) {
            $query->where('user_role', $request->role);
        }

        // Filter tanggal
        if ($request->filled('dari')) {
            $query->whereDate('created_at', '>=', $request->dari);
        }
        if ($request->filled('sampai')) {
            $query->whereDate('created_at', '<=', $request->sampai);
        }

        $logs = $query->paginate(20)->withQueryString();

        // Data untuk dropdown filter
        $daftarAksi = ActivityLog::distinct()->pluck('aksi')->filter()->sort()->values();
        $daftarRole = ['warga', 'rt', 'kelurahan', 'admin'];

        // Statistik
        $statistik = [
            'total'     => ActivityLog::count(),
            'hari_ini'  => ActivityLog::whereDate('created_at', today())->count(),
            'minggu_ini' => ActivityLog::whereBetween('created_at', [
                now()->startOfWeek(),
                now()->endOfWeek(),
            ])->count(),
            'user_aktif' => ActivityLog::where('created_at', '>=', now()->subDays(7))
                ->distinct('user_id')
                ->count('user_id'),
        ];

        return view('admin.logs', compact('logs', 'daftarAksi', 'daftarRole', 'statistik'));
    }
}