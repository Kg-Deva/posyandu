<?php

namespace App\Exports;

use App\Models\PemeriksaanBalita;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Cell\DataType;

class BalitaExport implements FromCollection, WithHeadings, WithMapping, WithEvents, WithColumnFormatting
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
            // BARIS 1 - ✅ HILANGKAN YANG DI LUAR GRUP
            ['NO', 'NAMA', 'NOMOR NIK ANAK', 'TANGGAL LAHIR', 'JENIS KELAMIN', 'NAMA IBU', 'NO. HP', 'ALAMAT', 'USIA ANAK SAAT INI (bulan)', 'PENIMBANGAN/PENGUKURAN', '', '', '', 'BERAT BADAN/UMUR', '', 'HASIL PENGUKURAN', '', '', '', 'Bergejala TB', 'ASI EKSKLUSIF 0-6bln', 'MP ASI >6bln sesuai', 'TANGGAL IMUNISASI usia 0-59 bln', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 'VIT A', 'OBAT CACING', 'PMT'],

            // BARIS 2 - ✅ HILANGKAN YANG DI LUAR GRUP
            ['', '', '', '', '', '', '', '', '', 'BB', 'TB', 'LILA', 'LIKA', 'Status BB', 'Gizi', 'PB/TB/Umur', 'BB/PB atau BB/TB', 'Lingkar Kepala', 'Lingkar Lengan Atas', '', '', '', 'HB 0', 'BCG', 'POLIO 1', 'DPT-HB-Hib 1', 'POLIO 2', 'PCV 1', 'RV 1', 'DPT-HB-Hib 2', 'POLIO 3', 'PCV 2', 'RV 2', 'DPT-HB-Hib 3', 'POLIO 4', 'IPV 1', 'RV 3', 'Campak-Rubella (MR)', 'IPV 2', 'PCV 3', 'DPT-HB-Hib Lanjutan', 'Campak-Rubella (MR) Lanjutan', '', '', '']
        ];
    }

    // ✅ TAMBAH METHOD INI UNTUK FORMAT KOLOM NIK
    public function columnFormats(): array
    {
        return [
            'C' => NumberFormat::FORMAT_TEXT, // ✅ KOLOM NIK (C) JADI TEXT
        ];
    }

    public function map($pemeriksaan): array
    {
        static $no = 0;
        $no++;

        return [
            $no,
            $pemeriksaan->user->nama ?? '',
            $pemeriksaan->nik,              // NIK ✅ (TANPA APOSTROF, FORMAT TEXT VIA registerEvents)
            $pemeriksaan->user->tanggal_lahir ?? '',
            $pemeriksaan->user->jenis_kelamin ?? '',
            $pemeriksaan->user->nama_ibu ?? '',
            $pemeriksaan->user->no_hp ?? '',
            $pemeriksaan->user->alamat ?? '',
            $pemeriksaan->umur,
            $pemeriksaan->bb,
            $pemeriksaan->tb,
            $pemeriksaan->lila,
            $pemeriksaan->lingkar_kepala,
            $this->formatStatusBB($pemeriksaan),
            $pemeriksaan->kesimpulan_bbu ?? '',
            $pemeriksaan->kesimpulan_tbuu ?? '',
            $pemeriksaan->kesimpulan_bbtb ?? '',
            $pemeriksaan->kesimpulan_lingkar_kepala ?? '',
            $pemeriksaan->kesimpulan_lila ?? '',
            $this->formatGejalaaTB($pemeriksaan),
            $pemeriksaan->asi_eksklusif ? 'YA' : 'TIDAK',
            $pemeriksaan->mp_asi ? 'YA' : 'TIDAK',
            // ✅ KOLOM IMUNISASI KOSONG (W sampai AO = 24 kolom)
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',

            // ✅ KOLOM GRUP TAMBAHAN (AP-AU = 6 kolom)
            $pemeriksaan->vitamin_a ? 'YA' : 'TIDAK',        // AP - VIT A
            $pemeriksaan->obat_cacing ? 'YA' : 'TIDAK',      // AQ - OBAT CACING
            // $pemeriksaan->pmt ? 'YA' : 'TIDAK',              // AR - PMT
            // $pemeriksaan->edukasi ? 'YA' : 'TIDAK',          // AS - EDUKASI
            // $pemeriksaan->sedang_sakit ? 'YA' : 'TIDAK',     // AT - SEDANG SAKIT
            $this->formatPMT($pemeriksaan),                              // AR - PMT ✅ FIX
            $this->formatEdukasi($pemeriksaan),                          // AS - EDUKASI ✅ FIX
            $this->formatSedangSakit($pemeriksaan),
            $this->formatRujukanTBC($pemeriksaan),           // AU - RUJUK ✅ (BERDASARKAN GEJALA TBC)
        ];
    }

    // ✅ TAMBAH METHOD BARU INI
    private function formatStatusBB($pemeriksaan)
    {
        // Cari pemeriksaan sebelumnya untuk anak yang sama
        $pemeriksaanSebelumnya = PemeriksaanBalita::where('nik', $pemeriksaan->nik)
            ->where('tanggal_pemeriksaan', '<', $pemeriksaan->tanggal_pemeriksaan)
            ->orderBy('tanggal_pemeriksaan', 'desc')
            ->first();

        if (!$pemeriksaanSebelumnya) {
            // Ini pemeriksaan pertama
            return "BB Awal: {$pemeriksaan->bb} kg";
        }

        // Hitung perubahan BB
        $selisihBB = $pemeriksaan->bb - $pemeriksaanSebelumnya->bb;
        $statusText = '';

        if ($selisihBB > 0) {
            $statusText = "NAIK +{$selisihBB} kg";
        } elseif ($selisihBB < 0) {
            $statusText = "TURUN {$selisihBB} kg";
        } else {
            $statusText = "STABIL (tidak berubah)";
        }

        return "{$statusText} (dari {$pemeriksaanSebelumnya->bb} kg ke {$pemeriksaan->bb} kg)";
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
    private function formatPMT($pemeriksaan)
    {
        // ✅ SUPER SIMPLE: LANGSUNG BOOLEAN CHECK
        return $pemeriksaan->mt_pangan_lokal ? 'YA' : 'TIDAK';
    }

    private function formatEdukasi($pemeriksaan)
    {
        // ✅ SUPER SIMPLE: LANGSUNG BOOLEAN CHECK
        return $pemeriksaan->mp_asi_protein_hewani ? 'YA' : 'TIDAK';
    }

    private function formatSedangSakit($pemeriksaan)
    {
        // ✅ SUPER SIMPLE: LANGSUNG BOOLEAN CHECK
        return $pemeriksaan->ada_gejala_sakit ? 'YA' : 'TIDAK';
    }
    // ✅ TAMBAH METHOD BARU UNTUK RUJUKAN BERDASARKAN GEJALA TBC
    private function formatRujukanTBC($pemeriksaan)
    {
        $gejalaCount = 0;

        // ✅ HITUNG JUMLAH GEJALA TBC
        if ($pemeriksaan->batuk_terus_menerus) $gejalaCount++;
        if ($pemeriksaan->demam_2_minggu) $gejalaCount++;
        if ($pemeriksaan->bb_tidak_naik) $gejalaCount++;
        if ($pemeriksaan->kontak_tbc) $gejalaCount++;

        // ✅ LOGIC RUJUKAN: ≥2 GEJALA = YA, <2 GEJALA = TIDAK
        if ($gejalaCount >= 2) {
            return 'YA';
        } else {
            return 'TIDAK';
        }
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // ✅ MERGE CELLS UNTUK HEADER UTAMA (BARIS 1)
                $sheet->mergeCells('A1:A2'); // NO
                $sheet->mergeCells('B1:B2'); // NAMA
                $sheet->mergeCells('C1:C2'); // NIK
                $sheet->mergeCells('D1:D2'); // TANGGAL LAHIR
                $sheet->mergeCells('E1:E2'); // JENIS KELAMIN
                $sheet->mergeCells('F1:F2'); // NAMA IBU
                $sheet->mergeCells('G1:G2'); // NO HP
                $sheet->mergeCells('H1:H2'); // ALAMAT
                $sheet->mergeCells('I1:I2'); // USIA

                // ✅ MERGE UNTUK PENIMBANGAN/PENGUKURAN
                $sheet->mergeCells('J1:M1');
                $sheet->setCellValue('J1', 'PENIMBANGAN/PENGUKURAN');

                // ✅ MERGE UNTUK BERAT BADAN/UMUR
                $sheet->mergeCells('N1:O1');
                $sheet->setCellValue('N1', 'BERAT BADAN/UMUR');

                // ✅ MERGE UNTUK HASIL PENGUKURAN
                $sheet->mergeCells('P1:S1');
                $sheet->setCellValue('P1', 'HASIL PENGUKURAN');

                $sheet->mergeCells('T1:T2'); // Bergejala TB
                $sheet->mergeCells('U1:U2'); // ASI EKSKLUSIF
                $sheet->mergeCells('V1:V2'); // MP ASI

                // ✅ MERGE UNTUK IMUNISASI (BERHENTI DI CAMPAK RUBELLA MR LANJUTAN = AO)
                $sheet->mergeCells('W1:AO1');
                $sheet->setCellValue('W1', 'TANGGAL IMUNISASI usia 0-59 bln');

                // ✅ MERGE UNTUK GRUP TAMBAHAN (VIT A - RUJUK)
                $sheet->mergeCells('AP1:AP2'); // VIT A
                $sheet->setCellValue('AP1', 'VIT A');

                $sheet->mergeCells('AQ1:AQ2'); // OBAT CACING
                $sheet->setCellValue('AQ1', 'OBAT CACING');

                $sheet->mergeCells('AR1:AR2'); // PMT
                $sheet->setCellValue('AR1', 'PMT');

                $sheet->mergeCells('AS1:AS2'); // EDUKASI
                $sheet->setCellValue('AS1', 'EDUKASI');

                $sheet->mergeCells('AT1:AT2'); // SEDANG SAKIT
                $sheet->setCellValue('AT1', 'SEDANG SAKIT');

                $sheet->mergeCells('AU1:AU2'); // RUJUK ✅ (BERDASARKAN GEJALA TBC)
                $sheet->setCellValue('AU1', 'RUJUK (≥2 GEJALA TBC)');

                // ✅ SET DETAIL BARIS 2
                $detailHeaders = [
                    'J2' => 'BB',
                    'K2' => 'TB',
                    'L2' => 'LILA',
                    'M2' => 'LIKA',
                    'N2' => 'Status BB',
                    'O2' => 'Gizi',
                    'P2' => 'PB/TB/Umur',
                    'Q2' => 'BB/PB atau BB/TB',
                    'R2' => 'Lingkar Kepala',
                    'S2' => 'Lingkar Lengan Atas',
                    // IMUNISASI (W sampai AO = 24 kolom)
                    'W2' => 'HB 0',
                    'X2' => 'BCG',
                    'Y2' => 'POLIO 1',
                    'Z2' => 'DPT-HB-Hib 1',
                    'AA2' => 'POLIO 2',
                    'AB2' => 'PCV 1',
                    'AC2' => 'RV 1',
                    'AD2' => 'DPT-HB-Hib 2',
                    'AE2' => 'POLIO 3',
                    'AF2' => 'PCV 2',
                    'AG2' => 'RV 2',
                    'AH2' => 'DPT-HB-Hib 3',
                    'AI2' => 'POLIO 4',
                    'AJ2' => 'IPV 1',
                    'AK2' => 'RV 3',
                    'AL2' => 'Campak-Rubella (MR)',
                    'AM2' => 'IPV 2',
                    'AN2' => 'PCV 3',
                    'AO2' => 'DPT-HB-Hib Lanjutan'
                    // ✅ KOLOM AP-AU sudah di-merge vertikal, jadi tidak perlu set baris 2
                ];

                foreach ($detailHeaders as $cell => $value) {
                    $sheet->setCellValue($cell, $value);
                }

                // ✅ STYLING HEADER BARIS 1 - WARNA PINK SERAGAM (KECUALI IMUNISASI)
                // Header utama (A-V) - Pink seragam
                $sheet->getStyle('A1:V1')->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'color' => ['rgb' => 'FFB6C1'] // Pink seragam
                    ],
                    'font' => ['bold' => true, 'size' => 10],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                        'wrapText' => true
                    ]
                ]);

                // ✅ IMUNISASI (W-AO) - HIJAU MUDA (BERHENTI DI CAMPAK RUBELLA MR LANJUTAN)
                $sheet->getStyle('W1:AO1')->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'color' => ['rgb' => '90EE90'] // Hijau muda
                    ],
                    'font' => ['bold' => true, 'size' => 10],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                        'wrapText' => true
                    ]
                ]);

                // ✅ GRUP TAMBAHAN (AP-AU) - PINK SERAGAM DENGAN HEADER UTAMA
                $sheet->getStyle('AP1:AU1')->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'color' => ['rgb' => 'FFB6C1'] // Pink seragam
                    ],
                    'font' => ['bold' => true, 'size' => 10],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                        'wrapText' => true
                    ]
                ]);

                // ✅ STYLING BARIS 2 (SUB HEADER) - ABU-ABU MUDA
                $sheet->getStyle('A2:AU2')->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'color' => ['rgb' => 'F0F0F0'] // Abu-abu muda
                    ],
                    'font' => ['bold' => true, 'size' => 9],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                        'wrapText' => true
                    ]
                ]);

                // Border untuk semua data
                $highestRow = $sheet->getHighestRow();
                $sheet->getStyle("A1:AU$highestRow")->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => '000000']
                        ]
                    ]
                ]);

                // ✅ SET TINGGI HEADER
                $sheet->getRowDimension('1')->setRowHeight(25);
                $sheet->getRowDimension('2')->setRowHeight(30);

                // ✅ AUTO WIDTH DENGAN BATAS MAKSIMAL
                foreach (range('A', 'AU') as $column) {
                    $sheet->getColumnDimension($column)->setAutoSize(true);

                    $currentWidth = $sheet->getColumnDimension($column)->getWidth();
                    if ($currentWidth > 25) {
                        $sheet->getColumnDimension($column)->setWidth(25);
                    }
                }

                // ✅ SET WIDTH KHUSUS
                $sheet->getColumnDimension('A')->setWidth(5);   // NO
                $sheet->getColumnDimension('B')->setWidth(18);  // Nama
                $sheet->getColumnDimension('C')->setWidth(16);  // NIK
                $sheet->getColumnDimension('D')->setWidth(12);  // Tanggal Lahir
                $sheet->getColumnDimension('E')->setWidth(8);   // Jenis Kelamin
                $sheet->getColumnDimension('F')->setWidth(18);  // Nama Ibu
                $sheet->getColumnDimension('G')->setWidth(12);  // No HP
                $sheet->getColumnDimension('H')->setWidth(20);  // Alamat
                $sheet->getColumnDimension('I')->setWidth(8);   // Usia

                // ✅ KOLOM PENGUKURAN
                foreach (range('J', 'S') as $column) {
                    $sheet->getColumnDimension($column)->setWidth(10);
                }

                // ✅ KOLOM ASI & TB
                foreach (range('T', 'V') as $column) {
                    $sheet->getColumnDimension($column)->setWidth(12);
                }

                // ✅ KOLOM IMUNISASI (W-AO)
                foreach (range('W', 'AO') as $column) {
                    $sheet->getColumnDimension($column)->setWidth(8);
                }

                // ✅ KOLOM GRUP TAMBAHAN (AP-AU)
                foreach (range('AP', 'AU') as $column) {
                    $sheet->getColumnDimension($column)->setWidth(10);
                }

                // ✅ KHUSUS KOLOM RUJUK (AU) - LEBIH LEBAR
                $sheet->getColumnDimension('AU')->setWidth(15);

                // ✅ SET SETIAP CELL NIK SEBAGAI TEXT EXPLICITLY
                $highestRow = $sheet->getHighestRow();
                for ($row = 3; $row <= $highestRow; $row++) {
                    $cellValue = $event->sheet->getCell('C' . $row)->getValue();

                    // Force sebagai string tanpa scientific notation
                    if (!empty($cellValue) && $cellValue !== '-') {
                        $event->sheet->setCellValueExplicit(
                            'C' . $row,
                            (string)$cellValue,
                            DataType::TYPE_STRING
                        );
                    }
                }

                // ✅ SET FORMAT KOLOM NIK
                $event->sheet->getDelegate()->getStyle('C:C')
                    ->getNumberFormat()
                    ->setFormatCode('@'); // @ = TEXT FORMAT
            }
        ];
    }
}
