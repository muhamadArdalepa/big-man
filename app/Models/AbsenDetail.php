<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AbsenDetail extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    public function absen()
    {
        return $this->belongsTo(Absen::class);
    }
}
