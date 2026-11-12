<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BreadcrumbNavigasiTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_sidebar_label_and_breadcrumb_are_shared_across_roles(): void
    {
        $this->seed(DatabaseSeeder::class);

        $akun = [
            ['resepsionis.demo@praktik.test', 'dasbor.resepsionis'],
            ['dokter.demo@praktik.test', 'dasbor.dokter'],
            ['dokter.demo@praktik.test', 'dasbor.administrator'],
        ];

        foreach ($akun as [$email, $rute]) {
            $pengguna = User::where('alamat_email', $email)->firstOrFail();

            $this->actingAs($pengguna)
                ->get(route($rute))
                ->assertOk()
                ->assertSeeText('Dashboard')
                ->assertSeeText('Ringkasan Aktivitas Klinik')
                ->assertSeeText('Ringkasan')
                ->assertSee('aria-label="Breadcrumb"', false)
                ->assertSee('aria-current="page"', false);
        }
    }

    public function test_visit_form_breadcrumb_includes_its_registration_parent(): void
    {
        $this->seed(DatabaseSeeder::class);
        $resepsionis = User::where('alamat_email', 'resepsionis.demo@praktik.test')->firstOrFail();

        $this->actingAs($resepsionis)
            ->get(route('kunjungan.create'))
            ->assertOk()
            ->assertSee('aria-label="Breadcrumb"', false)
            ->assertSeeText('Pendaftaran')
            ->assertSeeText('Pendaftaran kunjungan');
    }
}
