<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class DataPemeriksaanExport implements FromArray, WithHeadings, WithColumnFormatting, WithEvents
{
    protected $data;

    public function __construct($data, $filters = [])
    {
        $this->data = $data;
    }

    public function array(): array
    {
        $result = [];
        $counter = 1;
        
        foreach ($this->data as $row) {
            $userData = $row['user'] ?? [];
            
            $result[] = [
                $counter++,
                \Carbon\Carbon::parse($row['tanggal_pemeriksaan'])->format('d/m/Y'),
                $row['nik'] ?? '-', // ✅ HILANGKAN PREFIX PETIK
                $userData['nama'] ?? '-',
                $userData['rw'] ?? '-',
                $userData['level'] ?? '-',
                ucwords(str_replace('-', ' ', $row['jenis_pemeriksaan'] ?? '')),
                $row['bb'] ?? '-',
                $row['tb'] ?? '-',
                $row['lila'] ?? '-',
                ($row['rujuk_puskesmas'] === 'Perlu Rujukan') ? 'Perlu Rujukan' : 'Normal',
                $row['pemeriksa'] ?? '-'
            ];
        }
        
        return $result;
    }

    public function headings(): array
    {
        return [
            'No',
            'Tanggal Pemeriksaan',
            'NIK',
            'Nama',
            'RW',
            'Level',
            'Jenis Pemeriksaan',
            'Berat Badan (kg)',
            'Tinggi Badan (cm)',
            'LILA (cm)',
            'Status Rujukan',
            'Pemeriksa'
        ];
    }

    // ✅ TAMBAH METHOD INI UNTUK FORMAT KOLOM
    public function columnFormats(): array
    {
        return [
            'C' => NumberFormat::FORMAT_TEXT, // ✅ KOLOM NIK JADI TEXT
        ];
    }
    
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $highestRow = $event->sheet->getHighestRow();
                
                // ✅ SET SETIAP CELL NIK SEBAGAI TEXT EXPLICITLY
                for ($row = 2; $row <= $highestRow; $row++) {
                    $cellValue = $event->sheet->getCell('C' . $row)->getValue();
                    
                    // Force sebagai string tanpa scientific notation
                    if (!empty($cellValue) && $cellValue !== '-') {
                        $event->sheet->setCellValueExplicit(
                            'C' . $row, 
                            (string)$cellValue, 
                            \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING
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
