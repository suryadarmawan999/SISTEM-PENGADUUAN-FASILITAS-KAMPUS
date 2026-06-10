<?php

namespace App\Http\Controllers;

use App\Models\FacilityCategory;
use Illuminate\Http\Request;

class FacilityCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = FacilityCategory::withCount('facilities')->latest()->get();
        return view('facility-categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('facility-categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_name' => 'required|string|max:100|unique:facility_categories,category_name',
        ]);

        FacilityCategory::create($validated);

        return redirect()->route('facility-categories.index')
            ->with('success', 'Kategori berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $category = FacilityCategory::with('facilities')->findOrFail($id);
        return view('facility-categories.show', compact('category'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $category = FacilityCategory::findOrFail($id);
        return view('facility-categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $category = FacilityCategory::findOrFail($id);

        $validated = $request->validate([
            'category_name' => 'required|string|max:100|unique:facility_categories,category_name,' . $id,
        ]);

        $category->update($validated);

        return redirect()->route('facility-categories.index')
            ->with('success', 'Kategori berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $category = FacilityCategory::findOrFail($id);
        
        if ($category->facilities()->count() > 0) {
            return redirect()->route('facility-categories.index')
                ->with('error', 'Kategori tidak dapat dihapus karena masih memiliki fasilitas.');
        }

        $category->delete();

        return redirect()->route('facility-categories.index')
            ->with('success', 'Kategori berhasil dihapus.');
    }
}
