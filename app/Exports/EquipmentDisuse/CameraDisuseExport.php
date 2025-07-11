<?php

namespace App\Exports\EquipmentDisuse;


use App\Models\EquipmentDisuse\EquipmentDisuse;
use Maatwebsite\Excel\Concerns\WithTitle;

class CameraDisuseExport extends EquipmentDisuseExport implements WithTitle
{
    public function collection()
    {
        return EquipmentDisuse::where('equipment', 'Cámara')->with(['cameraDisuse', 'cameraInventoriesDisuse'])->get()->sortBy('nvr');
    }

    public function getSheetName(): string
    {
        return 'Cámaras';
    }

    public function getTitle(): string
    {
        return 'Cámaras Eliminadas';
    }

    public function headings(): array
    {
        return ['Mac', 'Marca', 'Modelo', 'Nombre', 'Nvr/Conexión', 'IP', 'Localidad', 'Descripción', 'Fecha'];
    }

    public function map($device): array
    {
        $camera = optional($device->cameraDisuse);
        $cameraInventories = optional($device->cameraInventoriesDisuse);

        return [
            $device->id,
            $device->mark,
            $device->model,
            $camera->name ?? 'Nota de Entrega - ' . $cameraInventories->delivery_note,
            $camera->nvr ?? 'No aplica',
            $camera->ip ?? 'No aplica',
            $device->location != 'No Aplica' ?: 'Destino - ' . $cameraInventories->destination,
            $device->description,
            $device->created_at->format('d/m/Y')
        ];
    }

    public function title(): string
    {
        return 'Cámaras';
    }
}
