<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PeriksaPeran
{
    public function handle(Request $request, Closure $next, string ...$peran): Response
    {
        $pengguna = $request->user();

        abort_unless($pengguna && $pengguna->roles()->whereIn('nama_peran', $peran)->exists(), 403);

        return $next($request);
    }
}
