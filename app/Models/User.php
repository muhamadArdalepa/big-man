<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = ['id'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Always encrypt the password when it is updated.
     *
     * @param $value
    * @return string
    */
    
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }


   
    public function wilayah(){
        return $this->belongsTo(Wilayah::class);
    }
    public function tims()
    {
        return $this->hasMany(Tim::class);
    }

    public function tim_anggotas()
    {
        return $this->hasMany(TimAnggota::class);
    }
    public function laporans()
    {
        return $this->hasMany(Laporan::class);
    }
    public function pemasangan()
    {
        return $this->hasOne(Pemasangan::class);
    }
    public function absens()
    {
        return $this->hasMany(Absen::class);
    }

}
