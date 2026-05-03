<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ResepMedis;
use App\Models\ResepMedisItem;
use App\Models\Medicine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ResepMedisController extends Controller
{
    /**
     * Store resep baru dan kurangi stok obat.
     */
    public function store(Request $request)
    {
        $request->validate([
            'rekam_medis_id'    => 'required|exists:rekam_medis,id',
            'nama_pasien'       => 'required|string',
            'dokter_pemeriksa'  => 'nullable|string',
            'tanggal_resep'     => 'required|date',
            'catatan_apoteker'  => 'nullable|string',
            'items'             => 'required|array|min:1',
            'items.*.medicine_id' => 'required|exists:medicines,id',
            'items.*.jumlah'    => 'required|integer|min:1',
            'items.*.aturan_pakai' => 'nullable|string',
        ]);

        DB::transaction(function () use ($request) {
            $resep = ResepMedis::create([
                'no_resep'          => ResepMedis::generateNoResep(),
                'rekam_medis_id'    => $request->rekam_medis_id,
                'nama_pasien'       => $request->nama_pasien,
                'dokter_pemeriksa'  => $request->dokter_pemeriksa,
                'tanggal_resep'     => $request->tanggal_resep,
                'catatan_apoteker'  => $request->catatan_apoteker,
                'status'            => 'Pending',
            ]);

            foreach ($request->items as $item) {
                $medicine = Medicine::findOrFail($item['medicine_id']);

                ResepMedisItem::create([
                    'resep_medis_id' => $resep->id,
                    'medicine_id'    => $medicine->id,
                    'nama_obat'      => $medicine->name,
                    'satuan'         => $medicine->unit,
                    'jumlah'         => $item['jumlah'],
                    'aturan_pakai'   => $item['aturan_pakai'] ?? null,
                ]);

                // Kurangi stok inventori
                $medicine->decrement('stock', $item['jumlah']);
            }
        });

        return redirect()
            ->route('admin.dashboard', ['tab' => 'rekam_medis'])
            ->with('success', 'Resep medis berhasil dibuat dan stok obat telah diperbarui.');
    }

    /**
     * Update status resep (Pending → Diproses → Selesai).
     */
    public function updateStatus(Request $request, ResepMedis $resepMedis)
    {
        $request->validate(['status' => 'required|in:Pending,Diproses,Selesai']);
        $resepMedis->update(['status' => $request->status]);

        return back()->with('success', 'Status resep diperbarui.');
    }

    /**
     * Hapus resep (kembalikan stok).
     */
    public function destroy(ResepMedis $resepMedis)
    {
        DB::transaction(function () use ($resepMedis) {
            foreach ($resepMedis->items as $item) {
                // Kembalikan stok saat resep dihapus
                Medicine::where('id', $item->medicine_id)
                        ->increment('stock', $item->jumlah);
            }
            $resepMedis->delete();
        });

        return back()->with('success', 'Resep medis dihapus dan stok obat dikembalikan.');
    }

    /**
     * API: cari obat dari inventori (JSON untuk Alpine.js)
     */
    public function searchMedicine(Request $request)
    {
        $q = $request->get('q', '');
        $medicines = Medicine::where(function($query) use ($q) {
                $query->where('name', 'like', "%{$q}%")
                      ->orWhere('brand', 'like', "%{$q}%");
            })
            ->where('stock', '>', 0)
            ->select('id', 'name', 'brand', 'stock', 'unit', 'price')
            ->limit(10)
            ->get();

        return response()->json($medicines);
    }
}
