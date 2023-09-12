<?php

namespace App\Http\Controllers;

use App\Models\Absen;
use App\Models\Wilayah;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class AbsenController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $this->addBreadcrumb('absen', url('absen'));
        Carbon::setLocale('id');
        $wilayahs = Wilayah::all();
        $date = date('Y-m-d');
        $nMonth = now()->lastOfMonth()->day;

        // check role
        switch (auth()->user()->role) {
            case 1:
                return view('pages.admin.absen', compact('wilayahs', 'date', 'nMonth'));
            case 2:
                $id = auth()->user()->id;
                $absen = Absen::select('created_at')
                    ->where('user_id', $id)
                    ->whereDate('created_at', date('Y-m-d'))
                    ->orderBy('created_at', 'desc')
                    ->first();
                $date = Carbon::parse($date)->translatedFormat("l, j F Y");
                $hour = substr(now(), 11, 2);
                $action = false;
                $whichAbsen = null;

                if ($absen == null) {
                    $times = Absen::getSettedTime();
                    foreach ($times as $i => $time) {
                        if (($hour >=  $time[0]  && $hour < $time[1])) {
                            $action = true;
                            $whichAbsen = 'Ke-' . $i + 1;
                        }
                    }
                } else {
                    $lastAbsen = (int) substr($absen->created_at, 11, 2);
                    $times = $absen->getSettedTime();
                    foreach ($times as $i => $time) {
                        if (($lastAbsen >=  $time[0]  && $lastAbsen < $time[1])) {
                            $action = true;
                            $whichAbsen = 'Ke-' . $i + 1;
                        }else{
                            $action = false;
                            $whichAbsen = null;

                        }
                    }
                }

                return view('pages.teknisi.absen', compact('date', 'action', 'whichAbsen'));
            default:
                abort(404);
        }
    }
}
