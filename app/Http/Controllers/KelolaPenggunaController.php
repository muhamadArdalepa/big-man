<?php

namespace App\Http\Controllers;

use App\Models\Kota;
use Illuminate\Http\Request;

class KelolaPenggunaController extends Controller
{
    function teknisi() {
        $kotas = Kota::all();
        return view('pages.teknisi',compact('kotas'));
    }
}
