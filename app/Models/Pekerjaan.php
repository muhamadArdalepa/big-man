<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class Pekerjaan extends Model
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

    public static function queryPekerjaanWithWilayah($wilayah_id)
    {
        $pekerjaan = Pekerjaan::select('pekerjaans.*', 'users.wilayah_id', 'nama_wilayah')
            ->join('tims', 'tim_id', '=', 'tims.id')
            ->leftJoin('users', 'user_id', '=', 'users.id')
            ->leftJoin('wilayahs', 'users.wilayah_id', '=', 'wilayahs.id');
        if ($wilayah_id) {
            return $pekerjaan->where('wilayahs.id', $wilayah_id);
        }
        return $pekerjaan = Pekerjaan::select('pekerjaans.*', 'users.wilayah_id', 'nama_wilayah')
            ->join('tims', 'tim_id', '=', 'tims.id')
            ->leftJoin('users', 'user_id', '=', 'users.id')
            ->leftJoin('wilayahs', 'users.wilayah_id', '=', 'wilayahs.id');
    }

    public function getFullTim()
    {
        $tim = TimAnggota::with('user:id,nama,foto_profil,speciality')
            ->where('tim_id', $this->tim_id)
            ->get()
            ->pluck('user');
        return $tim;
    }

    public function getKetuaTim()
    {
        return Tim::with('user')->find($this->tim_id);
    }

    public function getAnggotaTim()
    {
        return TimAnggota::with('user')->where('tim_id', $this->tim_id)->whereNot('user_id', $this->getKetuaTim()->user_id)->get();
    }

    public function getDetailPekerjaan()
    {
        switch ($this->jenis_pekerjaan_id) {
            case 1:
                $pemasangan = Pemasangan::with('user')->find($this->pemasangan_id);
                return $pemasangan->user->nama . ' - ' . $pemasangan->alamat;

            default:
                # code...
                break;
        }
    }

    public function laporan()
    {
        return $this->belongsTo(Laporan::class);
    }
    public function pemasangan()
    {
        return $this->belongsTo(Pemasangan::class);
    }
    public function tim()
    {
        return $this->belongsTo(Tim::class);
    }
    public function tim_anggotas()
    {
        return $this->belongsTo(TimAnggota::class, 'tim_id', 'tim_id');
    }
    public function wilayah()
    {
        return $this->belongsTo(Wilayah::class);
    }

    public function jenis_pekerjaan()
    {
        return $this->belongsTo(JenisPekerjaan::class);
    }
}
