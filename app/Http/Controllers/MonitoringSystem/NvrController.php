<?php

namespace App\Http\Controllers\MonitoringSystem;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NvrController extends Controller
{
    //

    public function index()
    {
        return view('front.nvr.index');
    }

    public function create()
    {
        return view('front.nvr.create');
    }
}
