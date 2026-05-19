<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\RekamMedisRequest;
use App\Models\RekamMedis;

class RekamMedisController extends Controller
{
    /**
     * Store a newly created record.
     */
    public function store(RekamMedisRequest $request)
    {
        $validated = $request->validated();
        $validated['no_rekam_medis'] = RekamMedis::generateNoRekamMedis();

        // Jika ada reservasi, ambil user_id dari reservasi untuk relasi yang reliable
        if ($request->filled('reservasi_id')) {
            $reservasi = \App\Models\Reservasi::find($request->reservasi_id);
            if ($reservasi && $reservasi->user_id) {
                $validated['user_id'] = $reservasi->user_id;
            }
        }

        $rekamMedis = RekamMedis::create($validated);

        // Update status reservasi otomatis
        if ($request->filled('reservasi_id')) {
            if (isset($reservasi) && $reservasi) {
                $reservasi->status = 'Datang';
                $reservasi->save();
            }
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Rekam medis pasien berhasil ditambahkan.',
                'data' => $rekamMedis
            ]);
        }

        return redirect()
            ->route('admin.dashboard', ['tab' => 'rekam_medis'])
            ->with('success', 'Rekam medis pasien berhasil ditambahkan.');
    }

    /**
     * Update the specified record.
     */
    public function update(RekamMedisRequest $request, RekamMedis $rekamMedis)
    {
        $rekamMedis->update($request->validated());

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Rekam medis berhasil diperbarui.',
                'data' => $rekamMedis
            ]);
        }

        return redirect()
            ->route('admin.dashboard', ['tab' => 'rekam_medis'])
            ->with('success', 'Rekam medis berhasil diperbarui.');
    }

    /**
     * Soft-delete the specified record.
     */
    public function destroy(RekamMedis $rekamMedis)
    {
        $rekamMedis->delete(); // Now soft-deletes thanks to SoftDeletes trait

        return redirect()
            ->route('admin.dashboard', ['tab' => 'rekam_medis'])
            ->with('success', 'Rekam medis berhasil dihapus.');
    }
}
