<?php

namespace Tests\Feature;

use App\Models\HakAkses;
use App\Models\Kunjungan;
use App\Models\Pasien;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardDokterAntreanTest extends TestCase
{
    use RefreshDatabase;

    public function test_doctor_dashboard_shows_the_first_active_patient_and_active_queue(): void
    {
        $this->seed(DatabaseSeeder::class);
        $dokter = User::where('alamat_email', 'dokter.demo@praktik.test')->firstOrFail();

        $this->buatKunjungan($dokter, 1, 'Akmal Dwi Saputra', 'sedang_konsultasi', 'demam dan batuk');
        $this->buatKunjungan($dokter, 2, 'Nadia Putri', 'menunggu', 'muntaber');
        $this->buatKunjungan($dokter, 3, 'Pasien Selesai', 'selesai', 'kontrol rutin');

        $response = $this->actingAs($dokter)
            ->get(route('dasbor.dokter'))
            ->assertOk()
            ->assertSeeText('Pasien berikutnya')
            ->assertSeeText('A-001')
            ->assertSeeText('Akmal Dwi Saputra')
            ->assertSeeText('Keluhan awal: demam dan batuk')
            ->assertSeeText('A-002')
            ->assertSeeText('Nadia Putri')
            ->assertSeeText('muntaber')
            ->assertSeeText('2 pasien aktif hari ini.')
            ->assertSeeText('Sedang Konsultasi')
            ->assertSeeText('Menunggu')
            ->assertDontSeeText('Pasien Selesai');

        $this->assertMatchesRegularExpression(
            '/<h2[^>]*id="pasien-berikutnya-heading"[^>]*>A-001<\/h2>/',
            $response->getContent(),
        );
    }

    public function test_doctor_dashboard_hides_complaints_without_clinical_permission(): void
    {
        $this->seed(DatabaseSeeder::class);
        $dokter = User::where('alamat_email', 'dokter.demo@praktik.test')->firstOrFail();
        $peranDokter = $dokter->roles()->where('nama_peran', 'dokter')->firstOrFail();
        $izinKlinis = HakAkses::where('nama_hak_akses', 'klinis.lihat')->firstOrFail();
        $peranDokter->hakAkses()->detach($izinKlinis->id_hak_akses);

        $this->buatKunjungan($dokter, 1, 'Nadia Putri', 'menunggu', 'keluhan rahasia');

        $this->actingAs($dokter)
            ->get(route('dasbor.dokter'))
            ->assertOk()
            ->assertSeeText('Nadia Putri')
            ->assertDontSeeText('keluhan rahasia');
    }

    private function buatKunjungan(User $dokter, int $nomorAntrean, string $namaPasien, string $status, string $keluhan): void
    {
        $pasien = Pasien::create([
            'nomor_rekam_medis' => sprintf('RM-DASH-%03d', $nomorAntrean),
            'nama_lengkap' => $namaPasien,
        ]);

        Kunjungan::create([
            'id_pasien' => $pasien->id_pasien,
            'tanggal_kunjungan' => today()->toDateString(),
            'nomor_antrean' => $nomorAntrean,
            'waktu_pendaftaran' => now()->format('H:i:s'),
            'keluhan_awal' => $keluhan,
            'status_kunjungan' => $status,
            'id_pengguna_pendaftar' => $dokter->id_pengguna,
        ]);
    }
}
