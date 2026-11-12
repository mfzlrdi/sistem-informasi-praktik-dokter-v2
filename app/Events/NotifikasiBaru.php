<?php

namespace App\Events;

use App\Models\Notifikasi;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Contracts\Events\ShouldDispatchAfterCommit;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NotifikasiBaru implements ShouldBroadcastNow, ShouldDispatchAfterCommit
{
    use Dispatchable,SerializesModels;

    public function __construct(public Notifikasi $notifikasi) {}

    public function broadcastOn(): array
    {
        return [new PrivateChannel('pengguna.'.$this->notifikasi->id_pengguna_penerima)];
    }

    public function broadcastAs(): string
    {
        return 'notifikasi.baru';
    }

    public function broadcastWith(): array
    {
        return ['id_notifikasi' => $this->notifikasi->id_notifikasi, 'judul_notifikasi' => $this->notifikasi->judul_notifikasi, 'pesan_notifikasi' => $this->notifikasi->pesan_notifikasi, 'dibuat_pada' => $this->notifikasi->dibuat_pada?->toIso8601String()];
    }
}
