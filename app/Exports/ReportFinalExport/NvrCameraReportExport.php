<?php

namespace App\Exports\ReportFinalExport;

use App\Exports\ReportFinalExport\BaseReportExport;
use App\Models\monitoringSystem\Nvr;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class NvrCameraReportExport extends BaseReportExport
{
    protected $locations;

    public function __construct()
    {
        // Cargar localidades únicas desde los NVRs que tienen cámaras
        $this->locations = Nvr::whereHas('camera')->pluck('location')->unique()->values()->toArray();
    }

    public function collection()
    {
        $groupedData = [];

        foreach ($this->locations as $location) {
            $nvrList = Nvr::where('location', $location)->with('camera')->get();

            $totalInoperative = 0;
            $totalOperative = 0;
            $totalTruck = 0;
            $totalOnProcess = 0;
            $totalInventory = 0;
            $totalOthers = 0;

            $items = [];

            foreach ($nvrList as $nvr) {
                $cameras = $nvr->camera;

                $inoperative = $cameras->filter(fn($c) => $c->status === 'offline')->count();
                $operative = $cameras->filter(fn($c) => $c->status === 'online')->count();

                $truck = $cameras->filter(fn($c) => optional($c->conditionAttention)->name === 'CAMION CESTA')->count();
                $onProcess = $cameras->filter(fn($c) => optional($c->conditionAttention)->name === 'EN PROCESO DE ATENCION')->count();
                $inventory = $cameras->filter(fn($c) => optional($c->conditionAttention)->name === 'POR INVENTARIO')->count();
                $others = $cameras->filter(fn($c) => !optional($c->conditionAttention)->name || !in_array(optional($c->conditionAttention)->name, ['CAMION CESTA', 'EN PROCESO DE ATENCION', 'POR INVENTARIO']))->count();

                $items[] = [
                    'nvr' => $nvr,
                    'total' => $cameras->count(),
                    'inoperative' => $inoperative,
                    'operative' => $operative,
                    'truck' => $truck,
                    'on_process' => $onProcess,
                    'inventory' => $inventory,
                    'others' => $others,
                ];

                // Acumular totales por localidad
                $totalInoperative += $inoperative;
                $totalOperative += $operative;
                $totalTruck += $truck;
                $totalOnProcess += $onProcess;
                $totalInventory += $inventory;
                $totalOthers += $others;
            }

            $groupedData[$location] = [
                'name' => 'nvr',
                'nvrList' => $items,
                'totals' => [
                    'inoperative' => $totalInoperative,
                    'operative' => $totalOperative,
                    'truck' => $totalTruck,
                    'on_process' => $totalOnProcess,
                    'inventory' => $totalInventory,
                    'others' => $totalOthers,
                ],
            ];
        }

        return $groupedData;
    }

    public function getSheetName(): string
    {
        return 'Informe Consolidado';
    }

    public function getTitle(): string
    {
        return 'Cámaras por localidad';
    }

    public function headings(): array
    {
        return [
            'Ubicación',
            'Cantidad',
            'Inoperativas',
            'Operativas',
            'CAMIÓN CESTA',
            'EN PROCESO DE ATENCIÓN',
            'POR INVENTARIO',
            'OTROS'
        ];
    }

    public function mapGroup($phpSheet, $location, array $groupData, int &$currentRow)
    {
        // Título de ubicación
        $phpSheet->setCellValue("A{$currentRow}", strtoupper($location));
        $phpSheet->getStyle("A{$currentRow}")
            ->getFont()
            ->setBold(true)
            ->setSize(12);
        $phpSheet->getRowDimension($currentRow)->setRowHeight(25);
        $currentRow++;

        // Datos por NVR
        foreach ($groupData['nvrList'] as $item) {
            $rowData = $this->map($item);
            foreach ($rowData as $colIndex => $value) {
                $colLetter = Coordinate::stringFromColumnIndex($colIndex + 1);
                $phpSheet->setCellValue("{$colLetter}{$currentRow}", $value);
            }

            $phpSheet->getStyle("A{$currentRow}:H{$currentRow}")
                ->getAlignment()
                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
                ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

            $currentRow++;
        }

        // Fila amarilla con totales por ubicación
        $totalRow = $currentRow;
        $rowData = $this->mapTotal($groupData['totals']);
        foreach ($rowData as $colIndex => $value) {
            $colLetter = Coordinate::stringFromColumnIndex($colIndex + 1);
            $phpSheet->setCellValue("{$colLetter}{$totalRow}", $value);
        }

        $phpSheet->getStyle("A{$totalRow}:H{$totalRow}")
            ->getFill()
            ->setFillname(Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FFFFCC'); // Amarillo claro

        $currentRow++;
    }

    public function map(array $item): array
    {
        return [
            $item['nvr']->name,
            $item['total'],
            $item['inoperative'],
            $item['operative'],
            $item['truck'],
            $item['on_process'],
            $item['inventory'],
            $item['others'],
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
            $totals['inventory'],
            $totals['others'],
        ];
    }

    protected function calculateGeneralTotals(array $data): array
    {
        $totalInoperative = 0;
        $totalOperative = 0;
        $totalTruck = 0;
        $totalOnProcess = 0;
        $totalInventory = 0;
        $totalOthers = 0;

        foreach ($data as $locationData) {
            $totalInoperative += $locationData['totals']['inoperative'];
            $totalOperative += $locationData['totals']['operative'];
            $totalTruck += $locationData['totals']['truck'];
            $totalOnProcess += $locationData['totals']['on_process'];
            $totalInventory += $locationData['totals']['inventory'];
            $totalOthers += $locationData['totals']['others'];
        }

        return [
            'total' => $totalInoperative + $totalOperative,
            'inoperative' => $totalInoperative,
            'operative' => $totalOperative,
            'truck' => $totalTruck,
            'on_process' => $totalOnProcess,
            'inventory' => $totalInventory,
            'others' => $totalOthers,
        ];
    }

    public function mapGeneralTotals(array $totals): array
    {
        return [
            'TOTAL GENERAL',
            $totals['total'],
            $totals['inoperative'],
            $totals['operative'],
            $totals['truck'],
            $totals['on_process'],
            $totals['inventory'],
            $totals['others'],
        ];
    }
}
