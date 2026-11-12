<?php $__env->startSection('content'); ?>
<div class="mx-auto max-w-4xl print:max-w-none">
    <div class="mb-7 flex items-end justify-between print:hidden"><div><p class="text-xs font-bold uppercase tracking-[0.18em] text-emerald-700">Pendaftaran berhasil</p><h1 class="mt-2 text-3xl font-semibold">Tiket antrean</h1><p class="mt-2 text-sm text-slate-500">Nomor antrean tersimpan pada basis data.</p></div><a href="<?php echo e(route('antrean.index')); ?>" class="rounded-lg border border-slate-300 px-4 py-2 text-xs font-semibold">Kembali ke antrean</a></div>
    <?php if(session('status')): ?><div role="status" class="mb-5 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800 print:hidden"><?php echo e(session('status')); ?></div><?php endif; ?>
    <div class="mx-auto max-w-md rounded-2xl border border-slate-200 bg-white p-8 text-center shadow-sm print:border-slate-400 print:shadow-none">
        <div class="mx-auto grid h-12 w-12 place-items-center rounded-full bg-emerald-50 text-emerald-700 print:hidden"><svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="m5 12 4 4L19 6" stroke-linecap="round" stroke-linejoin="round"/></svg></div>
        <p class="mt-4 text-xs font-semibold uppercase tracking-[0.18em] text-emerald-700">Praktik dr. Sumarwanto</p><p class="mt-1 text-xs text-slate-400">Administrasi Klinik</p><p class="mt-8 text-xs text-slate-500">Nomor antrean</p><div class="mt-1 text-7xl font-bold tracking-widest text-emerald-700">A-<?php echo e($kunjungan->nomorAntrean()); ?></div>
        <p class="mt-5 font-semibold text-slate-700"><?php echo e($kunjungan->pasien->nama_lengkap); ?></p><p class="mt-1 text-sm text-slate-500"><?php echo e($kunjungan->pasien->nomor_rekam_medis); ?></p><p class="mt-4 text-xs leading-5 text-slate-400"><?php echo e($kunjungan->tanggal_kunjungan->format('d/m/Y')); ?> · <?php echo e(substr($kunjungan->waktu_pendaftaran, 0, 5)); ?><br>Silakan menunggu panggilan petugas.</p>
        <div class="mt-7 flex justify-center gap-3 print:hidden">
            <?php if(auth()->user()->memilikiHakAkses('kunjungan.daftar') && ! $kunjungan->tiket_dicetak_pada): ?>
                <form method="POST" action="<?php echo e(route('kunjungan.tiket.cetak', $kunjungan)); ?>"><?php echo csrf_field(); ?><button class="rounded-xl bg-emerald-700 px-5 py-3 text-sm font-semibold text-white">Cetak tiket</button></form>
            <?php elseif($kunjungan->tiket_dicetak_pada): ?>
                <button onclick="window.print()" class="rounded-xl border border-slate-300 px-5 py-3 text-sm font-semibold text-slate-700">Cetak ulang tiket</button>
            <?php endif; ?>
            <a href="<?php echo e(route('kunjungan.create')); ?>" class="rounded-xl border border-slate-300 px-5 py-3 text-sm font-semibold text-slate-600">Pendaftaran baru</a>
        </div>
    </div>
</div>
<?php if(session('cetak_tiket')): ?><script>window.addEventListener('load', () => window.print());</script><?php endif; ?>
<style>@media print{body{background:#fff!important}.print\:hidden{display:none!important}}</style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.aplikasi', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\Ardi\Kampus\Semester 7\KP\v0\sistem-informasi-praktik-dokter-v2\resources\views\kunjungan\tiket.blade.php ENDPATH**/ ?>