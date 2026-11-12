<form method="POST" action="{{ route('antrean.update', $item) }}">
    @csrf
    @method('PATCH')
    <input type="hidden" name="status_kunjungan" value="{{ $tujuan }}">
    <button class="rounded-lg bg-emerald-700 px-3 py-2 text-[11px] font-semibold text-white">{{ $label }}</button>
</form>
