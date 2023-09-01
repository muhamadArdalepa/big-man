<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pekerjaan extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function getNamaPekerjaan() {
        return JenisPekerjaan::find($this->jenis_pekerjaan_id)->nama_pekerjaan;
    }

    
    public function getBadgeJenisPekerjaan() {
        $color = ['success','warning','primary'];
        $id = $this->jenis_pekerjaan_id > 2 ? 2 :  $this->jenis_pekerjaan_id; 
        return '<span class="badge bg-gradient-'.$color[$id-1].' text-xxs">'.$this->getNamaPekerjaan().'</span>';
    }

    public function getKetuaTim() {
        return Tim::with('user')->find($this->tim_id);
    }

    public function getAnggotaTim() {
        return TimAnggota::with('user')->where('tim_id',$this->tim_id)->whereNot('user_id',$this->getKetuaTim()->user_id)->get();
    }

    public function getDetailPekerjaan() {
        switch ($this->jenis_pekerjaan_id) {
            case 1:
                $pemasangan = Pemasangan::with('user')->find($this->pemasangan_id);
                return $pemasangan->user->nama.' - '.$pemasangan->alamat;
                break;
            
            default:
                # code...
                break;
        }
    }

    public function laporan()
    {
        return $this->belongsTo(Laporan::class);
    }
    public function pemasangan()
    {
        return $this->belongsTo(Pemasangan::class);
    }
    public function tim()
    {
        return $this->belongsTo(Tim::class);
    }
    public function tim_anggotas()
    {
        return $this->belongsTo(TimAnggota::class,'tim_id','tim_id');
    }
    public function wilayah()
    {
        return $this->belongsTo(Wilayah::class);
    }
    
    public function jenis_pekerjaan()
    {
        return $this->belongsTo(JenisPekerjaan::class);
    }

}
