<?php

namespace App\Http\Controllers\MonitoringSystem;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Models\EquipmentDisuse\EquipmentDisuse;
use App\Models\EquipmentDisuse\NvrDisuse;
use App\Models\equipmentDisuse\SlotNvrDisuse;
use App\Models\monitoringSystem\Nvr;
use App\Models\monitoringSystem\SlotNvr;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use function app\Helpers\filter;
use function app\Helpers\marksUpdate;
use function app\Helpers\nvrSlotValidateCreate;
use function app\Helpers\nvrSlotValidateUpdate;


/* controlador para el crud de nvr
y sus volumenes */

class NvrController extends Controller
{

    public function index(Request $request)
    {
        $hasFilters = $request->filled('location') ||
            $request->filled('status');

        if (!$hasFilters) { // Valida si hay algún filtro activo
            $nvrs = Nvr::select('id', 'mac', 'mark', 'model', 'name', 'ip', 'ports_number', 'location', 'status')
                ->orderBy('created_at', 'desc')
                ->paginate(10);
            return view('front.nvr.index', compact('nvrs'));
        }

        return filter($request, 'nvrs'); //helper
    }

    public function create()
    {
        $marks = json_decode(file_get_contents(resource_path('js/data.json')), true)['marks']; // json con las marcas agregadas
        return view('front.nvr.create', compact('marks'));
    }

    public function store(Request $request)
    {
        try {
            $request->validate(
                [
                    'mac' => 'required|unique:nvrs,mac|alpha_num|min:10|max:12',
                    'mark' => 'required',
                    'other_mark' => 'nullable|alpha_num|min:3|required_if:mark,Otra',
                    'model' => 'required|alpha_dash|min:3',
                    'name' => 'required|unique:nvrs,name|regex:/^[a-zA-Z0-9\/\-. ]+$/|min:5',
                    'ip' => 'required|ip|unique:nvrs,ip',
                    'ports_number' => 'required',
                    'slot_number' => 'required',
                    'location' => 'required|regex:/^[a-zA-Z0-9\/\-. ]+$/|min:5',
                    'description' => 'nullable'
                ],
                ['required_if' => 'Debe agregar el nombre de la marca'],
                ['name' => 'Nombre', 'location' => 'Localidad', 'model' => 'Modelo', 'other_mark' => 'Marca']
            );

            $slots = nvrSlotValidateCreate($request); //validacion para (slot)

            $request = marksUpdate($request, 'marks'); //si hay  una marca nueva

            $nvr = Nvr::create($request->all());

            foreach ($slots as $index => $slot) { // Guarda los datos para cada (slot)
                SlotNvr::create([
                    'nvr_id' => $nvr->id,
                    'hdd_serial' => $slot['serial_disco'],
                    'hdd_capacity' => $slot['capacidad_disco'],
                    'capacity_max' => $slot['capacidad_max_volumen'],
                    'status' => $slot['serial_disco'] == null ? 'Disponible' : 'Ocupado'
                ]);
            }

            return redirect()->route('nvr.index')->with('success', 'NVR creado exitosamente.');
        } catch (QueryException $e) {
            if ($e->getCode() === '23000') { // Código de error de integridad para la db *IP*
                return redirect()->back()->withInput()->withErrors([
                    'ip' => 'La dirección IP ya está en uso.',
                ]);
            }
        }
    }

    public function edit($name)
    {
        try {
            session(['nvrUrl' => url()->previous()]);

            $nvr = Nvr::where('name', $name)->firstOrFail();

            $marks = json_decode(file_get_contents(resource_path('js/data.json')), true)['marks']; // json con las marcas agregadas
            return view('front.nvr.edit', compact('nvr', 'marks'));
        } catch (ModelNotFoundException $e) {
            return redirect()->route('nvr.index')->with('warnings', 'Nvr no encontrado');
        }
    }

    public function update(Request $request, $mac)
    {
        try {
            $nvr = Nvr::where('mac', $mac)->firstOrFail();
            $request->validate(
                [
                    'mark' => 'required',
                    'other_mark' => 'nullable|alpha_num|min:3|required_if:mark,Otra',
                    'model' => 'required|alpha_dash|min:3',
                    'ip' => 'required|ip|unique:nvrs',
                    'ports_number' => 'required',
                    'location' => 'required|regex:/^[a-zA-Z0-9\/\- ]+$/|min:5',
                    'description' => 'nullable'
                ],
                ['required_if' => 'Debe agregar el nombre de la marca'],
                ['location' => 'Localidad', 'model' => 'Modelo', 'other_mark' => 'Marca']
            );


            $slotsRequest = nvrSlotValidateUpdate($request, $nvr); //validación para los slots

            $request = marksUpdate($request, 'marks');

            $nvr->update($request->all());

            $slots = $nvr->slotNvr;  //slots que seran actualizados 
            $i = 0;
            foreach ($slotsRequest as $slotData) { // actualiza volumen (sltos)
                $slot = $slots[$i];
                if ($slot) {
                    $slot->update([
                        'hdd_serial' => $slotData['serial_disco'],
                        'hdd_capacity' => $slotData['capacidad_disco'],
                        'status' => $slotData['serial_disco'] == null ? 'Disponible' : 'Ocupado'
                    ]);
                }
                $i++;
            }

            $redirectRoute = Route::getRoutes()->match(app('request')->create(session('nvrUrl')))->getName();
            if ($redirectRoute === 'nvr.show')
                return redirect()->route('nvr.show', ['nvr' => $nvr->name])->with('success', 'Nvr actualizado.');

            return redirect()->route('nvr.index')->with('success', 'Nvr actualizado.');
        } catch (QueryException $e) {
            if ($e->getCode() === '23000') { // Código de error de integridad para la db *IP*
                return redirect()->back()->withInput()->withErrors([
                    'ip' => 'La dirección IP ya está en uso.',
                ]);
            }
        }
    }

    public function show($name)
    {
        try {
            $nvr = Nvr::where('name', $name)->firstOrFail();
            $cameras = $nvr->camera()->orderBy('created_at', 'desc')->paginate(5);
            return view('front.nvr.show', compact('nvr', 'cameras'));
        } catch (ModelNotFoundException $e) {
            return redirect()->route('nvr.index')->with('warnings', 'Nvr no encontrado');
        }
    }

    public function destroy(Request $request, $mac) //elimina un nvr
    {
        $nvr = Nvr::where('mac', $mac)->firstOrFail();

        $equipment = EquipmentDisuse::find($mac);
        if ($equipment)
            return redirect()->route('nvr.index')->with('warning', 'Ya existe un registro eliminado con el mismo ID.');


        EquipmentDisuse::create([
            'id' => $nvr->mac,
            'mark' => $nvr->mark,
            'model' => $nvr->model,
            'location' => $nvr->location,
            'equipment' => 'Nvr',
            'description' => $request->input('deletion_description')
        ]);
        NvrDisuse::create([
            'id' => $nvr->mac,
            'name' => $nvr->name,
            'ports_number' => $nvr->ports_number,
            'ip' => $nvr->ip,
            'slot_number' => $nvr->slot_number
        ]);

        foreach ($nvr->slotNvr as  $slot) {
            SlotNvrDisuse::create([
                'nvr_id' => $nvr->mac,
                'capacity_max' => $slot->capacity_max
            ]);
        }

        // Eliminar el NVR
        $nvr->delete();

        return redirect()->route('nvr.index')->with('success', 'Nvr eliminado exitosamente.');
    }
}
