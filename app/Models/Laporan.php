<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Laporan extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function pelapors()
    {
        return $this->belongsTo(User::class, 'pelapor');
    }

    public function penerima()
    {
        return $this->belongsTo(User::class, 'penerima');
    }

    public function jenis_gangguan()
    {
        return $this->belongsTo(JenisGangguan::class);
    }
    
    public function pemasangan()
    {
        return $this->belongsTo(Pemasangan::class,'pelapor','pelanggan');
    }
    
}
