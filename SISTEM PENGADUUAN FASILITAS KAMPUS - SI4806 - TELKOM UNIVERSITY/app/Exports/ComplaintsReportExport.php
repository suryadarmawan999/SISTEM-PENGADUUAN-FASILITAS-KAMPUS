<?php

namespace App\Exports;

use App\Models\Complaint;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ComplaintsReportExport implements FromCollection, WithHeadings, WithMapping
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $query = Complaint::with(['user', 'facility.category']);

        if ($this->request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $this->request->start_date);
        }
        if ($this->request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $this->request->end_date);
        }
        if ($this->request->filled('status')) {
            $query->where('status', $this->request->status);
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Judul',
            'Pelapor',
            'Fasilitas',
            'Kategori',
            'Lokasi',
            'Status',
            'Tanggal Lapor',
        ];
    }

    public function map($complaint): array
    {
        return [
            $complaint->id,
            $complaint->title,
            $complaint->user->name,
            $complaint->facility->facility_name,
            $complaint->facility->category->category_name ?? '-',
            $complaint->location,
            $complaint->status,
            $complaint->created_at->format('d/m/Y H:i'),
        ];
    }
}
