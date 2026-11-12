<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
class SimpanObatRequest extends FormRequest { public function authorize():bool{return $this->user()?->memilikiHakAkses('obat.kelola')??false;} public function rules():array{return ['nama_obat'=>['required','string','max:150'],'kekuatan_obat'=>['nullable','string','max:80'],'bentuk_sediaan'=>['nullable','string','max:80'],'satuan_obat'=>['nullable','string','max:40'],'status_aktif'=>['nullable','boolean']];} public function attributes():array{return ['nama_obat'=>'nama obat','kekuatan_obat'=>'kekuatan obat','bentuk_sediaan'=>'bentuk sediaan','satuan_obat'=>'satuan obat'];} }
