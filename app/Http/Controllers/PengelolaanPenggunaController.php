<?php

namespace App\Http\Controllers;

use App\Http\Requests\PerbaruiPenggunaRequest;
use App\Http\Requests\SimpanPenggunaRequest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class PengelolaanPenggunaController extends Controller
{
    public function index(Request $request): View
    {
        $this->assertCanManageUsers($request);

        $search = trim((string) $request->query('q', ''));
        $query = User::query()->with('roles')->orderBy('nama_lengkap');

        if ($search !== '') {
            $query->where(function (Builder $builder) use ($search): void {
                $builder->where('nama_lengkap', 'like', "%{$search}%")
                    ->orWhere('alamat_email', 'like', "%{$search}%");
            });
        }

        $pengguna = $query->paginate(12)->withQueryString();
        $jumlahPengguna = User::count();
        $jumlahAktif = User::where('status_aktif', true)->count();
        $jumlahNonaktif = $jumlahPengguna - $jumlahAktif;

        return view('pengguna.index', compact('pengguna', 'search', 'jumlahPengguna', 'jumlahAktif', 'jumlahNonaktif'));
    }

    public function create(Request $request): View
    {
        $this->assertCanManageUsers($request);

        return view('pengguna.form', [
            'mode' => 'create',
            'pengguna' => new User,
            'peran' => Role::orderBy('nama_peran')->get(),
        ]);
    }

    public function store(SimpanPenggunaRequest $request): RedirectResponse
    {
        $data = $request->validated();

        DB::transaction(function () use ($data): void {
            $pengguna = User::create([
                'nama_lengkap' => $data['nama_lengkap'],
                'alamat_email' => $data['alamat_email'],
                'kata_sandi' => $data['kata_sandi'],
                'status_aktif' => (bool) $data['status_aktif'],
            ]);

            $pengguna->roles()->sync(array_map('intval', $data['id_peran']));
        });

        return redirect()->route('pengguna.index')->with('status', 'Akun pengguna berhasil ditambahkan.');
    }

    public function edit(Request $request, User $pengguna): View
    {
        $this->assertCanManageUsers($request);
        $pengguna->load('roles');

        return view('pengguna.form', [
            'mode' => 'edit',
            'pengguna' => $pengguna,
            'peran' => Role::orderBy('nama_peran')->get(),
        ]);
    }

    public function update(PerbaruiPenggunaRequest $request, User $pengguna): RedirectResponse
    {
        $data = $request->validated();

        DB::transaction(function () use ($request, $pengguna, $data): void {
            $akun = User::query()->lockForUpdate()->findOrFail($pengguna->getKey());
            $adalahAkunSendiri = $akun->is($request->user());
            $statusAktif = (bool) $data['status_aktif'];

            if ($adalahAkunSendiri && ! $statusAktif) {
                throw ValidationException::withMessages([
                    'status_aktif' => 'Anda tidak dapat menonaktifkan akun yang sedang digunakan.',
                ]);
            }

            $idPeran = $adalahAkunSendiri
                ? $akun->roles()->pluck('peran.id_peran')->map(fn ($id): int => (int) $id)->all()
                : array_map('intval', $data['id_peran']);

            $administratorAktif = $this->lockActiveAdministrators();
            $adalahAdministratorAktif = $administratorAktif->contains(
                fn (User $administrator): bool => (int) $administrator->getKey() === (int) $akun->getKey(),
            );
            $idPeranAdministrator = Role::query()->where('nama_peran', 'administrator')->value('id_peran');
            $tetapMenjadiAdministratorAktif = $statusAktif
                && $idPeranAdministrator !== null
                && in_array((int) $idPeranAdministrator, $idPeran, true);

            if ($adalahAdministratorAktif && ! $tetapMenjadiAdministratorAktif && $administratorAktif->count() <= 1) {
                throw ValidationException::withMessages([
                    'id_peran' => 'Sistem harus selalu memiliki minimal satu administrator aktif.',
                ]);
            }

            $akun->fill([
                'nama_lengkap' => $data['nama_lengkap'],
                'alamat_email' => $data['alamat_email'],
                'status_aktif' => $statusAktif,
            ]);

            if (filled($data['kata_sandi'] ?? null)) {
                $akun->kata_sandi = $data['kata_sandi'];
            }

            $akun->save();

            if (! $adalahAkunSendiri) {
                $akun->roles()->sync($idPeran);
            }
        });

        return redirect()->route('pengguna.index')->with('status', 'Data akun pengguna berhasil diperbarui.');
    }

    public function destroy(Request $request, User $pengguna): RedirectResponse
    {
        $this->assertCanManageUsers($request);

        DB::transaction(function () use ($request, $pengguna): void {
            $akun = User::query()->lockForUpdate()->findOrFail($pengguna->getKey());

            if ($akun->is($request->user())) {
                throw ValidationException::withMessages([
                    'pengguna' => 'Anda tidak dapat menghapus akun yang sedang digunakan.',
                ]);
            }

            $administratorAktif = $this->lockActiveAdministrators();
            $adalahAdministratorAktif = $administratorAktif->contains(
                fn (User $administrator): bool => (int) $administrator->getKey() === (int) $akun->getKey(),
            );

            if ($adalahAdministratorAktif && $administratorAktif->count() <= 1) {
                throw ValidationException::withMessages([
                    'pengguna' => 'Administrator aktif terakhir tidak dapat dihapus. Tetapkan administrator pengganti terlebih dahulu.',
                ]);
            }

            if ($this->hasProtectedHistory($akun)) {
                throw ValidationException::withMessages([
                    'pengguna' => 'Akun memiliki riwayat pendaftaran atau pelayanan klinis. Nonaktifkan akun agar riwayat tetap utuh.',
                ]);
            }

            $akun->delete();
        });

        return redirect()->route('pengguna.index')->with('status', 'Akun pengguna berhasil dihapus.');
    }

    private function assertCanManageUsers(Request $request): void
    {
        abort_unless($request->user()?->memilikiHakAkses('pengguna.role.ubah'), 403);
    }

    private function lockActiveAdministrators(): Collection
    {
        return User::query()
            ->where('status_aktif', true)
            ->whereHas('roles', fn (Builder $query) => $query->where('peran.nama_peran', 'administrator'))
            ->orderBy('id_pengguna')
            ->lockForUpdate()
            ->get(['id_pengguna']);
    }

    private function hasProtectedHistory(User $pengguna): bool
    {
        $idPengguna = $pengguna->getKey();

        return DB::table('kunjungan')->where('id_pengguna_pendaftar', $idPengguna)->exists()
            || DB::table('rekam_medis')->where('id_pengguna_dokter', $idPengguna)->exists()
            || DB::table('resep')->where('id_pengguna_dokter', $idPengguna)->exists()
            || DB::table('rincian_penyerahan_obat')->where('id_pengguna_penyerah', $idPengguna)->exists();
    }
}
