<form method="POST" action="<?php echo e(route('antrean.update', $item)); ?>">
    <?php echo csrf_field(); ?>
    <?php echo method_field('PATCH'); ?>
    <input type="hidden" name="status_kunjungan" value="<?php echo e($tujuan); ?>">
    <button class="rounded-lg bg-emerald-700 px-3 py-2 text-[11px] font-semibold text-white"><?php echo e($label); ?></button>
</form>
<?php /**PATH E:\Ardi\Kampus\Semester 7\KP\v0\sistem-informasi-praktik-dokter-v2\resources\views\antrean\tombol-status.blade.php ENDPATH**/ ?>