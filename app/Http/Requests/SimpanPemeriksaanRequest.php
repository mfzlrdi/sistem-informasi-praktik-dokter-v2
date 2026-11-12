<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SimpanPemeriksaanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->memilikiHakAkses('klinis.ubah') ?? false;
    }

    public function rules(): array
    {
        $finalisasi = $this->boolean('finalisasi_resep');
        $rules = [
            'anamnesis' => [$finalisasi ? 'required' : 'nullable', 'string', 'max:10000'],
            'hasil_pemeriksaan' => [$finalisasi ? 'required' : 'nullable', 'string', 'max:10000'],
            'tekanan_darah' => ['nullable', 'regex:/^\\d{2,3}\\/\\d{2,3}$/'],
            'suhu_tubuh' => ['nullable', 'numeric', 'between:20,50'],
            'denyut_nadi' => ['nullable', 'integer', 'between:20,250'],
            'frekuensi_pernapasan' => ['nullable', 'integer', 'between:5,80'],
            'berat_badan' => ['nullable', 'numeric', 'gt:0', 'max:500'],
            'tinggi_badan' => ['nullable', 'numeric', 'gt:0', 'max:250'],
            'rencana_terapi' => ['nullable', 'string', 'max:10000'],
            'instruksi_tindak_lanjut' => ['nullable', 'string', 'max:10000'],
            'diagnosa' => [$finalisasi ? 'required' : 'nullable', 'array', 'min:1', 'max:10'],
            'diagnosa.*' => [$finalisasi ? 'required' : 'nullable', 'string', 'max:255'],
            'tindakan' => ['nullable', 'array', 'max:10'],
            'tindakan.*' => ['nullable', 'string', 'max:255'],
            'catatan_resep' => ['nullable', 'string', 'max:2000'],
            'obat_id' => ['nullable', 'array', 'max:10'],
            'obat_id.*' => ['nullable', 'integer', 'distinct', 'exists:obat,id_obat'],
            'dosis' => ['nullable', 'array', 'max:10'],
            'dosis.*' => ['nullable', 'string', 'max:100'],
            'frekuensi' => ['nullable', 'array', 'max:10'],
            'frekuensi.*' => ['nullable', 'string', 'max:100'],
            'jumlah' => ['nullable', 'array', 'max:10'],
            'jumlah.*' => ['nullable', 'numeric', 'gt:0', 'max:10000'],
            'satuan' => ['nullable', 'array', 'max:10'],
            'satuan.*' => ['nullable', 'string', 'max:40'],
            'petunjuk' => ['nullable', 'array', 'max:10'],
            'petunjuk.*' => ['nullable', 'string', 'max:500'],
            'finalisasi_resep' => ['nullable', 'boolean'],
        ];

        foreach ((array) $this->input('obat_id', []) as $index => $obatId) {
            if ($obatId === null || $obatId === '') {
                continue;
            }

            $rules["dosis.{$index}"] = ['required', 'string', 'max:100'];
            $rules["frekuensi.{$index}"] = ['required', 'string', 'max:100'];
            $rules["jumlah.{$index}"] = ['required', 'numeric', 'gt:0', 'max:10000'];
            $rules["satuan.{$index}"] = ['required', 'string', 'max:40'];
            $rules["petunjuk.{$index}"] = ['required', 'string', 'max:500'];
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'anamnesis.required' => 'Anamnesis wajib diisi sebelum pemeriksaan difinalisasi.',
            'hasil_pemeriksaan.required' => 'Hasil pemeriksaan wajib diisi sebelum pemeriksaan difinalisasi.',
            'diagnosa.required' => 'Tambahkan diagnosis sebelum pemeriksaan difinalisasi.',
            'diagnosa.*.required' => 'Diagnosis tidak boleh kosong saat finalisasi.',
            'tekanan_darah.regex' => 'Gunakan format sistolik/diastolik, misalnya 120/80.',
            'suhu_tubuh.between' => 'Suhu tubuh harus berada pada rentang 20–50 °C.',
            'denyut_nadi.between' => 'Denyut nadi harus berada pada rentang 20–250 kali per menit.',
            'frekuensi_pernapasan.between' => 'Frekuensi pernapasan harus berada pada rentang 5–80 kali per menit.',
            'berat_badan.gt' => 'Berat badan harus lebih dari 0 kg.',
            'berat_badan.max' => 'Berat badan tidak boleh melebihi 500 kg.',
            'tinggi_badan.gt' => 'Tinggi badan harus lebih dari 0 cm.',
            'tinggi_badan.max' => 'Tinggi badan tidak boleh melebihi 250 cm.',
            'obat_id.*.exists' => 'Obat yang dipilih tidak tersedia atau sudah dihapus dari katalog.',
            'obat_id.*.distinct' => 'Obat yang sama tidak boleh dipilih lebih dari satu kali.',
            'dosis.*.required' => 'Dosis wajib diisi untuk setiap obat yang dipilih.',
            'frekuensi.*.required' => 'Frekuensi pemakaian wajib diisi untuk setiap obat yang dipilih.',
            'jumlah.*.required' => 'Jumlah obat wajib diisi dan harus lebih dari 0.',
            'jumlah.*.gt' => 'Jumlah obat harus lebih dari 0.',
            'satuan.*.required' => 'Satuan jumlah wajib diisi.',
            'petunjuk.*.required' => 'Petunjuk penggunaan wajib diisi untuk setiap obat.',
        ];
    }

    public function attributes(): array
    {
        return [
            'anamnesis' => 'anamnesis dokter',
            'hasil_pemeriksaan' => 'hasil pemeriksaan',
            'tekanan_darah' => 'tekanan darah',
            'suhu_tubuh' => 'suhu tubuh',
            'denyut_nadi' => 'denyut nadi',
            'frekuensi_pernapasan' => 'frekuensi pernapasan',
            'berat_badan' => 'berat badan',
            'tinggi_badan' => 'tinggi badan',
            'rencana_terapi' => 'rencana terapi',
            'instruksi_tindak_lanjut' => 'instruksi tindak lanjut',
            'diagnosa.*' => 'diagnosis',
            'tindakan.*' => 'tindakan',
            'dosis.*' => 'dosis obat',
            'frekuensi.*' => 'frekuensi obat',
            'jumlah.*' => 'jumlah obat',
            'satuan.*' => 'satuan obat',
            'petunjuk.*' => 'petunjuk penggunaan obat',
        ];
    }
}
