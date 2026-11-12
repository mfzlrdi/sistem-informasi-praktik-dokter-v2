<?php

namespace Tests\Feature;

use App\Models\Pasien;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PencarianPasienKunjunganTest extends TestCase
{
    use RefreshDatabase;

    private User $resepsionis;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
        $this->resepsionis = User::where('alamat_email', 'resepsionis.demo@praktik.test')->firstOrFail();
    }

    public function test_receptionist_can_search_patients_by_medical_record_name_phone_and_nik(): void
    {
        $pasien = Pasien::create([
            'nomor_rekam_medis' => 'RM-SEARCH-001',
            'nama_lengkap' => 'Nadia Putri',
            'nomor_identitas' => '3276014505890001',
            'nomor_telepon' => '081234567890',
        ]);

        foreach (['RM-SEARCH-001', 'Nadia Putri', '081234567890', '3276014505890001'] as $kataKunci) {
            $this->actingAs($this->resepsionis)
                ->get(route('pendaftaran.index', ['q' => $kataKunci]))
                ->assertOk()
                ->assertSeeText('Nadia Putri')
                ->assertSeeText('RM-SEARCH-001')
                ->assertSee(route('kunjungan.create', ['id_pasien' => $pasien->id_pasien]), false);
        }
    }

    public function test_patient_table_shows_nik_and_contact_address_columns(): void
    {
        Pasien::create([
            'nomor_rekam_medis' => 'RM-TABLE-001',
            'nama_lengkap' => 'Rina Puspita',
            'nomor_identitas' => '3276014505890009',
            'nomor_telepon' => '081234567899',
            'alamat' => 'Jl. Merdeka No. 12, Bandung',
        ]);

        $this->actingAs($this->resepsionis)
            ->get(route('pasien.index'))
            ->assertOk()
            ->assertSeeText('Nama & NIK')
            ->assertSeeText('Kontak & Alamat')
            ->assertSeeText('Rina Puspita')
            ->assertSeeText('NIK: 3276014505890009')
            ->assertSeeText('081234567899')
            ->assertSeeText('Jl. Merdeka No. 12, Bandung');
    }

    public function test_patient_directory_can_search_by_nik(): void
    {
        Pasien::create([
            'nomor_rekam_medis' => 'RM-NIK-001',
            'nama_lengkap' => 'Nina Kartika',
            'nomor_identitas' => '3276014505890010',
        ]);

        $this->actingAs($this->resepsionis)
            ->get(route('pasien.index', ['kata_kunci' => '3276014505890010']))
            ->assertOk()
            ->assertSeeText('Nina Kartika')
            ->assertSeeText('NIK: 3276014505890010');
    }

    public function test_patient_chosen_from_search_is_preselected_on_visit_form(): void
    {
        $pasien = Pasien::create([
            'nomor_rekam_medis' => 'RM-SEARCH-002',
            'nama_lengkap' => 'Bima Santoso',
            'nomor_identitas' => '3276014505890002',
            'nomor_telepon' => '081234567891',
        ]);

        $this->actingAs($this->resepsionis)
            ->get(route('kunjungan.create', ['id_pasien' => $pasien->id_pasien]))
            ->assertOk()
            ->assertSeeText('Pasien dari hasil pencarian telah dipilih')
            ->assertSee('<option value="'.$pasien->id_pasien.'" selected>', false);
    }
}
