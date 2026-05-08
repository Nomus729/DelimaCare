<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    // 🔥 KITA KOSONGIN DALAM KURUNGNYA BIAR GAK BENTROK SAMA ROUTE LAIN 🔥
    public function update()
    {
        // Kita panggil request-nya secara manual pakai helper bawaan Laravel
        $request = request();

        // Tarik data user yang lagi login
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Validasi inputan
        $request->validate([
            'username' => ['required', 'string', 'max:255', Rule::unique('users', 'username')->ignore($user->id)],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'foto' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'], // Max 2MB
        ]);

        // Timpa data lama dengan data baru
        $user->username = $request->username;
        $user->email = $request->email;

        // Proses foto kalau ada yang di-upload
        if ($request->hasFile('foto')) {
            // Hapus foto lama biar memori gak penuh
            if ($user->foto && Storage::disk('public')->exists($user->foto)) {
                Storage::disk('public')->delete($user->foto);
            }

            // Simpan foto baru ke folder storage/app/public/profil_pasien
            $path = $request->file('foto')->store('profil_pasien', 'public');
            $user->foto = $path;
        }

        // Simpan semuanya ke database
        $user->save();

        return redirect()->back()->with('success', 'Wih mantap! Profil dan foto berhasil diperbarui.');
    }
}
