<?php

namespace App\Http\Controllers\MonitoringSystem;

use App\Http\Controllers\Controller;
use App\Models\monitoringSystem\Nvr;
use App\Models\monitoringSystem\SlotNvr;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

use function app\Helpers\filter;

/* controlador para el crud de nvr
y sus volumenes */

class NvrController extends Controller
{
    //

    public function index(Request $request)
    {
        // Valida si hay algún filtro activo
        $hasFilters = $request->filled('location') ||
            $request->filled('status');

        if (!$hasFilters) { //si no se aplica un filtro
            $nvrs = Nvr::paginate(10);
            return view('front.nvr.index', compact('nvrs'));
        }

        return filter($request, 'nvrs'); //helper
    }

    public function create()
    {
        return view('front.nvr.create');
    }

    public function store(Request $request)
    {
        try { //try para para evitar ip duplicadas

            // Validación principal del NVR
            $validated = $request->validate([
                'mac' => 'required|unique:nvrs,mac',
                'mark' => 'required',
                'model' => 'required',
                'name' => 'required|unique:nvrs,name',
                'ip' => 'required|ip|unique:nvrs,ip',
                'ports_number' => 'required',
                'slot_number' => 'required',
                'location' => 'required',
                'status' => 'required',
                'description' => 'nullable'
            ]);

            // Validación de volumenes(slots)
            $slots = [];
            $slotCount = $request->input('slot_number'); //extrae el numero de volumens 
            $slotRules = []; //reglas de validacion
            for ($i = 1; $i <= $slotCount; $i++) { //recorrido para cada slot
                $slotRules["volumen.{$i}.serial_disco"] = 'nullable';
                $slotRules["volumen.{$i}.capacidad_disco"] = 'nullable|lt:capacidad_max_volumen';
                $slotRules["volumen.{$i}.capacidad_max_volumen"] = 'required';
                $slotRules["volumen.{$i}.status"] = 'required';
            }
            $slotValidator = Validator::make($request->all(), $slotRules); //validacion
            if ($slotValidator->fails()) { //si captura un error
                return back()->withErrors($slotValidator)->withInput();
            }

            //extrae todos los volumen(slots) y luego la elimina del request
            $slots = $request->input('volumen', []);
            $request->offsetUnset('volumen');

            // Guarda el NVR
            Nvr::create($request->all());

            // Guarda los datos para cada volumen (slot)
            foreach ($slots as $index => $slot) {
                SlotNvr::create([
                    'nvr_id' => $validated['mac'],
                    'hdd_serial' => $slot['serial_disco'] ?? null,
                    'hdd_capacity' => $slot['capacidad_disco'] ?? null,
                    'capacity_max' => $slot['capacidad_max_volumen'],
                    'status' => $slot['status'],
                ]);
            }

            return redirect()->route('nvr.index')->with('success', 'NVR creado exitosamente.');
        } catch (QueryException $e) {
            if ($e->getCode() === '23000') { // Código de error de integridad para la db *IP*
                throw ValidationException::withMessages([
                    'ip' => ['La dirección IP ya está en uso.'],
                ]);
            }
        }
    }

    public function destroy(Nvr $nvr)
    {

        $nvr->delete();
        return redirect()->route('nvr.index')->with('success', 'Nvr eliminado exitosamnete');
    }

    public function edit(Nvr $nvr)
    {
        return view('front.nvr.edit', compact('nvr'));
    }

    public function update(Request $request, Nvr $nvr)
    {
        try { //try para para evitar ip duplicadas

            // Validación principal del NVR
            $validated = $request->validate([
                'mark' => 'required',
                'model' => 'required',
                'ip' => 'required|ip|unique:nvrs',
                'ports_number' => 'required',
                'location' => 'required',
                'status' => 'required',
                'description' => 'nullable'
            ]);

            //validación volumen(slots)
            $slotCount = $request->input('slot_number');
            $slotRules = []; //reglas de validacion
            for ($i = 1; $i <= $slotCount; $i++) {
                $slotRules["volumen.{$i}.serial_disco"] = 'nullable';
                $slotRules["volumen.{$i}.capacidad_disco"] = 'nullable';
                $slotRules["volumen.{$i}.status"] = 'required';
            }
            $slotValidator = Validator::make($request->all(), $slotRules); //validacion
            if ($slotValidator->fails()) { //si captura un error
                return back()->withErrors($slotValidator)->withInput();
            }

            //extrae todos las volumen(slots) y luego la elimina del request
            $slotsRequest = $request->input('volumen', []);
            $request->offsetUnset('volumen');

            // actualiza nvr
            $nvr->update($request->all());

            //slots que seran actualizados 
            $slots = $nvr->slotNvr;
            $i = 0;
            // actualiza volumen (sltos)
            foreach ($slotsRequest as $slotData) {
                $slot = $slots[$i];
                if ($slot) {
                    $slot->update([
                        'hdd_serial' => $slotData['serial_disco'],
                        'hdd_capacity' => $slotData['capacidad_disco'],
                        'status' => $slotData['status'],
                    ]);
                }
                $i++;
            }

            return redirect()->route('nvr.index')->with('success', 'NVR actualizado exitosamente.');
        } catch (QueryException $e) {
            if ($e->getCode() === '23000') { // Código de error de integridad para la db *IP*
                throw ValidationException::withMessages([
                    'ip' => ['La dirección IP ya está en uso.'],
                ]);
            }
        }
    }

    public function show(Nvr $nvr)
    {
        return view('front.nvr.show', compact('nvr'));
    }
}
