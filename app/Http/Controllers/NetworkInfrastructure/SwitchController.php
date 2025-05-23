<?php

namespace App\Http\Controllers\NetworkInfrastructure;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SwitchController extends Controller
{
    public function index(){
        return view('front.switch.index');
    }
}
