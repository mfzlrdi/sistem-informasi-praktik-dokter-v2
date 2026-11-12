<?php

namespace Tests\Feature;

use App\Models\Pasien;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MenuDanNomorRekamMedisTest extends TestCase
{
    use RefreshDatabase;

    public function test_sidebar_menu_descriptions_are_available_for_each_role(): void
    {
        $this->seed(DatabaseSeeder::class);

        $administrator = User::where('alamat_email', 'dokter.demo@praktik.test')->firstOrFail();
        $tampilanAdministrator = $this->actingAs($administrator)->get(route('dasbor.administrator'))->assertOk();

        foreach ([
            'Ringkasan Aktivitas Klinik',
            'Pantau antrean aktif',
            'Pasien dan kunjungan',
            'Kelola data pasien',
            'Kelola stok obat',
            'Ringkasan kinerja klinik',
            'Kelola akses pengguna',
        ] as $deskripsi) {
            $tampilanAdministrator->assertSeeText($deskripsi);
        }

        $resepsionis = User::where('alamat_email', 'resepsionis.demo@praktik.test')->firstOrFail();
        $tampilanResepsionis = $this->actingAs($resepsionis)->get(route('dasbor.resepsionis'))->assertOk();

        foreach ([
            'Ringkasan Aktivitas Klinik',
            'Pantau antrean aktif',
            'Pasien dan kunjungan',
            'Kelola data pasien',
            'Tinjau riwayat pasien',
            'Ringkasan kinerja klinik',
            'Pantau status resep',
        ] as $deskripsi) {
            $tampilanResepsionis->assertSeeText($deskripsi);
        }
    }

    public function test_administrator_sees_the_custom_confirmation_for_medicine_deactivation(): void
    {
        $this->seed(DatabaseSeeder::class);
        $administrator = User::where('alamat_email', 'dokter.demo@praktik.test')->firstOrFail();

        $this->actingAs($administrator)
            ->get(route('obat.index'))
            ->assertOk()
            ->assertSee('id="modal-konfirmasi-admin"', false)
            ->assertSee('data-confirm-action="true"', false)
            ->assertSee('data-confirm-name="Amoksisilin"', false)
            ->assertDontSee('onsubmit="return confirm', false);
    }

    public function test_patient_create_form_disables_and_does_not_submit_medical_record_number(): void
    {
        $this->seed(DatabaseSeeder::class);
        $resepsionis = User::where('alamat_email', 'resepsionis.demo@praktik.test')->firstOrFail();

        $this->actingAs($resepsionis)
            ->get(route('pasien.create'))
            ->assertOk()
            ->assertSee('placeholder="Dibuat otomatis saat disimpan"', false)
            ->assertSee('disabled', false)
            ->assertDontSee('name="nomor_rekam_medis"', false);
    }

    public function test_server_generates_medical_record_number_even_if_client_submits_one(): void
    {
        $this->seed(DatabaseSeeder::class);
        $resepsionis = User::where('alamat_email', 'resepsionis.demo@praktik.test')->firstOrFail();
        $nomorPalsu = 'RM-DITENTUKAN-CLIENT';

        $response = $this->actingAs($resepsionis)->post(route('pasien.store'), [
            'nomor_rekam_medis' => $nomorPalsu,
            'nama_lengkap' => 'Pasien Otomatis',
        ]);

        $pasien = Pasien::where('nama_lengkap', 'Pasien Otomatis')->firstOrFail();

        $response->assertRedirect(route('pasien.show', $pasien));
        $this->assertNotSame($nomorPalsu, $pasien->nomor_rekam_medis);
        $this->assertMatchesRegularExpression('/\\ARM-\\d{8}-[A-Z0-9]{4}\\z/', $pasien->nomor_rekam_medis);
    }

    public function test_patient_update_ignores_attempts_to_change_medical_record_number(): void
    {
        $this->seed(DatabaseSeeder::class);
        $administrator = User::where('alamat_email', 'dokter.demo@praktik.test')->firstOrFail();
        $pasien = Pasien::create([
            'nomor_rekam_medis' => 'RM-LOCKED-0001',
            'nama_lengkap' => 'Pasien Awal',
        ]);

        $this->actingAs($administrator)
            ->put(route('pasien.update', $pasien), [
                'nomor_rekam_medis' => 'RM-UBAH-RESEPSIONIS',
                'nama_lengkap' => 'Pasien Diperbarui',
            ])
            ->assertRedirect(route('pasien.show', $pasien));

        $this->assertDatabaseHas('pasien', [
            'id_pasien' => $pasien->id_pasien,
            'nomor_rekam_medis' => 'RM-LOCKED-0001',
            'nama_lengkap' => 'Pasien Diperbarui',
        ]);
    }
}
