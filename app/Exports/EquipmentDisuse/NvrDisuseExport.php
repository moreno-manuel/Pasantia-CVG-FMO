<?php

namespace App\Exports\EquipmentDisuse;


use App\Models\EquipmentDisuse\EquipmentDisuse;
use Maatwebsite\Excel\Concerns\WithTitle;

class NvrDisuseExport extends EquipmentDisuseExport implements WithTitle
{
    public function collection()
    {
        return EquipmentDisuse::where('equipment', 'Nvr')->with('nvrDisuse')->get()->sortBy('location');
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
        return ['MAC', 'Marca', 'Modelo', 'Nombre', 'N°/Puertos', 'IP', 'Volumen 1/Capacidad Max. (TB)', 'Volumen 2/Capacidad Max. (TB)', 'Localidad', 'Descripción', 'Fecha'];
    }

    public function map($device): array
    {
        $nvr = optional($device->nvrDisuse);

        // Obtener los volúmenes si existen
        $slot1 = '';
        $slot2 = '';

        if ($nvr && $nvr->slotNvrDisuse->isNotEmpty()) {
            $slot1 = optional($nvr->slotNvrDisuse->first())->capacity_max;
            $slot2 = optional($nvr->slotNvrDisuse->first())->capacity_max;
        }

        return [
            $device->id,
            $device->mark,
            $device->model,
            $nvr->name,
            $nvr->ports_number,
            $nvr->ip,
            $slot1,
            $slot2,
            $device->location,
            $device->description,
            $device->created_at->format('d/m/Y')
        ];
    }

    public function title(): string
    {
        return 'Nvr';
    }
}
