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

                // Filtrar cámaras offline y procesar su última condición en un solo paso
                $inoperativeStats = $cameras
                    ->filter(fn($c) => $c->status === 'offline')
                    ->reduce(
                        function ($carry, $camera) {
                            // Obtener la última condición de atención
                            $lastCondition = $camera->conditionAttention()
                                ->latest('created_at')
                                ->first();

                            $name = optional($lastCondition)->name;

                            // Clasificar según el nombre
                            if ($name === 'CAMION CESTA') {
                                $carry['truck']++;
                            } elseif ($name === 'EN PROCESO DE ATENCION') {
                                $carry['onProcess']++;
                            } elseif ($name === 'POR INVENTARIO') {
                                $carry['inventory']++;
                            } elseif ($name === 'OTRO') {
                                $carry['others']++;
                            }

                            $carry['inoperative']++;

                            return $carry;
                        },
                        ['truck' => 0, 'onProcess' => 0, 'inventory' => 0, 'others' => 0, 'inoperative' => 0]
                    );

                // Asignar resultados a variables
                $truck = $inoperativeStats['truck'];
                $onProcess = $inoperativeStats['onProcess'];
                $inventory = $inoperativeStats['inventory'];
                $others = $inoperativeStats['others'];
                //camaras inoperativas
                $inoperative = $inoperativeStats['inoperative'];

                // Contar cámaras online
                $operative = $cameras->filter(fn($c) => $c->status === 'online')->count();

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
                $phpSheet->setCellValue("{$colLetter}{$currentRow}", $value === 0 ? "" : $value);
            }

            $phpSheet->getStyle("B{$currentRow}:H{$currentRow}")
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
            $phpSheet->setCellValue("{$colLetter}{$totalRow}", $value == 0 ? '' : $value);

            $phpSheet->getStyle("{$colLetter}{$totalRow}")
                ->getFont()
                ->setBold(true)
                ->setSize(12); // texto en negrita
        }


        $phpSheet->getStyle("A{$totalRow}:H{$totalRow}")
            ->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FFE600'); // Amarillo claro

        $phpSheet->getStyle("B{$totalRow}:H{$totalRow}")
            ->getAlignment()
            ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

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
