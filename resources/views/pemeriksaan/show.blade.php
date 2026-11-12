@extends('layouts.aplikasi')

@section('content')
@php
    $resepTersimpan = $rekamMedis?->resep?->first();
    $rincianResep = $resepTersimpan?->rincian?->first();
    $pemeriksaanFinal = $rekamMedis?->status_rekam_medis === 'difinalisasi';
    $statusKunjungan = str_replace('_', ' ', ucfirst($kunjungan->status_kunjungan));
    $kelasInput = 'mt-2 block w-full rounded-xl border border-slate-300 bg-white px-3.5 py-3 text-sm text-slate-800 shadow-sm outline-none transition placeholder:text-slate-400 hover:border-slate-400 focus:border-teal-700 focus:ring-4 focus:ring-teal-700/10';
    $kelasBantuan = 'mt-1.5 block text-xs leading-5 text-slate-500';
@endphp
<div class="mx-auto max-w-7xl pb-10">
    <nav class="mb-5 flex items-center gap-2 text-xs text-slate-500" aria-label="Breadcrumb">
        <a href="{{ route('dasbor.pilih') }}" class="transition hover:text-teal-800">Dashboard</a>
        <span aria-hidden="true">›</span>
        <a href="{{ route('antrean.index') }}" class="transition hover:text-teal-800">Antrean pasien</a>
        <span aria-hidden="true">›</span>
        <span class="font-medium text-slate-800" aria-current="page">Pemeriksaan pasien</span>
    </nav>
    <header class="mb-6 flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
        <div>
            <p class="text-xs font-bold uppercase tracking-[0.18em] text-teal-700">Ruang kerja dokter</p>
            <h1 class="mt-2 text-3xl font-semibold tracking-tight text-slate-900">Konsultasi pasien</h1>
            <p class="mt-2 text-sm text-slate-600">{{ $kunjungan->pasien->nama_lengkap }} <span aria-hidden="true">·</span> {{ $kunjungan->pasien->nomor_rekam_medis }}</p>
        </div>
        <a href="{{ route('antrean.index') }}" class="inline-flex min-h-10 items-center justify-center rounded-xl border border-slate-200 bg-white px-4 text-sm font-medium text-slate-700 shadow-sm transition hover:bg-slate-50">Kembali ke antrean</a>
    </header>

    @if(session('status'))
        <div role="status" class="mb-5 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">{{ session('status') }}</div>
    @endif
    @if($errors->any())
        <div id="validation-summary" role="alert" tabindex="-1" class="mb-6 rounded-xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-900">
            <p class="font-semibold">Periksa kembali data berikut sebelum menyimpan:</p>
            <ul class="mt-2 list-inside list-disc space-y-1">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif
    @if($pemeriksaanFinal)
        <div role="status" class="mb-6 rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm leading-6 text-amber-900">
            Catatan ini telah difinalisasi. Perubahan klinis setelah finalisasi perlu mengikuti alur koreksi yang dapat ditelusuri.
        </div>
    @endif

    <form id="form-pemeriksaan" method="POST" action="{{ route('pemeriksaan.store', $kunjungan) }}" class="grid items-start gap-5 lg:grid-cols-[minmax(0,1fr)_288px]">
        @csrf
        <div class="flex flex-col gap-5">
            <section class="order-1 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6" aria-labelledby="judul-pasien">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-teal-700">Pasien</p>
                        <h2 id="judul-pasien" class="mt-1 text-lg font-semibold text-slate-900">{{ $kunjungan->pasien->nama_lengkap }}</h2>
                        <p class="mt-1 text-sm text-slate-500">{{ $kunjungan->pasien->nomor_rekam_medis }} <span aria-hidden="true">·</span> {{ $kunjungan->pasien->jenis_kelamin ?? 'Data jenis kelamin tidak tersedia' }} <span aria-hidden="true">·</span> {{ $kunjungan->pasien->tanggal_lahir?->age ? $kunjungan->pasien->tanggal_lahir->age.' tahun' : 'Usia tidak tersedia' }}</p>
                    </div>
                    <div class="text-left sm:text-right">
                        <p class="text-2xl font-bold tracking-tight text-teal-800">A-{{ $kunjungan->nomorAntrean() }}</p>
                        <span class="mt-1 inline-flex items-center gap-2 rounded-full bg-violet-50 px-3 py-1.5 text-xs font-semibold capitalize text-violet-800 ring-1 ring-inset ring-violet-200"><span class="h-1.5 w-1.5 rounded-full bg-violet-600" aria-hidden="true"></span>{{ $statusKunjungan }}</span>
                    </div>
                </div>
                <div class="mt-4 rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm leading-6 text-amber-950">
                    <span class="font-semibold text-amber-900">Keluhan awal resepsionis</span>
                    <p class="mt-1">{{ $kunjungan->keluhan_awal ?: 'Tidak ada keluhan awal yang dicatat.' }}</p>
                </div>
            </section>

            <section class="order-3 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6" aria-labelledby="judul-kunjungan">
                <div class="flex items-start gap-3">
                    <span class="grid h-9 w-9 shrink-0 place-items-center rounded-xl bg-teal-50 text-sm font-bold text-teal-800" aria-hidden="true">02</span>
                    <div><h2 id="judul-kunjungan" class="font-semibold text-slate-900">Hasil pemeriksaan</h2><p class="mt-1 text-xs leading-5 text-slate-500">Catat anamnesis pasien dan temuan pemeriksaan klinis secara terpisah.</p></div>
                </div>
                <div class="mt-5 grid gap-4 sm:grid-cols-2">
                    <label for="anamnesis" class="block text-sm font-medium text-slate-800">Anamnesis dokter <span class="text-rose-600" aria-hidden="true">*</span>
                        <textarea id="anamnesis" name="anamnesis" rows="5" maxlength="10000" data-finalize-required placeholder="Tuliskan riwayat keluhan, durasi, gejala penyerta, dan informasi relevan dari pasien…" aria-describedby="anamnesis-bantuan @error('anamnesis') anamnesis-error @enderror" @error('anamnesis') aria-invalid="true" @enderror class="{{ $kelasInput }} @error('anamnesis') border-rose-400 focus:border-rose-600 focus:ring-rose-600/10 @enderror">{{ old('anamnesis', $rekamMedis?->anamnesis ?? '') }}</textarea>
                        <span id="anamnesis-bantuan" class="{{ $kelasBantuan }}">Wajib saat finalisasi. Catat riwayat klinis yang Anda gali; verifikasi keluhan awal dan maksimal 10.000 karakter.</span>
                        @error('anamnesis')<span id="anamnesis-error" class="mt-1 block text-xs font-medium text-rose-700">{{ $message }}</span>@enderror
                    </label>
                    <label for="hasil_pemeriksaan" class="block text-sm font-medium text-slate-800">Hasil pemeriksaan <span class="text-rose-600" aria-hidden="true">*</span>
                        <textarea id="hasil_pemeriksaan" name="hasil_pemeriksaan" rows="5" maxlength="10000" data-finalize-required placeholder="Catat temuan pemeriksaan yang relevan…" aria-describedby="hasil-bantuan @error('hasil_pemeriksaan') hasil-error @enderror" @error('hasil_pemeriksaan') aria-invalid="true" @enderror class="{{ $kelasInput }} @error('hasil_pemeriksaan') border-rose-400 focus:border-rose-600 focus:ring-rose-600/10 @enderror">{{ old('hasil_pemeriksaan', $rekamMedis?->hasil_pemeriksaan ?? '') }}</textarea>
                        <span id="hasil-bantuan" class="{{ $kelasBantuan }}">Dokumentasikan temuan pemeriksaan fisik/penunjang. Wajib diisi untuk finalisasi.</span>
                        @error('hasil_pemeriksaan')<span id="hasil-error" class="mt-1 block text-xs font-medium text-rose-700">{{ $message }}</span>@enderror
                    </label>
                </div>
            </section>

            <section class="order-2 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6" aria-labelledby="judul-vital">
                <div class="flex items-start gap-3">
                    <span class="grid h-9 w-9 shrink-0 place-items-center rounded-xl bg-sky-50 text-sm font-bold text-sky-800" aria-hidden="true">01</span>
                    <div><h2 id="judul-vital" class="font-semibold text-slate-900">Tanda vital <span class="font-normal text-slate-500">(opsional)</span></h2><p class="mt-1 text-xs leading-5 text-slate-500">Masukkan hasil ukur pasien. Kosongkan bila tidak dilakukan; angka di luar rentang akan ditolak.</p></div>
                </div>
                <div class="mt-5 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    <label for="tekanan_darah" class="block text-sm font-medium text-slate-800">Tekanan darah
                        <input id="tekanan_darah" name="tekanan_darah" type="text" inputmode="numeric" maxlength="7" value="{{ old('tekanan_darah', $rekamMedis?->tanda_vital['tekanan_darah'] ?? '') }}" placeholder="120/80" aria-describedby="tekanan-bantuan @error('tekanan_darah') tekanan-error @enderror" @error('tekanan_darah') aria-invalid="true" @enderror class="{{ $kelasInput }} @error('tekanan_darah') border-rose-400 @enderror">
                        <span id="tekanan-bantuan" class="{{ $kelasBantuan }}">mmHg, format sistolik/diastolik, contoh 120/80.</span>@error('tekanan_darah')<span id="tekanan-error" class="mt-1 block text-xs font-medium text-rose-700">{{ $message }}</span>@enderror
                    </label>
                    <label for="suhu_tubuh" class="block text-sm font-medium text-slate-800">Suhu tubuh
                        <div class="relative"><input id="suhu_tubuh" name="suhu_tubuh" type="number" inputmode="decimal" min="20" max="50" step="0.1" value="{{ old('suhu_tubuh', $rekamMedis?->tanda_vital['suhu_tubuh'] ?? '') }}" placeholder="36.5" aria-describedby="suhu-bantuan @error('suhu_tubuh') suhu-error @enderror" @error('suhu_tubuh') aria-invalid="true" @enderror class="{{ $kelasInput }} pr-14 @error('suhu_tubuh') border-rose-400 @enderror"><span class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-xs text-slate-500">°C</span></div>
                        <span id="suhu-bantuan" class="{{ $kelasBantuan }}">Rentang valid 20–50 °C.</span>@error('suhu_tubuh')<span id="suhu-error" class="mt-1 block text-xs font-medium text-rose-700">{{ $message }}</span>@enderror
                    </label>
                    <label for="denyut_nadi" class="block text-sm font-medium text-slate-800">Denyut nadi
                        <div class="relative"><input id="denyut_nadi" name="denyut_nadi" type="number" inputmode="numeric" min="20" max="250" step="1" value="{{ old('denyut_nadi', $rekamMedis?->tanda_vital['denyut_nadi'] ?? '') }}" placeholder="72" aria-describedby="nadi-bantuan @error('denyut_nadi') nadi-error @enderror" @error('denyut_nadi') aria-invalid="true" @enderror class="{{ $kelasInput }} pr-20 @error('denyut_nadi') border-rose-400 @enderror"><span class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-[11px] text-slate-500">kali/menit</span></div>
                        <span id="nadi-bantuan" class="{{ $kelasBantuan }}">Bilangan bulat 20–250 kali per menit.</span>@error('denyut_nadi')<span id="nadi-error" class="mt-1 block text-xs font-medium text-rose-700">{{ $message }}</span>@enderror
                    </label>
                    <label for="frekuensi_pernapasan" class="block text-sm font-medium text-slate-800">Frekuensi pernapasan
                        <div class="relative"><input id="frekuensi_pernapasan" name="frekuensi_pernapasan" type="number" inputmode="numeric" min="5" max="80" step="1" value="{{ old('frekuensi_pernapasan', $rekamMedis?->tanda_vital['frekuensi_pernapasan'] ?? '') }}" placeholder="18" aria-describedby="napas-bantuan @error('frekuensi_pernapasan') napas-error @enderror" @error('frekuensi_pernapasan') aria-invalid="true" @enderror class="{{ $kelasInput }} pr-20 @error('frekuensi_pernapasan') border-rose-400 @enderror"><span class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-[11px] text-slate-500">kali/menit</span></div>
                        <span id="napas-bantuan" class="{{ $kelasBantuan }}">Bilangan bulat 5–80 kali per menit.</span>@error('frekuensi_pernapasan')<span id="napas-error" class="mt-1 block text-xs font-medium text-rose-700">{{ $message }}</span>@enderror
                    </label>
                    <label for="berat_badan" class="block text-sm font-medium text-slate-800">Berat badan
                        <div class="relative"><input id="berat_badan" name="berat_badan" type="number" inputmode="decimal" min="0.1" max="500" step="0.1" value="{{ old('berat_badan', $rekamMedis?->tanda_vital['berat_badan'] ?? '') }}" placeholder="60" aria-describedby="berat-bantuan @error('berat_badan') berat-error @enderror" @error('berat_badan') aria-invalid="true" @enderror class="{{ $kelasInput }} pr-12 @error('berat_badan') border-rose-400 @enderror"><span class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-xs text-slate-500">kg</span></div>
                        <span id="berat-bantuan" class="{{ $kelasBantuan }}">Lebih dari 0 sampai 500 kg.</span>@error('berat_badan')<span id="berat-error" class="mt-1 block text-xs font-medium text-rose-700">{{ $message }}</span>@enderror
                    </label>
                    <label for="tinggi_badan" class="block text-sm font-medium text-slate-800">Tinggi badan
                        <div class="relative"><input id="tinggi_badan" name="tinggi_badan" type="number" inputmode="decimal" min="0.1" max="250" step="0.1" value="{{ old('tinggi_badan', $rekamMedis?->tanda_vital['tinggi_badan'] ?? '') }}" placeholder="165" aria-describedby="tinggi-bantuan @error('tinggi_badan') tinggi-error @enderror" @error('tinggi_badan') aria-invalid="true" @enderror class="{{ $kelasInput }} pr-12 @error('tinggi_badan') border-rose-400 @enderror"><span class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-xs text-slate-500">cm</span></div>
                        <span id="tinggi-bantuan" class="{{ $kelasBantuan }}">Lebih dari 0 sampai 250 cm.</span>@error('tinggi_badan')<span id="tinggi-error" class="mt-1 block text-xs font-medium text-rose-700">{{ $message }}</span>@enderror
                    </label>
                </div>
            </section>

            <section class="order-4 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6" aria-labelledby="judul-diagnosis">
                <div class="flex items-start gap-3">
                    <span class="grid h-9 w-9 shrink-0 place-items-center rounded-xl bg-violet-50 text-sm font-bold text-violet-800" aria-hidden="true">03</span>
                    <div><h2 id="judul-diagnosis" class="font-semibold text-slate-900">Diagnosis dan rencana perawatan</h2><p class="mt-1 text-xs leading-5 text-slate-500">Diagnosis wajib diisi sebelum finalisasi. Simpan draf untuk melanjutkan nanti.</p></div>
                </div>
                <div class="mt-5 grid gap-4 sm:grid-cols-2">
                    <label for="diagnosa" class="block text-sm font-medium text-slate-800">Diagnosis utama <span class="text-rose-600" aria-hidden="true">*</span>
                        <textarea id="diagnosa" name="diagnosa[]" rows="3" maxlength="255" data-finalize-required placeholder="Masukkan diagnosis yang telah ditetapkan…" aria-describedby="diagnosa-bantuan @error('diagnosa') diagnosa-error @enderror @error('diagnosa.0') diagnosa-item-error @enderror" @if($errors->has('diagnosa') || $errors->has('diagnosa.0')) aria-invalid="true" @endif class="{{ $kelasInput }} @if($errors->has('diagnosa') || $errors->has('diagnosa.0')) border-rose-400 @endif">{{ old('diagnosa.0', $rekamMedis?->diagnosa?->first()?->nama_diagnosa ?? '') }}</textarea>
                        <span id="diagnosa-bantuan" class="{{ $kelasBantuan }}">Wajib saat finalisasi. Gunakan istilah klinis yang sesuai; maksimal 255 karakter.</span>@error('diagnosa')<span id="diagnosa-error" class="mt-1 block text-xs font-medium text-rose-700">{{ $message }}</span>@enderror @error('diagnosa.0')<span id="diagnosa-item-error" class="mt-1 block text-xs font-medium text-rose-700">{{ $message }}</span>@enderror
                    </label>
                    <label for="tindakan" class="block text-sm font-medium text-slate-800">Tindakan
                        <textarea id="tindakan" name="tindakan[]" rows="3" maxlength="255" placeholder="Catat tindakan yang dilakukan, jika ada…" aria-describedby="tindakan-bantuan @error('tindakan.0') tindakan-error @enderror" @error('tindakan.0') aria-invalid="true" @enderror class="{{ $kelasInput }} @error('tindakan.0') border-rose-400 @enderror">{{ old('tindakan.0', $rekamMedis?->tindakan?->first()?->nama_tindakan ?? '') }}</textarea>
                        <span id="tindakan-bantuan" class="{{ $kelasBantuan }}">Opsional. Maksimal 255 karakter.</span>@error('tindakan.0')<span id="tindakan-error" class="mt-1 block text-xs font-medium text-rose-700">{{ $message }}</span>@enderror
                    </label>
                </div>
                <label for="rencana_terapi" class="mt-5 block text-sm font-medium text-slate-800">Rencana terapi
                    <textarea id="rencana_terapi" name="rencana_terapi" rows="3" maxlength="10000" placeholder="Rencana perawatan atau terapi yang dipertimbangkan…" aria-describedby="terapi-bantuan @error('rencana_terapi') terapi-error @enderror" @error('rencana_terapi') aria-invalid="true" @enderror class="{{ $kelasInput }} @error('rencana_terapi') border-rose-400 @enderror">{{ old('rencana_terapi', $rekamMedis?->rencana_terapi ?? '') }}</textarea>
                    <span id="terapi-bantuan" class="{{ $kelasBantuan }}">Catat rencana perawatan yang diputuskan dokter, bukan rekomendasi otomatis. Maksimal 10.000 karakter.</span>@error('rencana_terapi')<span id="terapi-error" class="mt-1 block text-xs font-medium text-rose-700">{{ $message }}</span>@enderror
                </label>
                <label for="instruksi_tindak_lanjut" class="mt-5 block text-sm font-medium text-slate-800">Instruksi tindak lanjut
                    <textarea id="instruksi_tindak_lanjut" name="instruksi_tindak_lanjut" rows="3" maxlength="10000" placeholder="Jadwal kontrol atau hal yang perlu dipantau…" aria-describedby="lanjut-bantuan @error('instruksi_tindak_lanjut') lanjut-error @enderror" @error('instruksi_tindak_lanjut') aria-invalid="true" @enderror class="{{ $kelasInput }} @error('instruksi_tindak_lanjut') border-rose-400 @enderror">{{ old('instruksi_tindak_lanjut', $rekamMedis?->instruksi_tindak_lanjut ?? '') }}</textarea>
                    <span id="lanjut-bantuan" class="{{ $kelasBantuan }}">Petunjuk kontrol, pemantauan, atau tindak lanjut yang disepakati.</span>@error('instruksi_tindak_lanjut')<span id="lanjut-error" class="mt-1 block text-xs font-medium text-rose-700">{{ $message }}</span>@enderror
                </label>
            </section>
            @include('pemeriksaan.resep')
        </div>

        <aside class="flex flex-col gap-4 lg:sticky lg:top-5" aria-label="Status dan tindakan pemeriksaan">
            <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm" aria-labelledby="status-rekam-medis">
                <h2 id="status-rekam-medis" class="font-semibold text-slate-900">Status rekam medis</h2>
                <dl class="mt-4 space-y-3 text-sm">
                    <div><dt class="text-xs text-slate-500">Kunjungan</dt><dd class="mt-1 font-medium capitalize text-slate-800">{{ $statusKunjungan }}</dd></div>
                    <div><dt class="text-xs text-slate-500">Rekam medis</dt><dd class="mt-1 font-medium capitalize text-slate-800">{{ $rekamMedis ? str_replace('_', ' ', $rekamMedis->status_rekam_medis) : 'Belum ada' }}</dd></div>
                </dl>
                @unless($pemeriksaanFinal)
                    <div class="mt-5 flex flex-col gap-3">
                        <button type="submit" name="finalisasi_resep" value="0" data-save-draft class="flex min-h-11 w-full items-center justify-center rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50 focus:outline-none focus:ring-4 focus:ring-slate-500/10">Simpan draf</button>
                        <button type="submit" name="finalisasi_resep" value="1" data-finalize class="flex min-h-11 w-full items-center justify-center rounded-xl bg-teal-800 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-teal-900 focus:outline-none focus:ring-4 focus:ring-teal-700/20">Simpan &amp; selesaikan</button>
                    </div>
                    <p class="mt-3 text-xs leading-5 text-slate-500">Periksa semua data sebelum finalisasi. Catatan yang sudah final tidak dapat ditimpa.</p>
                @else
                    <p class="mt-4 rounded-xl bg-amber-50 p-3 text-xs leading-5 text-amber-900">Catatan telah difinalisasi dan tidak dapat diedit.</p>
                    <a href="{{ route('pemeriksaan.print', $kunjungan) }}" class="mt-4 flex min-h-11 w-full items-center justify-center rounded-xl border border-slate-200 px-4 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">Cetak hasil pemeriksaan</a>
                @endunless
            </section>
            <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm" aria-labelledby="status-resep">
                <h2 id="status-resep" class="font-semibold text-slate-900">Resep</h2>
                @if($rincianResep)
                    <p class="mt-2 text-sm font-medium text-slate-800">{{ $rincianResep->nama_obat_saat_diresepkan }}</p>
                    <p class="mt-1 text-xs leading-5 text-slate-500">{{ $rincianResep->dosis }} · {{ $rincianResep->frekuensi_pemberian }}</p>
                @else
                    <p class="mt-2 text-sm text-slate-500">Belum ada resep untuk kunjungan ini.</p>
                @endif
            </section>
        </aside>
    </form>
</div>
<script>
(() => {
    const form = document.getElementById('form-pemeriksaan');
    if (!form) return;

    const requiredForFinalization = [...form.querySelectorAll('[data-finalize-required]')];
    const finalizeButton = form.querySelector('[data-finalize]');
    const draftButton = form.querySelector('[data-save-draft]');
    const medicationSelect = form.querySelector('#obat_id');
    const medicationFields = [...form.querySelectorAll('[name="dosis[]"], [name="frekuensi[]"], [name="jumlah[]"], [name="satuan[]"], [name="petunjuk[]"]')];

    const syncMedicationRequirement = () => {
        const selected = Boolean(medicationSelect?.value);
        medicationFields.forEach((field) => {
            field.required = selected;
            field.setAttribute('aria-required', String(selected));
        });
    };

    medicationSelect?.addEventListener('change', syncMedicationRequirement);
    syncMedicationRequirement();

    finalizeButton?.addEventListener('click', (event) => {
        requiredForFinalization.forEach((field) => {
            field.required = true;
            field.setAttribute('aria-required', 'true');
        });
        if (!form.reportValidity()) {
            event.preventDefault();
            return;
        }
        if (!window.confirm('Finalisasi pemeriksaan? Pastikan semua catatan dan resep sudah diperiksa.')) event.preventDefault();
    });

    draftButton?.addEventListener('click', () => {
        requiredForFinalization.forEach((field) => {
            field.required = false;
            field.removeAttribute('aria-required');
        });
    });

    const summary = document.getElementById('validation-summary');
    if (summary) summary.focus({ preventScroll: true });
})();
</script>
@endsection
