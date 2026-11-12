<?php $__env->startSection('content'); ?>
<?php
    $resepTersimpan = $rekamMedis?->resep?->first();
    $rincianResep = $resepTersimpan?->rincian?->first();
    $pemeriksaanFinal = $rekamMedis?->status_rekam_medis === 'difinalisasi';
    $statusKunjungan = str_replace('_', ' ', ucfirst($kunjungan->status_kunjungan));
    $kelasInput = 'mt-2 block w-full rounded-xl border border-slate-300 bg-white px-3.5 py-3 text-sm text-slate-800 shadow-sm outline-none transition placeholder:text-slate-400 hover:border-slate-400 focus:border-teal-700 focus:ring-4 focus:ring-teal-700/10';
    $kelasBantuan = 'mt-1.5 block text-xs leading-5 text-slate-500';
?>
<div class="mx-auto max-w-7xl pb-10">
    <nav class="mb-5 flex items-center gap-2 text-xs text-slate-500" aria-label="Breadcrumb">
        <a href="<?php echo e(route('dasbor.pilih')); ?>" class="transition hover:text-teal-800">Dashboard</a>
        <span aria-hidden="true">›</span>
        <a href="<?php echo e(route('antrean.index')); ?>" class="transition hover:text-teal-800">Antrean pasien</a>
        <span aria-hidden="true">›</span>
        <span class="font-medium text-slate-800" aria-current="page">Pemeriksaan pasien</span>
    </nav>
    <header class="mb-6 flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
        <div>
            <p class="text-xs font-bold uppercase tracking-[0.18em] text-teal-700">Ruang kerja dokter</p>
            <h1 class="mt-2 text-3xl font-semibold tracking-tight text-slate-900">Konsultasi pasien</h1>
            <p class="mt-2 text-sm text-slate-600"><?php echo e($kunjungan->pasien->nama_lengkap); ?> <span aria-hidden="true">·</span> <?php echo e($kunjungan->pasien->nomor_rekam_medis); ?></p>
        </div>
        <a href="<?php echo e(route('antrean.index')); ?>" class="inline-flex min-h-10 items-center justify-center rounded-xl border border-slate-200 bg-white px-4 text-sm font-medium text-slate-700 shadow-sm transition hover:bg-slate-50">Kembali ke antrean</a>
    </header>

    <?php if(session('status')): ?>
        <div role="status" class="mb-5 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800"><?php echo e(session('status')); ?></div>
    <?php endif; ?>
    <?php if($errors->any()): ?>
        <div id="validation-summary" role="alert" tabindex="-1" class="mb-6 rounded-xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-900">
            <p class="font-semibold">Periksa kembali data berikut sebelum menyimpan:</p>
            <ul class="mt-2 list-inside list-disc space-y-1"><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><li><?php echo e($error); ?></li><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></ul>
        </div>
    <?php endif; ?>
    <?php if($pemeriksaanFinal): ?>
        <div role="status" class="mb-6 rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm leading-6 text-amber-900">
            Catatan ini telah difinalisasi. Perubahan klinis setelah finalisasi perlu mengikuti alur koreksi yang dapat ditelusuri.
        </div>
    <?php endif; ?>

    <form id="form-pemeriksaan" method="POST" action="<?php echo e(route('pemeriksaan.store', $kunjungan)); ?>" class="grid items-start gap-5 lg:grid-cols-[minmax(0,1fr)_288px]">
        <?php echo csrf_field(); ?>
        <div class="flex flex-col gap-5">
            <section class="order-1 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6" aria-labelledby="judul-pasien">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-teal-700">Pasien</p>
                        <h2 id="judul-pasien" class="mt-1 text-lg font-semibold text-slate-900"><?php echo e($kunjungan->pasien->nama_lengkap); ?></h2>
                        <p class="mt-1 text-sm text-slate-500"><?php echo e($kunjungan->pasien->nomor_rekam_medis); ?> <span aria-hidden="true">·</span> <?php echo e($kunjungan->pasien->jenis_kelamin ?? 'Data jenis kelamin tidak tersedia'); ?> <span aria-hidden="true">·</span> <?php echo e($kunjungan->pasien->tanggal_lahir?->age ? $kunjungan->pasien->tanggal_lahir->age.' tahun' : 'Usia tidak tersedia'); ?></p>
                    </div>
                    <div class="text-left sm:text-right">
                        <p class="text-2xl font-bold tracking-tight text-teal-800">A-<?php echo e($kunjungan->nomorAntrean()); ?></p>
                        <span class="mt-1 inline-flex items-center gap-2 rounded-full bg-violet-50 px-3 py-1.5 text-xs font-semibold capitalize text-violet-800 ring-1 ring-inset ring-violet-200"><span class="h-1.5 w-1.5 rounded-full bg-violet-600" aria-hidden="true"></span><?php echo e($statusKunjungan); ?></span>
                    </div>
                </div>
                <div class="mt-4 rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm leading-6 text-amber-950">
                    <span class="font-semibold text-amber-900">Keluhan awal resepsionis</span>
                    <p class="mt-1"><?php echo e($kunjungan->keluhan_awal ?: 'Tidak ada keluhan awal yang dicatat.'); ?></p>
                </div>
            </section>

            <section class="order-3 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6" aria-labelledby="judul-kunjungan">
                <div class="flex items-start gap-3">
                    <span class="grid h-9 w-9 shrink-0 place-items-center rounded-xl bg-teal-50 text-sm font-bold text-teal-800" aria-hidden="true">02</span>
                    <div><h2 id="judul-kunjungan" class="font-semibold text-slate-900">Hasil pemeriksaan</h2><p class="mt-1 text-xs leading-5 text-slate-500">Catat anamnesis pasien dan temuan pemeriksaan klinis secara terpisah.</p></div>
                </div>
                <div class="mt-5 grid gap-4 sm:grid-cols-2">
                    <label for="anamnesis" class="block text-sm font-medium text-slate-800">Anamnesis dokter <span class="text-rose-600" aria-hidden="true">*</span>
                        <textarea id="anamnesis" name="anamnesis" rows="5" maxlength="10000" data-finalize-required placeholder="Tuliskan riwayat keluhan, durasi, gejala penyerta, dan informasi relevan dari pasien…" aria-describedby="anamnesis-bantuan <?php $__errorArgs = ['anamnesis'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> anamnesis-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" <?php $__errorArgs = ['anamnesis'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> aria-invalid="true" <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?> class="<?php echo e($kelasInput); ?> <?php $__errorArgs = ['anamnesis'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-rose-400 focus:border-rose-600 focus:ring-rose-600/10 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"><?php echo e(old('anamnesis', $rekamMedis?->anamnesis ?? '')); ?></textarea>
                        <span id="anamnesis-bantuan" class="<?php echo e($kelasBantuan); ?>">Wajib saat finalisasi. Catat riwayat klinis yang Anda gali; verifikasi keluhan awal dan maksimal 10.000 karakter.</span>
                        <?php $__errorArgs = ['anamnesis'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span id="anamnesis-error" class="mt-1 block text-xs font-medium text-rose-700"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </label>
                    <label for="hasil_pemeriksaan" class="block text-sm font-medium text-slate-800">Hasil pemeriksaan <span class="text-rose-600" aria-hidden="true">*</span>
                        <textarea id="hasil_pemeriksaan" name="hasil_pemeriksaan" rows="5" maxlength="10000" data-finalize-required placeholder="Catat temuan pemeriksaan yang relevan…" aria-describedby="hasil-bantuan <?php $__errorArgs = ['hasil_pemeriksaan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> hasil-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" <?php $__errorArgs = ['hasil_pemeriksaan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> aria-invalid="true" <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?> class="<?php echo e($kelasInput); ?> <?php $__errorArgs = ['hasil_pemeriksaan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-rose-400 focus:border-rose-600 focus:ring-rose-600/10 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"><?php echo e(old('hasil_pemeriksaan', $rekamMedis?->hasil_pemeriksaan ?? '')); ?></textarea>
                        <span id="hasil-bantuan" class="<?php echo e($kelasBantuan); ?>">Dokumentasikan temuan pemeriksaan fisik/penunjang. Wajib diisi untuk finalisasi.</span>
                        <?php $__errorArgs = ['hasil_pemeriksaan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span id="hasil-error" class="mt-1 block text-xs font-medium text-rose-700"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
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
                        <input id="tekanan_darah" name="tekanan_darah" type="text" inputmode="numeric" maxlength="7" value="<?php echo e(old('tekanan_darah', $rekamMedis?->tanda_vital['tekanan_darah'] ?? '')); ?>" placeholder="120/80" aria-describedby="tekanan-bantuan <?php $__errorArgs = ['tekanan_darah'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> tekanan-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" <?php $__errorArgs = ['tekanan_darah'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> aria-invalid="true" <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?> class="<?php echo e($kelasInput); ?> <?php $__errorArgs = ['tekanan_darah'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-rose-400 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                        <span id="tekanan-bantuan" class="<?php echo e($kelasBantuan); ?>">mmHg, format sistolik/diastolik, contoh 120/80.</span><?php $__errorArgs = ['tekanan_darah'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span id="tekanan-error" class="mt-1 block text-xs font-medium text-rose-700"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </label>
                    <label for="suhu_tubuh" class="block text-sm font-medium text-slate-800">Suhu tubuh
                        <div class="relative"><input id="suhu_tubuh" name="suhu_tubuh" type="number" inputmode="decimal" min="20" max="50" step="0.1" value="<?php echo e(old('suhu_tubuh', $rekamMedis?->tanda_vital['suhu_tubuh'] ?? '')); ?>" placeholder="36.5" aria-describedby="suhu-bantuan <?php $__errorArgs = ['suhu_tubuh'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> suhu-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" <?php $__errorArgs = ['suhu_tubuh'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> aria-invalid="true" <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?> class="<?php echo e($kelasInput); ?> pr-14 <?php $__errorArgs = ['suhu_tubuh'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-rose-400 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"><span class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-xs text-slate-500">°C</span></div>
                        <span id="suhu-bantuan" class="<?php echo e($kelasBantuan); ?>">Rentang valid 20–50 °C.</span><?php $__errorArgs = ['suhu_tubuh'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span id="suhu-error" class="mt-1 block text-xs font-medium text-rose-700"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </label>
                    <label for="denyut_nadi" class="block text-sm font-medium text-slate-800">Denyut nadi
                        <div class="relative"><input id="denyut_nadi" name="denyut_nadi" type="number" inputmode="numeric" min="20" max="250" step="1" value="<?php echo e(old('denyut_nadi', $rekamMedis?->tanda_vital['denyut_nadi'] ?? '')); ?>" placeholder="72" aria-describedby="nadi-bantuan <?php $__errorArgs = ['denyut_nadi'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> nadi-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" <?php $__errorArgs = ['denyut_nadi'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> aria-invalid="true" <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?> class="<?php echo e($kelasInput); ?> pr-20 <?php $__errorArgs = ['denyut_nadi'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-rose-400 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"><span class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-[11px] text-slate-500">kali/menit</span></div>
                        <span id="nadi-bantuan" class="<?php echo e($kelasBantuan); ?>">Bilangan bulat 20–250 kali per menit.</span><?php $__errorArgs = ['denyut_nadi'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span id="nadi-error" class="mt-1 block text-xs font-medium text-rose-700"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </label>
                    <label for="frekuensi_pernapasan" class="block text-sm font-medium text-slate-800">Frekuensi pernapasan
                        <div class="relative"><input id="frekuensi_pernapasan" name="frekuensi_pernapasan" type="number" inputmode="numeric" min="5" max="80" step="1" value="<?php echo e(old('frekuensi_pernapasan', $rekamMedis?->tanda_vital['frekuensi_pernapasan'] ?? '')); ?>" placeholder="18" aria-describedby="napas-bantuan <?php $__errorArgs = ['frekuensi_pernapasan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> napas-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" <?php $__errorArgs = ['frekuensi_pernapasan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> aria-invalid="true" <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?> class="<?php echo e($kelasInput); ?> pr-20 <?php $__errorArgs = ['frekuensi_pernapasan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-rose-400 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"><span class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-[11px] text-slate-500">kali/menit</span></div>
                        <span id="napas-bantuan" class="<?php echo e($kelasBantuan); ?>">Bilangan bulat 5–80 kali per menit.</span><?php $__errorArgs = ['frekuensi_pernapasan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span id="napas-error" class="mt-1 block text-xs font-medium text-rose-700"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </label>
                    <label for="berat_badan" class="block text-sm font-medium text-slate-800">Berat badan
                        <div class="relative"><input id="berat_badan" name="berat_badan" type="number" inputmode="decimal" min="0.1" max="500" step="0.1" value="<?php echo e(old('berat_badan', $rekamMedis?->tanda_vital['berat_badan'] ?? '')); ?>" placeholder="60" aria-describedby="berat-bantuan <?php $__errorArgs = ['berat_badan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> berat-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" <?php $__errorArgs = ['berat_badan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> aria-invalid="true" <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?> class="<?php echo e($kelasInput); ?> pr-12 <?php $__errorArgs = ['berat_badan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-rose-400 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"><span class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-xs text-slate-500">kg</span></div>
                        <span id="berat-bantuan" class="<?php echo e($kelasBantuan); ?>">Lebih dari 0 sampai 500 kg.</span><?php $__errorArgs = ['berat_badan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span id="berat-error" class="mt-1 block text-xs font-medium text-rose-700"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </label>
                    <label for="tinggi_badan" class="block text-sm font-medium text-slate-800">Tinggi badan
                        <div class="relative"><input id="tinggi_badan" name="tinggi_badan" type="number" inputmode="decimal" min="0.1" max="250" step="0.1" value="<?php echo e(old('tinggi_badan', $rekamMedis?->tanda_vital['tinggi_badan'] ?? '')); ?>" placeholder="165" aria-describedby="tinggi-bantuan <?php $__errorArgs = ['tinggi_badan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> tinggi-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" <?php $__errorArgs = ['tinggi_badan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> aria-invalid="true" <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?> class="<?php echo e($kelasInput); ?> pr-12 <?php $__errorArgs = ['tinggi_badan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-rose-400 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"><span class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-xs text-slate-500">cm</span></div>
                        <span id="tinggi-bantuan" class="<?php echo e($kelasBantuan); ?>">Lebih dari 0 sampai 250 cm.</span><?php $__errorArgs = ['tinggi_badan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span id="tinggi-error" class="mt-1 block text-xs font-medium text-rose-700"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
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
                        <textarea id="diagnosa" name="diagnosa[]" rows="3" maxlength="255" data-finalize-required placeholder="Masukkan diagnosis yang telah ditetapkan…" aria-describedby="diagnosa-bantuan <?php $__errorArgs = ['diagnosa'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> diagnosa-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?> <?php $__errorArgs = ['diagnosa.0'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> diagnosa-item-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" <?php if($errors->has('diagnosa') || $errors->has('diagnosa.0')): ?> aria-invalid="true" <?php endif; ?> class="<?php echo e($kelasInput); ?> <?php if($errors->has('diagnosa') || $errors->has('diagnosa.0')): ?> border-rose-400 <?php endif; ?>"><?php echo e(old('diagnosa.0', $rekamMedis?->diagnosa?->first()?->nama_diagnosa ?? '')); ?></textarea>
                        <span id="diagnosa-bantuan" class="<?php echo e($kelasBantuan); ?>">Wajib saat finalisasi. Gunakan istilah klinis yang sesuai; maksimal 255 karakter.</span><?php $__errorArgs = ['diagnosa'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span id="diagnosa-error" class="mt-1 block text-xs font-medium text-rose-700"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?> <?php $__errorArgs = ['diagnosa.0'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span id="diagnosa-item-error" class="mt-1 block text-xs font-medium text-rose-700"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </label>
                    <label for="tindakan" class="block text-sm font-medium text-slate-800">Tindakan
                        <textarea id="tindakan" name="tindakan[]" rows="3" maxlength="255" placeholder="Catat tindakan yang dilakukan, jika ada…" aria-describedby="tindakan-bantuan <?php $__errorArgs = ['tindakan.0'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> tindakan-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" <?php $__errorArgs = ['tindakan.0'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> aria-invalid="true" <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?> class="<?php echo e($kelasInput); ?> <?php $__errorArgs = ['tindakan.0'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-rose-400 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"><?php echo e(old('tindakan.0', $rekamMedis?->tindakan?->first()?->nama_tindakan ?? '')); ?></textarea>
                        <span id="tindakan-bantuan" class="<?php echo e($kelasBantuan); ?>">Opsional. Maksimal 255 karakter.</span><?php $__errorArgs = ['tindakan.0'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span id="tindakan-error" class="mt-1 block text-xs font-medium text-rose-700"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </label>
                </div>
                <label for="rencana_terapi" class="mt-5 block text-sm font-medium text-slate-800">Rencana terapi
                    <textarea id="rencana_terapi" name="rencana_terapi" rows="3" maxlength="10000" placeholder="Rencana perawatan atau terapi yang dipertimbangkan…" aria-describedby="terapi-bantuan <?php $__errorArgs = ['rencana_terapi'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> terapi-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" <?php $__errorArgs = ['rencana_terapi'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> aria-invalid="true" <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?> class="<?php echo e($kelasInput); ?> <?php $__errorArgs = ['rencana_terapi'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-rose-400 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"><?php echo e(old('rencana_terapi', $rekamMedis?->rencana_terapi ?? '')); ?></textarea>
                    <span id="terapi-bantuan" class="<?php echo e($kelasBantuan); ?>">Catat rencana perawatan yang diputuskan dokter, bukan rekomendasi otomatis. Maksimal 10.000 karakter.</span><?php $__errorArgs = ['rencana_terapi'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span id="terapi-error" class="mt-1 block text-xs font-medium text-rose-700"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </label>
                <label for="instruksi_tindak_lanjut" class="mt-5 block text-sm font-medium text-slate-800">Instruksi tindak lanjut
                    <textarea id="instruksi_tindak_lanjut" name="instruksi_tindak_lanjut" rows="3" maxlength="10000" placeholder="Jadwal kontrol atau hal yang perlu dipantau…" aria-describedby="lanjut-bantuan <?php $__errorArgs = ['instruksi_tindak_lanjut'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> lanjut-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" <?php $__errorArgs = ['instruksi_tindak_lanjut'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> aria-invalid="true" <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?> class="<?php echo e($kelasInput); ?> <?php $__errorArgs = ['instruksi_tindak_lanjut'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-rose-400 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"><?php echo e(old('instruksi_tindak_lanjut', $rekamMedis?->instruksi_tindak_lanjut ?? '')); ?></textarea>
                    <span id="lanjut-bantuan" class="<?php echo e($kelasBantuan); ?>">Petunjuk kontrol, pemantauan, atau tindak lanjut yang disepakati.</span><?php $__errorArgs = ['instruksi_tindak_lanjut'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span id="lanjut-error" class="mt-1 block text-xs font-medium text-rose-700"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </label>
            </section>
            <?php echo $__env->make('pemeriksaan.resep', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </div>

        <aside class="flex flex-col gap-4 lg:sticky lg:top-5" aria-label="Status dan tindakan pemeriksaan">
            <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm" aria-labelledby="status-rekam-medis">
                <h2 id="status-rekam-medis" class="font-semibold text-slate-900">Status rekam medis</h2>
                <dl class="mt-4 space-y-3 text-sm">
                    <div><dt class="text-xs text-slate-500">Kunjungan</dt><dd class="mt-1 font-medium capitalize text-slate-800"><?php echo e($statusKunjungan); ?></dd></div>
                    <div><dt class="text-xs text-slate-500">Rekam medis</dt><dd class="mt-1 font-medium capitalize text-slate-800"><?php echo e($rekamMedis ? str_replace('_', ' ', $rekamMedis->status_rekam_medis) : 'Belum ada'); ?></dd></div>
                </dl>
                <?php if (! ($pemeriksaanFinal)): ?>
                    <div class="mt-5 flex flex-col gap-3">
                        <button type="submit" name="finalisasi_resep" value="0" data-save-draft class="flex min-h-11 w-full items-center justify-center rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50 focus:outline-none focus:ring-4 focus:ring-slate-500/10">Simpan draf</button>
                        <button type="submit" name="finalisasi_resep" value="1" data-finalize class="flex min-h-11 w-full items-center justify-center rounded-xl bg-teal-800 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-teal-900 focus:outline-none focus:ring-4 focus:ring-teal-700/20">Simpan &amp; selesaikan</button>
                    </div>
                    <p class="mt-3 text-xs leading-5 text-slate-500">Periksa semua data sebelum finalisasi. Catatan yang sudah final tidak dapat ditimpa.</p>
                <?php else: ?>
                    <p class="mt-4 rounded-xl bg-amber-50 p-3 text-xs leading-5 text-amber-900">Catatan telah difinalisasi dan tidak dapat diedit.</p>
                    <a href="<?php echo e(route('pemeriksaan.print', $kunjungan)); ?>" class="mt-4 flex min-h-11 w-full items-center justify-center rounded-xl border border-slate-200 px-4 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">Cetak hasil pemeriksaan</a>
                <?php endif; ?>
            </section>
            <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm" aria-labelledby="status-resep">
                <h2 id="status-resep" class="font-semibold text-slate-900">Resep</h2>
                <?php if($rincianResep): ?>
                    <p class="mt-2 text-sm font-medium text-slate-800"><?php echo e($rincianResep->nama_obat_saat_diresepkan); ?></p>
                    <p class="mt-1 text-xs leading-5 text-slate-500"><?php echo e($rincianResep->dosis); ?> · <?php echo e($rincianResep->frekuensi_pemberian); ?></p>
                <?php else: ?>
                    <p class="mt-2 text-sm text-slate-500">Belum ada resep untuk kunjungan ini.</p>
                <?php endif; ?>
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
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.aplikasi', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\Ardi\Kampus\Semester 7\KP\v0\sistem-informasi-praktik-dokter-v2\resources\views\pemeriksaan\show.blade.php ENDPATH**/ ?>