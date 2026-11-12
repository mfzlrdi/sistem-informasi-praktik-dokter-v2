<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('pengguna.{id}', function ($user, $id) {
    return (int) $user->id_pengguna === (int) $id;
});
