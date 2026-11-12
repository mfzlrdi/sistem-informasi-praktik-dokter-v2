<?php $__env->startSection('content'); ?>
<?php
    $pengguna = auth()->user();
    $dokter = $pengguna->memilikiPeran('dokter') && $pengguna->memilikiHakAkses('klinis.lihat');
    $resepsionis = $pengguna->memilikiPeran('resepsionis');
    $administrator = $pengguna->memilikiPeran('administrator');
    $kelasStatus = [
        'menunggu' => 'bg-amber-50 text-amber-800 ring-1 ring-amber-200',
        'dipanggil' => 'bg-sky-50 text-sky-800 ring-1 ring-sky-200',
        'sedang_konsultasi' => 'bg-violet-50 text-violet-800 ring-1 ring-violet-200',
        'selesai' => 'bg-emerald-50 text-emerald-800 ring-1 ring-emerald-200',
        'dibatalkan' => 'bg-rose-50 text-rose-800 ring-1 ring-rose-200',
    ];
    $labelStatus = ['menunggu'=>'Menunggu','dipanggil'=>'Dipanggil','sedang_konsultasi'=>'Sedang Konsultasi','selesai'=>'Selesai','dibatalkan'=>'Dibatalkan'];
?>
<div class="mx-auto max-w-[1260px]">
    <div class="mb-7">
        <p class="text-xs font-bold uppercase tracking-[0.18em] text-emerald-700"><?php echo e($riwayat ? 'Riwayat pelayanan' : 'Operasional'); ?></p>
        <h1 class="mt-2 text-3xl font-semibold"><?php echo e($riwayat ? 'Riwayat kunjungan' : 'Antrean pasien'); ?></h1>
        <p class="mt-2 text-sm text-slate-500"><?php echo e($riwayat ? 'Daftar kunjungan yang telah selesai dan tersimpan di database.' : 'Pantau pasien yang sudah didaftarkan resepsionis dan sedang mengantre.'); ?></p>
    </div>
    <?php if(session('status')): ?><div role="status" class="mt-6 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800"><?php echo e(session('status')); ?></div><?php endif; ?>
    <form method="GET" class="mt-7 flex flex-wrap items-end gap-3 rounded-xl border border-slate-200 bg-white p-4">
        <label class="text-sm"><span class="mr-2 text-slate-500">Tanggal</span><input type="date" name="tanggal" value="<?php echo e($tanggal); ?>" class="rounded-lg border border-slate-300 px-3 py-2"></label>
        <label class="text-sm"><span class="mr-2 text-slate-500">Status</span><select name="status" class="rounded-lg border border-slate-300 px-3 py-2"><option value=""><?php echo e($riwayat ? 'Semua riwayat' : 'Semua status antrean'); ?></option><?php $__currentLoopData = ($riwayat ? ['selesai'=>'Selesai'] : ['menunggu'=>'Menunggu','dipanggil'=>'Dipanggil','sedang_konsultasi'=>'Sedang Konsultasi']); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kode => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($kode); ?>" <?php if($status === $kode): echo 'selected'; endif; ?>><?php echo e($label); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select></label>
        <button class="rounded-lg bg-slate-800 px-4 py-2 text-sm font-semibold text-white focus:outline-none focus:ring-2 focus:ring-emerald-500">Terapkan</button>
    </form>
    <div class="mt-5 overflow-hidden rounded-xl border border-slate-200 bg-white">
        <div class="overflow-x-auto"><table class="w-full min-w-[760px] text-left text-sm"><thead class="border-b border-slate-100 bg-slate-50 text-[10px] uppercase tracking-wider text-slate-500"><tr><th scope="col" class="px-5 py-4">No. antrean</th><th scope="col" class="px-5 py-4">Pasien</th><th scope="col" class="px-5 py-4">Waktu daftar</th><th scope="col" class="px-5 py-4">Status</th><th scope="col" class="px-5 py-4">Aksi</th></tr></thead><tbody class="divide-y divide-slate-100">
        <?php $__empty_1 = true; $__currentLoopData = $kunjungan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <?php ($status = $item->status_kunjungan); ?>
            <tr>
                <td class="px-5 py-4 font-bold text-emerald-700">A-<?php echo e($item->nomorAntrean()); ?></td>
                <td class="px-5 py-4"><p class="font-semibold"><?php echo e($item->pasien->nama_lengkap); ?></p><p class="text-xs text-slate-400"><?php echo e($item->pasien->nomor_rekam_medis); ?></p></td>
                <td class="px-5 py-4 text-slate-500"><?php echo e(substr($item->waktu_pendaftaran, 0, 5)); ?></td>
                <td class="px-5 py-4"><span class="inline-flex whitespace-nowrap rounded-full px-3 py-1 text-[11px] font-semibold <?php echo e($kelasStatus[$status] ?? 'bg-slate-100 text-slate-700'); ?>"><?php echo e($labelStatus[$status] ?? $status); ?></span>
                    <?php if($dokter && $status === 'menunggu'): ?><p class="mt-1 text-xs <?php echo e($item->tiket_dicetak_pada ? 'text-emerald-700' : 'text-amber-700'); ?>"><?php echo e($item->tiket_dicetak_pada ? 'Tiket sudah dicetak' : 'Menunggu tiket dari resepsionis'); ?></p><?php endif; ?>
                </td>
                <td class="px-5 py-4"><div class="flex flex-wrap items-center gap-2">
                    <?php if(($resepsionis || $administrator) && $status !== 'dibatalkan'): ?>
                        <a href="<?php echo e(route('kunjungan.tiket', $item)); ?>" class="rounded-lg border border-slate-200 px-3 py-2 text-[11px] font-semibold transition hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-emerald-600 focus:ring-offset-2">Cetak Tiket</a>
                    <?php endif; ?>
                    <?php if($dokter && auth()->user()->memilikiHakAkses('antrean.kelola') && $status === 'menunggu'): ?>
                        <form method="POST" action="<?php echo e(route('antrean.update', $item)); ?>" data-queue-call>
                            <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?> <input type="hidden" name="status_kunjungan" value="dipanggil">
                            <button type="submit" <?php if(! $item->tiket_dicetak_pada): echo 'disabled'; endif; ?> class="rounded-lg bg-emerald-700 px-3 py-2 text-[11px] font-semibold text-white disabled:cursor-not-allowed disabled:bg-slate-300" <?php if($item->tiket_dicetak_pada): ?> data-panggil="true" <?php else: ?> title="Tunggu resepsionis mencetak tiket antrean" <?php endif; ?>>Panggil</button>
                        </form>
                    <?php endif; ?>
                    <?php if(($dokter || $administrator) && in_array($status, ['menunggu', 'dipanggil', 'sedang_konsultasi'], true)): ?>
                        <form method="POST" action="<?php echo e(route('antrean.update', $item)); ?>" data-confirm-action="true" data-confirm-title="Batalkan antrean?" data-confirm-subtitle="Konfirmasi pembatalan antrean" data-confirm-name="Antrean A-<?php echo e($item->nomorAntrean()); ?> · <?php echo e($item->pasien->nama_lengkap); ?>" data-confirm-detail="Status saat ini: <?php echo e($labelStatus[$status] ?? $status); ?>" data-confirm-message="Status antrean akan menjadi Dibatalkan. Catatan klinis yang sudah tersimpan tetap dipertahankan." data-confirm-confirm-label="Ya, batalkan antrean" data-confirm-ack-label="Saya memahami bahwa pembatalan antrean ini bersifat permanen." data-confirm-requires-ack="true" data-confirm-tone="danger">
                            <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?> <input type="hidden" name="status_kunjungan" value="dibatalkan">
                            <button type="submit" class="rounded-lg border border-rose-200 px-3 py-2 text-[11px] font-semibold text-rose-700 transition hover:bg-rose-50 focus:outline-none focus:ring-2 focus:ring-rose-500 focus:ring-offset-2">Batalkan</button>
                        </form>
                    <?php endif; ?>
                    <?php if($dokter && auth()->user()->memilikiHakAkses('antrean.kelola') && $status === 'sedang_konsultasi'): ?> <?php echo $__env->make('antrean.tombol-status', ['tujuan' => 'selesai', 'label' => 'Selesaikan'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?> <?php endif; ?>
                    <?php if($dokter && in_array($status, ['dipanggil', 'sedang_konsultasi'], true)): ?>
                        <?php if($status === 'dipanggil'): ?><form method="POST" action="<?php echo e(route('pemeriksaan.mulai', $item)); ?>"><?php echo csrf_field(); ?><button class="rounded-lg border border-violet-200 px-3 py-2 text-[11px] font-semibold text-violet-700">Periksa</button></form>
                        <?php else: ?><a href="<?php echo e(route('pemeriksaan.show', $item)); ?>" class="rounded-lg border border-violet-200 px-3 py-2 text-[11px] font-semibold text-violet-700">Periksa</a><?php endif; ?>
                    <?php endif; ?>
                </div></td>
            </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr><td colspan="5" class="px-5 py-14 text-center text-slate-400"><?php echo e($riwayat ? 'Belum ada riwayat kunjungan pada tanggal ini.' : 'Belum ada pasien yang mengantre pada tanggal ini.'); ?></td></tr>
        <?php endif; ?>
        </tbody></table></div><div class="border-t border-slate-100 px-5 py-4"><?php echo e($kunjungan->links()); ?></div>
    </div>
    <p id="pengumuman-status" class="sr-only" role="status" aria-live="polite"></p>
    <script>
        document.querySelectorAll('[data-queue-call]').forEach((form) => {
            form.addEventListener('submit', async (event) => {
                if (!form.querySelector('[data-panggil]')) return;
                event.preventDefault();
                const button = form.querySelector('button');
                button.disabled = true;
                try {
                    const response = await fetch(form.action, {
                        method: 'POST',
                        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                        body: new FormData(form),
                    });
                    const result = await response.json();
                    if (!response.ok) throw new Error(result.message || result.pesan || 'Panggilan antrean gagal.');
                    const announcement = result.pengumuman;
                    const region = document.getElementById('pengumuman-status');
                    region.textContent = announcement;
                    if ('speechSynthesis' in window && announcement) {
                        const utterance = new SpeechSynthesisUtterance(announcement);
                        utterance.lang = 'id-ID';
                        utterance.rate = 0.92;
                        utterance.onend = () => window.location.reload();
                        window.speechSynthesis.cancel();
                        window.speechSynthesis.speak(utterance);
                    } else {
                        window.location.reload();
                    }
                } catch (error) {
                    alert(error.message || 'Panggilan antrean gagal.');
                    button.disabled = false;
                }
            });
        });
        setTimeout(() => window.location.reload(), 15000);
    </script>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.aplikasi', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\Ardi\Kampus\Semester 7\KP\v0\sistem-informasi-praktik-dokter-v2\resources\views/antrean/index.blade.php ENDPATH**/ ?>