<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use App\Models\Facility;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf as DomPDF;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ComplaintsReportExport;

class ReportController extends Controller
{
    /**
     * Display reports dashboard
     */
    public function index(Request $request)
    {
        $query = Complaint::with(['user', 'facility.category']);

        // Filter by period
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by facility category
        if ($request->filled('category_id')) {
            $query->whereHas('facility', function ($q) use ($request) {
                $q->where('category_id', $request->category_id);
            });
        }

        $complaints = $query->get();

        // Statistics
        $stats = [
            'total' => $complaints->count(),
            'pending' => $complaints->where('status', 'Pending')->count(),
            'in_progress' => $complaints->where('status', 'In Progress')->count(),
            'completed' => $complaints->where('status', 'Completed')->count(),
            'rejected' => $complaints->where('status', 'Rejected')->count(),
        ];

        // Group by facility category
        $byCategory = $complaints->groupBy(function ($complaint) {
            return $complaint->facility->category->category_name ?? 'Tidak Ada Kategori';
        })->map->count();

        // Group by status
        $byStatus = $complaints->groupBy('status')->map->count();

        return view('reports.index', compact('complaints', 'stats', 'byCategory', 'byStatus'));
    }

    /**
     * Export report to PDF
     */
    public function exportPdf(Request $request)
    {
        $query = Complaint::with(['user', 'facility.category']);

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $complaints = $query->get();

        // Pass request data to view
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $pdf = DomPDF::loadView('reports.pdf', compact('complaints', 'startDate', 'endDate'));
        return $pdf->download('laporan-rekap-pengaduan.pdf');
    }

    /**
     * Export report to Excel
     */
    public function exportExcel(Request $request)
    {
        return Excel::download(new ComplaintsReportExport($request), 'laporan-rekap-pengaduan.xlsx');
    }
}
