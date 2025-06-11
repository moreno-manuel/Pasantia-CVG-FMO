<?php

namespace App\Http\Controllers\MonitoringSystem;

use App\Http\Controllers\Controller;
use App\Models\EquipmentDisuse\EquipmentDisuse;
use App\Models\EquipmentDisuse\NvrDisuse;
use App\Models\equipmentDisuse\SlotNvrDisuse;
use App\Models\monitoringSystem\Nvr;
use App\Models\monitoringSystem\SlotNvr;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


use function app\Helpers\filter;
use function app\Helpers\marksUpdate;
use function app\Helpers\nvrSlotValidateCreate;
use function app\Helpers\nvrSlotValidateUpdate;
use Exception;

/* controlador para el crud de nvr
y sus volumenes */

class NvrController extends Controller
{
    //

    public function index(Request $request) // Lista de registros
    {

        $hasFilters = $request->filled('location') ||     // Valida si hay algún filtro activo
            $request->filled('status');

        if (!$hasFilters) { //si no se aplica un filtro
            $nvrs = Nvr::orderBy('created_at', 'desc')->paginate(10);
            return view('front.nvr.index', compact('nvrs'));
        }

        return filter($request, 'nvrs'); //helper
    }

    public function create() //muestra el  formulario de validacion
    {
        $marks = json_decode(file_get_contents(resource_path('js/marks.json')), true)['marks']; // json con las marcas agregadas
        return view('front.nvr.create', compact('marks'));
    }

    public function store(Request $request) //validad cuando se crea un nuevo registro
    {
        try { //try para para evitar ip duplicadas

            $validated = $request->validate([  // Validación principal del NVR
                'mac' => 'required|unique:nvrs,mac',
                'mark' => 'required',
                'other_mark' => 'required_if:mark,OTRA',
                'model' => 'required',
                'name' => 'required|unique:nvrs,name',
                'ip' => 'required|ip|unique:nvrs,ip',
                'ports_number' => 'required',
                'slot_number' => 'required',
                'location' => 'required',
                'status' => 'required',
                'description' => 'nullable'
            ], ['required_if' => 'Debe agregar el nombre de la marca']);


            $slots = nvrSlotValidateCreate($request); //valida slot y devuelve un arreglo con datos de los slots 

            $request = marksUpdate($request);   // para nueva marca 

            Nvr::create($request->all());       // Guarda el NVR

            // Guarda los datos para cada volumen (slot)
            foreach ($slots as $index => $slot) {
                $status = 'Disponible';
                if ($slot['serial_disco'] != null) {
                    $status = 'Ocupado';
                }
                SlotNvr::create([
                    'nvr_id' => $validated['mac'],
                    'hdd_serial' => $slot['serial_disco'],
                    'hdd_capacity' => $slot['capacidad_disco'],
                    'capacity_max' => $slot['capacidad_max_volumen'],
                    'status' => $status
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

    public function edit(Nvr $nvr) //muestra el formulario editar 
    {
        $marks = json_decode(file_get_contents(resource_path('js/marks.json')), true)['marks']; // json con las marcas agregadas
        return view('front.nvr.edit', compact('nvr', 'marks'));
    }

    public function update(Request $request, Nvr $nvr) //valida la actualizacion 
    {
        try { //try para para evitar ip duplicadas

            $validated = $request->validate([       // Validación principal del NVR
                'mark' => 'required',
                'other_mark' => 'required_if:mark,OTRA',
                'model' => 'required',
                'ip' => 'required|ip|unique:nvrs',
                'ports_number' => 'required',
                'location' => 'required',
                'status' => 'required',
                'description' => 'nullable'
            ], ['required_if' => 'Debe agregar el nombre de la marca']);


            $slotsRequest = nvrSlotValidateUpdate($request, $nvr); //validación para los slots

            $request = marksUpdate($request);   // para nueva marca 

            $nvr->update($request->all());   // actualiza nvr


            $slots = $nvr->slotNvr;  //slots que seran actualizados 
            $i = 0;
            foreach ($slotsRequest as $slotData) { // actualiza volumen (sltos)
                $slot = $slots[$i];
                $status = 'Disponible';
                if ($slotData['serial_disco'] != null) {
                    $status = 'Ocupado';
                }
                if ($slot) {
                    $slot->update([
                        'hdd_serial' => $slotData['serial_disco'],
                        'hdd_capacity' => $slotData['capacidad_disco'],
                        'status' => $status,
                    ]);
                }
                $i++;
            }

            return redirect()->route('nvr.index')->with('success', 'NVR actualizado exitosamente.');
        } catch (QueryException $e) {
            if ($e->getCode() === '23000') { // Código de error de integridad para la db *IP*
                return redirect()->back()->withInput()->withErrors([
                    'ip' => 'La dirección IP ya está en uso.',
                ]);
            }
        }
    }

    public function show(Nvr $nvr) //muestra los datos de un registro
    {
        $cameras = $nvr->camera()->orderBy('created_at', 'desc')->paginate(5);
        return view('front.nvr.show', compact('nvr', 'cameras'));
    }

    public function destroy(Request $request, Nvr $nvr) //elimina un nvr
    {

        EquipmentDisuse::create([
            'id' => $nvr->mac,
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
            'mark' => $nvr->mark,
            'slot_number' => $nvr->slot_number


        ]);

        foreach ($nvr->slotNvr as  $slot) {
            SlotNvrDisuse::create([
                'nvr_id' => $slot->nvr_id,
                'capacity_max' => $slot->capacity_max
            ]);
        }

        // Eliminar el NVR
        $nvr->delete();

        return redirect()->route('nvr.index')->with('success', 'Enlace eliminado exitosamente.');
    }
}
