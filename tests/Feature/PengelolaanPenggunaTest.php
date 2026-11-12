<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class PengelolaanPenggunaTest extends TestCase
{
    use RefreshDatabase;

    private User $administrator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
        $this->administrator = User::where('alamat_email', 'dokter.demo@praktik.test')->firstOrFail();
    }

    public function test_administrator_can_view_the_user_list_and_account_form(): void
    {
        $this->actingAs($this->administrator)
            ->get(route('pengguna.index'))
            ->assertOk()
            ->assertSeeText('Data Pengguna')
            ->assertSeeText('Tambah pengguna')
            ->assertSeeText('Kelola akses pengguna')
            ->assertSee('id="modal-konfirmasi-admin"', false)
            ->assertSee('data-confirm-action="true"', false)
            ->assertSee('data-confirm-requires-ack="true"', false)
            ->assertDontSee('window.confirm', false);

        $this->get(route('pengguna.create'))
            ->assertOk()
            ->assertSee('data-password-optional="false"', false)
            ->assertSee('data-password-toggle', false)
            ->assertSee('data-password-rule="lowercase"', false)
            ->assertSee('Konfirmasi kata sandi belum sesuai.');

        $resepsionis = User::where('alamat_email', 'resepsionis.demo@praktik.test')->firstOrFail();

        $this->get(route('pengguna.edit', $resepsionis))
            ->assertOk()
            ->assertSeeText('Edit data pengguna')
            ->assertSeeText('Role akses')
            ->assertSee('data-password-optional="true"', false);
    }

    public function test_administrator_can_create_an_account_with_a_strong_password_and_role(): void
    {
        $role = Role::where('nama_peran', 'resepsionis')->firstOrFail();

        $this->actingAs($this->administrator)
            ->post(route('pengguna.store'), [
                'nama_lengkap' => 'Staf Klinik Baru',
                'alamat_email' => '  staf.baru@praktik.test  ',
                'kata_sandi' => 'ClinicStrong123!',
                'kata_sandi_confirmation' => 'ClinicStrong123!',
                'status_aktif' => '1',
                'id_peran' => [$role->id_peran],
            ])
            ->assertRedirect(route('pengguna.index'));

        $akun = User::where('alamat_email', 'staf.baru@praktik.test')->firstOrFail();

        $this->assertTrue(Hash::check('ClinicStrong123!', $akun->kata_sandi));
        $this->assertTrue($akun->roles()->where('peran.id_peran', $role->id_peran)->exists());
    }

    public function test_non_administrator_cannot_open_user_management(): void
    {
        $pengguna = User::create([
            'nama_lengkap' => 'Pengguna biasa',
            'alamat_email' => 'biasa@praktik.test',
            'kata_sandi' => 'ClinicStrong123!',
            'status_aktif' => true,
        ]);

        $this->actingAs($pengguna)
            ->get(route('pengguna.index'))
            ->assertForbidden();
    }

    public function test_administrator_cannot_delete_their_own_account(): void
    {
        $this->actingAs($this->administrator)
            ->delete(route('pengguna.destroy', $this->administrator))
            ->assertSessionHasErrors('pengguna');

        $this->assertDatabaseHas('pengguna', ['id_pengguna' => $this->administrator->id_pengguna]);
    }

    public function test_administrator_can_delete_an_account_without_protected_history(): void
    {
        $role = Role::where('nama_peran', 'resepsionis')->firstOrFail();
        $pengguna = User::create([
            'nama_lengkap' => 'Akun Tanpa Riwayat',
            'alamat_email' => 'tanpa.riwayat@praktik.test',
            'kata_sandi' => 'ClinicStrong123!',
            'status_aktif' => true,
        ]);
        $pengguna->roles()->attach($role->id_peran);

        $this->actingAs($this->administrator)
            ->delete(route('pengguna.destroy', $pengguna))
            ->assertRedirect(route('pengguna.index'));

        $this->assertDatabaseMissing('pengguna', ['id_pengguna' => $pengguna->id_pengguna]);
    }

    public function test_account_with_registration_history_cannot_be_permanently_deleted(): void
    {
        $role = Role::where('nama_peran', 'resepsionis')->firstOrFail();
        $pengguna = User::create([
            'nama_lengkap' => 'Akun dengan Riwayat',
            'alamat_email' => 'dengan.riwayat@praktik.test',
            'kata_sandi' => 'ClinicStrong123!',
            'status_aktif' => true,
        ]);
        $pengguna->roles()->attach($role->id_peran);

        $idPasien = DB::table('pasien')->insertGetId([
            'nomor_rekam_medis' => 'RM-TEST-001',
            'nama_lengkap' => 'Pasien Uji',
            'dibuat_pada' => now(),
            'diperbarui_pada' => now(),
        ]);
        DB::table('kunjungan')->insert([
            'id_pasien' => $idPasien,
            'tanggal_kunjungan' => today()->toDateString(),
            'nomor_antrean' => 1,
            'waktu_pendaftaran' => now()->format('H:i:s'),
            'status_kunjungan' => 'menunggu',
            'id_pengguna_pendaftar' => $pengguna->id_pengguna,
            'dibuat_pada' => now(),
            'diperbarui_pada' => now(),
        ]);

        $this->actingAs($this->administrator)
            ->delete(route('pengguna.destroy', $pengguna))
            ->assertSessionHasErrors('pengguna');

        $this->assertDatabaseHas('pengguna', ['id_pengguna' => $pengguna->id_pengguna]);
    }

    public function test_deactivated_user_is_logged_out_on_their_next_request(): void
    {
        $role = Role::where('nama_peran', 'resepsionis')->firstOrFail();
        $pengguna = User::create([
            'nama_lengkap' => 'Resepsionis Nonaktif',
            'alamat_email' => 'nonaktif@praktik.test',
            'kata_sandi' => 'ClinicStrong123!',
            'status_aktif' => true,
        ]);
        $pengguna->roles()->attach($role->id_peran);

        $this->actingAs($this->administrator)
            ->put(route('pengguna.update', $pengguna), [
                'nama_lengkap' => $pengguna->nama_lengkap,
                'alamat_email' => $pengguna->alamat_email,
                'status_aktif' => '0',
                'id_peran' => [$role->id_peran],
            ])
            ->assertRedirect(route('pengguna.index'));

        $this->actingAs($pengguna->fresh())
            ->get(route('dasbor.pilih'))
            ->assertRedirect(route('masuk'))
            ->assertSessionHasErrors('alamat_email');
    }

    public function test_account_creation_rejects_a_weak_password(): void
    {
        $role = Role::where('nama_peran', 'resepsionis')->firstOrFail();

        $this->actingAs($this->administrator)
            ->from(route('pengguna.create'))
            ->post(route('pengguna.store'), [
                'nama_lengkap' => 'Staf Klinik Baru',
                'alamat_email' => 'staf.lemah@praktik.test',
                'kata_sandi' => 'clinicpassword123',
                'kata_sandi_confirmation' => 'clinicpassword123',
                'status_aktif' => '1',
                'id_peran' => [$role->id_peran],
            ])
            ->assertRedirect(route('pengguna.create'))
            ->assertSessionHasErrors('kata_sandi');
    }
}
