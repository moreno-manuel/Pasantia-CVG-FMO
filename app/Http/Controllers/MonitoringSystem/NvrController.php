<?php

namespace App\Http\Controllers\MonitoringSystem;

use App\Http\Controllers\Controller;
use App\Models\monitoringSystem\Nvr;
use App\Models\monitoringSystem\SlotNvr;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

use function app\Helpers\filter;

/* controlador para el crud de nvr
y sus volumenes */

class NvrController extends Controller
{
    //

    public function index(Request $request) // Lista de registros
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

    public function create() //muestra el  formulario de validacion
    {
        return view('front.nvr.create');
    }

    public function store(Request $request) //validad cuando se crea un nuevo registro
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
            $slotCount = $request->input('slot_number'); //extrae el numero de volumens 
            $slotRules = []; //guarda reglas de validacion
            $customAttributes = []; //nombre legibles de los campos 
            $messages = []; //mensajes personalizados

            for ($i = 1; $i <= $slotCount; $i++) {
                // Reglas principales
                $slotRules["volumen.{$i}.serial_disco"] = "nullable|string|required_with:volumen.{$i}.capacidad_disco";
                $slotRules["volumen.{$i}.capacidad_disco"] = "nullable|numeric|lte:volumen.{$i}.capacidad_max_volumen|required_with:volumen.{$i}.serial_disco";
                $slotRules["volumen.{$i}.capacidad_max_volumen"] = 'required|numeric';

                // Nombres legibles (custom attributes)
                $customAttributes["volumen.{$i}.serial_disco"] = "Serial Disco";
                $customAttributes["volumen.{$i}.capacidad_disco"] = "Capacidad Disco";
                $customAttributes["volumen.{$i}.capacidad_max_volumen"] = "Capacidad Máxima/Volumen";

                // Mensajes personalizados
                $messages["volumen.{$i}.serial_dico.required_with"] = "El campo :attribute es obligatorio cuando se proporciona una Capacidad/Disco.";
                $messages["volumen.{$i}.capacidad_disco.required_with"] = "El campo :attribute es obligatorio cuando se proporciona un Serial.";
                $messages["volumen.{$i}.capacidad_disco.lte"] = "El campo :attribute debe ser menor o igual a Capacidad Máxima/Volumen.";
            }

            $validator = Validator::make($request->all(), $slotRules, $messages, $customAttributes);
            $validator->validate();

            //extrae todos los volumen(slots) y luego la elimina del request
            $slots = $request->input('volumen', []);
            $request->offsetUnset('volumen');

            // Guarda el NVR
            Nvr::create($request->all());

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
                throw ValidationException::withMessages([
                    'ip' => ['La dirección IP ya está en uso.'],
                ]);
            }
        }
    }

    public function destroy(Nvr $nvr) //elimina un nvr
    {

        $nvr->delete();
        return redirect()->route('nvr.index')->with('success', 'Nvr eliminado exitosamnete');
    }

    public function edit(Nvr $nvr) //muestra el formulario editar 
    {
        return view('front.nvr.edit', compact('nvr'));
    }

    public function update(Request $request, Nvr $nvr) //valida la actualizacion 
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

            $existingSlots = []; // Aquí almacena los valores existentes de capacity_max por slot
            $i = 1;
            foreach ($nvr->slotNvr as $slot) {
                // Busca el slot específico por MAC 
                $existingSlots[$i] = $slot->capacity_max;
                $i++;
            }

            //validacion de volumenes
            $slotRules = [];
            $customAttributes = [];
            $messages = [];
            for ($i = 1; $i <= $nvr->slot_number; $i++) {
                $existingMaxCapacity = $existingSlots[$i];

                // Reglas principales (sin capacidad_max_volumen)
                $slotRules["volumen.{$i}.serial_disco"] = "nullable|string|required_with:volumen.{$i}.capacidad_disco";
                $slotRules["volumen.{$i}.capacidad_disco"] = "nullable|numeric|lte:{$existingMaxCapacity}|required_with:volumen.{$i}.serial_disco";

                // Nombres legibles
                $customAttributes["volumen.{$i}.serial_disco"] = "Serial Disco";
                $customAttributes["volumen.{$i}.capacidad_disco"] = "Capacidad Disco";

                // Mensajes personalizados
                $messages["volumen.{$i}.serial_dico.required_with"] = "El campo :attribute es obligatorio cuando se proporciona una Capacidad/Disco.";
                $messages["volumen.{$i}.capacidad_disco.required_with"] = "El campo :attribute es obligatorio cuando se proporciona un Serial.";
                $messages["volumen.{$i}.capacidad_disco.lte"] = "El campo :attribute debe ser menor o igual a {$existingMaxCapacity}.";
            }
            $validator = Validator::make($request->all(), $slotRules, $messages, $customAttributes);
            $validator->validate();

            //extrae todos los volumen(slots) y luego la elimina del request
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
                throw ValidationException::withMessages([
                    'ip' => ['La dirección IP ya está en uso.'],
                ]);
            }
        }
    }

    public function show(Nvr $nvr) //muestra los datos
    {
        $cameras = $nvr->camera()->paginate(5);
        return view('front.nvr.show', compact('nvr', 'cameras'));
    }
}
