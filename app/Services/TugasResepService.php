<?php
namespace App\Services;
use App\Events\NotifikasiBaru;
use App\Models\Notifikasi;
use App\Models\PemenuhanResep;
use App\Models\Resep;
use App\Models\User;
use Illuminate\Support\Facades\DB;
class TugasResepService { public function buatSetelahFinalisasi(Resep $resep): PemenuhanResep { return DB::transaction(function()use($resep){$tugas=PemenuhanResep::firstOrCreate(['id_resep'=>$resep->id_resep],['status_pemenuhan'=>'menunggu_tindak_lanjut']);$penerima=User::where('status_aktif',true)->whereHas('roles.hakAkses',fn($q)=>$q->where('nama_hak_akses','kunjungan.daftar'))->get();foreach($penerima as $user){$n=Notifikasi::firstOrCreate(['id_pengguna_penerima'=>$user->id_pengguna,'kunci_peristiwa'=>'resep-final-'.$resep->id_resep],['jenis_notifikasi'=>'resep_difinalisasi','judul_notifikasi'=>'Resep selesai — perlu tindak lanjut','pesan_notifikasi'=>'Resep '.$resep->nomor_resep.' telah difinalisasi. Silakan periksa tugas tindak lanjut obat.','data_tambahan'=>['id_resep'=>$resep->id_resep,'id_pemenuhan_resep'=>$tugas->id_pemenuhan_resep]]);if($n->wasRecentlyCreated)event(new NotifikasiBaru($n));}return $tugas;});}}
