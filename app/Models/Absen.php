<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Absen extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $minAbsen = 3;
    public static $late = 20;
    // public static $late = 9;
    public static function getSettedTime()
    {
        return [
            [8, 10],
            [11, 13],
            [13, 15],
            [16, 18]
        ];
        // return [
        //     [19, 21],
        //     [21, 22],
        //     [22, 23],
        //     [23, 24]
        // ];
    }
    public function getTimes()
    {
        $aktivitass = Aktivitas::where('absen_id', $this->id)->get();
        $times = $this->getSettedTime();

        foreach ($times as $i => $time) {
            $aktivitas[$i] = null;
        }
        foreach ($aktivitass as $a) {
            // ubah timestamp ke int jam
            $hour = (int) Carbon::parse($a->created_at)->format('H');

            // looping setted time, cek range waktu $hour
            foreach ($times as $i => $time) {
                if ($hour >= $time[0] && $hour < $time[1]) {
                    $aktivitas[$i] = Carbon::parse($a->created_at)->format('H:i');
                }
            }
            $aktivitas;
        };
        return $aktivitas;
    }
    public function getStatus()
    {
        $end = $this->getSettedTime();

        
        // jika hari ini tapi belum sampai akhir jam
        $status = ['-', 'bg-gradient-secondary'];
        switch ($this->status) {
            case 1:
                $status = ['Hadir', 'bg-gradient-success'];
                break;
            case 2:
                $status = ['Terlambat', 'bg-gradient-wawrning'];
                break;
            case 3:
                $status = ['Tidak hadir', 'bg-gradient-danger'];
                break;
        }

        if (Carbon::parse($this->created_at)->format('Y-m-d') == date('Y-m-d')) {
            if (strtotime($this->created_at) < strtotime(Carbon::createFromFormat('H', end($end)[1]))) {
                $status = ['Proses', 'bg-gradient-secondary'];
            }
        }

        return $status;

    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function aktivitass()
    {
        return $this->hasMany(Aktivitas::class);
    }
}
