<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengeluaran;
use Illuminate\Http\Request;

class PengeluaranController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'kategori' => 'required|in:Operasional,Gaji Pegawai,Pembelian Alat,Lainnya',
            'nominal' => 'required|numeric|min:0',
            'tanggal' => 'required|date',
            'keterangan' => 'nullable|string',
        ]);

        Pengeluaran::create($validated);

        return redirect()->route('admin.dashboard', ['tab' => 'keuangan'])->with('success', 'Data pengeluaran berhasil ditambahkan.');
    }

    public function destroy($id)
    {
        $pengeluaran = Pengeluaran::findOrFail($id);
        $pengeluaran->delete();

        return redirect()->route('admin.dashboard', ['tab' => 'keuangan'])->with('success', 'Data pengeluaran berhasil dihapus.');
    }
}
