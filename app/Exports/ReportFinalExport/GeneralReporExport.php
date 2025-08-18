<?php

namespace App\Exports\ReportFinalExport;

use App\Exports\ReportFinalExport\BaseReportExport;
use App\Models\monitoringSystem\Camera;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

class GeneralReporExport extends BaseReportExport implements WithTitle
{
    protected $locations;

    public function __construct()
    {
        // Cargar localidades únicas
        $this->locations = Camera::pluck('location')->unique()->values()->toArray();
    }

    public function collection()
    {
        $groupedData = [];


        foreach ($this->locations as $location) {
            $cameras = Camera::where('location', $location)->with('conditionAttention')->get();

            // segun el tipo de condicion
            $truck = 0;
            $onProcess = 0;
            $replace = 0;
            $others = 0;
            $inoperative = 0; //camaras inoperativas
            $operative = 0; // camaras operativas

            foreach ($cameras as $camera) {

                $lastCondition = $camera->conditionAttention()
                    ->latest('created_at')
                    ->first();

                // Solo si la condición existe y tiene status 'por atender'
                if (optional($lastCondition)->status === 'Por atender') {
                    $name = $lastCondition->name;

                    // Clasificar según el nombre
                    if ($name === 'CAMION CESTA') {
                        $truck++;
                    } elseif ($name === 'EN PROCESO DE ATENCION') {
                        $onProcess++;
                    } elseif ($name === 'PARA REMPLAZAR') {
                        $replace++;
                    } else {
                        $others++;
                    }

                    $inoperative++;
                } else
                    $operative++;
            }

            $groupedData[$location] = [
                'locations' => [
                    'name' => $location,
                    'inoperative' => $inoperative,
                    'operative' => $operative,
                    'truck' => $truck,
                    'on_process' => $onProcess,
                    'replace' => $replace,
                    'others' => $others,
                ],

            ];
        }

        return $groupedData;
    }

    public function getSheetName(): string
    {
        return 'Informe consolidado';
    }

    public function getTitle(): string
    {
        return 'Relación General';
    }

    public function headings(): array
    {
        return [
            'Localidad',
            'Cámaras',
            'Operativas',
            'Inoperativas',
            'CAMIÓN CESTA',
            'EN PROCESO DE ATENCIÓN',
            'PARA REMPLAZAR',
            'OTROS',
            "Operatividad (%)",
            "Inoperatividad (%)",
        ];
    }

    public function mapGroup($phpSheet, $location, array $groupData, int &$currentRow)
    {
        $locations = $groupData['locations'];
        $rowData = $this->map($locations);

        foreach ($rowData as $colIndex => $value) {

            if ($colIndex == 1) {
                $colLetter = Coordinate::stringFromColumnIndex($colIndex + 1);
                $phpSheet->setCellValue("{$colLetter}{$currentRow}", $value);

                // Título de ubicación
                $phpSheet->setCellValue("A{$currentRow}", $location);
                $phpSheet->getStyle("A{$currentRow}")
                    ->getFont()
                    ->setBold(true)
                    ->setSize(12);
                $phpSheet->getRowDimension($currentRow)->setRowHeight(25);
            }

            $colLetter = Coordinate::stringFromColumnIndex($colIndex + 1);
            $phpSheet->setCellValue("{$colLetter}{$currentRow}", $value === 0 ? "" : $value);
        }

        $phpSheet->getStyle("B{$currentRow}:J{$currentRow}")
            ->getAlignment()
            ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
            ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
    }

    public function map(array $locations): array
    {
        $total = $locations['inoperative'] + $locations['operative'];
        $operativeRate = $total > 0 ? round(($locations['operative'] / $total) * 100, 2) : 0;
        $inoperativeRate = $total > 0 ? round(($locations['inoperative'] / $total) * 100, 2) : 0;

        return [
            $locations['name'],                           // Localidad
            $total,                                       // Cámaras (total)
            $locations['operative'],                     // Operativas
            $locations['inoperative'],                   // Inoperativas
            $locations['truck'],                         // CAMIÓN CESTA
            $locations['on_process'],                    // EN PROCESO DE ATENCIÓN
            $locations['replace'],                       // PARA REMPLAZAR
            $locations['others'],                        // OTROS
            $operativeRate,                              // Operatividad (%)
            $inoperativeRate,                            // Inoperatividad (%)
        ];
    }

    public function mapTotal(array $totals): array
    {
        return [
            'TOTAL',
            $totals['inoperative'] + $totals['operative'],
            $totals['inoperative'],
            $totals['operative'],
            $totals['truck'],
            $totals['on_process'],
            $totals['replace'],
            $totals['others'],
        ];
    }

    protected function calculateGeneralTotals(array $data): array
    {
        $totalInoperative = 0;
        $totalOperative = 0;
        $totalTruck = 0;
        $totalOnProcess = 0;
        $totalreplace = 0;
        $totalOthers = 0;

        //porcentanjes
        $opPercent = 0;
        $inopPercent = 0;

        //contador para el porcentaje
        $loop = 0;

        foreach ($data as $location) {
            $totalInoperative += $location['locations']['inoperative'];
            $totalOperative += $location['locations']['operative'];
            $totalTruck += $location['locations']['truck'];
            $totalOnProcess += $location['locations']['on_process'];
            $totalreplace += $location['locations']['replace'];
            $totalOthers += $location['locations']['others'];

            //porcentaje operativas
            $opPercent  += $location['locations']['inoperative'] + $location['locations']['operative'] > 0
                ? round(($location['locations']['operative']
                    / ($location['locations']['inoperative'] + $location['locations']['operative'])) * 100, 2)
                : 0;

            //porcentaje inoperativas
            $inopPercent  += $location['locations']['inoperative'] + $location['locations']['operative'] > 0
                ? round(($location['locations']['inoperative']
                    / ($location['locations']['inoperative'] + $location['locations']['operative'])) * 100, 2)
                : 0;

            $loop++;
        }

        return [
            'total' => $totalInoperative + $totalOperative,
            'inoperative' => $totalInoperative,
            'operative' => $totalOperative,
            'truck' => $totalTruck,
            'on_process' => $totalOnProcess,
            'replace' => $totalreplace,
            'others' => $totalOthers,
            'operativePercent' =>  round($opPercent / $loop, 2),
            'inoperativePercent' => round($inopPercent / $loop, 2),

        ];
    }

    public function mapGeneralTotals(array $totals): array
    {
        return [
            'TOTAL GENERAL',
            $totals['total'],
            $totals['operative'],
            $totals['inoperative'],
            $totals['truck'],
            $totals['on_process'],
            $totals['replace'],
            $totals['others'],
            $totals['operativePercent'],
            $totals['inoperativePercent'],

        ];
    }

    public function title(): string
    {
        return 'Relación general';
    }
}
