<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Models\User;

class ForgotPasswordController extends Controller
{
    // 1. Nampilin form masukin email
    public function showForgotForm()
    {
        return view('auth.forgot-password');
    }

    // 2. Proses ngirim KODE OTP ke email
    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:users,email']);

        // 🔥 BIKIN KODE OTP 6 DIGIT ACAK 🔥
        $code = rand(100000, 999999);

        // Simpan kode ke database
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();
        DB::table('password_reset_tokens')->insert([
            'email' => $request->email,
            'token' => $code, // Disimpan sebagai OTP
            'created_at' => Carbon::now()
        ]);

        // Kirim email berisi Kode OTP
        Mail::send('auth.email-reset', ['code' => $code], function($message) use($request){
            $message->to($request->email);
            $message->subject('Kode OTP Reset Password DelimaCare');
        });

        // Lempar user ke halaman masukin kode OTP, sambil bawa data emailnya
        return redirect()->route('password.reset', ['email' => $request->email])
                         ->with('success', 'Kode OTP 6-digit telah dikirim ke email Anda!');
    }

    // 3. Nampilin form reset password (masukin OTP & Password Baru)
    public function showResetForm(Request $request)
    {
        // Ambil email dari URL biar pasien nggak usah ngetik ulang
        return view('auth.reset-password', ['email' => $request->email]);
    }

    // 4. Proses verifikasi OTP & update password
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'code' => 'required|numeric', // Validasi input harus angka
            'password' => 'required|min:8|confirmed',
        ]);

        // Cek apakah email dan kode OTP-nya cocok
        $resetData = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->where('token', $request->code)
            ->first();

        if (!$resetData) {
            return back()->with('error', 'Kode OTP salah atau sudah kadaluarsa!');
        }

        // Kalau cocok, update password
        User::where('email', $request->email)->update([
            'password' => Hash::make($request->password)
        ]);

        // Hapus OTP dari database biar aman
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return redirect('/login')->with('success', 'Password berhasil diubah! Silakan login dengan password baru.');
    }
}
