<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SimpanKunjunganRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->memilikiHakAkses('kunjungan.daftar') ?? false;
    }

    public function rules(): array
    {
        return [
            'id_pasien' => ['required', 'integer', 'exists:pasien,id_pasien'],
            'tanggal_kunjungan' => ['required', 'date', 'date_format:Y-m-d'],
            'keluhan_awal' => ['nullable', 'string', 'max:2000'],
        ];
    }

    public function messages(): array
    {
        return [
            'id_pasien.required' => 'Pilih pasien yang akan didaftarkan ke antrean.',
            'id_pasien.integer' => 'Pilihan pasien tidak valid. Silakan pilih kembali dari daftar.',
            'id_pasien.exists' => 'Pasien tidak ditemukan. Muat ulang daftar atau daftarkan pasien terlebih dahulu.',
            'tanggal_kunjungan.required' => 'Tanggal kunjungan wajib diisi.',
            'tanggal_kunjungan.date' => 'Masukkan tanggal kunjungan yang valid.',
            'tanggal_kunjungan.date_format' => 'Gunakan format tanggal yang valid.',
            'keluhan_awal.string' => 'Keluhan awal harus berupa teks.',
            'keluhan_awal.max' => 'Keluhan awal maksimal 2.000 karakter.',
        ];
    }

    public function attributes(): array
    {
        return [
            'id_pasien' => 'pasien',
            'tanggal_kunjungan' => 'tanggal kunjungan',
            'keluhan_awal' => 'keluhan awal',
        ];
    }
}
