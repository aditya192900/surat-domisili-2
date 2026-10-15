<?php

namespace App\Exports;

use App\Models\SuratDomisili;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class SuratDomisiliExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithEvents, WithTitle
{
    protected array $filter;

    public function __construct(array $filter = [])
    {
        $this->filter = $filter;
    }

    public function collection(): Collection
    {
        $query = SuratDomisili::with('warga', 'rtVerifier', 'kelurahanProcessor')
            ->whereIn('status', [
                'diajukan_ke_rt',
                'diverifikasi_rt',
                'diproses_kelurahan',
                'selesai',
                'ditolak_rt',
                'ditolak_kelurahan',
            ]);

        if (!empty($this->filter['status'])) {
            $query->where('status', $this->filter['status']);
        }

        if (!empty($this->filter['dari']) && !empty($this->filter['sampai'])) {
            $query->whereBetween('created_at', [
                $this->filter['dari'] . ' 00:00:00',
                $this->filter['sampai'] . ' 23:59:59',
            ]);
        }

        if (!empty($this->filter['cari'])) {
            $cari = $this->filter['cari'];
            $query->where(function ($q) use ($cari) {
                $q->where('nomor_pengajuan', 'like', "%{$cari}%")
                  ->orWhere('nomor_surat', 'like', "%{$cari}%")
                  ->orWhereHas('warga', function ($w) use ($cari) {
                      $w->where('name', 'like', "%{$cari}%");
                  });
            });
        }

        return $query->latest()->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Nomor Pengajuan',
            'Nomor Surat',
            'Tanggal Diajukan',
            'Nama Pemohon',
            'NIK',
            'Alamat',
            'RT/RW',
            'Jenis Layanan',
            'Status',
            'Tanggal Verifikasi RT',
            'Petugas Verifikasi RT',
            'Tanggal Proses Kelurahan',
            'Petugas Kelurahan',
            'Catatan RT',
            'Catatan Kelurahan',
        ];
    }

    public function map($surat): array
    {
        static $no = 0;
        $no++;

        $statusLabel = [
            'diajukan_ke_rt'     => 'Menunggu RT',
            'diverifikasi_rt'    => 'Diverifikasi RT',
            'diproses_kelurahan' => 'Diproses Kelurahan',
            'selesai'            => 'Selesai',
            'ditolak_rt'         => 'Ditolak RT',
            'ditolak_kelurahan'  => 'Ditolak Kelurahan',
        ][$surat->status] ?? $surat->status;

        return [
            $no,
            $surat->nomor_pengajuan,
            $surat->nomor_surat ?? '-',
            $surat->created_at->format('d/m/Y H:i'),
            $surat->warga->name ?? '-',
            $surat->warga->nik ?? '-',
            $surat->warga->alamat ?? '-',
            ($surat->warga->rt ?? '-') . ' / ' . ($surat->warga->rw ?? '-'),
            $surat->keperluan,
            $statusLabel,
            $surat->rt_verified_at ? $surat->rt_verified_at->format('d/m/Y H:i') : '-',
            $surat->rtVerifier->name ?? '-',
            $surat->kelurahan_processed_at ? $surat->kelurahan_processed_at->format('d/m/Y H:i') : '-',
            $surat->kelurahanProcessor->name ?? '-',
            $surat->catatan_rt ?? '-',
            $surat->catatan_kelurahan ?? '-',
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $highestRow = $sheet->getHighestRow();
                $highestColumn = $sheet->getHighestColumn();

                // Style seluruh sel data: border + alignment
                $sheet->getStyle("A1:{$highestColumn}{$highestRow}")->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => 'D1D5DB'],
                        ],
                    ],
                    'alignment' => [
                        'vertical'   => Alignment::VERTICAL_CENTER,
                        'wrapText'   => true,
                    ],
                ]);

                // Header: biru + teks putih + bold
                $sheet->getStyle("A1:{$highestColumn}1")->applyFromArray([
                    'font' => [
                        'bold'  => true,
                        'color' => ['rgb' => 'FFFFFF'],
                        'size'  => 11,
                    ],
                    'fill' => [
                        'fillType'   => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '2563EB'],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical'   => Alignment::VERTICAL_CENTER,
                    ],
                ]);

                // Tinggi header
                $sheet->getRowDimension(1)->setRowHeight(30);

                // Freeze baris pertama (header tetap terlihat saat scroll)
                $sheet->freezePane('A2');

                // Autofilter: header punya tombol dropdown untuk filter/sort
                $sheet->setAutoFilter("A1:{$highestColumn}{$highestRow}");

                // Alignment khusus kolom "No" (kolom A) rata tengah
                $sheet->getStyle("A2:A{$highestRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // Striping baris (baris genap abu-abu muda)
                for ($row = 2; $row <= $highestRow; $row++) {
                    if ($row % 2 == 0) {
                        $sheet->getStyle("A{$row}:{$highestColumn}{$row}")->applyFromArray([
                            'fill' => [
                                'fillType' => Fill::FILL_SOLID,
                                'startColor' => ['rgb' => 'F9FAFB'],
                            ],
                        ]);
                    }
                }
            },
        ];
    }

    public function title(): string
    {
        return 'Laporan Surat';
    }
}