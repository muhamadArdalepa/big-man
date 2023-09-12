<?php

namespace App\Http\Controllers;

use App\Models\Paket;
use App\Models\Wilayah;
use App\Models\Pemasangan;
use Illuminate\Http\Request;

class PemasanganController extends Controller
{
    public function index()
    {
        $this->addBreadcrumb('pemasangan', route('pemasangan.index'));
        $wilayahs = Wilayah::all();
        $date = date('Y-m-d');
        $pakets = Paket::all();

        return view('pages.admin.pemasangan', compact('wilayahs', 'date', 'pakets'));
    }
}
