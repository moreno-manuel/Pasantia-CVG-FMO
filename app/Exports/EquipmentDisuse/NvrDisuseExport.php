<?php

namespace App\Exports\EquipmentDisuse;


use App\Models\EquipmentDisuse\EquipmentDisuse;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\WithTitle;

class NvrDisuseExport extends EquipmentDisuseExport implements WithTitle
{
    public function collection()
    {
        return EquipmentDisuse::where('equipment', 'NVR')->with('nvrDisuse')->get()->sortBy('location');
    }

    public function getSheetName(): string
    {
        return 'NVR';
    }

    public function getTitle(): string
    {
        return 'NVR Eliminados';
    }

    public function headings(): array
    {
        return [
            'MAC',
            'Marca',
            'Modelo',
            'Nombre',
            'N°/Puertos',
            'IP',
            'Vol. 1 - Capacidad/Max.(TB)',
            'Vol. 2 - Capacidad/Max.(TB)',
            'Localidad',
            'Descripción',
            'Fecha'
        ];
    }

    public function map($device): array
    {
        $nvr = optional($device->nvrDisuse);
        $slots = [];
        $i = 0;
        foreach ($nvr->slotNvrDisuse as $slot) {
            $slots[$i] = $slot->capacity_max;
            $i++;
        }

        return [
            $device->id,
            $device->mark,
            $device->model,
            $nvr->name,
            $nvr->ports_number,
            $nvr->ip,
            $slots[0],
            $slots[1] ?? 'No Aplica',
            $device->location,
            $device->description,
            $device->created_at->format('d/m/Y')
        ];
    }

    public function title(): string
    {
        return 'NVR';
    }
}
