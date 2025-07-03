<?php

namespace App\Exports;

use App\Models\PemeriksaanBalita;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Carbon\Carbon;

class RekapBalitaExport implements FromArray, WithEvents
{
    protected $filters;
    protected $rekap;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
        $this->rekap = $this->generateRekap();
    }

    protected function generateRekap()
    {
        $query = PemeriksaanBalita::with('user');

        // Terapkan filter
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
            } else if ($this->filters['rujukan'] === 'Normal') {
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

        $data = $query->get();

        // Header sesuai design
        $result = [];

        // Baris 1: Header utama dengan merge
        $result[] = [
            'DESA',
            'POSYANDU',
            'NAMA POSYANDU',
            'JUMLAH KADER',
            'JUMLAH BALITA',
            'DITIMBANG',
            '',
            '',
            'PUNYA KMS',
            '',
            'NAIK BB',
            '',
            'TIDAK NAIK BB',
            '',
            'BGM',
            '',
            'KURUS',
            '',
            'GEMUK',
            '',
            'STUNTING',
            '',
            'GIZI BURUK',
            '',
            'VITAMIN A',
            '',
            'DIRUJUK',
            'KET'
        ];

        // Baris 2: Sub header
        $result[] = [
            '',
            '',
            '',
            '',
            '',
            'L',
            'P',
            'JML',
            'L',
            'P',
            'L',
            'P',
            'L',
            'P',
            'L',
            'P',
            'L',
            'P',
            'L',
            'P',
            'L',
            'P',
            'L',
            'P',
            'MERAH',
            'BIRU',
            '',
            ''
        ];

        // Data rekap berdasarkan bulan/tahun dari tanggal_pemeriksaan
        $rekapData = $this->processRekapData($data);
        foreach ($rekapData as $row) {
            $result[] = $row;
        }

        return $result;
    }

    protected function processRekapData($data)
    {
        $rekap = [];

        // Group by bulan-tahun dari tanggal_pemeriksaan
        $groupedByPeriod = $data->groupBy(function ($item) {
            $date = Carbon::parse($item->tanggal_pemeriksaan);
            return $date->format('Y-m'); // Format: 2025-01
        });

        foreach ($groupedByPeriod as $period => $items) {
            $date = Carbon::createFromFormat('Y-m', $period);
            $bulanTahun = $date->format('M Y'); // Jan 2025

            $total = $items->count();
            $laki = $items->where('user.jenis_kelamin', 'L')->count();
            $perempuan = $items->where('user.jenis_kelamin', 'P')->count();

            // Hitung status gizi berdasarkan field yang ada
            $naikBBL = $items->where('user.jenis_kelamin', 'L')->where('bb', '>', 0)->count();
            $naikBBP = $items->where('user.jenis_kelamin', 'P')->where('bb', '>', 0)->count();

            $rujukan = $items->where('rujuk_puskesmas', 'Perlu Rujukan')->count();

            $rekap[] = [
                'Desa ABC', // Sesuaikan dengan data desa
                'Posyandu 1', // Sesuaikan dengan data posyandu
                'Posyandu Melati', // Nama posyandu
                '5', // Jumlah kader
                $total, // Jumlah balita
                $laki, // Ditimbang L
                $perempuan, // Ditimbang P
                $total, // Ditimbang JML
                $laki, // Punya KMS L
                $perempuan, // Punya KMS P
                $naikBBL, // Naik BB L
                $naikBBP, // Naik BB P
                $laki - $naikBBL, // Tidak Naik BB L
                $perempuan - $naikBBP, // Tidak Naik BB P
                '0', // BGM L
                '0', // BGM P
                '0', // Kurus L
                '0', // Kurus P
                '0', // Gemuk L
                '0', // Gemuk P
                '0', // Stunting L
                '0', // Stunting P
                '0', // Gizi Buruk L
                '0', // Gizi Buruk P
                '0', // Vitamin A Merah
                '0', // Vitamin A Biru
                $rujukan, // Dirujuk
                $bulanTahun // Periode (Bulan/Tahun)
            ];
        }

        return $rekap;
    }

    public function array(): array
    {
        return $this->rekap;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Merge cells sesuai design
                $sheet->mergeCells('F1:H1'); // DITIMBANG
                $sheet->mergeCells('I1:J1'); // PUNYA KMS
                $sheet->mergeCells('K1:L1'); // NAIK BB
                $sheet->mergeCells('M1:N1'); // TIDAK NAIK BB
                $sheet->mergeCells('O1:P1'); // BGM
                $sheet->mergeCells('Q1:R1'); // KURUS
                $sheet->mergeCells('S1:T1'); // GEMUK
                $sheet->mergeCells('U1:V1'); // STUNTING
                $sheet->mergeCells('W1:X1'); // GIZI BURUK
                $sheet->mergeCells('Y1:Z1'); // VITAMIN A

                // Styling header utama
                $sheet->getStyle('A1:AB1')->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'color' => ['rgb' => 'FFB6C1'] // Pink muda sesuai design
                    ],
                    'font' => [
                        'bold' => true,
                        'size' => 10
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER
                    ]
                ]);

                // Styling sub header
                $sheet->getStyle('A2:AB2')->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'color' => ['rgb' => 'FFB6C1']
                    ],
                    'font' => [
                        'bold' => true,
                        'size' => 9
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER
                    ]
                ]);

                // Border untuk semua cell
                $highestRow = $sheet->getHighestRow();
                $sheet->getStyle("A1:AB$highestRow")->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => '000000']
                        ]
                    ]
                ]);

                // Set tinggi baris
                $sheet->getRowDimension('1')->setRowHeight(25);
                $sheet->getRowDimension('2')->setRowHeight(20);

                // Auto-width untuk kolom tertentu
                foreach (range('A', 'AB') as $column) {
                    $sheet->getColumnDimension($column)->setWidth(8);
                }
            }
        ];
    }
}
