<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('doctors', 'public');
            $validated['image'] = $path;
        }

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
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada
            if ($doctor->image && Storage::disk('public')->exists($doctor->image)) {
                Storage::disk('public')->delete($doctor->image);
            }
            
            $path = $request->file('image')->store('doctors', 'public');
            $validated['image'] = $path;
        }

        $doctor->update($validated);

        return redirect()->back()->with('success', 'Data dokter berhasil diperbarui!');
    }

    /**
     * Remove the specified doctor.
     */
    public function destroy(Doctor $doctor)
    {
        // Hapus gambar jika ada sebelum data dihapus
        if ($doctor->image && Storage::disk('public')->exists($doctor->image)) {
            Storage::disk('public')->delete($doctor->image);
        }

        $doctor->delete();
        return redirect()->back()->with('success', 'Data dokter berhasil dihapus!');
    }
}
