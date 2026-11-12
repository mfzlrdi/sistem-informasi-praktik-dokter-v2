@php
    $routeName = request()->route()?->getName();

    $breadcrumbItems = match ($routeName) {
        'dasbor.pilih', 'dasbor.dokter', 'dasbor.resepsionis', 'dasbor.administrator' => [
            ['label' => 'Ringkasan'],
        ],
        'pendaftaran.index' => [
            ['label' => 'Pendaftaran'],
        ],
        'kunjungan.create' => [
            ['label' => 'Pendaftaran', 'url' => route('pendaftaran.index')],
            ['label' => 'Pendaftaran kunjungan'],
        ],
        'kunjungan.tiket' => [
            ['label' => 'Pendaftaran', 'url' => route('pendaftaran.index')],
            ['label' => 'Tiket kunjungan'],
        ],
        'antrean.index' => [
            ['label' => 'Antrean pasien'],
        ],
        'riwayat.kunjungan' => [
            ['label' => 'Riwayat kunjungan'],
        ],
        'pemeriksaan.show' => [
            ['label' => 'Antrean pasien', 'url' => route('antrean.index')],
            ['label' => 'Pemeriksaan pasien'],
        ],
        'pasien.index' => [
            ['label' => 'Data pasien'],
        ],
        'pasien.create' => [
            ['label' => 'Data pasien', 'url' => route('pasien.index')],
            ['label' => 'Tambah pasien'],
        ],
        'pasien.show' => [
            ['label' => 'Data pasien', 'url' => route('pasien.index')],
            ['label' => 'Detail pasien'],
        ],
        'pasien.edit' => [
            ['label' => 'Data pasien', 'url' => route('pasien.index')],
            ['label' => 'Ubah data pasien'],
        ],
        'laporan.index' => [
            ['label' => 'Laporan'],
        ],
        'pemenuhan.index' => [
            ['label' => 'Tugas resep'],
        ],
        'resep.print' => [
            ['label' => 'Tugas resep', 'url' => route('pemenuhan.index')],
            ['label' => 'Cetak resep'],
        ],
        'pengguna.index' => [
            ['label' => 'Data pengguna'],
        ],
        'pengguna.create' => [
            ['label' => 'Data pengguna', 'url' => route('pengguna.index')],
            ['label' => 'Tambah pengguna'],
        ],
        'pengguna.edit' => [
            ['label' => 'Data pengguna', 'url' => route('pengguna.index')],
            ['label' => 'Ubah pengguna'],
        ],
        'obat.index' => [
            ['label' => 'Katalog obat'],
        ],
        'obat.create' => [
            ['label' => 'Katalog obat', 'url' => route('obat.index')],
            ['label' => 'Tambah obat'],
        ],
        'obat.edit' => [
            ['label' => 'Katalog obat', 'url' => route('obat.index')],
            ['label' => 'Ubah obat'],
        ],
        'notifikasi.index' => [
            ['label' => 'Notifikasi'],
        ],
        default => [
            ['label' => \Illuminate\Support\Str::headline(str_replace('.', ' ', (string) $routeName))],
        ],
    };
@endphp

<nav aria-label="Breadcrumb" class="mx-auto mb-5 w-full max-w-[1260px]">
    <ol class="flex flex-wrap items-center gap-x-1.5 gap-y-1 text-xs">
        <li class="flex items-center gap-1.5">
            <a href="{{ route('dasbor.pilih') }}" class="rounded-md px-2 py-1.5 text-slate-500 transition-colors hover:bg-emerald-50 hover:text-emerald-800 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-500">Dashboard</a>
            <svg class="h-3.5 w-3.5 text-slate-300" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M7.2 4.7a.75.75 0 0 1 1.06 0l5 5a.75.75 0 0 1 0 1.06l-5 5A.75.75 0 1 1 7.2 14.7l4.47-4.47L7.2 5.76a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd"/></svg>
        </li>
        @foreach($breadcrumbItems as $item)
            <li class="flex items-center gap-1.5">
                @if(!$loop->first)
                    <svg class="h-3.5 w-3.5 text-slate-300" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M7.2 4.7a.75.75 0 0 1 1.06 0l5 5a.75.75 0 0 1 0 1.06l-5 5A.75.75 0 1 1 7.2 14.7l4.47-4.47L7.2 5.76a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd"/></svg>
                @endif
                @if(!$loop->last && !empty($item['url']))
                    <a href="{{ $item['url'] }}" class="rounded-md px-2 py-1.5 text-slate-500 transition-colors hover:bg-emerald-50 hover:text-emerald-800 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-500">{{ $item['label'] }}</a>
                @else
                    <span aria-current="page" class="rounded-md px-2 py-1.5 font-medium text-slate-700">{{ $item['label'] }}</span>
                @endif
            </li>
        @endforeach
    </ol>
</nav>
