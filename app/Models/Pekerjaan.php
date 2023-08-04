<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pekerjaan extends Model
{
    use HasFactory;

    public function laporan()
    {
        return $this->belongsTo(Laporan::class)    ;
    }
    public function pemasangan()
    {
        return $this->belongsTo(Pemasangan::class)    ;
    }
    public function tim()
    {
        return $this->belongsTo(Tim::class)    ;
    }
}
