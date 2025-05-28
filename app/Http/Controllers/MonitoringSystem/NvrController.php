<?php

namespace App\Http\Controllers\MonitoringSystem;

use App\Http\Controllers\Controller;
use App\Models\monitoringSystem\Hddnvr;
use App\Models\monitoringSystem\Nvr;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class NvrController extends Controller
{
    //

    public function index(Request $request)
    {
        $nvrs = Nvr::paginate(5);
        return view('front.nvr.index', compact('nvrs'));
    }

    public function create()
    {
        return view('front.nvr.create');
    }

    public function store(Request $request)
    {
        
        //$request->except('hdd');


        try {
            $validator = Validator::make($request->all(), [ //para capturar si hay dato incorrecto
                'mac' => 'required|unique:nvrs',
                'mark' => 'required',
                'model' => 'required',
                'name' => 'required|unique:nvrs',
                'ip' => 'required|ip|unique:nvrs',
                'number_ports' => 'required',
                'number_hdd' => 'required',
                'description' => 'nullable',
                'serial' => 'nullable|unique:hdd_nvrs',
                'capacity_hdd' => 'nullable',
                'capacity_max' => 'required'

            ]);

            if ($validator->fails()) {
                return redirect()->back()->withInput()->withErrors($validator);
            }

            $nvr = Nvr::create($request->all());
            $nvr->save();
            Session::flash('success', 'Nvr Agregado Exitosamente');
            return redirect()->route('nvr.index');
        } catch (QueryException $e) {
            if ($e->getCode() === '23000') { // Código de error de integridad para la db *IP*
                throw ValidationException::withMessages([
                    'ip' => ['La dirección IP ya está en uso.'],
                ]);
            }
        }
    }
}
