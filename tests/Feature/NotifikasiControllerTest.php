<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class NotifikasiControllerTest extends TestCase
{
    public function test_unauthenticated_requests_redirect_to_login(): void
    {
        $this->get(route('notifikasi.unread'))
            ->assertRedirect(route('masuk'));
    }

    public function test_unread_endpoint_returns_total_and_only_the_current_users_latest_eight(): void
    {
        Schema::create('notifikasi', function (Blueprint $table): void {
            $table->id('id_notifikasi');
            $table->unsignedBigInteger('id_pengguna_penerima');
            $table->string('jenis_notifikasi', 60);
            $table->string('judul_notifikasi', 150);
            $table->text('pesan_notifikasi');
            $table->json('data_tambahan')->nullable();
            $table->string('kunci_peristiwa', 150)->nullable();
            $table->timestamp('dibaca_pada')->nullable();
            $table->timestamp('dibuat_pada')->useCurrent();
        });

        $user = new User;
        $user->forceFill(['id_pengguna' => 1, 'status_aktif' => true]);
        $this->actingAs($user);

        $notifications = [];
        foreach (range(1, 10) as $number) {
            $notifications[] = [
                'id_pengguna_penerima' => 1,
                'jenis_notifikasi' => 'uji',
                'judul_notifikasi' => "Notifikasi {$number}",
                'pesan_notifikasi' => "Pesan {$number}",
                'data_tambahan' => null,
                'kunci_peristiwa' => "uji-{$number}",
                'dibaca_pada' => null,
                'dibuat_pada' => now()->addSeconds($number),
            ];
        }

        $notifications[] = [
            'id_pengguna_penerima' => 2,
            'jenis_notifikasi' => 'uji',
            'judul_notifikasi' => 'Notifikasi milik pengguna lain',
            'pesan_notifikasi' => 'Tidak boleh terlihat',
            'data_tambahan' => null,
            'kunci_peristiwa' => 'uji-user-lain',
            'dibaca_pada' => null,
            'dibuat_pada' => now()->addSeconds(11),
        ];
        $notifications[] = [
            'id_pengguna_penerima' => 1,
            'jenis_notifikasi' => 'uji',
            'judul_notifikasi' => 'Notifikasi sudah dibaca',
            'pesan_notifikasi' => 'Tidak boleh terlihat',
            'data_tambahan' => null,
            'kunci_peristiwa' => 'uji-sudah-dibaca',
            'dibaca_pada' => now(),
            'dibuat_pada' => now()->addSeconds(12),
        ];
        DB::table('notifikasi')->insert($notifications);

        $this->get(route('notifikasi.unread'))
            ->assertOk()
            ->assertJsonPath('jumlah', 10)
            ->assertJsonCount(8, 'data')
            ->assertJsonPath('data.0.judul_notifikasi', 'Notifikasi 10')
            ->assertJsonMissing(['judul_notifikasi' => 'Notifikasi milik pengguna lain'])
            ->assertJsonMissing(['judul_notifikasi' => 'Notifikasi sudah dibaca']);
    }
}
