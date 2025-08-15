<?php

namespace App\Exports\EquipmentDisuse;


use App\Models\EquipmentDisuse\EquipmentDisuse;
use Maatwebsite\Excel\Concerns\WithTitle;

class StockEqDisuse extends EquipmentDisuseExport implements WithTitle
{
    public function collection()
    {
        return EquipmentDisuse::where('equipment', "!=", 'Cámara')
            ->where('equipment', '!=', 'NVR')
            ->where('equipment', '!=', 'Enlace')
            ->where('equipment', '!=', 'Switch')
            ->with(['stockEqDisuse'])->get()->sortBy('nvr');
    }

    public function getSheetName(): string
    {
        return 'Equipos Stock';
    }

    public function getTitle(): string
    {
        return 'Equipos Stock';
    }

    public function headings(): array
    {
        return ['Equipo', 'MAC', 'Marca', 'Modelo', 'Nota de Entrega', 'Descripción', 'Fecha'];
    }

    public function map($device): array
    {
        $stock = optional($device->stockEqDisuse);

        return [
            $device->equipment,
            $device->id,
            $device->mark,
            $device->model,
            $stock->delivery_note,
            $device->description,
            $device->created_at->format('d/m/Y')
        ];
    }

    public function title(): string
    {
        return 'Equipos Stock';
    }
}
