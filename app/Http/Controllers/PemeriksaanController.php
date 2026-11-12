<?php
namespace App\Http\Controllers;
use App\Http\Requests\SimpanPemeriksaanRequest;
use App\Models\Kunjungan;
use App\Models\Obat;
use App\Models\RekamMedis;
use App\Models\Resep;
use App\Services\TugasResepService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
class PemeriksaanController extends Controller {
    public function mulai(Kunjungan $kunjungan): RedirectResponse
    {
        abort_unless(request()->user()->memilikiHakAkses('klinis.lihat'), 403);

        DB::transaction(function () use ($kunjungan): void {
            $terkunci = Kunjungan::query()->lockForUpdate()->findOrFail($kunjungan->id_kunjungan);
            abort_unless($terkunci->status_kunjungan === 'dipanggil', 422, 'Pemeriksaan hanya dapat dimulai setelah antrean dipanggil.');
            $terkunci->update([
                'status_kunjungan' => 'sedang_konsultasi',
                'dimulai_pada' => now(),
                'id_pengguna_pengubah' => request()->user()->id_pengguna,
            ]);
        });

        return redirect()->route('pemeriksaan.show', $kunjungan);
    }

    public function show(Kunjungan $kunjungan): View { $this->izin($kunjungan); abort_if(in_array($kunjungan->status_kunjungan,['menunggu','selesai','dibatalkan'],true),422,'Kunjungan belum atau tidak dapat diperiksa.'); $kunjungan->load(['pasien','pendaftar']); $rekamMedis=RekamMedis::with(['diagnosa','tindakan','resep.rincian'])->where('id_kunjungan',$kunjungan->id_kunjungan)->first(); $obat=Obat::where('status_aktif',true)->orderBy('nama_obat')->get(); $riwayat=RekamMedis::with('kunjungan')->whereHas('kunjungan',fn($q)=>$q->where('id_pasien',$kunjungan->id_pasien))->where('status_rekam_medis','difinalisasi')->where('id_kunjungan','!=',$kunjungan->id_kunjungan)->latest('dibuat_pada')->paginate(5); return view('pemeriksaan.show',compact('kunjungan','rekamMedis','obat','riwayat')); }
    public function store(SimpanPemeriksaanRequest $request,Kunjungan $kunjungan): RedirectResponse { $this->izin($kunjungan); $data=$request->validated(); DB::transaction(function() use ($request, $kunjungan, $data): void {
            $kunjunganTerkunci = Kunjungan::query()->lockForUpdate()->findOrFail($kunjungan->id_kunjungan);
            abort_unless($kunjunganTerkunci->status_kunjungan === 'sedang_konsultasi', 422, 'Pemeriksaan hanya dapat disimpan saat konsultasi sedang berlangsung.');

            $rekamTerkunci = RekamMedis::query()->where('id_kunjungan', $kunjunganTerkunci->id_kunjungan)->lockForUpdate()->first();
            abort_if($rekamTerkunci?->status_rekam_medis === 'difinalisasi', 422, 'Catatan yang telah difinalisasi tidak dapat ditimpa. Gunakan alur koreksi yang dapat ditelusuri.');

            $vital=array_filter(['tekanan_darah'=>$data['tekanan_darah']??null,'suhu_tubuh'=>$data['suhu_tubuh']??null,'denyut_nadi'=>$data['denyut_nadi']??null,'frekuensi_pernapasan'=>$data['frekuensi_pernapasan']??null,'berat_badan'=>$data['berat_badan']??null,'tinggi_badan'=>$data['tinggi_badan']??null],fn($v)=>$v!==null&&$v!==''); $rekam=RekamMedis::updateOrCreate(['id_kunjungan'=>$kunjungan->id_kunjungan],['id_pengguna_dokter'=>$request->user()->id_pengguna,'anamnesis'=>$data['anamnesis']??null,'hasil_pemeriksaan'=>$data['hasil_pemeriksaan']??null,'tanda_vital'=>$vital,'rencana_terapi'=>$data['rencana_terapi']??null,'instruksi_tindak_lanjut'=>$data['instruksi_tindak_lanjut']??null,'status_rekam_medis'=>'draf']); $rekam->diagnosa()->delete(); foreach(array_filter($data['diagnosa']??[]) as $nama)$rekam->diagnosa()->create(['nama_diagnosa'=>$nama,'jenis_diagnosa'=>'utama']); $rekam->tindakan()->delete(); foreach(array_filter($data['tindakan']??[]) as $nama)$rekam->tindakan()->create(['nama_tindakan'=>$nama]); $ids=array_filter($data['obat_id']??[]); if(count($ids)){ $resep=$rekam->resep()->where('status_resep','draf')->first()??$rekam->resep()->create(['id_kunjungan'=>$kunjungan->id_kunjungan,'id_pengguna_dokter'=>$request->user()->id_pengguna,'nomor_resep'=>'RX-'.now()->format('YmdHis').'-'.random_int(10,99),'status_resep'=>'draf','catatan_resep'=>$data['catatan_resep']??null]); $resep->rincian()->delete(); foreach($ids as $i=>$id){$item=Obat::findOrFail($id);$resep->rincian()->create(['id_obat'=>$item->id_obat,'nama_obat_saat_diresepkan'=>$item->nama_obat,'kekuatan_saat_diresepkan'=>$item->kekuatan_obat,'bentuk_sediaan_saat_diresepkan'=>$item->bentuk_sediaan,'dosis'=>$data['dosis'][$i],'frekuensi_pemberian'=>$data['frekuensi'][$i],'jumlah_diresepkan'=>$data['jumlah'][$i],'satuan_jumlah'=>$data['satuan'][$i],'petunjuk_penggunaan'=>$data['petunjuk'][$i]]);} if($request->boolean('finalisasi_resep'))$resep->update(['status_resep'=>'difinalisasi','difinalisasi_pada'=>now()]); } if($data['finalisasi_resep']??false)$rekam->update(['status_rekam_medis'=>'difinalisasi','difinalisasi_pada'=>now()]); }); $resepFinal=Resep::where('id_kunjungan',$kunjungan->id_kunjungan)->where('status_resep','difinalisasi')->first(); if($resepFinal) app(TugasResepService::class)->buatSetelahFinalisasi($resepFinal); return back()->with('status','Catatan pemeriksaan berhasil disimpan.'); }
    public function print(Kunjungan $kunjungan): View { $this->izin($kunjungan); $rekam=$kunjungan->load(['pasien','rekamMedis.diagnosa','rekamMedis.tindakan','rekamMedis.resep.rincian'])->rekamMedis; abort_unless($rekam && $rekam->status_rekam_medis==='difinalisasi',403); return view('pemeriksaan.print',compact('kunjungan','rekam')); }
    private function izin(Kunjungan $kunjungan): void { abort_unless(request()->user()->memilikiHakAkses('klinis.lihat'),403); abort_unless($kunjungan->status_kunjungan!=='dibatalkan',404); }
}
