<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisGangguan extends Model
{
    use HasFactory;

    public function laporans()
    {
        return $this->hasMany(Laporan::class);
    }
}
