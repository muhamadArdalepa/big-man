<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PekerjaanController extends Controller
{
    public function index(){
        return view('pages.pekerjaan');
    }
}
