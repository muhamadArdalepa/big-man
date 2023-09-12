<?php

namespace App\Models;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Laporan extends Model
{
    use HasFactory;
    protected $guarded = [];
    
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
            3 => ['Pending', 'gradient-warning'],
            4 => ['Selesai', 'gradient-success'],
            5 => ['Dibatalkan', 'gradient-danger']
        ];
        return $status[$this->status];
    }

    public function pelanggan()
    {
        return $this->belongsTo(User::class, 'pelanggan_id');
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function jenis_gangguan()
    {
        return $this->belongsTo(JenisGangguan::class);
    }
    
    public function pemasangan()
    {
        return $this->belongsTo(Pemasangan::class,'pelanggan_id');
    }
    
}
