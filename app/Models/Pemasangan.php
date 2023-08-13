<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pemasangan extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function pelanggan()
    {
        return $this->belongsTo(User::class,'pelanggan','id');
    }
    
    public function marketer()
    {
        return $this->belongsTo(User::class,'marketer','id');
    }
    public function paket()
    {
        return $this->belongsTo(Paket::class);
    }
    public function pekerjaan()
    {
        return $this->hasOne(Pekerjaan::class);
    }
}
