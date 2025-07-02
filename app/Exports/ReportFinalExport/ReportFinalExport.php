<?php

namespace App\Exports\ReportFinalExport;


use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ReportFinalExport implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            'Informe consolidado' => new NvrCameraReportExport(),
            'Cámaras por condición' => new CameraConditionExport(),
        ];
    }
}
