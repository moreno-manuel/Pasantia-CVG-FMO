<?php

namespace App\Exports\EquipmentDisuse;


use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ReportEquipmentDisuse implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            'Nvr' => new NvrDisuseExport(),
            'Cámaras' => new CameraDisuseExport(),
            'Switches' => new SwitchDisuseExport(),
            'Enlaces' => new LinkDisuseExport(),
            'Equipos en Stock' => new StockEqDisuse(),
        ];
    }
}
