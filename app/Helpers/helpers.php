<?php

namespace app\Helpers;

use App\Models\EquipmentDisuse\EquipmentDisuse;
use App\Models\monitoringSystem\Camera;
use App\Models\monitoringSystem\ConditionAttention;
use App\Models\monitoringSystem\Nvr;
use App\Models\networkInfrastructure\CameraInventory;
use App\Models\networkInfrastructure\Link;
use App\Models\networkInfrastructure\Switche;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/* busqueda filtrada
--el string recibe el nombre 
del bloque que contiene 
la tabla y filtros
para la busqueda*/

function filter(Request $request, string $table)
{
    switch ($table) {
        case 'switches': {
                // Obtén el valor del filtro
                $serial = $request->input('serial');
                $location = $request->input('location');
                $status = $request->input('status');

                // Construye la consulta base
                $query = Switche::query();

                // Aplica filtros condicionalmente
                if ($serial) {
                    $query->where('serial', 'like',  $serial . '%');
                }

                if ($location) {
                    $query->where('location', 'like',  $location . '%');
                }

                if ($status) {
                    $query->where('status', 'like',  $status . '%');
                }

                // Ejecuta la consulta y aplica paginación
                $switches = $query->orderBy('created_at', 'desc')->paginate(10);

                // Mantiene los valores de los filtros en la vista
                return view('front.switch.index', compact('switches'))
                    ->with('filters', $request->all());
                break;
            }


        case 'links': {
                // Obtén los valores de los filtros
                $location = $request->input('location');
                $status = $request->input('status');


                // Construye la consulta base
                $query = Link::query();

                // Aplica filtros condicionalmente
                if ($location) {
                    $query->where('location', 'like',  $location . '%');
                }

                // Aplica filtros condicionalmente
                if ($status) {
                    $query->where('status', 'like',  $status . '%');
                }


                // Ejecuta la consulta y aplica paginación
                $links = $query->orderBy('created_at', 'desc')->paginate(10);

                // Mantiene los valores de los filtros en la vista
                return view('front.link.index', compact('links'))
                    ->with('filters', $request->all());
                break;
            }

        case 'nvrs': {
                // Obtén los valores de los filtros
                $location = $request->input('location');
                $status = $request->input('status');


                // Construye la consulta base
                $query = Nvr::query();

                // Aplica filtros condicionalmente
                if ($location) {
                    $query->where('location', 'like',  $location . '%');
                }

                // Aplica filtros condicionalmente
                if ($status) {
                    $query->where('status', 'like',  $status . '%');
                }


                // Ejecuta la consulta y aplica paginación
                $nvrs = $query->orderBy('created_at', 'desc')->paginate(10);

                // Mantiene los valores de los filtros en la vista
                return view('front.nvr.index', compact('nvrs'))
                    ->with('filters', $request->all());
                break;
            }
        case 'cameras': {
                // Obtén los valores de los filtros
                $location = $request->input('location');
                $status = $request->input('status');


                // Construye la consulta base
                $query = Camera::query();

                // Aplica filtros condicionalmente
                if ($location) {
                    $query->where('location', 'like',  $location . '%');
                }

                // Aplica filtros condicionalmente
                if ($status) {
                    $query->where('status', 'like',  $status . '%');
                }


                // Ejecuta la consulta y aplica paginación
                $cameras = $query->orderBy('created_at', 'desc')->paginate(10);

                // Mantiene los valores de los filtros en la vista
                return view('front.camera.index', compact('cameras'))
                    ->with('filters', $request->all());
                break;
            }
        case 'conditions': {
                // Obtén los valores de los filtros
                $name = $request->input('name');

                // Construye la consulta base
                $query = ConditionAttention::query();

                // Aplica filtros y las que no se ham atendido
                $query->where('name', $name)
                    ->where('status', 'Por atender');

                // Ejecuta la consulta y aplica paginación
                $conditions = $query->orderBy('created_at', 'desc')->paginate(10);

                $names = json_decode(file_get_contents(resource_path('js/data.json')), true)['conditions']; // json con los tipos de condicion

                // Mantiene los valores de los filtros en la vista
                return view('front.attention.index', compact('conditions', 'names'))
                    ->with('filters', $request->all());
                break;
            }

        case 'equipments': {

                $equipment = $request->input('equipment');

                // Construye la consulta base
                $query = EquipmentDisuse::query();

                // Aplica filtros 
                $query->where('equipment',  $equipment);


                // Ejecuta la consulta y aplica paginación
                $equipments = $query->orderBy('created_at', 'desc')->paginate(10);

                // Mantiene los valores de los filtros en la vista
                return view('front.eliminated.index', compact('equipments'))
                    ->with('filters', $request->all());
                break;
            }
        case 'camera_inventories': {

                // Obtén los valores de los filtros
                $delivery_note = $request->input('delivery_note');
                $mark = $request->input('mark');


                // Construye la consulta base
                $query = CameraInventory::query();

                // Aplica filtros condicionalmente
                if ($delivery_note) {
                    $query->where('delivery_note', 'like',  $delivery_note . '%');
                }

                // Aplica filtros condicionalmente
                if ($mark) {
                    $query->where('mark', 'like',  $mark . '%');
                }


                // Ejecuta la consulta y aplica paginación
                $cameras = $query->orderBy('created_at', 'desc')->paginate(10);
                $marks = json_decode(file_get_contents(resource_path('js/marks.json')), true)['marks']; // json con las marcas agregadas

                // Mantiene los valores de los filtros en la vista
                return view('front.camera.camera_inventories.index', compact('cameras', 'marks'))
                    ->with('filters', $request->all());
                break;
            }
        default:
            return 'error en controlador';
    }
}


/* para actualizar el json marks 
para el manejo de las marcas */
function marksUpdate(Request $request, $data)
{
    if ($request->filled('other_mark')) {
        $newMark = strtoupper($request->input('other_mark'));

        $filePath = resource_path('js/data.json'); // Ruta del archivo JSON

        if (File::exists($filePath)) {
            $jsonData = json_decode(File::get($filePath), true); // Cargar TODO el contenido JSON actual

            if (isset($jsonData[$data])) {   // Verificar si la clave existe en el JSON
                $currentArray = $jsonData[$data];  // Obtener el arreglo específico

                if (!in_array($newMark, $currentArray)) { // Verificar si el nuevo valor ya existe
                    array_unshift($currentArray, $newMark);  // Agregar el nuevo valor al principio

                    $jsonData[$data] = $currentArray;   // Actualizar SOLO el arreglo modificado

                    File::put($filePath, json_encode($jsonData, JSON_PRETTY_PRINT)); // Guardar TODO el JSON con los cambios
                }
            }
            $request['mark'] = $newMark;
        }

        $request->offsetUnset('other_mark'); // se elimina el campo other_mark y se agrega el valor en el campo mark
        return $request;
    }
}

/* para liberar el metodo 
store de NvrController con las validaciones 
de los slots*/
function nvrSlotValidateCreate(Request $request)
{
    $slotRules = []; //guarda reglas de validacion
    $customAttributes = []; //nombre legibles de los campos 
    $messages = []; //mensajes personalizados

    for ($i = 0; $i <= $request->slot_number - 1; $i++) {
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

    return $slots;
}


/* para liberar el metodo 
store de NvrController con las validaciones */
function nvrSlotValidateUpdate(Request $request, Nvr $nvr)
{
    $existingSlots = []; // Aquí almacena los valores existentes de capacity_max por slot
    $i = 0;
    foreach ($nvr->slotNvr as $slot) {
        // Busca el slot específico por MAC 
        $existingSlots[$i] = $slot->capacity_max;
        $i++;
    }

    //validacion de volumenes
    $slotRules = [];
    $customAttributes = [];
    $messages = [];
    for ($i = 0; $i <= $nvr->slot_number - 1; $i++) {
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
        $messages["volumen.{$i}.capacidad_disco.lte"] = "El campo :attribute debe ser menor o igual a Capacidad Máxima/volumen.";
    }

    $validator = Validator::make($request->all(), $slotRules, $messages, $customAttributes);
    $validator->validate();

    //extrae todos los volumen(slots) y luego la elimina del request
    $slotsRequest = $request->input('volumen', []);
    $request->offsetUnset('volumen');

    return $slotsRequest;
}



/* para liberar el metodo store
de ConditionACotroller  */
function conditionValidate(Request $request, $condition)
{
    //para validar fecha futura
    $date_max = Carbon::parse($request->input('date_ini'))->isFuture();

    //si ya existe una atencion generada
    if ((($condition->name == $request->input('name')) > 0) && (($condition->date_ini == $request->input('date_ini')) > 0)) {
        return ['camera_id' => 'Ya existe una condición de atención con el mismo tipo y fecha para la cámara seleccionada'];

        //si no se ha culminado la ultima atención
    } else if (!$condition->date_end) {
        return ['camera_id' => 'Existe una condición de atención sin finalizar para la cámara seleccionada'];

        //si la fecha final de la ultima atencion supera a la fecha inicial de la nueva atencion
    } else if ($condition->date_end > $request->input('date_ini')) {
        return ['camera_id' => 'La nueva condición de atención para la cámara seleccionada debe tener una fecha mayor o igual a la anterior (' . $condition->date_end . ")"];

        //si se ingresa fechas futuras
    } else if ($date_max) {
        return ['date_ini' => 'La fecha ingresada supera la fecha actual (' . Carbon::now()->format('d/m/Y') . ')'];
    }

    return 'success';
}
