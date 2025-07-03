<?php

namespace App\Exports;

use App\Models\PemeriksaanRemaja;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

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
            'Tanggal Pemeriksaan',
            'NIK',
            'Nama',
            'RW',
            'BB',
            'TB',
            'IMT',
            'LILA',
            'Umur',
            'Rujukan',
            'Pemeriksa'
        ];
    }

    public function map($item): array
    {
        return [
            $item->tanggal_pemeriksaan,
            $item->nik,
            $item->user->nama ?? '',
            $item->user->rw ?? '',
            $item->bb,
            $item->tb,
            $item->imt,
            $item->lila,
            $item->umur,
            $item->rujuk_puskesmas,
            $item->pemeriksa
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                // Ungu untuk header remaja
                $event->sheet->getStyle('A1:K1')->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'color' => ['rgb' => '9C27B0']
                    ],
                    'font' => [
                        'color' => ['rgb' => 'FFFFFF'],
                        'bold' => true
                    ]
                ]);
            }
        ];
    }
}
