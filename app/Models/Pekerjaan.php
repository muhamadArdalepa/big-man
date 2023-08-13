<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pekerjaan extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
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
        return $this->belongsTo(Tim::class);
    }
    
    public function jenis_pekerjaan()
    {
        return $this->belongsTo(JenisPekerjaan::class);
    }

}
