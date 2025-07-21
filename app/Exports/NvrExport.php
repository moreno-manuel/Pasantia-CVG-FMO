<?php

namespace App\Exports;

use App\Models\monitoringSystem\Nvr;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Protection;

class NvrExport implements ShouldAutoSize, WithDrawings, WithEvents
{
    protected $data;

    public function __construct()
    {
        // Cargar NVR con su relación slot_nvr y cámaras
        $this->data = Nvr::with(['slotNvr', 'camera'])
            ->select('id', 'mac', 'mark', 'model', 'name', 'ip', 'location',  'description', 'status', 'ports_number')
            ->get()
            ->sortBy('location');
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $phpSheet = $event->sheet->getDelegate(); // Worksheet nativo
                $data = $this->prepareData();

                // Altura de filas para logo y título
                $phpSheet->getRowDimension('1')->setRowHeight(30);
                $phpSheet->getRowDimension('2')->setRowHeight(30);

                // Título ocupando filas 1 y 2
                $phpSheet->mergeCells("A1:Q2");
                $phpSheet->setCellValue('A1', 'Inventario de NVR');

                $phpSheet->getStyle('A1')->getFont()
                    ->setBold(true)
                    ->setSize(18);

                $phpSheet->getStyle('A1')->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
                    ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);


                // Encabezados (con soporte para hasta 4 slots)
                $headers = [
                    'Mac',
                    'Marca',
                    'Modelo',
                    'Nombre',
                    'IP',
                    'Localidad',
                    'Descripción',
                    'N° de Puertos',
                    'N° de Puertos Usados',
                    'N° de Puertos Disponibles',
                    'Status',
                ];

                // Añadir encabezados dinámicos para cada slot (hasta 2)
                for ($i = 1; $i <= 2; $i++) {
                    $headers[] = " Vol. {$i} Capacidad/Max.(TB) ";
                    $headers[] = "Serial HDD (TB)";
                    $headers[] = "Capacidad HDD (TB)";
                }

                $headerRow = 3;

                foreach ($headers as $colIndex => $header) {
                    $colLetter = Coordinate::stringFromColumnIndex($colIndex + 1);
                    $phpSheet->setCellValue($colLetter . $headerRow, $header);

                    // Estilo encabezado
                    $phpSheet->getStyle($colLetter . $headerRow)
                        ->getFont()
                        ->setBold(true)
                        ->setColor(new Color('FFFFFFFF')); // Blanco

                    $phpSheet->getStyle($colLetter . $headerRow)
                        ->getAlignment()
                        ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
                        ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

                    $phpSheet->getStyle($colLetter . $headerRow)
                        ->getFill()
                        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                        ->getStartColor()->setARGB('FF555555'); // Gray 700
                }

                // Datos
                $startRow = 4;
                foreach ($data as $rowIndex => $item) {
                    $nvr = $item['nvr'];
                    $slots = $item['slots'];

                    // Datos de fila
                    $rowData = [
                        $nvr->mac,
                        $nvr->mark,
                        $nvr->model,
                        $nvr->name,
                        $nvr->ip,
                        $nvr->location,
                        $nvr->description,
                        $nvr->ports_number,
                        $nvr->camera->count(),
                        $nvr->getAvailablePortsAttribute(),
                        $nvr->status,
                    ];

                    // Agregar datos de slots
                    for ($i = 0; $i < 2; $i++) {
                        $rowData[] = $slots[$i]['capacity_max'] ?? 'No aplica';
                        $rowData[] = $slots[$i]['hdd_serial'] ?? 'Sin HDD';
                        $rowData[] = $slots[$i]['hdd_capacity'] ?? '';
                    }

                    $rowNumber = $startRow + $rowIndex;

                    foreach ($rowData as $colIndex => $value) {
                        $colLetter = Coordinate::stringFromColumnIndex($colIndex + 1);
                        $phpSheet->setCellValue($colLetter . $rowNumber, $value);

                        $phpSheet->getStyle($colLetter . $rowNumber)
                            ->getAlignment()
                            ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
                            ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
                    }
                }


                // Pie de página en columna A
                $lastRow = count($data) + 6;
                $footerRow = $lastRow + 2;

                // Primera línea: Gerencia
                $phpSheet->setCellValue("A{$footerRow}", "Gerencia: Telemática");
                $phpSheet->getStyle("A{$footerRow}")
                    ->getFont()
                    ->setItalic(true)
                    ->setSize(10)
                    ->setColor(new Color('FF555555'));
                $phpSheet->getStyle("A{$footerRow}")
                    ->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                // Segunda línea: Área
                $phpSheet->setCellValue("A" . ($footerRow + 1), "Área: Seguridad Tecnológica");
                $phpSheet->getStyle("A" . ($footerRow + 1))
                    ->getFont()
                    ->setItalic(true)
                    ->setSize(10)
                    ->setColor(new Color('FF555555'));
                $phpSheet->getStyle("A" . ($footerRow + 1))
                    ->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);

                // Fecha de exportación en pie de página (derecha, en rojo)
                $date = now()->format('d/m/Y H:i');
                $phpSheet->setCellValue("Q{$lastRow}", "Fecha de Exportación: {$date}");
                $phpSheet->getStyle("Q{$lastRow}")->getFont()
                    ->setItalic(true)
                    ->setSize(10)
                    ->setColor(new Color('FF0000')); // Rojo
                $phpSheet->getStyle("Q{$lastRow}")->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);


                // Ajustar ancho automático
                foreach (range('A', 'Q') as $col) {
                    $phpSheet->getColumnDimension($col)->setAutoSize(true);
                }

                // 🔐 Proteger la hoja: permite edición solo en ciertas celdas
                // Estilo filas de datos
                $lastDataRow = $startRow + count($data) - 1;

                $phpSheet->getProtection()->setSheet(true);
                $phpSheet->getStyle("A{$startRow}:Q{$lastDataRow}")
                    ->getProtection()
                    ->setLocked(Protection::PROTECTION_UNPROTECTED);
            },
        ];
    }

    public function drawings()
    {
        $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('Logo de la empresa');
        $drawing->setPath(public_path('images/logo.png'));
        $drawing->setHeight(50);
        $drawing->setCoordinates('A1');
        $drawing->setOffsetX(5);
        $drawing->setOffsetY(5);
        return $drawing;
    }

    /**
     * Preparar datos plano: un registro por NVR, con sus slots como columnas
     */
    private function prepareData()
    {
        $result = [];

        foreach ($this->data as $nvr) {
            $slots = [];
            foreach ($nvr->slotNvr as $index => $slot) {
                $slots[$index] = [
                    'capacity_max' => $slot->capacity_max,
                    'hdd_serial' => $slot->hdd_serial,
                    'hdd_capacity' => $slot->hdd_capacity,
                ];
            }

            $result[] = [
                'nvr' => $nvr,
                'slots' => $slots,
            ];
        }

        return $result;
    }
}
