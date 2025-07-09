<?php

namespace App\Exports\EquipmentDisuse;


use App\Models\EquipmentDisuse\EquipmentDisuse;
use Maatwebsite\Excel\Concerns\WithTitle;

class SwitchDisuseExport extends EquipmentDisuseExport implements WithTitle
{
    public function collection()
    {
        return EquipmentDisuse::where('equipment', 'Switch')->with('switchDisuse')->get()->sortBy('mark');
    }

    public function getSheetName(): string
    {
        return 'Switches';
    }

    public function getTitle(): string
    {
        return 'Switches Eliminadas';
    }

    public function headings(): array
    {
        return ['Serial', 'Marca', 'Modelo', 'N°/Puertos', 'Localidad', 'Descripción', 'Fecha'];
    }

    public function map($device): array
    {
        $switch = optional($device->switchDisuse);

        return [
            $device->id,
            $device->mark,
            $device->model,
            $switch->number_ports,
            $device->location,
            $device->description,
            $device->created_at->format('d/m/Y')
        ];
    }

    public function title(): string
    {
        return 'Switches';
    }
}
