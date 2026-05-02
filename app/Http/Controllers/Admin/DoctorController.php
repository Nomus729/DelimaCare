<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use Illuminate\Http\Request;

class DoctorController extends Controller
{
    /**
     * Store a newly created doctor.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'spesialisasi' => 'required|string|max:255',
            'status' => 'required|in:Tersedia,Istirahat,Libur',
            'jadwal_praktek' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
        ]);

        Doctor::create($validated);

        return redirect()->back()->with('success', 'Data dokter berhasil ditambahkan!');
    }

    /**
     * Update the specified doctor.
     */
    public function update(Request $request, Doctor $doctor)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'spesialisasi' => 'required|string|max:255',
            'status' => 'required|in:Tersedia,Istirahat,Libur',
            'jadwal_praktek' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
        ]);

        $doctor->update($validated);

        return redirect()->back()->with('success', 'Data dokter berhasil diperbarui!');
    }

    /**
     * Remove the specified doctor.
     */
    public function destroy(Doctor $doctor)
    {
        $doctor->delete();
        return redirect()->back()->with('success', 'Data dokter berhasil dihapus!');
    }
}
