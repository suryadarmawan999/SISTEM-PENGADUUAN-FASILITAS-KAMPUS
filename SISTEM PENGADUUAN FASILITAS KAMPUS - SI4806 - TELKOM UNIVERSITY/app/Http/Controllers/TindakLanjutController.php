<?php

namespace App\Http\Controllers;

use App\Models\TindakLanjut;
use App\Models\Complaint;
use App\Models\User;
use Illuminate\Http\Request;

class TindakLanjutController extends Controller
{
   
    public function index()
    {
        $tindakLanjut = TindakLanjut::with(['complaint', 'petugas'])->latest()->paginate(15);
        return view('tindak-lanjut.index', compact('tindakLanjut'));
    }

    public function create(Request $request)
    {
        $complaintId = $request->complaint_id;
        $complaint = $complaintId ? Complaint::findOrFail($complaintId) : null;

        $petugas = User::whereIn('role', ['Admin', 'Dosen', 'Staff', 'Teknisi'])
                    ->orderBy('role', 'asc')
                    ->orderBy('name', 'asc')
                    ->get();
        
        return view('tindak-lanjut.create', compact('complaint', 'petugas'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'complaint_id'       => 'required|exists:complaints,id',
            'petugas_id'         => 'required|exists:users,id',
            'catatan_penanganan' => 'required|string',
            'status_akhir'       => 'required|in:Pending,In Progress,Completed,Rejected',
        ]);

        $tindakLanjut = TindakLanjut::create($validated);

        $complaint = Complaint::findOrFail($validated['complaint_id']);
        $complaint->update(['status' => $validated['status_akhir']]);

        return redirect()->route('monitoring.show', $complaint->id)
            ->with('success', 'Tindak lanjut berhasil ditambahkan.');
    }

    public function show(string $id)
    {
        $tindakLanjut = TindakLanjut::with(['complaint', 'petugas'])->findOrFail($id);
        return view('tindak-lanjut.show', compact('tindakLanjut'));
    }

    public function edit(string $id)
    {
        $tindakLanjut = TindakLanjut::findOrFail($id);
        
        $petugas = User::whereIn('role', ['Admin', 'Dosen', 'Staff', 'Teknisi'])
                    ->orderBy('name', 'asc')
                    ->get();
        
        return view('tindak-lanjut.edit', compact('tindakLanjut', 'petugas'));
    }

    
    public function update(Request $request, string $id)
    {
        $tindakLanjut = TindakLanjut::findOrFail($id);

        $validated = $request->validate([
            'petugas_id'         => 'required|exists:users,id',
            'catatan_penanganan' => 'required|string',
            'status_akhir'       => 'required|in:Pending,In Progress,Completed,Rejected',
        ]);

        $tindakLanjut->update($validated);

        $complaint = $tindakLanjut->complaint;
        $complaint->update(['status' => $validated['status_akhir']]);

        return redirect()->route('monitoring.show', $complaint->id)
            ->with('success', 'Tindak lanjut berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $tindakLanjut = TindakLanjut::findOrFail($id);
        $complaintId = $tindakLanjut->complaint_id;
        $tindakLanjut->delete();

        return redirect()->route('monitoring.show', $complaintId)
            ->with('success', 'Tindak lanjut berhasil dihapus.');
    }
}