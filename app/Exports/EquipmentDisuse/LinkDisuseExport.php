<?php

namespace App\Exports\EquipmentDisuse;


use App\Models\EquipmentDisuse\EquipmentDisuse;
use Maatwebsite\Excel\Concerns\WithTitle;

class LinkDisuseExport extends EquipmentDisuseExport implements WithTitle
{
    public function collection()
    {
        return EquipmentDisuse::where('equipment', 'Enlace')->with('linkDisuse')->get()->sortBy('location');
    }

    public function getSheetName(): string
    {
        return 'Enlaces';
    }

    public function getTitle(): string
    {
        return 'Enlaces Eliminadas';
    }

    public function headings(): array
    {
        return ['MAC', 'Marca', 'Modelo', 'Nombre', 'SSID', 'IP', 'Localidad', 'Descripción','Fecha'];
    }

    public function map($device): array
    {
        $link = optional($device->linkDisuse);

        return [
            $device->id,
            $device->mark,
            $device->model,
            $link->name,
            $link->ssid,
            $link->ip,
            $device->location,
            $device->description,
            $device->created_at->format('d/m/Y')
        ];
    }

    public function title(): string
    {
        return 'Enlaces'; 
    }
}
