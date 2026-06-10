<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use App\Models\Facility;
// use App\Http\Services\WilayahIndonesiaService; // 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf as DomPDF;

class ComplaintController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Complaint::with(['user', 'facility.category']);

        // Filter by role
        if (Auth::user()->role !== 'Admin') {
            $query->where('user_id', Auth::id());
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by facility
        if ($request->filled('facility_id')) {
            $query->where('facility_id', $request->facility_id);
        }

        // Filter by date range
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $complaints = $query->latest()->paginate(15);
        $facilities = Facility::where('status', 'Aktif')->get();

        return view('complaints.index', compact('complaints', 'facilities'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $facilities = Facility::where('status', 'Aktif')->with('category')->get();
        
        return view('complaints.create', compact('facilities'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'facility_id' => 'required|exists:facilities,id',
            'description' => 'required|string',
            'campus' => 'required|string|max:100',
            'location' => 'required|string|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('complaints', 'public');
        }

        $validated['user_id'] = Auth::id();
        $validated['status'] = 'Pending';

        Complaint::create($validated);

        return redirect()->route('complaints.index')
            ->with('success', 'Pengaduan berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $complaint = Complaint::with(['user', 'facility.category', 'tindakLanjut.petugas'])
            ->findOrFail($id);

        // Check authorization
        if (Auth::user()->role !== 'Admin' && $complaint->user_id !== Auth::id()) {
            abort(403);
        }

        return view('complaints.show', compact('complaint'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $complaint = Complaint::findOrFail($id);

        // Only admin can edit
        if (Auth::user()->role !== 'Admin') {
            abort(403);
        }

        return view('complaints.edit', compact('complaint'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $complaint = Complaint::findOrFail($id);

        // Only admin can update
        if (Auth::user()->role !== 'Admin') {
            abort(403);
        }

        $validated = $request->validate([
            'status' => 'required|in:Pending,In Progress,Completed,Rejected',
            'admin_notes' => 'nullable|string',
        ]);

        $complaint->update($validated);

        return redirect()->route('complaints.show', $complaint)
            ->with('success', 'Status pengaduan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $complaint = Complaint::findOrFail($id);

        // Only admin can delete
        if (Auth::user()->role !== 'Admin') {
            abort(403);
        }

        if ($complaint->photo) {
            Storage::disk('public')->delete($complaint->photo);
        }

        $complaint->delete();

        return redirect()->route('complaints.index')
            ->with('success', 'Pengaduan berhasil dihapus.');
    }

    /**
     * Export complaint to PDF
     */
    public function exportPdf(string $id)
    {
        $complaint = Complaint::with(['user', 'facility.category', 'tindakLanjut.petugas'])
            ->findOrFail($id);

        // Check authorization
        if (Auth::user()->role !== 'Admin' && $complaint->user_id !== Auth::id()) {
            abort(403);
        }

        $pdf = DomPDF::loadView('complaints.pdf', compact('complaint'));
        return $pdf->download('pengaduan-' . $complaint->id . '.pdf');
    }
}