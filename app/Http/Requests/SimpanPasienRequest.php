<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SimpanPasienRequest extends FormRequest
{
    public function authorize(): bool { return $this->user()?->memilikiHakAkses($this->isMethod('POST') ? 'pasien.tambah' : 'pasien.ubah') ?? false; }
    public function rules(): array
    {
        return [
            'nama_lengkap' => ['required', 'string', 'max:150'],
            'nomor_identitas' => ['nullable', 'string', 'max:80'],
            'tanggal_lahir' => ['nullable', 'date', 'before_or_equal:today'],
            'jenis_kelamin' => ['nullable', Rule::in(['laki_laki', 'perempuan'])],
            'alamat' => ['nullable', 'string', 'max:1000'],
            'nomor_telepon' => ['nullable', 'string', 'max:30'],
            'alamat_email' => ['nullable', 'email', 'max:191'],
            'catatan_administratif' => ['nullable', 'string', 'max:2000'],
        ];
    }
    public function attributes(): array { return ['nama_lengkap' => 'nama lengkap', 'tanggal_lahir' => 'tanggal lahir', 'jenis_kelamin' => 'jenis kelamin', 'nomor_telepon' => 'nomor telepon', 'alamat_email' => 'alamat email']; }
}
