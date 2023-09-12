<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tim extends Model
{
    use HasFactory;

    protected $guarded = [];
    public function getStatus()
    {
        $status = [
            1 => ['Standby', 'gradient-warning'],
            2 => ['Bekerja', 'gradient-success'],
        ];
        return $status[$this->status];
    }
    public function getPekerjaan()
    {
        $status = [
            'Pemasangan' =>'gradient-success',
            2 => 'gradient-success',
        ];
        return $status[$this->status];
    }

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
