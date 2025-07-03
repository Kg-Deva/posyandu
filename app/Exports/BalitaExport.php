<?php

namespace App\Exports;

use App\Models\PemeriksaanBalita;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class BalitaExport implements FromCollection, WithHeadings, WithMapping, WithEvents
{
    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $query = PemeriksaanBalita::with('user');

        // Terapkan semua filter
        if (!empty($this->filters['tahun'])) {
            $query->whereYear('tanggal_pemeriksaan', $this->filters['tahun']);
        }
        if (!empty($this->filters['bulan'])) {
            $query->whereMonth('tanggal_pemeriksaan', $this->filters['bulan']);
        }
        if (!empty($this->filters['rw'])) {
            $query->whereHas('user', function ($q) {
                $q->where('rw', $this->filters['rw']);
            });
        }
        if (!empty($this->filters['rujukan'])) {
            if ($this->filters['rujukan'] === 'Perlu Rujukan') {
                $query->where('rujuk_puskesmas', 'Perlu Rujukan');
            } else if ($this->filters['rujukan'] === 'Tidak Perlu Rujukan' || $this->filters['rujukan'] === 'Normal') {
                $query->where('rujuk_puskesmas', '!=', 'Perlu Rujukan');
            }
        }
        if (!empty($this->filters['search'])) {
            $search = $this->filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('nik', 'LIKE', "%{$search}%")
                    ->orWhereHas('user', function ($subQ) use ($search) {
                        $subQ->where('nama', 'LIKE', "%{$search}%");
                    });
            });
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'NO',
            'NAMA',
            'NOMOR NIK ANAK',
            'TANGGAL LAHIR',
            'JENIS KELAMIN',
            'NAMA IBU',
            'NO. HP',
            'ALAMAT',
            'USIA ANAK SAAT INI (bulan)',
            'PENIMBANGAN/PENGUKURAN - BB',
            'PENIMBANGAN/PENGUKURAN - TB',
            'PENIMBANGAN/PENGUKURAN - LILA',
            'PENIMBANGAN/PENGUKURAN - LIKA',
            'BERAT BADAN/UMUR - Status BB',
            'BERAT BADAN/UMUR - Gizi',
            'Hasil Pengukuran PB/TB/Umur',
            'Hasil Pengukuran BB/PB atau BB/TB',
            'Hasil Pengukuran Lingkar Kepala',
            'Lingkar Lengan Atas',
            'Bergejala TB',
            'ASI EKSKLUSIF 0-6bln',
            'MP ASI >6bln sesuai'
        ];
    }

    public function map($pemeriksaan): array
    {
        static $no = 0;
        $no++;

        return [
            $no,
            $pemeriksaan->user->nama ?? '',
            $pemeriksaan->nik,
            $pemeriksaan->user->tanggal_lahir ?? '',
            $pemeriksaan->user->jenis_kelamin ?? '',
            $pemeriksaan->user->nama_ibu ?? '', // Jika ada field ini
            $pemeriksaan->user->no_hp ?? '',
            $pemeriksaan->user->alamat ?? '',
            $pemeriksaan->umur,
            $pemeriksaan->bb,
            $pemeriksaan->tb,
            $pemeriksaan->lila,
            $pemeriksaan->lingkar_kepala,
            $pemeriksaan->status_perubahan_bb ?? '',
            $pemeriksaan->kesimpulan_bbu ?? '',
            $pemeriksaan->kesimpulan_tbuu ?? '',
            $pemeriksaan->kesimpulan_bbtb ?? '',
            $pemeriksaan->kesimpulan_lingkar_kepala ?? '',
            $pemeriksaan->kesimpulan_lila ?? '',
            $this->formatGejalaaTB($pemeriksaan),
            $pemeriksaan->asi_eksklusif ? 'YA' : 'TIDAK',
            $pemeriksaan->mp_asi ? 'YA' : 'TIDAK'
        ];
    }

    private function formatGejalaaTB($pemeriksaan)
    {
        $gejalaCount = 0;

        if ($pemeriksaan->batuk_terus_menerus) $gejalaCount++;
        if ($pemeriksaan->demam_2_minggu) $gejalaCount++;
        if ($pemeriksaan->bb_tidak_naik) $gejalaCount++;
        if ($pemeriksaan->kontak_tbc) $gejalaCount++;

        if ($gejalaCount >= 2) {
            return 'YA (2 Gejala)';
        } else if ($gejalaCount >= 1) {
            return 'YA (1 Gejala)';
        } else {
            return 'TIDAK';
        }
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Styling header
                $sheet->getStyle('A1:V1')->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'color' => ['rgb' => 'FFB6C1'] // Pink sesuai design
                    ],
                    'font' => [
                        'bold' => true,
                        'size' => 10,
                        'color' => ['rgb' => '000000']
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                        'wrapText' => true
                    ]
                ]);

                // Border untuk semua data
                $highestRow = $sheet->getHighestRow();
                $sheet->getStyle("A1:V$highestRow")->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => '000000']
                        ]
                    ]
                ]);

                // Set tinggi header
                $sheet->getRowDimension('1')->setRowHeight(40);

                // Auto width untuk kolom
                foreach (range('A', 'V') as $column) {
                    $sheet->getColumnDimension($column)->setAutoSize(true);
                }

                // Set width khusus untuk kolom tertentu
                $sheet->getColumnDimension('B')->setWidth(20); // Nama
                $sheet->getColumnDimension('C')->setWidth(18); // NIK
                $sheet->getColumnDimension('H')->setWidth(25); // Alamat
            }
        ];
    }
}
