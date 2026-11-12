<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AutentikasiController extends Controller
{
    public function create(): View
    {
        return view('autentikasi.masuk');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'alamat_email' => ['required', 'email'],
            'kata_sandi' => ['required', 'string'],
        ], [
            'alamat_email.required' => 'Alamat email wajib diisi.',
            'alamat_email.email' => 'Format alamat email tidak valid.',
            'kata_sandi.required' => 'Kata sandi wajib diisi.',
        ]);

        if (! Auth::attempt(['alamat_email' => $data['alamat_email'], 'password' => $data['kata_sandi'], 'status_aktif' => true], $request->boolean('ingat_saya'))) {
            return back()->withErrors(['alamat_email' => 'Alamat email atau kata sandi tidak sesuai.'])->onlyInput('alamat_email');
        }

        $request->session()->regenerate();
        $request->user()->forceFill(['terakhir_masuk_pada' => now()])->save();

        return redirect()->intended(route('dasbor.pilih'));
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('masuk');
    }
}
