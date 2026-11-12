<?php
namespace App\Http\Controllers;
use App\Models\Resep;
use Illuminate\View\View;
class ResepController extends Controller { public function print(Resep $resep):View{abort_unless(request()->user()->memilikiHakAkses('klinis.lihat'),403);$resep->load(['rincian','rekamMedis.kunjungan.pasien']);abort_unless($resep->status_resep==='difinalisasi',403);return view('resep.print',compact('resep'));}}
