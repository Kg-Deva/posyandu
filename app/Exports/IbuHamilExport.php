<?php

namespace App\Exports;

use App\Models\PemeriksaanIbuHamil;
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

class IbuHamilExport implements FromCollection, WithHeadings, WithMapping, WithEvents, WithColumnFormatting
{
    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = PemeriksaanIbuHamil::with('user');

        // ✅ FILTER BERDASARKAN PARAMETER
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
                $query->where('perlu_rujukan', true);
            } else {
                $query->where('perlu_rujukan', false);
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

        return $query->orderBy('tanggal_pemeriksaan', 'desc')->get();
    }

    public function headings(): array
    {
        return [
            ['NO', 'NIK', 'NAMA IBU HAMIL', 'NAMA IBU NIFAS', 'NAMA SUAMI', 'NO HP', 'ALAMAT', 'BB kurva Hijau/Merah', 'LINGKAR LENGAN ATAS kurva Hijau/Merah', 'TEKANAN DARAH kurva Hijau/Merah', 'TEKANAN DARAH', 'GULA DARAH', 'KOLESTEROL', 'ASAM URAT', 'Hb', 'SKRINING TB (YA 2 gejala/ TIDAK)', 'PEMBERIAN TABLET TAMBAH DARAH', 'PEMBERIAN PMT KEK', 'KELAS IBU HAMIL', 'RUJUK/TIDAK']
        ];
    }

    // ✅ TAMBAH METHOD INI UNTUK FORMAT KOLOM NIK
    public function columnFormats(): array
    {
        return [
            'B' => NumberFormat::FORMAT_TEXT, // ✅ KOLOM NIK (B) JADI TEXT
        ];
    }

    public function map($pemeriksaan): array
    {
        static $no = 0;
        $no++;

        // ✅ AMBIL DATA DARI USER & PEMERIKSAAN IBU HAMIL
        $user = $pemeriksaan->user;

        // ✅ NAMA IBU HAMIL (dari User.php)
        $namaIbuHamil = $user->nama ?? '';

        // ✅ NAMA IBU NIFAS (kosong karena ini data ibu hamil)
        $namaIbuNifas = '-';

        // ✅ NAMA SUAMI (dari User.php) - FIX: PAKE nama_suami
        $namaSuami = $user->nama_suami ?? '';

        // ✅ NO HP (dari User.php)
        $noHP = $user->no_hp ?? '';

        // ✅ ALAMAT (dari User.php)
        $alamat = $user->alamat ?? '';

        // ✅ BB KURVA HIJAU/MERAH - ISI DENGAN ANGKA BB + KURVA
        $bbKurva = '';
        $beratBadan = $pemeriksaan->bb ?? '';
        $statusKurva = '';

        if (!empty($pemeriksaan->status_kenaikan_bb)) {
            if ($pemeriksaan->status_kenaikan_bb === 'Sesuai') {
                $statusKurva = 'Hijau';
            } else {
                $statusKurva = 'Merah';
            }
        } else {
            // ✅ KALAU status_kenaikan_bb KOSONG, BUAT LOGIKA DEFAULT
            if (!empty($beratBadan)) {
                // ✅ ANGGAP HIJAU KALAU ADA BB (DEFAULT NORMAL)
                $statusKurva = 'Hijau';
            }
        }

        // ✅ GABUNGKAN BB + KURVA
        if (!empty($beratBadan)) {
            $bbKurva = $beratBadan . ' kg';
            if (!empty($statusKurva)) {
                $bbKurva .= ' (' . $statusKurva . ')';
            }
        } else {
            $bbKurva = $statusKurva ?: '-';
        }

        // ✅ LINGKAR LENGAN ATAS KURVA HIJAU/MERAH - ISI DENGAN ANGKA LILA + KURVA
        $lilaKurva = '';
        $lilaValue = $pemeriksaan->lila ?? '';
        $lilaStatus = '';

        if (!empty($pemeriksaan->lila)) {
            if ($pemeriksaan->lila >= 23.5) {
                $lilaStatus = 'Hijau';
            } else {
                $lilaStatus = 'Merah';
            }
        }

        // ✅ GABUNGKAN LILA + KURVA
        if (!empty($lilaValue)) {
            $lilaKurva = $lilaValue . ' cm';
            if (!empty($lilaStatus)) {
                $lilaKurva .= ' (' . $lilaStatus . ')';
            }
        } else {
            $lilaKurva = $lilaStatus;
        }

        // ✅ TEKANAN DARAH KURVA HIJAU/MERAH - ISI DENGAN ANGKA TD + KURVA
        $tdKurva = '';
        $tekananDarah = '';
        $tdStatus = '';

        if (!empty($pemeriksaan->sistole) && !empty($pemeriksaan->diastole)) {
            $tekananDarah = $pemeriksaan->sistole . '/' . $pemeriksaan->diastole;

            if ($pemeriksaan->sistole >= 140 || $pemeriksaan->diastole >= 90) {
                $tdStatus = 'Merah'; // Hipertensi
            } elseif ($pemeriksaan->sistole < 90 || $pemeriksaan->diastole < 60) {
                $tdStatus = 'Merah'; // Hipotensi
            } else {
                $tdStatus = 'Hijau'; // Normal
            }

            // ✅ GABUNGKAN TD + KURVA
            $tdKurva = $tekananDarah;
            if (!empty($tdStatus)) {
                $tdKurva .= ' (' . $tdStatus . ')';
            }
        }

        // ✅ GULA DARAH (tidak ada di model, beri tanda -)
        $gulaDarah = '-';

        // ✅ KOLESTEROL (tidak ada di model, beri tanda -)
        $kolesterol = '-';

        // ✅ ASAM URAT (tidak ada di model, beri tanda -)
        $asamUrat = '-';

        // ✅ Hb (dari PemeriksaanIbuHamil.php)
        $hb = $pemeriksaan->hb ?? '';

        // ✅ SKRINING TB (YA 2 gejala/ TIDAK)
        $skriningTB = 'TIDAK';
        $jumlahGejala = $pemeriksaan->jumlah_gejala_tbc ?? 0;
        if ($jumlahGejala >= 2) {
            $skriningTB = 'YA';
        }

        // ✅ PEMBERIAN TABLET TAMBAH DARAH (YA/TIDAK) - FIX: PAKE FIELD YANG BENAR
        $tabletDarah = 'TIDAK';
        if (!empty($pemeriksaan->jumlah_tablet_fe) && $pemeriksaan->jumlah_tablet_fe > 0) {
            $tabletDarah = 'YA';
        }

        // ✅ PEMBERIAN PMT KEK (YA/TIDAK) - FIX: PAKE FIELD YANG BENAR
        $pmtKek = 'TIDAK';
        if (!empty($pemeriksaan->jumlah_porsi_mt) && $pemeriksaan->jumlah_porsi_mt > 0) {
            $pmtKek = 'YA';
        }

        // ✅ KELAS IBU HAMIL (YA/TIDAK) - FIX: PAKE FIELD YANG BENAR
        $kelasIbuHamil = 'TIDAK';
        if (!empty($pemeriksaan->mengikuti_kelas_ibu)) {
            $kelasValue = strtolower($pemeriksaan->mengikuti_kelas_ibu);
            if (in_array($kelasValue, ['ya', 'hadir', 'sudah', 'iya'])) {
                $kelasIbuHamil = 'YA';
            }
        }

        // ✅ RUJUK/TIDAK
        $rujukan = 'TIDAK';
        if (!empty($pemeriksaan->perlu_rujukan)) {
            if ($pemeriksaan->perlu_rujukan === true || $pemeriksaan->perlu_rujukan === 1) {
                $rujukan = 'YA';
            }
        }

        return [
            $no,                    // NO
            $user->nik ?? '',       // NIK ✅ (TANPA APOSTROF, FORMAT TEXT VIA registerEvents)
            $namaIbuHamil,          // NAMA IBU HAMIL (dari User.php)
            $namaIbuNifas,          // NAMA IBU NIFAS (-)
            $namaSuami,             // NAMA SUAMI (dari User.php - nama_suami) ✅ FIXED
            $noHP,                  // NO HP (dari User.php)
            $alamat,                // ALAMAT (dari User.php)
            $bbKurva,               // BB kurva Hijau/Merah (BB + Status)
            $lilaKurva,             // LINGKAR LENGAN ATAS kurva (LILA + Status)
            $tdKurva,               // TEKANAN DARAH kurva (TD + Status)
            $tekananDarah,          // TEKANAN DARAH (sistole/diastole)
            $gulaDarah,             // GULA DARAH (-)
            $kolesterol,            // KOLESTEROL (-)
            $asamUrat,              // ASAM URAT (-)
            $hb,                    // Hb (dari PemeriksaanIbuHamil.php)
            $skriningTB,            // SKRINING TB (YA/TIDAK)
            $tabletDarah,           // PEMBERIAN TABLET TAMBAH DARAH (YA/TIDAK) ✅ FIXED
            $pmtKek,                // PEMBERIAN PMT KEK (YA/TIDAK) ✅ FIXED
            $kelasIbuHamil,         // KELAS IBU HAMIL (YA/TIDAK) ✅ FIXED
            $rujukan                // RUJUK/TIDAK (YA/TIDAK)
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // ✅ STYLING HEADER - BIRU MUDA (UPDATE JADI 20 KOLOM)
                $sheet->getStyle('A1:T1')->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'color' => ['rgb' => 'ADD8E6'] // Biru muda
                    ],
                    'font' => ['bold' => true, 'size' => 10],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                        'wrapText' => true
                    ]
                ]);

                // ✅ BORDER UNTUK SEMUA DATA (UPDATE JADI 20 KOLOM)
                $highestRow = $sheet->getHighestRow();
                $sheet->getStyle("A1:T$highestRow")->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => '000000']
                        ]
                    ]
                ]);

                // ✅ SET TINGGI HEADER
                $sheet->getRowDimension('1')->setRowHeight(35);

                // ✅ SET WIDTH KOLOM SESUAI DESAIN (UPDATE DENGAN KOLOM BARU)
                $sheet->getColumnDimension('A')->setWidth(5);   // NO
                $sheet->getColumnDimension('B')->setWidth(18);  // NIK
                $sheet->getColumnDimension('C')->setWidth(20);  // NAMA IBU HAMIL
                $sheet->getColumnDimension('D')->setWidth(20);  // NAMA IBU NIFAS
                $sheet->getColumnDimension('E')->setWidth(20);  // NAMA SUAMI
                $sheet->getColumnDimension('F')->setWidth(15);  // NO HP
                $sheet->getColumnDimension('G')->setWidth(30);  // ALAMAT
                $sheet->getColumnDimension('H')->setWidth(15);  // BB kurva
                $sheet->getColumnDimension('I')->setWidth(20);  // LINGKAR LENGAN ATAS kurva
                $sheet->getColumnDimension('J')->setWidth(20);  // TEKANAN DARAH kurva
                $sheet->getColumnDimension('K')->setWidth(15);  // TEKANAN DARAH
                $sheet->getColumnDimension('L')->setWidth(12);  // GULA DARAH
                $sheet->getColumnDimension('M')->setWidth(12);  // KOLESTEROL
                $sheet->getColumnDimension('N')->setWidth(12);  // ASAM URAT
                $sheet->getColumnDimension('O')->setWidth(10);  // Hb
                $sheet->getColumnDimension('P')->setWidth(20);  // SKRINING TB
                $sheet->getColumnDimension('Q')->setWidth(20);  // PEMBERIAN TABLET TAMBAH DARAH
                $sheet->getColumnDimension('R')->setWidth(15);  // PEMBERIAN PMT KEK
                $sheet->getColumnDimension('S')->setWidth(15);  // KELAS IBU HAMIL
                $sheet->getColumnDimension('T')->setWidth(12);  // RUJUK/TIDAK

                // ✅ ALIGNMENT DATA ROWS (UPDATE JADI 20 KOLOM)
                $sheet->getStyle("A2:T$highestRow")->applyFromArray([
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_LEFT,
                        'vertical' => Alignment::VERTICAL_CENTER,
                        'wrapText' => true
                    ]
                ]);

                // ✅ ALIGNMENT NOMOR (CENTER)
                $sheet->getStyle("A2:A$highestRow")->applyFromArray([
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER
                    ]
                ]);

                // ✅ ALIGNMENT NILAI & YA/TIDAK (CENTER)
                $sheet->getStyle("H2:T$highestRow")->applyFromArray([
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER
                    ]
                ]);

                // ✅ SET TINGGI BARIS DATA
                for ($i = 2; $i <= $highestRow; $i++) {
                    $sheet->getRowDimension($i)->setRowHeight(20);
                }

                // ✅ SET SETIAP CELL NIK SEBAGAI TEXT EXPLICITLY
                for ($row = 2; $row <= $highestRow; $row++) {
                    $cellValue = $event->sheet->getCell('B' . $row)->getValue();

                    // Force sebagai string tanpa scientific notation
                    if (!empty($cellValue) && $cellValue !== '-') {
                        $event->sheet->setCellValueExplicit(
                            'B' . $row,
                            (string)$cellValue,
                            DataType::TYPE_STRING
                        );
                    }
                }

                // ✅ SET FORMAT KOLOM NIK
                $event->sheet->getDelegate()->getStyle('B:B')
                    ->getNumberFormat()
                    ->setFormatCode('@'); // @ = TEXT FORMAT
            }
        ];
    }
}
