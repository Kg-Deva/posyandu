<?php

namespace App\Exports;

use App\Models\PemeriksaanRemaja;
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

class RemajaExport implements FromCollection, WithHeadings, WithMapping, WithEvents, WithColumnFormatting
{
    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = PemeriksaanRemaja::with('user');

        // ✅ FILTER BERDASARKAN PARAMETER
        if (!empty($this->filters['tahun'])) {
            $query->whereYear('tanggal_pemeriksaan', $this->filters['tahun']);
        }
        if (!empty($this->filters['bulan'])) {
            $query->whereMonth('tanggal_pemeriksaan', $this->filters['bulan']);
        }

        // ✅ FIX RW FILTER - SUPPORT NORMALIZATION
        if (!empty($this->filters['rw'])) {
            $rwFilter = $this->filters['rw'];
            $query->whereHas('user', function ($q) use ($rwFilter) {
                $q->where(function ($subQ) use ($rwFilter) {
                    $subQ->where('rw', $rwFilter)
                        ->orWhere('rw', sprintf('%02d', intval($rwFilter)))
                        ->orWhere('rw', sprintf('%03d', intval($rwFilter)))
                        ->orWhere('rw', sprintf('%04d', intval($rwFilter)))
                        ->orWhere('rw', strval(intval($rwFilter)));
                });
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

        // ✅ GET DATA DULU, BARU FILTER RUJUKAN MANUAL
        $result = $query->orderBy('tanggal_pemeriksaan', 'desc')->get();

        // ✅ FILTER RUJUKAN MANUAL - CUMA TBC ≥ 2
        if (!empty($this->filters['rujukan'])) {
            $result = $result->filter(function ($pemeriksaan) {
                // ✅ LOGIC RUJUKAN REMAJA - CUMA TBC ≥ 2
                $rujukanStatus = 'Normal';

                // ✅ CEK GEJALA TBC >= 2
                $jumlahGejala = $pemeriksaan->jumlah_gejala_tbc ?? 0;
                if ($jumlahGejala >= 2) {
                    $rujukanStatus = 'Perlu Rujukan';
                }

                // ✅ STRICT FILTER MATCHING
                return $this->filters['rujukan'] === $rujukanStatus;
            });
        }

        return $result;
    }

    public function headings(): array
    {
        return [
            // BARIS 1 - Header utama dengan merge
            ['NO', 'NAMA', 'NOMOR NIK', 'TANGGAL LAHIR', 'JENIS KELAMIN', 'NAMA AYAH/IBU', 'ALAMAT', 'USIA ANAK SAAT INI', 'RIWAYAT PENYAKIT', '', 'PENIMBANGAN/ PENGUKURAN', '', '', '', '', '', 'PEMERIKSAAN SKRINING', '', '', '', 'EDUKASI', 'RUJUK'],
            // BARIS 2 - Sub header
            ['', '', '', '', '', '', '', '', 'KELUARGA', 'DIRI SENDIRI', 'BERAT BADAN', 'TINGGI BADAN', 'IMT : Sangat Kurus (SK)/ Kurus (K)', 'LINGKAR PERUT (Umur >15)', 'TEKANAN DARAH', 'GULA DARAH', 'KOLESTEROL', 'ASAM URAT', 'Hb', 'SKRINING TB (2 Gejala)', 'Skrining Masalah Kesehatan', 'YA/TIDAK', 'TIDAK']
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

        // ✅ AMBIL DATA DARI USER & PEMERIKSAAN
        $user = $pemeriksaan->user;

        // ✅ NAMA AYAH/IBU - ambil yang ada, prioritas nama_ayah
        $namaOrtu = '';
        if (!empty($user->nama_ayah)) {
            $namaOrtu = $user->nama_ayah;
        } elseif (!empty($user->nama_ibu)) {
            $namaOrtu = $user->nama_ibu;
        }

        // ✅ FORMAT TANGGAL LAHIR
        $tanggalLahir = '';
        if (!empty($user->tanggal_lahir)) {
            $tanggalLahir = date('d/m/Y', strtotime($user->tanggal_lahir));
        }

        // ✅ USIA - HITUNG TAHUN SAJA dari tanggal lahir
        $usia = '';
        if (!empty($user->tanggal_lahir)) {
            $tanggalLahirObj = new \DateTime($user->tanggal_lahir);
            $sekarang = new \DateTime();
            $umurTahun = $sekarang->diff($tanggalLahirObj)->y;
            $usia = $umurTahun . ' tahun';
        } else {
            $umurData = $user->umur ?? $pemeriksaan->umur ?? '';
            if (!empty($umurData)) {
                if (preg_match('/(\d+)\s*tahun/', $umurData, $matches)) {
                    $usia = $matches[1] . ' tahun';
                } else {
                    $usia = $umurData;
                }
            }
        }

        // ✅ FORMAT TEKANAN DARAH
        $tekananDarah = '';
        if (!empty($pemeriksaan->sistole) && !empty($pemeriksaan->diastole)) {
            $tekananDarah = $pemeriksaan->sistole . '/' . $pemeriksaan->diastole;
        }

        // ✅ FORMAT RUJUKAN - FIX LOGIKA!
        $rujukan = 'TIDAK';

        // ✅ CEK GEJALA TBC >= 2
        $jumlahGejala = $pemeriksaan->jumlah_gejala_tbc ?? 0;
        if ($jumlahGejala >= 2) {
            $rujukan = 'YA';
        } else {
            $rujukan = 'TIDAK';
        }

        // ✅ FORMAT EDUKASI - FIX JUGA!
        $edukasi = 'TIDAK';
        if (!empty($pemeriksaan->edukasi)) {
            $edukasiValue = strtolower($pemeriksaan->edukasi);
            if (in_array($edukasiValue, ['tidak', 'no', 'tidak ada', 'kosong'])) {
                $edukasi = 'TIDAK';
            } else {
                $edukasi = 'YA';
            }
        } else {
            $edukasi = 'TIDAK';
        }

        // ✅ FORMAT SKRINING TB (2 GEJALA) - FIX LOGIKA!
        $skriningTB = 'TIDAK';
        $jumlahGejala = $pemeriksaan->jumlah_gejala_tbc ?? 0;
        if ($jumlahGejala >= 2) {
            $skriningTB = 'YA';
        } else {
            $skriningTB = 'TIDAK';
        }

        // ✅ FORMAT SKRINING MASALAH KESEHATAN - FIX LOGIKA!
        $skriningMasalah = 'TIDAK';
        $masalahList = [];

        // ✅ CEK MASING-MASING FIELD DENGAN LOGIC YANG BENAR
        if (!empty($pemeriksaan->masalah_pendidikan)) {
            $nilai = strtolower(trim($pemeriksaan->masalah_pendidikan));
            if (!in_array($nilai, ['tidak ada', 'tidak ada masalah', 'normal', 'tidak', 'kosong', '-'])) {
                $masalahList[] = 'Pendidikan';
            }
        }

        if (!empty($pemeriksaan->masalah_pola_makan)) {
            $nilai = strtolower(trim($pemeriksaan->masalah_pola_makan));
            if (!in_array($nilai, ['tidak ada', 'tidak ada masalah', 'normal', 'tidak', 'kosong', '-'])) {
                $masalahList[] = 'Pola Makan';
            }
        }

        if (!empty($pemeriksaan->masalah_aktivitas)) {
            $nilai = strtolower(trim($pemeriksaan->masalah_aktivitas));
            if (!in_array($nilai, ['tidak ada', 'tidak ada masalah', 'normal', 'tidak', 'kosong', '-'])) {
                $masalahList[] = 'Aktivitas';
            }
        }

        if (!empty($pemeriksaan->masalah_obat)) {
            $nilai = strtolower(trim($pemeriksaan->masalah_obat));
            if (!in_array($nilai, ['tidak ada', 'tidak ada masalah', 'normal', 'tidak', 'kosong', '-'])) {
                $masalahList[] = 'Obat';
            }
        }

        if (!empty($pemeriksaan->masalah_kesehatan_seksual)) {
            $nilai = strtolower(trim($pemeriksaan->masalah_kesehatan_seksual));
            if (!in_array($nilai, ['tidak ada', 'tidak ada masalah', 'normal', 'tidak', 'kosong', '-'])) {
                $masalahList[] = 'Kesehatan Seksual';
            }
        }

        if (!empty($pemeriksaan->masalah_keamanan)) {
            $nilai = strtolower(trim($pemeriksaan->masalah_keamanan));
            if (!in_array($nilai, ['tidak ada', 'tidak ada masalah', 'normal', 'tidak', 'kosong', '-'])) {
                $masalahList[] = 'Keamanan';
            }
        }

        if (!empty($pemeriksaan->masalah_kesehatan_mental)) {
            $nilai = strtolower(trim($pemeriksaan->masalah_kesehatan_mental));
            if (!in_array($nilai, ['tidak ada', 'tidak ada masalah', 'normal', 'tidak', 'kosong', '-'])) {
                $masalahList[] = 'Kesehatan Mental';
            }
        }

        if (!empty($masalahList)) {
            $skriningMasalah = 'YA';    // ✅ Ada masalah = YA
        } else {
            $skriningMasalah = 'TIDAK'; // ✅ Tidak ada masalah = TIDAK
        }

        // ✅ FORMAT RIWAYAT KELUARGA - DARI USER.PHP
        $riwayatKeluarga = 'TIDAK';
        if (!empty($user->riwayat_keluarga)) {
            $riwayatKeluarga = 'YA';    // ✅ Ada riwayat = YA
        } else {
            $riwayatKeluarga = 'TIDAK'; // ✅ Tidak ada riwayat = TIDAK
        }

        // ✅ FORMAT RIWAYAT DIRI SENDIRI - DARI USER.PHP
        $riwayatDiri = 'TIDAK';
        if (!empty($user->riwayat_diri)) {
            $riwayatDiri = 'YA';        // ✅ Ada riwayat = YA
        } else {
            $riwayatDiri = 'TIDAK';     // ✅ Tidak ada riwayat = TIDAK
        }

        return [
            $no,                                                    // NO
            $user->nama ?? '',                                      // NAMA (dari User.php)
            $user->nik ?? '',                                       // NOMOR NIK ✅ (TANPA APOSTROF, FORMAT TEXT VIA registerEvents)
            $tanggalLahir,                                          // TANGGAL LAHIR (dari User.php)
            $user->jenis_kelamin ?? '',                             // JENIS KELAMIN (dari User.php)
            $namaOrtu,                                              // NAMA AYAH/IBU (dari User.php)
            $user->alamat ?? '',                                    // ALAMAT (dari User.php)
            $usia,                                                  // USIA ANAK SAAT INI (dari User.php)
            // ✅ RIWAYAT PENYAKIT (dari User.php) - YA/TIDAK
            $riwayatKeluarga,                                       // RIWAYAT PENYAKIT - KELUARGA (YA/TIDAK)
            $riwayatDiri,                                           // RIWAYAT PENYAKIT - DIRI SENDIRI (YA/TIDAK)
            // ✅ PENIMBANGAN/PENGUKURAN (dari PemeriksaanRemaja.php)
            $pemeriksaan->bb ?? '',                                 // BERAT BADAN (bb)
            $pemeriksaan->tb ?? '',                                 // TINGGI BADAN (tb)
            $pemeriksaan->kesimpulan_imt ?? '',                     // IMT (kesimpulan_imt)
            $pemeriksaan->lingkar_perut ?? '',                      // LINGKAR PERUT (lingkar_perut)
            $tekananDarah,                                          // TEKANAN DARAH (sistole/diastole)
            '-',                                                    // GULA DARAH (tidak ada di model)
            // ✅ PEMERIKSAAN SKRINING (dari PemeriksaanRemaja.php)
            '-',                                                    // KOLESTEROL (tidak ada di model)
            '-',                                                    // ASAM URAT (tidak ada di model)
            $pemeriksaan->hb ?? '',                                 // Hb (hb)
            $skriningTB,                                            // SKRINING TB - YA/TIDAK
            $skriningMasalah,                                       // Skrining Masalah Kesehatan - YA/TIDAK
            // ✅ EDUKASI & RUJUK - FIX!
            $edukasi,                                               // EDUKASI (YA/TIDAK)
            $rujukan                                                // RUJUK (YA/TIDAK)
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // ✅ MERGE CELLS SESUAI DESAIN
                // Kolom yang merge vertikal (A1:A2, B1:B2, dst)
                $sheet->mergeCells('A1:A2'); // NO
                $sheet->mergeCells('B1:B2'); // NAMA
                $sheet->mergeCells('C1:C2'); // NOMOR NIK
                $sheet->mergeCells('D1:D2'); // TANGGAL LAHIR
                $sheet->mergeCells('E1:E2'); // JENIS KELAMIN
                $sheet->mergeCells('F1:F2'); // NAMA AYAH/IBU
                $sheet->mergeCells('G1:G2'); // ALAMAT
                $sheet->mergeCells('H1:H2'); // USIA ANAK SAAT INI

                // Kolom yang merge horizontal di baris 1
                $sheet->mergeCells('I1:J1'); // RIWAYAT PENYAKIT
                $sheet->mergeCells('K1:P1'); // PENIMBANGAN/PENGUKURAN
                $sheet->mergeCells('Q1:U1'); // PEMERIKSAAN SKRINING
                $sheet->mergeCells('V1:V2'); // EDUKASI
                $sheet->mergeCells('W1:W2'); // RUJUK

                // ✅ SET NILAI HEADER MANUAL
                $sheet->setCellValue('A1', 'NO');
                $sheet->setCellValue('B1', 'NAMA');
                $sheet->setCellValue('C1', 'NOMOR NIK');
                $sheet->setCellValue('D1', 'TANGGAL LAHIR');
                $sheet->setCellValue('E1', 'JENIS KELAMIN');
                $sheet->setCellValue('F1', 'NAMA AYAH/IBU');
                $sheet->setCellValue('G1', 'ALAMAT');
                $sheet->setCellValue('H1', 'USIA ANAK SAAT INI');
                $sheet->setCellValue('I1', 'RIWAYAT PENYAKIT');
                $sheet->setCellValue('K1', 'PENIMBANGAN/ PENGUKURAN');
                $sheet->setCellValue('Q1', 'PEMERIKSAAN SKRINING');
                $sheet->setCellValue('V1', 'EDUKASI');
                $sheet->setCellValue('W1', 'RUJUK');

                // Sub header baris 2
                $sheet->setCellValue('I2', 'KELUARGA');
                $sheet->setCellValue('J2', 'DIRI SENDIRI');
                $sheet->setCellValue('K2', 'BERAT BADAN');
                $sheet->setCellValue('L2', 'TINGGI BADAN');
                $sheet->setCellValue('M2', 'IMT : Sangat Kurus (SK)/ Kurus (K)');
                $sheet->setCellValue('N2', 'LINGKAR PERUT (Umur >15)');
                $sheet->setCellValue('O2', 'TEKANAN DARAH');
                $sheet->setCellValue('P2', 'GULA DARAH');
                $sheet->setCellValue('Q2', 'KOLESTEROL');
                $sheet->setCellValue('R2', 'ASAM URAT');
                $sheet->setCellValue('S2', 'Hb');
                $sheet->setCellValue('T2', 'SKRINING TB (2 Gejala)');
                $sheet->setCellValue('U2', 'Skrining Masalah Kesehatan');
                $sheet->setCellValue('V2', 'YA/TIDAK');
                $sheet->setCellValue('W2', 'TIDAK');

                // ✅ STYLING HEADER - KUNING
                $sheet->getStyle('A1:W2')->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'color' => ['rgb' => 'FFFF00'] // Kuning
                    ],
                    'font' => ['bold' => true, 'size' => 9],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                        'wrapText' => true
                    ]
                ]);

                // ✅ BORDER UNTUK SEMUA DATA
                $highestRow = $sheet->getHighestRow();
                $sheet->getStyle("A1:W$highestRow")->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => '000000']
                        ]
                    ]
                ]);

                // ✅ SET TINGGI HEADER
                $sheet->getRowDimension('1')->setRowHeight(35);
                $sheet->getRowDimension('2')->setRowHeight(35);

                // ✅ SET WIDTH KOLOM
                $sheet->getColumnDimension('A')->setWidth(5);   // NO
                $sheet->getColumnDimension('B')->setWidth(20);  // NAMA
                $sheet->getColumnDimension('C')->setWidth(15);  // NOMOR NIK
                $sheet->getColumnDimension('D')->setWidth(12);  // TANGGAL LAHIR
                $sheet->getColumnDimension('E')->setWidth(12);  // JENIS KELAMIN
                $sheet->getColumnDimension('F')->setWidth(18);  // NAMA AYAH/IBU
                $sheet->getColumnDimension('G')->setWidth(25);  // ALAMAT
                $sheet->getColumnDimension('H')->setWidth(12);  // USIA ANAK SAAT INI
                $sheet->getColumnDimension('I')->setWidth(15);  // RIWAYAT KELUARGA
                $sheet->getColumnDimension('J')->setWidth(15);  // RIWAYAT DIRI
                $sheet->getColumnDimension('K')->setWidth(10);  // BERAT BADAN
                $sheet->getColumnDimension('L')->setWidth(10);  // TINGGI BADAN
                $sheet->getColumnDimension('M')->setWidth(15);  // IMT
                $sheet->getColumnDimension('N')->setWidth(12);  // LINGKAR PERUT
                $sheet->getColumnDimension('O')->setWidth(12);  // TEKANAN DARAH
                $sheet->getColumnDimension('P')->setWidth(10);  // GULA DARAH
                $sheet->getColumnDimension('Q')->setWidth(10);  // KOLESTEROL
                $sheet->getColumnDimension('R')->setWidth(10);  // ASAM URAT
                $sheet->getColumnDimension('S')->setWidth(8);   // Hb
                $sheet->getColumnDimension('T')->setWidth(15);  // SKRINING TB
                $sheet->getColumnDimension('U')->setWidth(15);  // SKRINING MASALAH
                $sheet->getColumnDimension('V')->setWidth(10);  // EDUKASI
                $sheet->getColumnDimension('W')->setWidth(10);  // RUJUK

                // ✅ ALIGNMENT DATA ROWS
                $sheet->getStyle("A3:W$highestRow")->applyFromArray([
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_LEFT,
                        'vertical' => Alignment::VERTICAL_CENTER,
                        'wrapText' => true
                    ]
                ]);

                // ✅ ALIGNMENT NOMOR (CENTER)
                $sheet->getStyle("A3:A$highestRow")->applyFromArray([
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER
                    ]
                ]);

                // ✅ SET TINGGI BARIS DATA
                for ($i = 3; $i <= $highestRow; $i++) {
                    $sheet->getRowDimension($i)->setRowHeight(18);
                }

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
