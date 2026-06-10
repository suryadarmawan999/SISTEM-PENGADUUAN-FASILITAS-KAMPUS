<?php

namespace App\Http\Controllers;

use App\Models\Facility;
use App\Models\FacilityCategory;
use App\Http\Services\WilayahIndonesiaService;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf as DomPDF;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\FacilitiesExport;

class FacilityController extends Controller
{
    protected $wilayahService;

    public function __construct(WilayahIndonesiaService $wilayahService)
    {
        $this->wilayahService = $wilayahService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Facility::with('category');

        // Filter by category
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search
        if ($request->filled('search')) {
            $query->where('facility_name', 'like', '%' . $request->search . '%');
        }

        $facilities = $query->latest()->paginate(15);
        $categories = FacilityCategory::all();

        return view('facilities.index', compact('facilities', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = FacilityCategory::all();
        $provinces = $this->wilayahService->getProvinces();

        return view('facilities.create', compact('categories', 'provinces'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'facility_name' => 'required|string|max:255',
            'category_id' => 'required|exists:facility_categories,id',
            'description' => 'nullable|string',
            'location' => 'required|string|max:255',
            'status' => 'required|in:Aktif,Tidak Aktif',
        ]);

        Facility::create($validated);

        return redirect()->route('facilities.index')
            ->with('success', 'Fasilitas berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $facility = Facility::with('category')->findOrFail($id);
        return view('facilities.show', compact('facility'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $facility = Facility::findOrFail($id);
        $categories = FacilityCategory::all();
        $provinces = $this->wilayahService->getProvinces();

        return view('facilities.edit', compact('facility', 'categories', 'provinces'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $facility = Facility::findOrFail($id);

        $validated = $request->validate([
            'facility_name' => 'required|string|max:255',
            'category_id' => 'required|exists:facility_categories,id',
            'description' => 'nullable|string',
            'location' => 'required|string|max:255',
            'status' => 'required|in:Aktif,Tidak Aktif',
        ]);

        $facility->update($validated);

        return redirect()->route('facilities.index')
            ->with('success', 'Fasilitas berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $facility = Facility::findOrFail($id);
        $facility->delete();

        return redirect()->route('facilities.index')
            ->with('success', 'Fasilitas berhasil dihapus.');
    }

    /**
     * Export facilities to PDF
     */
    public function exportPdf()
    {
        $facilities = Facility::with('category')->get();
        $pdf = DomPDF::loadView('facilities.pdf', compact('facilities'));
        return $pdf->download('daftar-fasilitas.pdf');
    }

    /**
     * Export facilities to Excel
     */
    public function exportExcel()
    {
        return Excel::download(new FacilitiesExport, 'daftar-fasilitas.xlsx');
    }
}
