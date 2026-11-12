<?php
namespace App\Services;
use App\Events\NotifikasiBaru;
use App\Models\Notifikasi;
use App\Models\User;
use Throwable;
class NotifikasiOperasionalService { public function kirimUntukPermission(string $permission,string $jenis,string $judul,string $pesan,array $data,string $kunci):void { $penerima=User::where('status_aktif',true)->whereHas('roles.hakAkses',fn($q)=>$q->where('nama_hak_akses',$permission))->get();foreach($penerima as $user){$notifikasi=Notifikasi::firstOrCreate(['id_pengguna_penerima'=>$user->id_pengguna,'kunci_peristiwa'=>$kunci],['jenis_notifikasi'=>$jenis,'judul_notifikasi'=>$judul,'pesan_notifikasi'=>$pesan,'data_tambahan'=>$data]);if($notifikasi->wasRecentlyCreated){try{event(new NotifikasiBaru($notifikasi));}catch(Throwable){/* Penyimpanan tetap menjadi sumber data jika broadcast belum aktif. */}}}}}
