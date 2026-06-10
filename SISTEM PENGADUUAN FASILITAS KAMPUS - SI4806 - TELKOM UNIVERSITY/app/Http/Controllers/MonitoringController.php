<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use App\Models\TindakLanjut;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf as DomPDF;

class MonitoringController extends Controller
{
    /**
     * Display monitoring dashboard
     */
    public function index(Request $request)
    {
        $query = Complaint::with(['user', 'facility.category', 'tindakLanjut.petugas']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $complaints = $query->latest()->paginate(15);

        return view('monitoring.index', compact('complaints'));
    }

    /**
     * Show complaint detail with monitoring
     */
    public function show(string $id)
    {
        $complaint = Complaint::with(['user', 'facility.category', 'tindakLanjut.petugas'])
            ->findOrFail($id);

        return view('monitoring.show', compact('complaint'));
    }

    /**
     * Export monitoring report to PDF
     */
    public function exportPdf(Request $request)
    {
        $query = Complaint::with(['user', 'facility.category', 'tindakLanjut.petugas']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $complaints = $query->get();
        $pdf = DomPDF::loadView('monitoring.pdf', compact('complaints'));
        return $pdf->download('laporan-monitoring.pdf');
    }
}
