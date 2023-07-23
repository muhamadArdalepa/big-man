<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Laporan extends Model
{
    use HasFactory;

    public function pelapor()
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
    
}
