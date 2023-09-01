<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notifikasi extends Model
{
    use HasFactory;

    public function pengirim() {
        return $this->belongsTo(User::class,'pengirim_id','id');
    }
    public function  penerima() {
        return $this->belongsTo(User::class,'penerima_id','id');
    }
}
