<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use Illuminate\Http\Request;

class ValidationController extends Controller
{
    /**
     * Display complaints that need validation
     */
    public function index(Request $request)
    {
        $query = Complaint::with(['user', 'facility.category'])
            ->where('status', 'Pending');

        // Filter by date
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $complaints = $query->latest()->paginate(15);

        return view('validation.index', compact('complaints'));
    }

    /**
     * Validate a complaint
     */
    public function validateComplaint(Request $request, string $id)
    {
        $complaint = Complaint::findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:In Progress,Rejected',
            'admin_notes' => 'nullable|string',
        ]);

        $complaint->update($validated);

        return redirect()->route('validation.index')
            ->with('success', 'Pengaduan berhasil divalidasi.');
    }

    /**
     * Show complaint detail for validation
     */
    public function show(string $id)
    {
        $complaint = Complaint::with(['user', 'facility.category'])->findOrFail($id);
        return view('validation.show', compact('complaint'));
    }
}
