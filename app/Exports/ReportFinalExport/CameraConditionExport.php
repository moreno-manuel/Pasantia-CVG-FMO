<?php

namespace App\Exports\ReportFinalExport;

use App\Exports\ReportFinalExport\BaseReportExport;
use App\Models\monitoringSystem\Camera;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class CameraConditionExport extends BaseReportExport
{
    protected $data;

    public function __construct()
    {
        // Cargar todas las cámaras con sus relaciones
        $this->data = $this->loadData();
    }

    protected function loadData()
    {
        $cameras = Camera::with(['nvr', 'conditionAttention'])->get();

        // Agrupar por attention_type y luego por location
        $groupedByCondition = [];

        foreach ($cameras as $camera) {
            $condition = optional($camera->conditionAttention)->name ?? 'SIN TIPO DE CONDICIÓN';
            $location = optional($camera->nvr)->location;

            if (!isset($groupedByCondition[$condition])) {
                $groupedByCondition[$condition] = [];
            }

            if (!isset($groupedByCondition[$condition][$location])) {
                $groupedByCondition[$condition][$location] = [
                    'items' => [],
                    'totals' => [
                        'inoperative' => 0,
                        'operative' => 0,
                    ],
                ];
            }

            $groupedByCondition[$condition][$location]['items'][] = $camera;

            // Actualizar totales
            if ($camera->status === 'Inactivo') {
                $groupedByCondition[$condition][$location]['totals']['inoperative'] += 1;
            } else {
                $groupedByCondition[$condition][$location]['totals']['operative'] += 1;
            }

            $groupedByCondition[$condition][$location]['totals']['total'] += 1;
        }

        return $groupedByCondition;
    }

    public function collection()
    {
        return $this->data;
    }

    public function getSheetName(): string
    {
        return 'Cámaras por Condición';
    }

    public function getTitle(): string
    {
        return 'Cámaras por Tipo de Condición';
    }

    public function headings(): array
    {
        return [
            'Tipo de condición',
            'Mac',
            'Nombre',
            'Marca',
            'Modelo',
            'Ip',
            'localidad',
        ];
    }

    public function mapGroup($phpSheet, $condition, array $groupData, int &$currentRow)
    {
        // Título de condición
        $phpSheet->setCellValue("A{$currentRow}", strtoupper($condition));
        $phpSheet->getStyle("A{$currentRow}")
            ->getFont()
            ->setBold(true)
            ->setSize(12);
        $phpSheet->getRowDimension($currentRow)->setRowHeight(25);
        $currentRow++;

        // Por cada ubicación dentro de esta condición
        foreach ($groupData as $location => $locationData) {
            // Título de ubicación
            $phpSheet->setCellValue("A{$currentRow}", "Ubicación: {$location}");
            $phpSheet->getStyle("A{$currentRow}")
                ->getFont()
                ->setBold(true)
                ->setSize(11);
            $currentRow++;

            // Cámaras
            foreach ($locationData['items'] as $camera) {
                $rowData = $this->map($camera);
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
            $phpSheet->setCellValue("A{$totalRow}", "TOTAL - {$location}");
            $rowData = $this->mapTotal($locationData['totals']);
            foreach ($rowData as $colIndex => $value) {
                $colLetter = Coordinate::stringFromColumnIndex($colIndex + 1);
                $phpSheet->setCellValue("{$colLetter}{$totalRow}", $value);
            }

            $phpSheet->getStyle("A{$totalRow}:H{$totalRow}")
                ->getFill()
                ->setFillType(Fill::FILL_SOLID)
                ->getStartColor()->setARGB('FFFFCC'); // Amarillo claro

            $currentRow += 2;
        }
    }

    public function map($camera): array
    {
        return [
            $camera->attentionDetail->type ?? '',
            $camera->mac,
            $camera->name,
            $camera->mark,
            $camera->model,
            $camera->ip,
            $camera->location,
        ];
    }

    public function mapTotal(array $totals): array
    {
        return [
            '', // vacío
            '', // vacío
            '', // vacío
            '', // vacío
            '', // vacío
            '', // vacío
            $totals['operative'],
            $totals['inoperative'],
        ];
    }

    public function calculateGeneralTotals(array $data): array
    {
        $totalInoperative = 0;
        $totalOperative = 0;

        foreach ($data as $condition => $locations) {
            foreach ($locations as $location => $item) {
                $totalInoperative += $item['totals']['inoperative'];
                $totalOperative += $item['totals']['operative'];
            }
        }

        return [
            'total' => $totalInoperative + $totalOperative,
            'inoperative' => $totalInoperative,
            'operative' => $totalOperative,
        ];
    }

    public function mapGeneralTotals(array $totals): array
    {
        return [
            'TOTAL GENERAL',
            '', // vacío
            '', // vacío
            '', // vacío
            '', // vacío
            '', // vacío
            $totals['operative'],
            $totals['inoperative'],
        ];
    }
}
