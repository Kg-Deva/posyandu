<?php

namespace App\Exports;

use App\Models\PemeriksaanRemaja;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class RemajaExport implements FromCollection, WithHeadings, WithMapping, WithEvents
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
        $query = PemeriksaanRemaja::with('user');

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
            // BARIS 1 - Header sesuai desain gambar
            ['NO', 'NAMA', 'NOMOR NIK', 'TANGGAL LAHIR', 'JENIS KELAMIN', 'NAMA AYAH/IBU', 'ALAMAT', 'USIA ANAK SAAT INI']
        ];
    }

    public function map($pemeriksaan): array
    {
        static $no = 0;
        $no++;

        // ✅ AMBIL DATA DARI USER (formdata.blade.php)
        $user = $pemeriksaan->user;

        // ✅ NAMA AYAH/IBU - ambil yang ada, prioritas nama_ayah
        $namaOrtu = '';
        if (!empty($user->nama_ayah)) {
            $namaOrtu = $user->nama_ayah;
        } elseif (!empty($user->nama_ibu)) {
            $namaOrtu = $user->nama_ibu;
        }

        // ✅ FORMAT TANGGAL LAHIR - FIX YANG ANEH INI!
        $tanggalLahir = '';
        if (!empty($user->tanggal_lahir)) {
            // Kalau format database: "2013-01-02 00:00:00" atau "2013-01-02"
            $tanggalLahir = date('d/m/Y', strtotime($user->tanggal_lahir)); // Output: "02/01/2013" ✅ dd/mm/yyyy
        }

        // ✅ USIA - HITUNG TAHUN SAJA dari tanggal lahir
        $usia = '';
        if (!empty($user->tanggal_lahir)) {
            $tanggalLahirObj = new \DateTime($user->tanggal_lahir);  // ✅ Beda variable
            $sekarang = new \DateTime();
            // Kalau ada tanggal lahir: hitung tahun saja
            $umurTahun = $sekarang->diff($tanggalLahirObj)->y;
            $usia = $umurTahun . ' tahun';  // Output: "12 tahun" ✅
        } else {
            // Fallback ke field umur yang sudah ada
            $umurData = $user->umur ?? $pemeriksaan->umur ?? '';
            if (!empty($umurData)) {
                // Kalau tidak ada tanggal lahir: ambil dari field umur
                // Tapi extract tahun saja dengan regex
                if (preg_match('/(\d+)\s*tahun/', $umurData, $matches)) {
                    $usia = $matches[1] . ' tahun';  // Output: "12 tahun" ✅
                } else {
                    $usia = $umurData;
                }
            }
        }

        return [
            $no,                                    // NO
            $user->nama ?? '',                      // NAMA (dari User.php)
            $user->nik ?? '',                       // NOMOR NIK (dari User.php)
            $tanggalLahir,                          // TANGGAL LAHIR - FORMAT: "02/01/2013" ✅
            $user->jenis_kelamin ?? '',             // JENIS KELAMIN (dari User.php)
            $namaOrtu,                              // NAMA AYAH/IBU (dari User.php - formdata.blade.php)
            $user->alamat ?? '',                    // ALAMAT (dari User.php)
            $usia                                   // USIA ANAK SAAT INI - FORMAT: "12 tahun"
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // ✅ STYLING HEADER - KUNING
                $sheet->getStyle('A1:H1')->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'color' => ['rgb' => 'FFFF00'] // Kuning
                    ],
                    'font' => ['bold' => true, 'size' => 11],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                        'wrapText' => true
                    ]
                ]);

                // ✅ BORDER UNTUK SEMUA DATA
                $highestRow = $sheet->getHighestRow();
                $sheet->getStyle("A1:H$highestRow")->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => '000000']
                        ]
                    ]
                ]);

                // ✅ SET TINGGI HEADER
                $sheet->getRowDimension('1')->setRowHeight(30);

                // ✅ SET WIDTH KOLOM SESUAI DESAIN
                $sheet->getColumnDimension('A')->setWidth(6);   // NO
                $sheet->getColumnDimension('B')->setWidth(25);  // NAMA
                $sheet->getColumnDimension('C')->setWidth(18);  // NOMOR NIK
                $sheet->getColumnDimension('D')->setWidth(15);  // TANGGAL LAHIR
                $sheet->getColumnDimension('E')->setWidth(15);  // JENIS KELAMIN
                $sheet->getColumnDimension('F')->setWidth(20);  // NAMA AYAH/IBU
                $sheet->getColumnDimension('G')->setWidth(30);  // ALAMAT
                $sheet->getColumnDimension('H')->setWidth(18);  // USIA ANAK SAAT INI

                // ✅ ALIGNMENT DATA ROWS
                $sheet->getStyle("A2:H$highestRow")->applyFromArray([
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_LEFT,
                        'vertical' => Alignment::VERTICAL_CENTER,
                        'wrapText' => true
                    ]
                ]);

                // ✅ ALIGNMENT NOMOR & TANGGAL (CENTER)
                $sheet->getStyle("A2:A$highestRow")->applyFromArray([
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER
                    ]
                ]);

                $sheet->getStyle("D2:D$highestRow")->applyFromArray([
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER
                    ]
                ]);

                // ✅ ALIGNMENT JENIS KELAMIN & USIA (CENTER)
                $sheet->getStyle("E2:E$highestRow")->applyFromArray([
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER
                    ]
                ]);

                $sheet->getStyle("H2:H$highestRow")->applyFromArray([
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER
                    ]
                ]);

                // ✅ SET TINGGI BARIS DATA
                for ($i = 2; $i <= $highestRow; $i++) {
                    $sheet->getRowDimension($i)->setRowHeight(20);
                }
            }
        ];
    }
}
