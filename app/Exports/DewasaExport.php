<?php

namespace App\Exports;

use App\Models\PemeriksaanDewasa;
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

class DewasaExport implements FromCollection, WithHeadings, WithMapping, WithEvents, WithColumnFormatting
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
        $query = PemeriksaanDewasa::with('user');

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
            // ✅ BARIS 1: HEADER UTAMA (DENGAN MERGE)
            [
                'NAMA',
                'NIK',
                'TGL LAHIR',
                'USIA SAAT INI',
                'JENIS KELAMIN',
                'ALAMAT',
                'NO.HP',
                'STATUS PERKAWINAN (MENIKAH / TIDAK MENIKAH)',
                'PEKERJAAN',
                'RIWAYAT PTM',
                '',  // KOSONG UNTUK MERGE RIWAYAT PTM
                'PERILAKU BERESIKO',
                '',  // KOSONG UNTUK MERGE PERILAKU BERESIKO
                '',  // KOSONG UNTUK MERGE PERILAKU BERESIKO
                '',  // KOSONG UNTUK MERGE PERILAKU BERESIKO
                'PENGUKURAN',
                '',  // KOSONG UNTUK MERGE PENGUKURAN
                '',  // KOSONG UNTUK MERGE PENGUKURAN
                '',  // KOSONG UNTUK MERGE PENGUKURAN
                '',  // KOSONG UNTUK MERGE PENGUKURAN
                '',  // KOSONG UNTUK MERGE PENGUKURAN
                'PEMERIKSAAN SKRINING KESEHATAN',
                '',  // KOSONG UNTUK MERGE PEMERIKSAAN SKRINING
                '',  // KOSONG UNTUK MERGE PEMERIKSAAN SKRINING
                '',  // KOSONG UNTUK MERGE PEMERIKSAAN SKRINING
                '',  // KOSONG UNTUK MERGE PEMERIKSAAN SKRINING
                '',  // KOSONG UNTUK MERGE PEMERIKSAAN SKRINING
                '',  // KOSONG UNTUK MERGE PEMERIKSAAN SKRINING
                '',  // KOSONG UNTUK MERGE PEMERIKSAAN SKRINING
                '',  // KOSONG UNTUK MERGE PEMERIKSAAN SKRINING
                '',  // KOSONG UNTUK MERGE PEMERIKSAAN SKRINING


                'EDUKASI',
                'RUJUK'
            ],
            // ✅ BARIS 2: SUB-HEADER
            [
                '',  // NAMA (MERGE KE ATAS)
                '',  // NIK (MERGE KE ATAS)
                '',  // TGL LAHIR (MERGE KE ATAS)
                '',  // USIA SAAT INI (MERGE KE ATAS)
                '',  // JENIS KELAMIN (MERGE KE ATAS)
                '',  // ALAMAT (MERGE KE ATAS)
                '',  // NO.HP (MERGE KE ATAS)
                '',  // STATUS PERKAWINAN (MERGE KE ATAS)
                '',  // PEKERJAAN (MERGE KE ATAS)
                'DIRI SENDIRI',
                'KELUARGA',
                'MEROKOK',
                'KONSUMSI TINGGI GULA',
                'KONSUMSI TINGGI GARAM',
                'KONSUMSI TINGGI MINYAK',
                'TINGGI BADAN',
                'BERAT BADAN',
                'IMT',
                'LINGKAR PERUT',
                'LINGKAR LENGAN ATAS',
                'TEKANAN DARAH',
                'GULA DARAH',
                'KOLESTEROL',
                'ASAM URAT',
                'SKRINING PENGLIHATAN',
                'SKRINING PENDENGARAN',
                'TES BERBISIK (NORMAL)',
                'SKOR PUMA',
                'GEJALA TB (2 GEJALA YA/TIDAK)',
                'MENGGUNAKAN ALAT KONTRASEPSI (PIL/KONDOM/SUNTIK)',
                'PEMERIKSAAN KESEHATAN JIWA, SKOR >= 6',
                'YA/TIDAK',  // EDUKASI
                'YA/TIDAK'   // RUJUK
            ]
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

        // ✅ AMBIL DATA DARI USER & PEMERIKSAAN DEWASA
        $user = $pemeriksaan->user;

        // ✅ NAMA (dari User.php)
        $nama = $user->nama ?? '';

        // ✅ NIK (dari User.php) - HILANGKAN APOSTROF, BIAR DIHANDLE registerEvents()
        $nik = $user->nik ?? '';

        // ✅ TGL LAHIR (dari User.php)
        $tglLahir = $user->tanggal_lahir ?? '';
        if ($tglLahir) {
            $tglLahir = date('d/m/Y', strtotime($tglLahir));
        }

        // ✅ USIA SAAT INI (KOSONG - SESUAI PERMINTAAN)
        $usia = '';

        // ✅ JENIS KELAMIN (dari User.php)
        $jenisKelamin = $user->jenis_kelamin ?? '';

        // ✅ ALAMAT (dari User.php)
        $alamat = $user->alamat ?? '';

        // ✅ NO.HP (dari User.php) - FORMAT SEBAGAI TEXT JUGA
        $noHp = $user->no_hp ?? '';
        if (!empty($noHp)) {
            // ✅ TAMBAH APOSTROF DI DEPAN BIAR JADI TEXT FORMAT
            $noHp = "'" . $noHp;
        }

        // ✅ STATUS PERKAWINAN (dari User.php)
        $statusPerkawinan = $user->status_perkawinan ?? '';

        // ✅ PEKERJAAN (dari User.php)
        $pekerjaan = $user->pekerjaan ?? '';

        // ✅ RIWAYAT PTM - DIRI SENDIRI (YA/TIDAK)
        $riwayatDiri = 'TIDAK';
        if (!empty($user->riwayat_diri) && trim($user->riwayat_diri) !== '') {
            $riwayatDiri = 'YA';
        }

        // ✅ RIWAYAT PTM - KELUARGA (YA/TIDAK)
        $riwayatKeluarga = 'TIDAK';
        if (!empty($user->riwayat_keluarga) && trim($user->riwayat_keluarga) !== '') {
            $riwayatKeluarga = 'YA';
        }

        // ✅ MEROKOK (dari perilaku_beresiko)
        $merokok = 'TIDAK';
        if (!empty($user->perilaku_beresiko)) {
            $perilakuArray = explode(',', strtolower($user->perilaku_beresiko));
            if (in_array('merokok', $perilakuArray) || in_array('rokok', $perilakuArray)) {
                $merokok = 'YA';
            }
        }

        // ✅ KONSUMSI TINGGI GULA (dari perilaku_beresiko)
        $konsumsiGula = 'TIDAK';
        if (!empty($user->perilaku_beresiko)) {
            $perilakuArray = explode(',', strtolower($user->perilaku_beresiko));
            if (
                in_array('konsumsi gula tinggi', $perilakuArray) ||
                in_array('gula tinggi', $perilakuArray) ||
                in_array('makanan manis', $perilakuArray)
            ) {
                $konsumsiGula = 'YA';
            }
        }

        // ✅ KONSUMSI TINGGI GARAM (dari perilaku_beresiko)
        $konsumsiGaram = 'TIDAK';
        if (!empty($user->perilaku_beresiko)) {
            $perilakuArray = explode(',', strtolower($user->perilaku_beresiko));
            if (
                in_array('konsumsi garam tinggi', $perilakuArray) ||
                in_array('garam tinggi', $perilakuArray) ||
                in_array('makanan asin', $perilakuArray)
            ) {
                $konsumsiGaram = 'YA';
            }
        }

        // ✅ KONSUMSI TINGGI MINYAK (dari perilaku_beresiko)
        $konsumsiMinyak = 'TIDAK';
        if (!empty($user->perilaku_beresiko)) {
            $perilakuArray = explode(',', strtolower($user->perilaku_beresiko));
            if (
                in_array('konsumsi minyak tinggi', $perilakuArray) ||
                in_array('minyak tinggi', $perilakuArray) ||
                in_array('makanan berlemak', $perilakuArray)
            ) {
                $konsumsiMinyak = 'YA';
            }
        }

        // ✅ DATA PENGUKURAN (dari PemeriksaanDewasa) - SESUAIKAN DENGAN FIELD YANG ADA
        $tinggiBadan = $pemeriksaan->tb ?? '';
        $beratBadan = $pemeriksaan->bb ?? '';
        $imt = $pemeriksaan->imt ?? '';
        $lingkarPerut = $pemeriksaan->lingkar_perut ?? '';
        $lingkarLenganAtas = '';  // ✅ TIDAK ADA DI DATABASE
        $tekananDarah = '';
        if (!empty($pemeriksaan->sistole) && !empty($pemeriksaan->diastole)) {
            $tekananDarah = $pemeriksaan->sistole . '/' . $pemeriksaan->diastole;
        }

        // ✅ DATA SKRINING KESEHATAN (dari PemeriksaanDewasa) - SESUAIKAN DENGAN FIELD YANG ADA
        $gulaDarah = $pemeriksaan->gula_darah ?? '';
        $kolesterol = '';  // ✅ TIDAK ADA DI DATABASE
        $asamUrat = '';    // ✅ TIDAK ADA DI DATABASE

        // ✅ SKRINING PENGLIHATAN (dari tes_jari_kanan & tes_jari_kiri)
        $skriningPenglihatan = '';
        if (!empty($pemeriksaan->tes_jari_kanan) && !empty($pemeriksaan->tes_jari_kiri)) {
            $skriningPenglihatan = 'Kanan: ' . $pemeriksaan->tes_jari_kanan . ', Kiri: ' . $pemeriksaan->tes_jari_kiri;
        }

        // ✅ SKRINING PENDENGARAN (dari tes_berbisik_kanan & tes_berbisik_kiri)
        $skriningPendengaran = '';
        if (!empty($pemeriksaan->tes_berbisik_kanan) && !empty($pemeriksaan->tes_berbisik_kiri)) {
            $skriningPendengaran = 'Kanan: ' . $pemeriksaan->tes_berbisik_kanan . ', Kiri: ' . $pemeriksaan->tes_berbisik_kiri;
        }

        // ✅ TES BERBISIK (dari tes_berbisik_kanan & tes_berbisik_kiri)
        $tesBerbisik = '';
        if (!empty($pemeriksaan->tes_berbisik_kanan) || !empty($pemeriksaan->tes_berbisik_kiri)) {
            $tesBerbisik = 'NORMAL';
        }

        $skorPuma = $pemeriksaan->skor_puma ?? '';

        // ✅ GEJALA TB (dari status_tbc atau gabungan tbc_*)
        $gejalaTb = $pemeriksaan->status_tbc ?? '';
        if (empty($gejalaTb)) {
            $gejalaTbCount = 0;
            if (!empty($pemeriksaan->tbc_batuk)) $gejalaTbCount++;
            if (!empty($pemeriksaan->tbc_demam)) $gejalaTbCount++;
            if (!empty($pemeriksaan->tbc_bb_turun)) $gejalaTbCount++;
            if (!empty($pemeriksaan->tbc_kontak)) $gejalaTbCount++;

            $gejalaTb = $gejalaTbCount >= 2 ? 'YA' : 'TIDAK';
        }

        // ✅ MENGGUNAKAN ALAT KONTRASEPSI (dari alat_kontrasepsi)
        $alatKontrasepsi = 'TIDAK';
        if (!empty($pemeriksaan->alat_kontrasepsi) && trim($pemeriksaan->alat_kontrasepsi) !== '') {
            $alatKontrasepsi = 'YA';
        }

        // ✅ PEMERIKSAAN KESEHATAN JIWA (TIDAK ADA DI DATABASE)
        $pemeriksaanJiwa = '';

        // ✅ EDUKASI (YA/TIDAK berdasarkan field edukasi)
        $edukasi = 'TIDAK';
        if (!empty($pemeriksaan->edukasi) && trim($pemeriksaan->edukasi) !== '') {
            $edukasi = 'YA';
        }

        // ✅ RUJUK (DEFAULT TIDAK - KARENA GA ADA FIELD RUJUK DI DATABASE)
        $rujuk = 'TIDAK';

        return [
            $nama,                  // NAMA
            $nik,                   // NIK ✅ (TANPA APOSTROF, FORMAT TEXT VIA registerEvents)
            $tglLahir,              // TGL LAHIR
            $usia,                  // USIA SAAT INI (KOSONG)
            $jenisKelamin,          // JENIS KELAMIN
            $alamat,                // ALAMAT
            $noHp,                  // NO.HP ✅ (FORMAT TEXT dengan apostrof)
            $statusPerkawinan,      // STATUS PERKAWINAN
            $pekerjaan,             // PEKERJAAN
            $riwayatDiri,           // RIWAYAT PTM - DIRI SENDIRI (YA/TIDAK)
            $riwayatKeluarga,       // RIWAYAT PTM - KELUARGA (YA/TIDAK)
            $merokok,               // PERILAKU BERESIKO - MEROKOK
            $konsumsiGula,          // PERILAKU BERESIKO - KONSUMSI TINGGI GULA
            $konsumsiGaram,         // PERILAKU BERESIKO - KONSUMSI TINGGI GARAM
            $konsumsiMinyak,        // PERILAKU BERESIKO - KONSUMSI TINGGI MINYAK
            $tinggiBadan,           // PENGUKURAN - TINGGI BADAN
            $beratBadan,            // PENGUKURAN - BERAT BADAN
            $imt,                   // PENGUKURAN - IMT
            $lingkarPerut,          // PENGUKURAN - LINGKAR PERUT
            $lingkarLenganAtas,     // PENGUKURAN - LINGKAR LENGAN ATAS (KOSONG)
            $tekananDarah,          // PENGUKURAN - TEKANAN DARAH
            $gulaDarah,             // PEMERIKSAAN SKRINING - GULA DARAH
            $kolesterol,            // PEMERIKSAAN SKRINING - KOLESTEROL (KOSONG)
            $asamUrat,              // PEMERIKSAAN SKRINING - ASAM URAT (KOSONG)
            $skriningPenglihatan,   // PEMERIKSAAN SKRINING - SKRINING PENGLIHATAN
            $skriningPendengaran,   // PEMERIKSAAN SKRINING - SKRINING PENDENGARAN
            $tesBerbisik,           // PEMERIKSAAN SKRINING - TES BERBISIK
            $skorPuma,              // PEMERIKSAAN SKRINING - SKOR PUMA
            $gejalaTb,              // PEMERIKSAAN SKRINING - GEJALA TB
            $alatKontrasepsi,       // PEMERIKSAAN SKRINING - ALAT KONTRASEPSI (YA/TIDAK)
            $pemeriksaanJiwa,       // PEMERIKSAAN SKRINING - PEMERIKSAAN JIWA (KOSONG)
            $edukasi,               // EDUKASI (YA/TIDAK)
            $rujuk                  // RUJUK (DEFAULT TIDAK - GA ADA FIELD DI DATABASE)
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // ✅ SET SETIAP CELL NIK SEBAGAI TEXT EXPLICITLY
                $highestRow = $sheet->getHighestRow();
                for ($row = 3; $row <= $highestRow; $row++) {
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

                // ✅ MERGE CELLS UNTUK HEADER UTAMA

                // MERGE KOLOM TUNGGAL (BARIS 1-2)
                $sheet->mergeCells('A1:A2');  // NAMA
                $sheet->mergeCells('B1:B2');  // NIK
                $sheet->mergeCells('C1:C2');  // TGL LAHIR
                $sheet->mergeCells('D1:D2');  // USIA SAAT INI
                $sheet->mergeCells('E1:E2');  // JENIS KELAMIN
                $sheet->mergeCells('F1:F2');  // ALAMAT
                $sheet->mergeCells('G1:G2');  // NO.HP
                $sheet->mergeCells('H1:H2');  // STATUS PERKAWINAN
                $sheet->mergeCells('I1:I2');  // PEKERJAAN

                // MERGE GRUP RIWAYAT PTM (J1:K1)
                $sheet->mergeCells('J1:K1');  // RIWAYAT PTM

                // MERGE GRUP PERILAKU BERESIKO (L1:O1)
                $sheet->mergeCells('L1:O1');  // PERILAKU BERESIKO

                // MERGE GRUP PENGUKURAN (P1:U1)
                $sheet->mergeCells('P1:U1');  // PENGUKURAN

                // MERGE GRUP PEMERIKSAAN SKRINING KESEHATAN (V1:AE1)
                $sheet->mergeCells('V1:AE1');  // PEMERIKSAAN SKRINING KESEHATAN

                // MERGE KOLOM TUNGGAL EDUKASI & RUJUK (AF1:AG1)
                $sheet->mergeCells('AF1:AF2');  // EDUKASI
                $sheet->mergeCells('AG1:AG2');  // RUJUK

                // ✅ STYLING HEADER UTAMA (BARIS 1) - 32 KOLOM
                $sheet->getStyle('A1:AG1')->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'color' => ['rgb' => '90C695'] // Hijau muda sesuai desain
                    ],
                    'font' => ['bold' => true, 'size' => 11],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                        'wrapText' => true
                    ]
                ]);

                // ✅ STYLING SUB-HEADER (BARIS 2)
                $sheet->getStyle('J2:AE2')->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'color' => ['rgb' => 'A8D5AA'] // Hijau lebih muda untuk sub-header
                    ],
                    'font' => ['bold' => true, 'size' => 9],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                        'wrapText' => true
                    ]
                ]);

                // ✅ BORDER UNTUK SEMUA DATA - 32 KOLOM
                $highestRow = $sheet->getHighestRow();
                $sheet->getStyle("A1:AG$highestRow")->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => '000000']
                        ]
                    ]
                ]);

                // ✅ SET TINGGI HEADER
                $sheet->getRowDimension('1')->setRowHeight(30);
                $sheet->getRowDimension('2')->setRowHeight(30);

                // ✅ SET WIDTH KOLOM - 32 KOLOM
                $sheet->getColumnDimension('A')->setWidth(20);  // NAMA
                $sheet->getColumnDimension('B')->setWidth(18);  // NIK
                $sheet->getColumnDimension('C')->setWidth(12);  // TGL LAHIR
                $sheet->getColumnDimension('D')->setWidth(12);  // USIA SAAT INI
                $sheet->getColumnDimension('E')->setWidth(15);  // JENIS KELAMIN
                $sheet->getColumnDimension('F')->setWidth(30);  // ALAMAT
                $sheet->getColumnDimension('G')->setWidth(15);  // NO.HP
                $sheet->getColumnDimension('H')->setWidth(20);  // STATUS PERKAWINAN
                $sheet->getColumnDimension('I')->setWidth(20);  // PEKERJAAN
                $sheet->getColumnDimension('J')->setWidth(15);  // RIWAYAT PTM - DIRI SENDIRI
                $sheet->getColumnDimension('K')->setWidth(15);  // RIWAYAT PTM - KELUARGA
                $sheet->getColumnDimension('L')->setWidth(12);  // PERILAKU BERESIKO - MEROKOK
                $sheet->getColumnDimension('M')->setWidth(15);  // PERILAKU BERESIKO - KONSUMSI TINGGI GULA
                $sheet->getColumnDimension('N')->setWidth(15);  // PERILAKU BERESIKO - KONSUMSI TINGGI GARAM
                $sheet->getColumnDimension('O')->setWidth(15);  // PERILAKU BERESIKO - KONSUMSI TINGGI MINYAK
                $sheet->getColumnDimension('P')->setWidth(12);  // PENGUKURAN - TINGGI BADAN
                $sheet->getColumnDimension('Q')->setWidth(12);  // PENGUKURAN - BERAT BADAN
                $sheet->getColumnDimension('R')->setWidth(10);  // PENGUKURAN - IMT
                $sheet->getColumnDimension('S')->setWidth(15);  // PENGUKURAN - LINGKAR PERUT
                $sheet->getColumnDimension('T')->setWidth(15);  // PENGUKURAN - LINGKAR LENGAN ATAS
                $sheet->getColumnDimension('U')->setWidth(15);  // PENGUKURAN - TEKANAN DARAH
                $sheet->getColumnDimension('V')->setWidth(12);  // PEMERIKSAAN SKRINING - GULA DARAH
                $sheet->getColumnDimension('W')->setWidth(12);  // PEMERIKSAAN SKRINING - KOLESTEROL
                $sheet->getColumnDimension('X')->setWidth(12);  // PEMERIKSAAN SKRINING - ASAM URAT
                $sheet->getColumnDimension('Y')->setWidth(15);  // PEMERIKSAAN SKRINING - SKRINING PENGLIHATAN
                $sheet->getColumnDimension('Z')->setWidth(15);  // PEMERIKSAAN SKRINING - SKRINING PENDENGARAN
                $sheet->getColumnDimension('AA')->setWidth(15); // PEMERIKSAAN SKRINING - TES BERBISIK
                $sheet->getColumnDimension('AB')->setWidth(12); // PEMERIKSAAN SKRINING - SKOR PUMA
                $sheet->getColumnDimension('AC')->setWidth(15); // PEMERIKSAAN SKRINING - GEJALA TB
                $sheet->getColumnDimension('AD')->setWidth(20); // PEMERIKSAAN SKRINING - ALAT KONTRASEPSI
                $sheet->getColumnDimension('AE')->setWidth(20); // PEMERIKSAAN SKRINING - PEMERIKSAAN JIWA
                $sheet->getColumnDimension('AF')->setWidth(12); // EDUKASI
                $sheet->getColumnDimension('AG')->setWidth(12); // RUJUK

                // ✅ ALIGNMENT DATA ROWS - 32 KOLOM
                $sheet->getStyle("A3:AG$highestRow")->applyFromArray([
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_LEFT,
                        'vertical' => Alignment::VERTICAL_CENTER,
                        'wrapText' => true
                    ]
                ]);

                // ✅ ALIGNMENT YA/TIDAK (CENTER)
                $sheet->getStyle("J3:O$highestRow")->applyFromArray([
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER
                    ]
                ]);

                // ✅ ALIGNMENT ANGKA (CENTER)
                $sheet->getStyle("P3:AG$highestRow")->applyFromArray([
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER
                    ]
                ]);

                // ✅ SET TINGGI BARIS DATA
                for ($i = 3; $i <= $highestRow; $i++) {
                    $sheet->getRowDimension($i)->setRowHeight(25);
                }
            }
        ];
    }
}
