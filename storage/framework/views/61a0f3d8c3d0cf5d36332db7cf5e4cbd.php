<?php $__env->startSection('title', 'Pendaftaran pasien dan kunjungan | Praktik dr. Sumarwanto'); ?>

<?php $__env->startSection('content'); ?>
<div class="mx-auto max-w-5xl">
    <div class="mb-7">
        <p class="text-xs font-bold uppercase tracking-[0.18em] text-emerald-700">Administrasi</p>
        <h1 class="mt-2 text-3xl font-semibold">Pendaftaran pasien dan kunjungan</h1>
        <p class="mt-2 text-sm text-slate-500">Pilih alur pendaftaran yang ingin dilakukan.</p>
    </div>

    <?php if($bolehDaftarKunjungan): ?>
        <section class="mb-7 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6" aria-labelledby="judul-cari-pasien">
            <div class="mb-4">
                <p class="text-[10px] font-bold uppercase tracking-[0.16em] text-emerald-700">Pasien terdaftar</p>
                <h2 id="judul-cari-pasien" class="mt-1 text-lg font-semibold text-slate-900">Cari pasien untuk pendaftaran kunjungan</h2>
                <p class="mt-1 text-sm leading-6 text-slate-500">Cari dengan nomor rekam medis, nama, nomor telepon, atau NIK. Pilih pasien dari hasil untuk melanjutkan pendaftaran kunjungan.</p>
            </div>

            <form method="GET" action="<?php echo e(route('pendaftaran.index')); ?>" role="search">
                <label for="cari-pasien" class="mb-2 block text-sm font-semibold text-slate-800">Cari pasien</label>
                <div class="flex flex-col gap-3 sm:flex-row">
                    <div class="relative flex-1">
                        <svg class="pointer-events-none absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><circle cx="10.8" cy="10.8" r="6.5"/><path d="m16 16 4 4" stroke-linecap="round"/></svg>
                        <input id="cari-pasien" name="q" type="search" value="<?php echo e($cariPasien); ?>" maxlength="100" autocomplete="off" placeholder="No. rekam medis, nama, nomor telepon, atau NIK" aria-describedby="petunjuk-cari-pasien<?php echo e($errors->has('q') ? ' galat-cari-pasien' : ''); ?>" class="min-h-11 w-full rounded-xl border border-slate-300 bg-white py-2.5 pl-10 pr-4 text-sm text-slate-900 shadow-sm placeholder:text-slate-400 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-500">
                    </div>
                    <button type="submit" class="inline-flex min-h-11 items-center justify-center gap-2 rounded-xl bg-emerald-700 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-emerald-800 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-500 focus-visible:ring-offset-2">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><circle cx="10.8" cy="10.8" r="6.5"/><path d="m16 16 4 4" stroke-linecap="round"/></svg>
                        Cari
                    </button>
                </div>
                <p id="petunjuk-cari-pasien" class="mt-2 text-xs text-slate-500">Masukkan sebagian atau seluruh informasi pasien.</p>
                <?php $__errorArgs = ['q'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p id="galat-cari-pasien" class="mt-2 text-sm text-rose-700" role="alert"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </form>

            <?php if($cariPasien !== ''): ?>
                <div class="mt-5 border-t border-slate-100 pt-4" role="region" aria-label="Hasil pencarian pasien" aria-live="polite">
                    <?php if($hasilPencarianPasien->isNotEmpty()): ?>
                        <div class="mb-2 flex flex-wrap items-center justify-between gap-2">
                            <h3 class="text-sm font-semibold text-slate-900">Hasil pencarian</h3>
                            <p class="text-xs text-slate-500"><?php echo e($hasilPencarianPasien->count()); ?> pasien ditemukan</p>
                        </div>
                        <ul class="divide-y divide-slate-100">
                            <?php $__currentLoopData = $hasilPencarianPasien; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li class="flex flex-col gap-3 py-4 sm:flex-row sm:items-center sm:justify-between">
                                    <div class="min-w-0">
                                        <p class="truncate font-semibold text-slate-900"><?php echo e($item->nama_lengkap); ?></p>
                                        <p class="mt-1 text-sm text-slate-500">No. rekam medis: <span class="font-medium text-slate-700"><?php echo e($item->nomor_rekam_medis); ?></span><?php if($item->nomor_telepon): ?> <span aria-hidden="true">·</span> Telepon berakhir <?php echo e(substr($item->nomor_telepon, -4)); ?><?php endif; ?></p>
                                    </div>
                                    <a href="<?php echo e(route('kunjungan.create', ['id_pasien' => $item->id_pasien])); ?>" aria-label="Pilih pasien <?php echo e($item->nama_lengkap); ?>, nomor rekam medis <?php echo e($item->nomor_rekam_medis); ?>" class="inline-flex min-h-10 shrink-0 items-center justify-center rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-2 text-sm font-semibold text-emerald-800 transition hover:bg-emerald-100 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-500 focus-visible:ring-offset-2">Pilih pasien</a>
                                </li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                        <?php if($hasilPencarianPasien->count() === 20): ?>
                            <p class="mt-2 text-xs text-slate-500">Maksimal 20 hasil ditampilkan. Gunakan kata kunci yang lebih spesifik jika pasien belum terlihat.</p>
                        <?php endif; ?>
                    <?php else: ?>
                        <div class="rounded-xl border border-amber-200 bg-amber-50 p-4" role="status">
                            <p class="font-semibold text-amber-950">Pasien tidak ditemukan</p>
                            <p class="mt-1 text-sm leading-5 text-amber-900">Tidak ada pasien yang cocok dengan “<?php echo e($cariPasien); ?>”. Periksa kembali kata kunci atau daftarkan pasien baru.</p>
                            <?php if($bolehDaftarPasien): ?>
                                <a href="<?php echo e(route('pasien.create')); ?>" class="mt-3 inline-flex min-h-10 items-center rounded-lg border border-amber-300 bg-white px-3 py-2 text-sm font-semibold text-amber-950 transition hover:bg-amber-100 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-amber-500">Daftarkan pasien baru</a>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </section>
    <?php endif; ?>

    <div class="grid gap-5 md:grid-cols-2">
        <?php if($bolehDaftarPasien): ?>
            <section class="flex flex-col rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="mb-5 flex h-11 w-11 items-center justify-center rounded-xl bg-emerald-50 text-emerald-700" aria-hidden="true">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><circle cx="10" cy="8" r="3"/><path d="M4.5 19c.5-3 2.3-4.5 5.5-4.5 1.4 0 2.6.3 3.5.9M18 14v6M15 17h6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </div>
                <p class="text-[10px] font-bold uppercase tracking-[0.16em] text-slate-400">Data pasien</p>
                <h2 class="mt-2 text-xl font-semibold text-slate-900">Daftarkan pasien baru</h2>
                <p class="mt-2 flex-1 text-sm leading-6 text-slate-500">Catat biodata pasien dan buat nomor rekam medis sebelum kunjungan pertamanya.</p>
                <a href="<?php echo e(route('pasien.create')); ?>" class="mt-6 inline-flex min-h-11 items-center justify-center rounded-xl bg-emerald-700 px-4 py-3 text-sm font-semibold text-white transition hover:bg-emerald-800 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-500 focus-visible:ring-offset-2">Mulai pendaftaran pasien</a>
            </section>
        <?php endif; ?>

        <?php if($bolehDaftarKunjungan): ?>
            <section class="flex flex-col rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="mb-5 flex h-11 w-11 items-center justify-center rounded-xl bg-teal-50 text-teal-700" aria-hidden="true">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><rect x="4" y="5" width="16" height="15" rx="2"/><path d="M8 3v4M16 3v4M4 10h16M9 14h2M9 17h6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </div>
                <p class="text-[10px] font-bold uppercase tracking-[0.16em] text-slate-400">Antrean pasien</p>
                <h2 class="mt-2 text-xl font-semibold text-slate-900">Daftarkan kunjungan</h2>
                <p class="mt-2 flex-1 text-sm leading-6 text-slate-500">Pilih pasien terdaftar, catat keluhan awal, dan masukkan kunjungan ke antrean.</p>
                <?php if($adaPasienTerdaftar): ?>
                    <a href="<?php echo e(route('kunjungan.create')); ?>" class="mt-6 inline-flex min-h-11 items-center justify-center rounded-xl bg-emerald-700 px-4 py-3 text-sm font-semibold text-white transition hover:bg-emerald-800 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-500 focus-visible:ring-offset-2">Mulai pendaftaran kunjungan</a>
                <?php else: ?>
                    <div class="mt-5 rounded-xl border border-amber-200 bg-amber-50 p-4 text-sm text-amber-900" role="status" aria-live="polite">
                        <p class="font-semibold">Belum ada pasien terdaftar</p>
                        <p class="mt-1 leading-5">Data pasien perlu dibuat lebih dulu sebelum kunjungan dapat dimasukkan ke antrean.</p>
                    </div>
                    <?php if($bolehDaftarPasien): ?>
                        <a href="<?php echo e(route('pasien.create')); ?>" class="mt-4 inline-flex min-h-11 items-center justify-center rounded-xl border border-emerald-700 px-4 py-3 text-sm font-semibold text-emerald-800 transition hover:bg-emerald-50 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-500 focus-visible:ring-offset-2">Daftarkan pasien terlebih dahulu</a>
                    <?php else: ?>
                        <p class="mt-4 text-sm text-slate-500">Hubungi administrator untuk membantu mendaftarkan pasien.</p>
                    <?php endif; ?>
                <?php endif; ?>
            </section>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.aplikasi', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\Ardi\Kampus\Semester 7\KP\v0\sistem-informasi-praktik-dokter-v2\resources\views/pendaftaran/index.blade.php ENDPATH**/ ?>