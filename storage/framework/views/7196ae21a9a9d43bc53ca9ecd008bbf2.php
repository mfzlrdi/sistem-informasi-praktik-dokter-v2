<section class="order-5 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6" aria-labelledby="judul-resep">
    <div class="flex items-start gap-3">
        <span class="grid h-9 w-9 shrink-0 place-items-center rounded-xl bg-emerald-50 text-sm font-bold text-emerald-800" aria-hidden="true">04</span>
        <div>
            <h2 id="judul-resep" class="font-semibold text-slate-900">Resep obat</h2>
            <p class="mt-1 text-xs leading-5 text-slate-500">Resep disimpan sebagai draf sampai dokter memfinalisasi pemeriksaan.</p>
        </div>
        <span class="inline-flex w-fit items-center rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-600">Opsional</span>
    </div>
    <?php if($obat->isEmpty()): ?>
        <p class="mt-4 rounded-xl border border-slate-200 bg-slate-50 p-3 text-xs leading-5 text-slate-600">Belum ada obat aktif di katalog. Pemeriksaan tetap dapat disimpan tanpa resep.</p>
    <?php endif; ?>
    <div class="mt-5 grid gap-4 sm:grid-cols-2">
        <label for="obat_id" class="block text-sm font-medium text-slate-800">Pilih dari katalog
            <select id="obat_id" name="obat_id[]" aria-describedby="obat-bantuan <?php $__errorArgs = ['obat_id.0'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> obat-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" <?php $__errorArgs = ['obat_id.0'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> aria-invalid="true" <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?> class="<?php echo e($kelasInput); ?> <?php $__errorArgs = ['obat_id.0'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-rose-400 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                <option value="">— pilih obat —</option>
                <?php $__currentLoopData = $obat; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($item->id_obat); ?>" <?php if((string) old('obat_id.0', $rincianResep?->id_obat ?? '') === (string) $item->id_obat): echo 'selected'; endif; ?>><?php echo e($item->nama_obat); ?><?php echo e($item->kekuatan_obat ? ' · '.$item->kekuatan_obat : ''); ?><?php echo e($item->bentuk_sediaan ? ' · '.$item->bentuk_sediaan : ''); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <span id="obat-bantuan" class="<?php echo e($kelasBantuan); ?>">Pilih obat dari katalog aktif. Jika resep tidak diperlukan, biarkan pilihan kosong.</span><?php $__errorArgs = ['obat_id.0'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span id="obat-error" class="mt-1 block text-xs font-medium text-rose-700"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </label>
        <label for="dosis" class="block text-sm font-medium text-slate-800">Dosis
            <input id="dosis" name="dosis[]" value="<?php echo e(old('dosis.0', $rincianResep?->dosis ?? '')); ?>" maxlength="100" placeholder="Contoh: 500 mg" aria-describedby="dosis-bantuan <?php $__errorArgs = ['dosis.0'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> dosis-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" <?php $__errorArgs = ['dosis.0'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> aria-invalid="true" <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?> class="<?php echo e($kelasInput); ?> <?php $__errorArgs = ['dosis.0'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-rose-400 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
            <span id="dosis-bantuan" class="<?php echo e($kelasBantuan); ?>">Jumlah atau kekuatan per kali pemberian.</span><?php $__errorArgs = ['dosis.0'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span id="dosis-error" class="mt-1 block text-xs font-medium text-rose-700"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </label>
        <label for="frekuensi" class="block text-sm font-medium text-slate-800">Frekuensi
            <input id="frekuensi" name="frekuensi[]" value="<?php echo e(old('frekuensi.0', $rincianResep?->frekuensi_pemberian ?? '')); ?>" maxlength="100" placeholder="Contoh: 3 kali sehari" aria-describedby="frekuensi-bantuan <?php $__errorArgs = ['frekuensi.0'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> frekuensi-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" <?php $__errorArgs = ['frekuensi.0'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> aria-invalid="true" <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?> class="<?php echo e($kelasInput); ?> <?php $__errorArgs = ['frekuensi.0'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-rose-400 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
            <span id="frekuensi-bantuan" class="<?php echo e($kelasBantuan); ?>">Berapa kali dan kapan obat digunakan.</span><?php $__errorArgs = ['frekuensi.0'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span id="frekuensi-error" class="mt-1 block text-xs font-medium text-rose-700"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </label>
        <label for="jumlah" class="block text-sm font-medium text-slate-800">Jumlah
            <input id="jumlah" name="jumlah[]" type="number" inputmode="decimal" min="0.01" max="10000" step="0.01" value="<?php echo e(old('jumlah.0', $rincianResep?->jumlah_diresepkan ?? '')); ?>" placeholder="Contoh: 10" aria-describedby="jumlah-bantuan <?php $__errorArgs = ['jumlah.0'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> jumlah-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" <?php $__errorArgs = ['jumlah.0'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> aria-invalid="true" <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?> class="<?php echo e($kelasInput); ?> <?php $__errorArgs = ['jumlah.0'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-rose-400 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
            <span id="jumlah-bantuan" class="<?php echo e($kelasBantuan); ?>">Jumlah harus lebih dari 0 dan maksimal 10.000.</span><?php $__errorArgs = ['jumlah.0'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span id="jumlah-error" class="mt-1 block text-xs font-medium text-rose-700"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </label>
        <label for="satuan" class="block text-sm font-medium text-slate-800">Satuan
            <input id="satuan" name="satuan[]" value="<?php echo e(old('satuan.0', $rincianResep?->satuan_jumlah ?? '')); ?>" maxlength="40" placeholder="Contoh: tablet" aria-describedby="satuan-bantuan <?php $__errorArgs = ['satuan.0'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> satuan-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" <?php $__errorArgs = ['satuan.0'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> aria-invalid="true" <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?> class="<?php echo e($kelasInput); ?> <?php $__errorArgs = ['satuan.0'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-rose-400 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
            <span id="satuan-bantuan" class="<?php echo e($kelasBantuan); ?>">Satuan jumlah yang diresepkan.</span><?php $__errorArgs = ['satuan.0'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span id="satuan-error" class="mt-1 block text-xs font-medium text-rose-700"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </label>
        <label for="petunjuk" class="block text-sm font-medium text-slate-800 sm:col-span-2">Petunjuk penggunaan
            <textarea id="petunjuk" name="petunjuk[]" rows="3" maxlength="500" placeholder="Contoh: diminum setelah makan" aria-describedby="petunjuk-bantuan <?php $__errorArgs = ['petunjuk.0'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> petunjuk-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" <?php $__errorArgs = ['petunjuk.0'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> aria-invalid="true" <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?> class="<?php echo e($kelasInput); ?> <?php $__errorArgs = ['petunjuk.0'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-rose-400 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"><?php echo e(old('petunjuk.0', $rincianResep?->petunjuk_penggunaan ?? '')); ?></textarea>
            <span id="petunjuk-bantuan" class="<?php echo e($kelasBantuan); ?>">Instruksi penggunaan yang jelas untuk pasien.</span><?php $__errorArgs = ['petunjuk.0'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span id="petunjuk-error" class="mt-1 block text-xs font-medium text-rose-700"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </label>
        <label for="catatan_resep" class="block text-sm font-medium text-slate-800 sm:col-span-2">Catatan resep <span class="font-normal text-slate-500">(opsional)</span>
            <textarea id="catatan_resep" name="catatan_resep" rows="2" maxlength="2000" placeholder="Catatan tambahan terkait resep…" aria-describedby="catatan-resep-bantuan <?php $__errorArgs = ['catatan_resep'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> catatan-resep-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" <?php $__errorArgs = ['catatan_resep'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> aria-invalid="true" <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?> class="<?php echo e($kelasInput); ?> <?php $__errorArgs = ['catatan_resep'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-rose-400 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"><?php echo e(old('catatan_resep', $resepTersimpan?->catatan_resep ?? '')); ?></textarea>
            <span id="catatan-resep-bantuan" class="<?php echo e($kelasBantuan); ?>">Maksimal 2.000 karakter.</span><?php $__errorArgs = ['catatan_resep'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span id="catatan-resep-error" class="mt-1 block text-xs font-medium text-rose-700"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </label>
    </div>
</section>
<?php /**PATH E:\Ardi\Kampus\Semester 7\KP\v0\sistem-informasi-praktik-dokter-v2\resources\views/pemeriksaan/resep.blade.php ENDPATH**/ ?>