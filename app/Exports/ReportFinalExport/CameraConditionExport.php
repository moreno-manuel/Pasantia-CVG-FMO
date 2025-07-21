<?php

namespace App\Exports\ReportFinalExport;

use App\Exports\ReportFinalExport\BaseReportExport;
use App\Models\monitoringSystem\Camera;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class CameraConditionExport extends BaseReportExport implements WithTitle
{
    protected $data;

    public function __construct()
    {
        // Cargar todas las cámaras con sus relaciones
        $this->data = $this->loadData();
    }

    protected function loadData()
    {
        $cameras = Camera::with(['nvr', 'conditionAttention'])
            ->whereHas('conditionAttention', function ($query) {
                $query->where('status', 'Por atender');
            })->get();

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
                        'total' => 0
                    ],
                ];
            }

            $groupedByCondition[$condition][$location]['items'][] = $camera;

            // Actualizar totales
            $groupedByCondition[$condition][$location]['totals']['inoperative'] += 1;

            $groupedByCondition[$condition][$location]['totals']['total'] += 1;
        }

        //  Ordenar cámaras en cada grupo por nombre del NVR
        foreach ($groupedByCondition as &$conditionGroups) {
            foreach ($conditionGroups as &$locationGroup) {
                usort($locationGroup['items'], function ($a, $b) {
                    // Obtener nombres de NVR, o cadena vacía si no existe
                    $nameA = optional($a->nvr)->name ?? '';
                    $nameB = optional($b->nvr)->name ?? '';
                    return strcmp($nameA, $nameB); // Orden alfabético ascendente
                });
            }
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
            'Fecha de inicio',
            'Descripción',
            'Fecha Control/Condición',
            'Última descripción',
            'Nvr/Conexión',
            'Nombre',
            'Marca',
            'Modelo',
            'IP',
        ];
    }

    public function mapGroup($phpSheet, $condition, array $groupData, int &$currentRow)
    {
        // Validación inicial
        if (!is_array($groupData) || empty($groupData)) {
            return; // Salir si $groupData no es válido
        }

        foreach ($groupData as $location => $locationData) {
            // Validar que $locationData sea un arreglo y tenga 'items'
            if (!is_array($locationData) || !isset($locationData['items'])) {
                continue; // Saltar ubicaciones inválidas
            }

            // Inicializar 'items' y 'totals' si no existen
            $items = is_array($locationData['items']) ? $locationData['items'] : [];
            $totals = isset($locationData['totals']) && is_array($locationData['totals'])
                ? $locationData['totals']
                : [];

            // Título de ubicación
            $phpSheet->setCellValue("A{$currentRow}", "Ubicación: {$location}");
            $phpSheet->getStyle("A{$currentRow}")
                ->getFont()
                ->setBold(true)
                ->setSize(11);
            $currentRow++;

            // Procesar cámaras
            foreach ($items as $camera) {
                $rowData = $this->map($camera);
                foreach ($rowData as $colIndex => $value) {
                    $colLetter = Coordinate::stringFromColumnIndex($colIndex + 1);
                    $phpSheet->setCellValue("{$colLetter}{$currentRow}", $value);
                }

                $phpSheet->getStyle("B{$currentRow}:j{$currentRow}")
                    ->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
                    ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

                $currentRow++;
            }

            // Fila amarilla con totales
            $totalRow = $currentRow;
            $phpSheet->setCellValue("A{$totalRow}", "TOTAL - {$location}");
            $rowData = $this->mapTotal($totals);
            foreach ($rowData as $colIndex => $value) {
                $colLetter = Coordinate::stringFromColumnIndex($colIndex + 1);
                $phpSheet->setCellValue("{$colLetter}{$totalRow}", $value);


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

            $currentRow += 2;
        }

        // Ancho automático
        foreach (range('A', 'J') as $col) {
            $phpSheet->getColumnDimension($col)->setAutoSize(true);
        }
    }

    public function map($camera): array
    {
        // Obtener la última condición de atención
        $lastCondition = $camera->conditionAttention()
            ->where('status', 'Por atender')
            ->latest('created_at')
            ->first();

        $nameCondition = '';
        $lastControlCondition = null;
        if ($lastCondition) {
            $nameCondition = optional($lastCondition)->name == 'OTRO' ? 'OTRO' . ' / ' . optional($lastCondition)->other_name : optional($lastCondition)->name;

            $lastControlCondition = $lastCondition->controlCondition() //obtener el último control de condición
                ->latest('created_at')
                ->first();
        }

        return [
            $nameCondition ?? 'Sin tipo de condición',
            optional($lastCondition)->created_at ? $lastCondition->created_at->format('d/m/Y') : '', // Fecha formateada
            optional($lastCondition)->description ?? '',
            optional($lastControlCondition)->created_at ? $lastControlCondition->created_at->format('d/m/Y') : '', // Fecha formateada
            optional($lastControlCondition)->text ?? '', // Última descripción
            $camera->nvr->name,
            $camera->name,
            $camera->mark,
            $camera->model,
            $camera->ip,
        ];
    }

    public function mapTotal(array $totals): array
    {
        return [
            'TOTAL', // vacío
            '', // vacío
            '', // vacío
            '', // vacío
            '', // vacío
            '', // vacío
            '', // vacío
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
            }
        }

        return [
            'total' => $totalInoperative + $totalOperative,
            'inoperative' => $totalInoperative,
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
            '', // vacío
            $totals['inoperative'],
        ];
    }

    public function title(): string
    {
        return 'cámaras por tipo de condición';
    }
}
