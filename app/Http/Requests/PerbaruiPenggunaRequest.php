<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PerbaruiPenggunaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->memilikiHakAkses('pengguna.role.ubah') ?? false;
    }

    protected function prepareForValidation(): void
    {
        if (is_string($this->input('alamat_email'))) {
            $this->merge(['alamat_email' => mb_strtolower(trim($this->input('alamat_email')))]);
        }
    }

    public function rules(): array
    {
        $pengguna = $this->route('pengguna');
        $adalahAkunSendiri = $pengguna instanceof User && $this->user()?->is($pengguna);

        return [
            'nama_lengkap' => ['required', 'string', 'max:150'],
            'alamat_email' => [
                'required',
                'email:rfc',
                'max:191',
                Rule::unique('pengguna', 'alamat_email')->ignore($pengguna?->getKey(), 'id_pengguna'),
            ],
            'kata_sandi' => ['nullable', 'string', 'min:12', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[^A-Za-z0-9\\s])[\\s\\S]+$/', 'confirmed'],
            'kata_sandi_confirmation' => ['required_with:kata_sandi', 'nullable', 'string'],
            'status_aktif' => ['required', 'boolean'],
            'id_peran' => $adalahAkunSendiri ? ['prohibited'] : ['required', 'array', 'min:1'],
            'id_peran.*' => ['required', 'integer', 'distinct', Rule::exists('peran', 'id_peran')],
        ];
    }

    public function messages(): array
    {
        return [
            'alamat_email.unique' => 'Alamat email sudah digunakan oleh akun lain.',
            'kata_sandi.min' => 'Kata sandi harus memiliki minimal 12 karakter.',
            'kata_sandi.regex' => 'Kata sandi harus memuat huruf kecil, huruf besar, angka, dan simbol.',
            'kata_sandi.confirmed' => 'Konfirmasi kata sandi tidak sama.',
            'kata_sandi_confirmation.required_with' => 'Konfirmasi kata sandi wajib diisi saat kata sandi baru digunakan.',
            'status_aktif.boolean' => 'Pilih status akun yang valid.',
            'status_aktif.prohibited' => 'Anda tidak dapat menonaktifkan akun yang sedang digunakan.',
            'id_peran.prohibited' => 'Role akun sendiri tidak dapat diubah dari halaman ini.',
            'id_peran.required' => 'Pilih minimal satu role untuk akun ini.',
            'id_peran.min' => 'Pilih minimal satu role untuk akun ini.',
            'id_peran.*.distinct' => 'Role yang sama tidak boleh dipilih lebih dari sekali.',
            'id_peran.*.exists' => 'Salah satu role yang dipilih tidak tersedia.',
        ];
    }

    public function attributes(): array
    {
        return [
            'nama_lengkap' => 'nama lengkap',
            'alamat_email' => 'alamat email',
            'kata_sandi' => 'kata sandi',
            'status_aktif' => 'status akun',
            'id_peran' => 'role',
        ];
    }
}
