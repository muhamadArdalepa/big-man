<?php

namespace App\Models;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pemasangan extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $encrypts = ['id'];

    public function getRouteKey()
    {
        return Crypt::encrypt($this->id);
    }

    public   function resolveRouteBinding($value, $field = null)
    {
        return $this->where($field ?? $this->getRouteKeyName(), Crypt::decrypt($value))->firstOrFail();
    }

    // getter

    public function getStatus()
    {
        $status = [
            1 => ['Menunggu Konfirmasi', 'secondary'],
            2 => ['Sedang Diproses', 'gradient-primary'],
            3 => ['pending', 'gradient-warning'],
            4 => ['aktif', 'gradient-success'],
            5 => ['ditolak', 'gradient-danger']
        ];
        return $status[$this->status];
    }

    public function getTim()
    {
        $tim = Tim::with('user')->find(Pekerjaan::where('pemasangan_id', $this->id)->first()->tim_id);
        $tim->anggota = TimAnggota::with('user')
            ->where('tim_id',$tim->id)
            ->whereNot('user_id',$tim->user_id)
            ->get();
        return $tim;
    }


    public function getWilayah()
    {
        return Wilayah::find($this->pelanggan->wilayah_id)->nama_wilayah;
    }
    // relation
    public function pelanggan()
    {
        return $this->belongsTo(User::class, 'pelanggan_id', 'id');
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
