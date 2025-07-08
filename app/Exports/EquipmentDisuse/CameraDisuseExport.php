<?php

namespace App\Exports\EquipmentDisuse;


use App\Models\EquipmentDisuse\EquipmentDisuse;


class CameraDisuseExport extends EquipmentDisuseExport
{
    public function collection()
    {
        return EquipmentDisuse::where('equipment', 'Cámara')->with('cameraDisuse')->get()->sortBy('nvr');
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

        return [
            $device->id,
            $device->mark,
            $device->model,
            $camera->name,
            $camera->nvr,
            $camera->ip,
            $device->location,
            $device->description,
            $device->created_at->format('d/m/Y')
        ];
    }
}
