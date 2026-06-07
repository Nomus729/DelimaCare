<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReservasiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'dokter_id' => 'required|exists:doctors,id',
            'phone'     => 'required|string|max:15',
            'layanan'   => 'required|string|max:100',
            'tanggal'   => 'required|date|after_or_equal:today',
            'waktu'     => 'required|date_format:H:i',
            'keluhan'   => 'nullable|string|max:1000',
        ];

        // Admin menambahkan field nama manual (pasien otomatis pakai Auth)
        if ($this->routeIs('admin.reservasi.store_admin')) {
            $rules['nama'] = 'required|string|max:255';
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'dokter_id.required' => 'Silakan pilih dokter terlebih dahulu.',
            'dokter_id.exists'   => 'Dokter yang dipilih tidak ditemukan.',
            'phone.required'     => 'Nomor telepon/WhatsApp wajib diisi.',
            'layanan.required'   => 'Jenis layanan wajib dipilih.',
            'tanggal.required'   => 'Tanggal kunjungan wajib diisi.',
            'tanggal.after_or_equal' => 'Tanggal kunjungan tidak boleh di masa lalu.',
            'waktu.required'     => 'Jam kedatangan wajib dipilih.',
            'waktu.date_format'  => 'Format jam kedatangan tidak valid.',
            'nama.required'      => 'Nama pasien wajib diisi.',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $tanggal = $this->input('tanggal');
            $waktu = $this->input('waktu');

            if ($tanggal && $waktu) {
                try {
                    $reservationDateTime = \Carbon\Carbon::parse($tanggal . ' ' . $waktu);
                    
                    if ($tanggal === now()->format('Y-m-d') && $reservationDateTime->isPast()) {
                        $validator->errors()->add('waktu', 'Waktu reservasi yang dipilih sudah terlewat untuk hari ini.');
                    }
                } catch (\Exception $e) {
                    // Abaikan jika format tidak valid, biar ditangani oleh rules()
                }
            }
        });
    }
}
