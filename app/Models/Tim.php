<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tim extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function tim_anggotas()
    {
        return $this->hasMany(TimAnggota::class);
    }
    public function pekerjaan()
    {
        return $this->hasMany(Pekerjaan::class);
    }
}
