@extends('layouts.aplikasi')

@section('content')
<div class="mx-auto max-w-6xl space-y-6">
    <header class="flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
        <div>
            <p class="text-xs font-semibold uppercase tracking-[0.16em] text-emerald-700">Administrasi sistem</p>
            <h1 class="mt-2 text-3xl font-bold tracking-tight text-slate-900">Data Pengguna</h1>
            <p class="mt-2 max-w-2xl text-sm text-slate-600">Kelola akun, status akses, dan role pengguna praktik.</p>
        </div>
        <a href="{{ route('pengguna.create') }}" class="inline-flex min-h-11 items-center justify-center gap-2 rounded-xl bg-emerald-700 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-800 focus:outline-none focus:ring-2 focus:ring-emerald-600 focus:ring-offset-2">
            <span aria-hidden="true" class="text-lg leading-none">+</span>
            Tambah pengguna
        </a>
    </header>

    @if(session('status'))
        <div role="status" aria-live="polite" class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">{{ session('status') }}</div>
    @endif

    @if($errors->has('pengguna'))
        <div role="alert" class="rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-800">{{ $errors->first('pengguna') }}</div>
    @endif

    <section aria-label="Ringkasan akun" class="grid gap-3 sm:grid-cols-3">
        <article class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
            <p class="text-sm text-slate-500">Semua akun</p>
            <p class="mt-2 text-2xl font-bold text-slate-900">{{ $jumlahPengguna }}</p>
        </article>
        <article class="rounded-2xl border border-emerald-200 bg-emerald-50/70 p-4 shadow-sm">
            <p class="text-sm text-emerald-800">Akun aktif</p>
            <p class="mt-2 text-2xl font-bold text-emerald-900">{{ $jumlahAktif }}</p>
        </article>
        <article class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
            <p class="text-sm text-slate-500">Akun nonaktif</p>
            <p class="mt-2 text-2xl font-bold text-slate-700">{{ $jumlahNonaktif }}</p>
        </article>
    </section>

    <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="flex flex-col gap-4 border-b border-slate-100 p-5 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <h2 class="font-semibold text-slate-900">Daftar akun</h2>
                <p class="mt-1 text-sm text-slate-500">{{ $pengguna->total() }} akun terdaftar</p>
            </div>
            <form role="search" method="GET" action="{{ route('pengguna.index') }}" class="flex w-full gap-2 sm:max-w-sm">
                <label class="sr-only" for="cari-pengguna">Cari nama atau email</label>
                <input id="cari-pengguna" name="q" value="{{ $search }}" type="search" maxlength="100" placeholder="Cari nama atau email…" class="min-h-11 min-w-0 flex-1 rounded-xl border border-slate-200 px-3 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100">
                <button class="min-h-11 rounded-xl border border-slate-200 px-4 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-emerald-600 focus:ring-offset-2">Cari</button>
            </form>
        </div>

        @if($pengguna->isEmpty())
            <div class="px-5 py-14 text-center">
                <h3 class="font-semibold text-slate-900">Belum ada akun yang cocok</h3>
                <p class="mt-2 text-sm text-slate-500">Ubah kata pencarian atau tambahkan akun pengguna baru.</p>
                @if($search !== '')
                    <a href="{{ route('pengguna.index') }}" class="mt-4 inline-flex min-h-10 items-center rounded-lg px-3 text-sm font-semibold text-emerald-800 hover:bg-emerald-50">Hapus pencarian</a>
                @endif
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full min-w-[760px] text-left text-sm">
                    <caption class="sr-only">Daftar akun pengguna praktik</caption>
                    <thead class="bg-slate-50 text-xs font-semibold uppercase tracking-wide text-slate-500">
                        <tr>
                            <th scope="col" class="px-5 py-3">Pengguna</th>
                            <th scope="col" class="px-5 py-3">Role akses</th>
                            <th scope="col" class="px-5 py-3">Status</th>
                            <th scope="col" class="px-5 py-3 text-right">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($pengguna as $akun)
                            <tr class="align-top">
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-3">
                                        <span aria-hidden="true" class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-emerald-50 text-sm font-bold text-emerald-800">{{ mb_strtoupper(mb_substr($akun->nama_lengkap, 0, 1)) }}</span>
                                        <div class="min-w-0">
                                            <div class="flex flex-wrap items-center gap-2 font-semibold text-slate-900">
                                                <span>{{ $akun->nama_lengkap }}</span>
                                                @if($akun->is(auth()->user()))<span class="rounded-full bg-slate-100 px-2 py-0.5 text-[10px] font-semibold uppercase tracking-wide text-slate-600">Akun Anda</span>@endif
                                            </div>
                                            <p class="mt-1 break-all text-xs text-slate-500">{{ $akun->alamat_email }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-4">
                                    <div class="flex max-w-sm flex-wrap gap-1.5">
                                        @forelse($akun->roles as $role)
                                            <span class="rounded-full border border-slate-200 bg-slate-50 px-2.5 py-1 text-xs font-medium text-slate-700">{{ ucfirst($role->nama_peran) }}</span>
                                        @empty
                                            <span class="text-xs text-amber-700">Belum memiliki role</span>
                                        @endforelse
                                    </div>
                                </td>
                                <td class="px-5 py-4">
                                    @if($akun->status_aktif)
                                        <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-800"><span aria-hidden="true" class="h-1.5 w-1.5 rounded-full bg-emerald-600"></span>Aktif</span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-600"><span aria-hidden="true" class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>Nonaktif</span>
                                    @endif
                                </td>
                                <td class="px-5 py-4">
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('pengguna.edit', $akun) }}" class="inline-flex min-h-9 items-center rounded-lg border border-slate-200 px-3 text-xs font-semibold text-slate-700 transition hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-emerald-600 focus:ring-offset-2">Edit</a>
                                        @unless($akun->is(auth()->user()))
                                            <form method="POST" action="{{ route('pengguna.destroy', $akun) }}" data-confirm-action="true" data-confirm-title="Hapus akun pengguna?" data-confirm-subtitle="Konfirmasi penghapusan data" data-confirm-name="{{ $akun->nama_lengkap }}" data-confirm-detail="{{ $akun->alamat_email }}" data-confirm-message="Akun akan dihapus permanen. Riwayat klinis tidak dapat dihapus; batalkan dan nonaktifkan akun jika masih terhubung dengan riwayat pelayanan." data-confirm-confirm-label="Ya, hapus akun" data-confirm-ack-label="Saya memahami bahwa penghapusan akun ini bersifat permanen." data-confirm-tone="danger" data-confirm-requires-ack="true">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" aria-label="Hapus akun {{ $akun->nama_lengkap }}" class="inline-flex min-h-9 items-center rounded-lg border border-rose-200 px-3 text-xs font-semibold text-rose-700 transition hover:bg-rose-50 focus:outline-none focus:ring-2 focus:ring-rose-500 focus:ring-offset-2">Hapus</button>
                                            </form>
                                        @endunless
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="flex flex-col gap-3 border-t border-slate-100 px-5 py-4 text-xs text-slate-500 sm:flex-row sm:items-center sm:justify-between">
                <p>Akun yang sudah terhubung dengan riwayat pendaftaran atau pelayanan klinis hanya dapat dinonaktifkan.</p>
                <div>{{ $pengguna->links() }}</div>
            </div>
        @endif
    </section>
</div>
@endsection
