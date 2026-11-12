@extends('layouts.aplikasi')

@section('content')
@php
    $isEdit = $mode === 'edit';
    $isSelf = $isEdit && auth()->user()->is($pengguna);
    $roleIds = collect(old('id_peran', $isEdit ? $pengguna->roles->pluck('id_peran')->all() : []))
        ->map(fn ($id) => (int) $id)
        ->all();
@endphp
<div class="mx-auto max-w-4xl space-y-6">
    <header>
        <a href="{{ route('pengguna.index') }}" class="inline-flex min-h-9 items-center gap-2 text-sm font-medium text-slate-600 transition hover:text-emerald-800 focus:outline-none focus:ring-2 focus:ring-emerald-600 focus:ring-offset-2">← Kembali ke Data Pengguna</a>
        <p class="mt-5 text-xs font-semibold uppercase tracking-[0.16em] text-emerald-700">Administrasi sistem</p>
        <h1 class="mt-2 text-3xl font-bold tracking-tight text-slate-900">{{ $isEdit ? 'Edit data pengguna' : 'Tambah pengguna' }}</h1>
        <p class="mt-2 text-sm text-slate-600">{{ $isEdit ? 'Perbarui informasi akun, status, dan role akses.' : 'Buat akun baru dengan informasi dan role akses yang sesuai.' }}</p>
    </header>

    @if($errors->any())
        <div role="alert" class="rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-800">
            <p class="font-semibold">Periksa kembali data yang dimasukkan.</p>
            <ul class="mt-2 list-inside list-disc space-y-1">
                @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ $isEdit ? route('pengguna.update', $pengguna) : route('pengguna.store') }}" data-user-account-form data-password-optional="{{ $isEdit ? 'true' : 'false' }}" class="space-y-5 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-7">
        @csrf
        @if($isEdit)@method('PUT')@endif

        <section aria-labelledby="informasi-akun-heading" class="space-y-4">
            <div>
                <h2 id="informasi-akun-heading" class="font-semibold text-slate-900">Informasi akun</h2>
                <p class="mt-1 text-xs text-slate-500">Alamat email digunakan untuk masuk ke sistem.</p>
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
                <div class="sm:col-span-2">
                    <label for="nama_lengkap" class="mb-1.5 block text-sm font-medium text-slate-700">Nama lengkap</label>
                    <input id="nama_lengkap" name="nama_lengkap" type="text" value="{{ old('nama_lengkap', $pengguna->nama_lengkap) }}" maxlength="150" autocomplete="name" required class="min-h-11 w-full rounded-xl border px-3 text-sm text-slate-900 outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100 {{ $errors->has('nama_lengkap') ? 'border-rose-500' : 'border-slate-200' }}">
                    @error('nama_lengkap')<p class="mt-1.5 text-xs text-rose-700">{{ $message }}</p>@enderror
                </div>
                <div class="sm:col-span-2">
                    <label for="alamat_email" class="mb-1.5 block text-sm font-medium text-slate-700">Alamat email</label>
                    <input id="alamat_email" name="alamat_email" type="email" value="{{ old('alamat_email', $pengguna->alamat_email) }}" maxlength="191" autocomplete="email" required class="min-h-11 w-full rounded-xl border px-3 text-sm text-slate-900 outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100 {{ $errors->has('alamat_email') ? 'border-rose-500' : 'border-slate-200' }}">
                    @error('alamat_email')<p class="mt-1.5 text-xs text-rose-700">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="kata_sandi" class="mb-1.5 block text-sm font-medium text-slate-700">{{ $isEdit ? 'Kata sandi baru' : 'Kata sandi' }}</label>
                    <div class="relative">
                        <input id="kata_sandi" name="kata_sandi" data-password-field data-server-invalid="{{ $errors->has('kata_sandi') ? 'true' : 'false' }}" type="password" {{ $isEdit ? '' : 'required' }} minlength="12" autocomplete="new-password" @if($isEdit) placeholder="Kosongkan jika tidak diubah" @endif aria-describedby="password-rules kata-sandi-feedback" aria-invalid="{{ $errors->has('kata_sandi') ? 'true' : 'false' }}" class="min-h-11 w-full rounded-xl border px-3 pr-12 text-sm text-slate-900 outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100 {{ $errors->has('kata_sandi') ? 'border-rose-500' : 'border-slate-200' }}">
                        <button type="button" data-password-toggle data-target="kata_sandi" data-show-label="Tampilkan kata sandi" data-hide-label="Sembunyikan kata sandi" aria-label="Tampilkan kata sandi" aria-controls="kata_sandi" aria-pressed="false" class="absolute right-2 top-1/2 inline-flex h-9 w-9 -translate-y-1/2 items-center justify-center rounded-lg text-slate-500 transition-colors hover:bg-slate-100 hover:text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-600">
                            <svg data-eye-visible aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="pointer-events-none absolute h-5 w-5 scale-100 opacity-100 transition-all duration-200 ease-out"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12s3.5-7 9.75-7 9.75 7 9.75 7-3.5 7-9.75 7-9.75-7-9.75-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                            <svg data-eye-hidden aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="pointer-events-none absolute h-5 w-5 scale-75 opacity-0 transition-all duration-200 ease-out"><path stroke-linecap="round" stroke-linejoin="round" d="m3 3 18 18M10.6 10.6a2 2 0 0 0 2.8 2.8M9.9 5.1A10.9 10.9 0 0 1 12 4.9c5.4 0 9.8 7.1 9.8 7.1a14.8 14.8 0 0 1-3.1 3.8M6.6 6.6C4.1 8.2 2.2 12 2.2 12s3.5 7.1 9.8 7.1c1.2 0 2.3-.3 3.3-.7"/></svg>
                        </button>
                    </div>
                    @if($isEdit)<p class="mt-1.5 text-xs text-slate-500">Kosongkan jika tidak ingin mengubah kata sandi.</p>@endif
                    @error('kata_sandi')<p class="mt-1.5 text-xs text-rose-700">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="kata_sandi_confirmation" class="mb-1.5 block text-sm font-medium text-slate-700">Konfirmasi kata sandi{{ $isEdit ? ' baru' : '' }}</label>
                    <div class="relative">
                        <input id="kata_sandi_confirmation" name="kata_sandi_confirmation" data-password-confirmation data-server-invalid="{{ $errors->has('kata_sandi_confirmation') ? 'true' : 'false' }}" type="password" {{ $isEdit ? '' : 'required' }} minlength="12" autocomplete="new-password" aria-describedby="konfirmasi-password-feedback" aria-invalid="{{ $errors->has('kata_sandi_confirmation') ? 'true' : 'false' }}" class="min-h-11 w-full rounded-xl border border-slate-200 px-3 pr-12 text-sm text-slate-900 outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100">
                        <button type="button" data-password-toggle data-target="kata_sandi_confirmation" data-show-label="Tampilkan konfirmasi kata sandi" data-hide-label="Sembunyikan konfirmasi kata sandi" aria-label="Tampilkan konfirmasi kata sandi" aria-controls="kata_sandi_confirmation" aria-pressed="false" class="absolute right-2 top-1/2 inline-flex h-9 w-9 -translate-y-1/2 items-center justify-center rounded-lg text-slate-500 transition-colors hover:bg-slate-100 hover:text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-600">
                            <svg data-eye-visible aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="pointer-events-none absolute h-5 w-5 scale-100 opacity-100 transition-all duration-200 ease-out"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12s3.5-7 9.75-7 9.75 7 9.75 7-3.5 7-9.75 7-9.75-7-9.75-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                            <svg data-eye-hidden aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="pointer-events-none absolute h-5 w-5 scale-75 opacity-0 transition-all duration-200 ease-out"><path stroke-linecap="round" stroke-linejoin="round" d="m3 3 18 18M10.6 10.6a2 2 0 0 0 2.8 2.8M9.9 5.1A10.9 10.9 0 0 1 12 4.9c5.4 0 9.8 7.1 9.8 7.1a14.8 14.8 0 0 1-3.1 3.8M6.6 6.6C4.1 8.2 2.2 12 2.2 12s3.5 7.1 9.8 7.1c1.2 0 2.3-.3 3.3-.7"/></svg>
                        </button>
                    </div>
                    <p id="konfirmasi-password-feedback" aria-live="polite" class="mt-1.5 min-h-4 text-xs text-slate-500">Ketik ulang kata sandi yang sama.</p>
                    @error('kata_sandi_confirmation')<p class="mt-1.5 text-xs text-rose-700">{{ $message }}</p>@enderror
                </div>
            </div>
            <div id="password-rules" class="space-y-2 rounded-xl bg-slate-50 px-3 py-3" aria-label="Persyaratan kata sandi">
                <p class="text-xs font-semibold text-slate-700">Persyaratan kata sandi</p>
                <ul class="grid gap-x-4 gap-y-2 text-xs sm:grid-cols-2">
                    <li data-password-rule="length" class="flex items-center justify-between gap-2 text-slate-500"><span>Minimal 12 karakter</span><span data-rule-state class="text-[10px] font-medium">Belum dicek</span></li>
                    <li data-password-rule="lowercase" class="flex items-center justify-between gap-2 text-slate-500"><span>Huruf kecil</span><span data-rule-state class="text-[10px] font-medium">Belum dicek</span></li>
                    <li data-password-rule="uppercase" class="flex items-center justify-between gap-2 text-slate-500"><span>Huruf besar</span><span data-rule-state class="text-[10px] font-medium">Belum dicek</span></li>
                    <li data-password-rule="number" class="flex items-center justify-between gap-2 text-slate-500"><span>Angka</span><span data-rule-state class="text-[10px] font-medium">Belum dicek</span></li>
                    <li data-password-rule="symbol" class="flex items-center justify-between gap-2 text-slate-500"><span>Simbol</span><span data-rule-state class="text-[10px] font-medium">Belum dicek</span></li>
                </ul>
                <p id="kata-sandi-feedback" role="status" aria-live="polite" class="text-xs leading-5 text-slate-600">Gunakan minimal 12 karakter dengan huruf besar, huruf kecil, angka, dan simbol.</p>
            </div>
        </section>

        <section aria-labelledby="status-akun-heading" class="space-y-3 border-t border-slate-100 pt-5">
            <div>
                <h2 id="status-akun-heading" class="font-semibold text-slate-900">Status akun</h2>
                <p class="mt-1 text-xs text-slate-500">Akun nonaktif tidak dapat masuk ke sistem.</p>
            </div>
            @if($isSelf)
                <input type="hidden" name="status_aktif" value="{{ $pengguna->status_aktif ? '1' : '0' }}">
                <p class="rounded-xl border border-amber-200 bg-amber-50 px-3 py-2.5 text-sm text-amber-900">Akun yang sedang digunakan tidak dapat dinonaktifkan dari halaman ini.</p>
            @else
                <label for="status_aktif" class="mb-1.5 block text-sm font-medium text-slate-700">Pilih status</label>
                <select id="status_aktif" name="status_aktif" required class="min-h-11 w-full rounded-xl border border-slate-200 bg-white px-3 text-sm text-slate-900 outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100 sm:max-w-xs">
                    <option value="1" @selected((string) old('status_aktif', $isEdit ? (int) $pengguna->status_aktif : 1) === '1')>Aktif</option>
                    <option value="0" @selected((string) old('status_aktif', $isEdit ? (int) $pengguna->status_aktif : 1) === '0')>Nonaktif</option>
                </select>
            @endif
            @error('status_aktif')<p class="text-xs text-rose-700">{{ $message }}</p>@enderror
        </section>

        <section aria-labelledby="role-akun-heading" class="space-y-3 border-t border-slate-100 pt-5">
            <div>
                <h2 id="role-akun-heading" class="font-semibold text-slate-900">Role akses</h2>
                <p class="mt-1 text-xs text-slate-500">Pilih hanya role yang dibutuhkan untuk pekerjaan pengguna ini.</p>
            </div>
            @if($isSelf)
                <div class="flex flex-wrap gap-2">
                    @forelse($pengguna->roles as $role)
                        <span class="rounded-full border border-slate-200 bg-slate-50 px-3 py-1.5 text-xs font-medium text-slate-700">{{ ucfirst($role->nama_peran) }}</span>
                    @empty
                        <span class="text-sm text-slate-500">Belum ada role yang ditetapkan.</span>
                    @endforelse
                </div>
                <p class="rounded-xl border border-amber-200 bg-amber-50 px-3 py-2.5 text-sm text-amber-900">Role akun Anda dikunci untuk mencegah hilangnya akses administrator.</p>
            @else
                <div class="grid gap-2 sm:grid-cols-2">
                    @foreach($peran as $role)
                        <label for="peran-{{ $role->id_peran }}" class="flex cursor-pointer items-start gap-3 rounded-xl border border-slate-200 p-3 transition hover:border-emerald-300 hover:bg-emerald-50/40">
                            <input id="peran-{{ $role->id_peran }}" name="id_peran[]" type="checkbox" value="{{ $role->id_peran }}" @checked(in_array((int) $role->id_peran, $roleIds, true)) class="mt-0.5 h-4 w-4 rounded border-slate-300 text-emerald-700 focus:ring-emerald-600">
                            <span>
                                <span class="block text-sm font-semibold text-slate-800">{{ ucfirst($role->nama_peran) }}</span>
                                @if($role->keterangan_peran)<span class="mt-1 block text-xs leading-5 text-slate-500">{{ $role->keterangan_peran }}</span>@endif
                                @if($role->nama_peran === 'administrator')<span class="mt-1 block text-xs font-medium text-amber-800">Memberikan akses administrasi sistem.</span>@endif
                            </span>
                        </label>
                    @endforeach
                </div>
                @error('id_peran')<p class="text-xs text-rose-700">{{ $message }}</p>@enderror
                @error('id_peran.*')<p class="text-xs text-rose-700">{{ $message }}</p>@enderror
            @endif
        </section>

        <div class="flex flex-col-reverse gap-3 border-t border-slate-100 pt-5 sm:flex-row sm:justify-end">
            <a href="{{ route('pengguna.index') }}" class="inline-flex min-h-11 items-center justify-center rounded-xl border border-slate-200 px-4 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-emerald-600 focus:ring-offset-2">Batal</a>
            <button type="submit" class="inline-flex min-h-11 items-center justify-center rounded-xl bg-emerald-700 px-5 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-800 focus:outline-none focus:ring-2 focus:ring-emerald-600 focus:ring-offset-2">{{ $isEdit ? 'Simpan perubahan' : 'Simpan akun' }}</button>
        </div>
    </form>
</div>
<script>
(() => {
    const form = document.querySelector('[data-user-account-form]');

    if (!form) return;

    const passwordInput = form.querySelector('#kata_sandi');
    const confirmationInput = form.querySelector('#kata_sandi_confirmation');
    const passwordFeedback = form.querySelector('#kata-sandi-feedback');
    const confirmationFeedback = form.querySelector('#konfirmasi-password-feedback');
    const passwordOptional = form.dataset.passwordOptional === 'true';
    const passwordRuleMessage = 'Kata sandi harus memuat huruf kecil, huruf besar, angka, dan simbol serta minimal 12 karakter.';

    const setMessageTone = (element, tone) => {
        element.classList.remove('text-slate-500', 'text-slate-600', 'text-rose-700', 'text-emerald-700', 'text-amber-700');
        element.classList.add(tone === 'invalid' ? 'text-rose-700' : tone === 'valid' ? 'text-emerald-700' : tone === 'warning' ? 'text-amber-700' : 'text-slate-600');
    };

    const setFieldState = (field, state) => {
        field.classList.remove('border-slate-200', 'border-rose-500', 'border-emerald-500', 'focus:border-emerald-600', 'focus:ring-emerald-100', 'focus:border-rose-600', 'focus:ring-rose-100');

        if (state === 'invalid') {
            field.classList.add('border-rose-500', 'focus:border-rose-600', 'focus:ring-rose-100');
        } else if (state === 'valid') {
            field.classList.add('border-emerald-500', 'focus:border-emerald-600', 'focus:ring-emerald-100');
        } else {
            field.classList.add('border-slate-200', 'focus:border-emerald-600', 'focus:ring-emerald-100');
        }

        field.setAttribute('aria-invalid', state === 'invalid' ? 'true' : 'false');
    };

    const validatePassword = () => {
        const value = passwordInput.value;
        const checks = {
            length: value.length >= 12,
            lowercase: /[a-z]/.test(value),
            uppercase: /[A-Z]/.test(value),
            number: /[0-9]/.test(value),
            symbol: /[^A-Za-z0-9\s]/.test(value),
        };
        const isValid = Object.values(checks).every(Boolean);
        const isWaitingForPassword = passwordOptional && !value && confirmationInput.value.length > 0;

        form.querySelectorAll('[data-password-rule]').forEach((ruleElement) => {
            const passed = checks[ruleElement.dataset.passwordRule];
            const state = ruleElement.querySelector('[data-rule-state]');
            const hasValue = value.length > 0;

            ruleElement.classList.remove('text-slate-500', 'text-rose-700', 'text-emerald-700');
            ruleElement.classList.add(!hasValue ? 'text-slate-500' : passed ? 'text-emerald-700' : 'text-rose-700');
            state.textContent = !hasValue ? 'Belum dicek' : passed ? 'Terpenuhi' : 'Belum terpenuhi';
        });

        if (!value) {
            if (isWaitingForPassword) {
                passwordFeedback.textContent = 'Isi kata sandi baru terlebih dahulu.';
                setMessageTone(passwordFeedback, 'invalid');
                setFieldState(passwordInput, 'invalid');
                passwordInput.setCustomValidity('Isi kata sandi baru terlebih dahulu.');
                return false;
            }

            passwordFeedback.textContent = 'Gunakan minimal 12 karakter dengan huruf besar, huruf kecil, angka, dan simbol.';
            setMessageTone(passwordFeedback, 'neutral');
            if (passwordInput.dataset.serverInvalid !== 'true') setFieldState(passwordInput, 'neutral');
            passwordInput.setCustomValidity('');
            return passwordOptional;
        }

        if (isValid) {
            passwordFeedback.textContent = 'Kata sandi memenuhi semua persyaratan.';
            setMessageTone(passwordFeedback, 'valid');
            setFieldState(passwordInput, 'valid');
            passwordInput.setCustomValidity('');
        } else {
            passwordFeedback.textContent = passwordRuleMessage;
            setMessageTone(passwordFeedback, 'invalid');
            setFieldState(passwordInput, 'invalid');
            passwordInput.setCustomValidity(passwordRuleMessage);
        }

        return isValid;
    };

    const validateConfirmation = () => {
        const password = passwordInput.value;
        const confirmation = confirmationInput.value;

        if (passwordOptional) {
            passwordInput.required = confirmation.length > 0;
            confirmationInput.required = password.length > 0;
        }

        if (!password && !confirmation) {
            confirmationFeedback.textContent = 'Ketik ulang kata sandi yang sama.';
            setMessageTone(confirmationFeedback, 'neutral');
            if (confirmationInput.dataset.serverInvalid !== 'true') setFieldState(confirmationInput, 'neutral');
            confirmationInput.setCustomValidity('');
            return passwordOptional;
        }

        if (!password) {
            const message = 'Isi kata sandi terlebih dahulu sebelum konfirmasi.';
            confirmationFeedback.textContent = message;
            setMessageTone(confirmationFeedback, 'invalid');
            setFieldState(confirmationInput, 'invalid');
            confirmationInput.setCustomValidity(message);
            return false;
        }

        if (!confirmation) {
            const message = 'Konfirmasi kata sandi belum diisi.';
            confirmationFeedback.textContent = message;
            setMessageTone(confirmationFeedback, 'warning');
            setFieldState(confirmationInput, 'invalid');
            confirmationInput.setCustomValidity(message);
            return false;
        }

        if (password !== confirmation) {
            const message = 'Konfirmasi kata sandi belum sesuai.';
            confirmationFeedback.textContent = message;
            setMessageTone(confirmationFeedback, 'invalid');
            setFieldState(confirmationInput, 'invalid');
            confirmationInput.setCustomValidity(message);
            return false;
        }

        confirmationFeedback.textContent = 'Konfirmasi kata sandi sesuai.';
        setMessageTone(confirmationFeedback, 'valid');
        setFieldState(confirmationInput, 'valid');
        confirmationInput.setCustomValidity('');
        return true;
    };

    passwordInput.addEventListener('input', () => {
        passwordInput.dataset.serverInvalid = 'false';
        validatePassword();
        validateConfirmation();
    });

    confirmationInput.addEventListener('input', () => {
        confirmationInput.dataset.serverInvalid = 'false';
        validatePassword();
        validateConfirmation();
    });

    form.querySelectorAll('[data-password-toggle]').forEach((button) => {
        const input = form.querySelector(`#${button.dataset.target}`);
        const visibleIcon = button.querySelector('[data-eye-visible]');
        const hiddenIcon = button.querySelector('[data-eye-hidden]');

        button.addEventListener('click', () => {
            const isVisible = input.type === 'password';
            input.type = isVisible ? 'text' : 'password';
            button.setAttribute('aria-pressed', String(isVisible));
            button.setAttribute('aria-label', isVisible ? button.dataset.hideLabel : button.dataset.showLabel);

            visibleIcon.classList.toggle('opacity-100', !isVisible);
            visibleIcon.classList.toggle('scale-100', !isVisible);
            visibleIcon.classList.toggle('opacity-0', isVisible);
            visibleIcon.classList.toggle('scale-75', isVisible);
            hiddenIcon.classList.toggle('opacity-100', isVisible);
            hiddenIcon.classList.toggle('scale-100', isVisible);
            hiddenIcon.classList.toggle('opacity-0', !isVisible);
            hiddenIcon.classList.toggle('scale-75', !isVisible);
        });
    });

    validatePassword();
    validateConfirmation();
})();
</script>
@endsection
