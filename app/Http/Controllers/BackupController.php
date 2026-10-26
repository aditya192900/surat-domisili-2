<?php

namespace App\Http\Controllers;

use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class BackupController extends Controller
{
    private string $backupPath;

    public function __construct()
    {
        $this->backupPath = storage_path('app/backups');
        if (!File::exists($this->backupPath)) {
            File::makeDirectory($this->backupPath, 0755, true);
        }
    }

    public function index()
    {
        $files = collect(File::files($this->backupPath))
            ->map(function ($file) {
                return [
                    'name'       => $file->getFilename(),
                    'size'       => $file->getSize(),
                    'size_human' => $this->humanSize($file->getSize()),
                    'created_at' => \Carbon\Carbon::createFromTimestamp($file->getMTime()),
                ];
            })
            ->sortByDesc('created_at')
            ->values();

        $totalSize = $files->sum('size');

        return view('kelurahan.backup.index', compact('files', 'totalSize'));
    }

    public function create(Request $request)
    {
        try {
            $filename = 'backup-' . now()->format('Y-m-d_His') . '.sql';
            $path = $this->backupPath . '/' . $filename;

            $db = config('database.connections.mysql');

            // 🚀 Koneksi via PDO (tanpa mysqldump)
            $dsn = "mysql:host={$db['host']};port={$db['port']};dbname={$db['database']};charset=utf8mb4";
            $pdo = new \PDO($dsn, $db['username'], $db['password'], [
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            ]);

            // Header backup
            $sql  = "-- ================================================\n";
            $sql .= "-- Backup Database: {$db['database']}\n";
            $sql .= "-- Generated: " . now()->format('d F Y, H:i:s') . " WIB\n";
            $sql .= "-- By: " . auth()->user()->name . "\n";
            $sql .= "-- ================================================\n\n";
            $sql .= "SET FOREIGN_KEY_CHECKS=0;\n";
            $sql .= "SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';\n\n";

            // Ambil semua tabel
            $tables = $pdo->query("SHOW TABLES")->fetchAll(\PDO::FETCH_COLUMN);

            if (empty($tables)) {
                throw new \Exception('Tidak ada tabel di database.');
            }

            foreach ($tables as $table) {
                $sql .= "\n-- ------------------------------------------------\n";
                $sql .= "-- Struktur tabel `{$table}`\n";
                $sql .= "-- ------------------------------------------------\n";
                $sql .= "DROP TABLE IF EXISTS `{$table}`;\n";

                // Struktur tabel
                $createTable = $pdo->query("SHOW CREATE TABLE `{$table}`")->fetch(\PDO::FETCH_ASSOC);
                $sql .= $createTable['Create Table'] . ";\n\n";

                // Data tabel
                $rows = $pdo->query("SELECT * FROM `{$table}`")->fetchAll(\PDO::FETCH_ASSOC);

                if (count($rows) > 0) {
                    $sql .= "-- Data `{$table}` (" . count($rows) . " baris)\n";

                    foreach ($rows as $row) {
                        $columns = array_map(fn($col) => "`{$col}`", array_keys($row));
                        $values = array_map(function ($val) use ($pdo) {
                            if ($val === null) return 'NULL';
                            return $pdo->quote($val);
                        }, array_values($row));

                        $sql .= "INSERT INTO `{$table}` (" . implode(', ', $columns) . ") VALUES (" . implode(', ', $values) . ");\n";
                    }
                    $sql .= "\n";
                }
            }

            $sql .= "\nSET FOREIGN_KEY_CHECKS=1;\n";
            $sql .= "-- Backup selesai: " . now()->format('d F Y, H:i:s') . " WIB\n";

            File::put($path, $sql);

            $size = $this->humanSize(File::size($path));

            ActivityLogger::log(
                'backup_database',
                "Admin membuat backup database: {$filename} ({$size}) - " . count($tables) . " tabel",
                null
            );

            return back()->with('success', "Backup berhasil: {$filename} ({$size}) — " . count($tables) . " tabel");
        } catch (\Exception $e) {
            return back()->with('error', 'Backup gagal: ' . $e->getMessage());
        }
    }

    public function download(string $filename)
    {
        $filename = basename($filename);
        $path = $this->backupPath . '/' . $filename;

        if (!File::exists($path)) {
            abort(404, 'File backup tidak ditemukan.');
        }

        return response()->download($path);
    }

    public function destroy(string $filename)
    {
        $filename = basename($filename);
        $path = $this->backupPath . '/' . $filename;

        if (File::exists($path)) {
            File::delete($path);

            ActivityLogger::log(
                'hapus_backup',
                "Admin menghapus backup database: {$filename}",
                null
            );

            return back()->with('success', "Backup {$filename} berhasil dihapus.");
        }

        return back()->with('error', 'File backup tidak ditemukan.');
    }

    private function humanSize(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $i = 0;
        while ($bytes >= 1024 && $i < count($units) - 1) {
            $bytes /= 1024;
            $i++;
        }
        return round($bytes, 2) . ' ' . $units[$i];
    }
}