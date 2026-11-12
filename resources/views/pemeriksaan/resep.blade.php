<section class="order-5 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6" aria-labelledby="judul-resep">
    <div class="flex items-start gap-3">
        <span class="grid h-9 w-9 shrink-0 place-items-center rounded-xl bg-emerald-50 text-sm font-bold text-emerald-800" aria-hidden="true">04</span>
        <div>
            <h2 id="judul-resep" class="font-semibold text-slate-900">Resep obat</h2>
            <p class="mt-1 text-xs leading-5 text-slate-500">Resep disimpan sebagai draf sampai dokter memfinalisasi pemeriksaan.</p>
        </div>
        <span class="inline-flex w-fit items-center rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-600">Opsional</span>
    </div>
    @if($obat->isEmpty())
        <p class="mt-4 rounded-xl border border-slate-200 bg-slate-50 p-3 text-xs leading-5 text-slate-600">Belum ada obat aktif di katalog. Pemeriksaan tetap dapat disimpan tanpa resep.</p>
    @endif
    <div class="mt-5 grid gap-4 sm:grid-cols-2">
        <label for="obat_id" class="block text-sm font-medium text-slate-800">Pilih dari katalog
            <select id="obat_id" name="obat_id[]" aria-describedby="obat-bantuan @error('obat_id.0') obat-error @enderror" @error('obat_id.0') aria-invalid="true" @enderror class="{{ $kelasInput }} @error('obat_id.0') border-rose-400 @enderror">
                <option value="">— pilih obat —</option>
                @foreach($obat as $item)
                    <option value="{{ $item->id_obat }}" @selected((string) old('obat_id.0', $rincianResep?->id_obat ?? '') === (string) $item->id_obat)>{{ $item->nama_obat }}{{ $item->kekuatan_obat ? ' · '.$item->kekuatan_obat : '' }}{{ $item->bentuk_sediaan ? ' · '.$item->bentuk_sediaan : '' }}</option>
                @endforeach
            </select>
            <span id="obat-bantuan" class="{{ $kelasBantuan }}">Pilih obat dari katalog aktif. Jika resep tidak diperlukan, biarkan pilihan kosong.</span>@error('obat_id.0')<span id="obat-error" class="mt-1 block text-xs font-medium text-rose-700">{{ $message }}</span>@enderror
        </label>
        <label for="dosis" class="block text-sm font-medium text-slate-800">Dosis
            <input id="dosis" name="dosis[]" value="{{ old('dosis.0', $rincianResep?->dosis ?? '') }}" maxlength="100" placeholder="Contoh: 500 mg" aria-describedby="dosis-bantuan @error('dosis.0') dosis-error @enderror" @error('dosis.0') aria-invalid="true" @enderror class="{{ $kelasInput }} @error('dosis.0') border-rose-400 @enderror">
            <span id="dosis-bantuan" class="{{ $kelasBantuan }}">Jumlah atau kekuatan per kali pemberian.</span>@error('dosis.0')<span id="dosis-error" class="mt-1 block text-xs font-medium text-rose-700">{{ $message }}</span>@enderror
        </label>
        <label for="frekuensi" class="block text-sm font-medium text-slate-800">Frekuensi
            <input id="frekuensi" name="frekuensi[]" value="{{ old('frekuensi.0', $rincianResep?->frekuensi_pemberian ?? '') }}" maxlength="100" placeholder="Contoh: 3 kali sehari" aria-describedby="frekuensi-bantuan @error('frekuensi.0') frekuensi-error @enderror" @error('frekuensi.0') aria-invalid="true" @enderror class="{{ $kelasInput }} @error('frekuensi.0') border-rose-400 @enderror">
            <span id="frekuensi-bantuan" class="{{ $kelasBantuan }}">Berapa kali dan kapan obat digunakan.</span>@error('frekuensi.0')<span id="frekuensi-error" class="mt-1 block text-xs font-medium text-rose-700">{{ $message }}</span>@enderror
        </label>
        <label for="jumlah" class="block text-sm font-medium text-slate-800">Jumlah
            <input id="jumlah" name="jumlah[]" type="number" inputmode="decimal" min="0.01" max="10000" step="0.01" value="{{ old('jumlah.0', $rincianResep?->jumlah_diresepkan ?? '') }}" placeholder="Contoh: 10" aria-describedby="jumlah-bantuan @error('jumlah.0') jumlah-error @enderror" @error('jumlah.0') aria-invalid="true" @enderror class="{{ $kelasInput }} @error('jumlah.0') border-rose-400 @enderror">
            <span id="jumlah-bantuan" class="{{ $kelasBantuan }}">Jumlah harus lebih dari 0 dan maksimal 10.000.</span>@error('jumlah.0')<span id="jumlah-error" class="mt-1 block text-xs font-medium text-rose-700">{{ $message }}</span>@enderror
        </label>
        <label for="satuan" class="block text-sm font-medium text-slate-800">Satuan
            <input id="satuan" name="satuan[]" value="{{ old('satuan.0', $rincianResep?->satuan_jumlah ?? '') }}" maxlength="40" placeholder="Contoh: tablet" aria-describedby="satuan-bantuan @error('satuan.0') satuan-error @enderror" @error('satuan.0') aria-invalid="true" @enderror class="{{ $kelasInput }} @error('satuan.0') border-rose-400 @enderror">
            <span id="satuan-bantuan" class="{{ $kelasBantuan }}">Satuan jumlah yang diresepkan.</span>@error('satuan.0')<span id="satuan-error" class="mt-1 block text-xs font-medium text-rose-700">{{ $message }}</span>@enderror
        </label>
        <label for="petunjuk" class="block text-sm font-medium text-slate-800 sm:col-span-2">Petunjuk penggunaan
            <textarea id="petunjuk" name="petunjuk[]" rows="3" maxlength="500" placeholder="Contoh: diminum setelah makan" aria-describedby="petunjuk-bantuan @error('petunjuk.0') petunjuk-error @enderror" @error('petunjuk.0') aria-invalid="true" @enderror class="{{ $kelasInput }} @error('petunjuk.0') border-rose-400 @enderror">{{ old('petunjuk.0', $rincianResep?->petunjuk_penggunaan ?? '') }}</textarea>
            <span id="petunjuk-bantuan" class="{{ $kelasBantuan }}">Instruksi penggunaan yang jelas untuk pasien.</span>@error('petunjuk.0')<span id="petunjuk-error" class="mt-1 block text-xs font-medium text-rose-700">{{ $message }}</span>@enderror
        </label>
        <label for="catatan_resep" class="block text-sm font-medium text-slate-800 sm:col-span-2">Catatan resep <span class="font-normal text-slate-500">(opsional)</span>
            <textarea id="catatan_resep" name="catatan_resep" rows="2" maxlength="2000" placeholder="Catatan tambahan terkait resep…" aria-describedby="catatan-resep-bantuan @error('catatan_resep') catatan-resep-error @enderror" @error('catatan_resep') aria-invalid="true" @enderror class="{{ $kelasInput }} @error('catatan_resep') border-rose-400 @enderror">{{ old('catatan_resep', $resepTersimpan?->catatan_resep ?? '') }}</textarea>
            <span id="catatan-resep-bantuan" class="{{ $kelasBantuan }}">Maksimal 2.000 karakter.</span>@error('catatan_resep')<span id="catatan-resep-error" class="mt-1 block text-xs font-medium text-rose-700">{{ $message }}</span>@enderror
        </label>
    </div>
</section>
