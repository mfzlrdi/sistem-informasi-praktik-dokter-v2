<?php
namespace App\Http\Controllers;
use App\Models\PemenuhanResep;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
class PemenuhanController extends Controller { public function index(Request $r):View{abort_unless($r->user()->memilikiHakAkses('pemenuhan.lihat'),403);$tugas=PemenuhanResep::with(['resep.rincian','resep.rekamMedis.kunjungan.pasien'])->latest('dibuat_pada')->paginate(12);return view('pemenuhan.index',compact('tugas'));}public function update(Request $r,PemenuhanResep $pemenuhan):RedirectResponse{abort_unless($r->user()->memilikiHakAkses('pemenuhan.ubah'),403);$tujuan=$r->validate(['status_pemenuhan'=>['required','in:menunggu_tindak_lanjut,sedang_diproses,siap_diserahkan,terhambat,dirujuk_ke_apotek_luar,dibatalkan']])['status_pemenuhan'];$valid=['menunggu_tindak_lanjut'=>['sedang_diproses','dibatalkan','terhambat','dirujuk_ke_apotek_luar'],'sedang_diproses'=>['siap_diserahkan','terhambat','dibatalkan'],'siap_diserahkan'=>['terhambat','dibatalkan']];abort_unless(in_array($tujuan,$valid[$pemenuhan->status_pemenuhan]??[],true),422,'Transisi pemenuhan tidak valid.');$pemenuhan->update(['status_pemenuhan'=>$tujuan,'id_pengguna_penanggung_jawab'=>$r->user()->id_pengguna,'mulai_diproses_pada'=>$tujuan==='sedang_diproses'?now():$pemenuhan->mulai_diproses_pada]);return back()->with('status','Status pemenuhan diperbarui.');}}
