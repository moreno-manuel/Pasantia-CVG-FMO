<?php

namespace App\Exports;

use App\Models\monitoringSystem\Camera;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Protection;

class CameraExport implements ShouldAutoSize, WithDrawings, WithEvents
{
    protected $data;

    public function __construct()
    {
        $this->data = Camera::select('mac', 'mark', 'model', 'name', 'ip', 'location', 'description', 'status')->get();
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $phpSheet = $event->sheet->getDelegate(); // Worksheet nativo
                $data = $this->data;

                // Altura de filas para logo y título
                $phpSheet->getRowDimension('1')->setRowHeight(30);
                $phpSheet->getRowDimension('2')->setRowHeight(30);

                // Título ocupando filas 1 y 2
                $phpSheet->mergeCells("A1:H2");
                $phpSheet->setCellValue('A1', 'Inventario de Cámaras');

                $phpSheet->getStyle('A1')->getFont()
                    ->setBold(true)
                    ->setSize(18);

                $phpSheet->getStyle('A1')->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
                    ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

                // Fecha de exportación en pie de página (derecha, en rojo)
                $date = now()->format('d/m/Y H:i');
                $lastRow = $data->count() + 6;
                $phpSheet->setCellValue("H{$lastRow}", "Fecha de Exportación: {$date}");
                $phpSheet->getStyle("H{$lastRow}")->getFont()
                    ->setItalic(true)
                    ->setSize(10)
                    ->setColor(new Color('FF0000')); // Rojo
                $phpSheet->getStyle("H{$lastRow}")->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);

                // Pie de página en columna A
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

                // Altura de filas del pie
                $phpSheet->getRowDimension($footerRow)->setRowHeight(18);
                $phpSheet->getRowDimension($footerRow + 1)->setRowHeight(18);

                // Encabezados
                $headers = ['Mac', 'Marca', 'Modelo', 'Nombre', 'IP', 'Localidad', 'Descripción', 'Status'];
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
                foreach ($data as $rowIndex => $row) {
                    $rowData = [
                        $row->mac,
                        $row->mark,
                        $row->model,
                        $row->name,
                        $row->ip,
                        $row->location,
                        $row->description,
                        $row->status
                    ];

                    $rowNumber = $startRow + $rowIndex;

                    foreach ($rowData as $colIndex => $value) {
                        $colLetter = Coordinate::stringFromColumnIndex($colIndex + 1);
                        $phpSheet->setCellValue($colLetter . $rowNumber, $value);

                        $phpSheet->getStyle($colLetter . $rowNumber)
                            ->getAlignment()
                            ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
                            ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
                    }

                    // Si el status es "Inactivo", coloreamos toda la fila de rojo
                    if ($rowData[7] === 'offline') {
                        $phpSheet->getStyle("A{$rowNumber}:H{$rowNumber}")
                            ->getFill()
                            ->setFillType(Fill::FILL_SOLID)
                            ->getStartColor()->setARGB('FFFF0000'); // Rojo

                        $phpSheet->getStyle("A{$rowNumber}:H{$rowNumber}")
                            ->getFont()
                            ->setColor(new Color('FFFFFFFF')); // Texto blanco
                    }
                }

                // Estilo filas de datos
                $lastDataRow = $startRow + $data->count() - 1;
                $phpSheet->getStyle("A{$startRow}:H{$lastDataRow}")
                    ->getFont()
                    ->setBold(true)
                    ->setColor(new Color('FF000000')); // Negro

                // Ajustar ancho automático
                foreach (range('A', 'H') as $col) {
                    $phpSheet->getColumnDimension($col)->setAutoSize(true);
                }

                // 🔐 Proteger la hoja: permite edición solo en ciertas celdas
                $phpSheet->getProtection()->setSheet(true);
                $phpSheet->getStyle("A{$startRow}:H{$lastDataRow}")
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
}
