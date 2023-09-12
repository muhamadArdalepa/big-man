<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TimAnggota extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    public function tim()
    {
        return $this->belongsTo(Tim::class);
    }

    public function pekerjaans()
    {
        return $this->hasMany(Pekerjaan::class,'tim_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
