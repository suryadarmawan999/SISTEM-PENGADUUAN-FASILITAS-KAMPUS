<?php

namespace App\Exports;

use App\Models\Facility;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class FacilitiesExport implements FromCollection, WithHeadings, WithMapping
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Facility::with('category')->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nama Fasilitas',
            'Kategori',
            'Deskripsi',
            'Lokasi',
            'Status',
            'Tanggal Dibuat',
        ];
    }

    public function map($facility): array
    {
        return [
            $facility->id,
            $facility->facility_name,
            $facility->category->category_name ?? '-',
            $facility->description ?? '-',
            $facility->location,
            $facility->status,
            $facility->created_at->format('d/m/Y H:i'),
        ];
    }
}
