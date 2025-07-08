<?php

namespace App\Exports;

use App\Models\networkInfrastructure\Link;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Protection;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class LinkExport implements ShouldAutoSize, WithDrawings, WithEvents
{
    protected $data;

    public function __construct()
    {
        $this->data = Link::select('mac', 'mark', 'model', 'name', 'ssid', 'ip', 'location', 'description')
            ->get()
            ->sortBy('location');
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $phpSheet = $event->sheet->getDelegate(); // Hoja nativa
                $data = $this->data;

                // Altura de filas para logo y título
                $phpSheet->getRowDimension('1')->setRowHeight(30);
                $phpSheet->getRowDimension('2')->setRowHeight(30);

                // Título ocupando filas 1 y 2
                $phpSheet->mergeCells("A1:H2");
                $phpSheet->setCellValue('A1', 'Inventario de Enlaces');

                $phpSheet->getStyle('A1')->getFont()
                    ->setBold(true)
                    ->setSize(18);

                $phpSheet->getStyle('A1')->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
                    ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);


                // Encabezados
                $headers = ['Mac', 'Marca', 'Modelo', 'Nombre', 'Ssid', 'IP', 'Localidad', 'Descripción'];
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
                        $row->ssid,
                        $row->ip,
                        $row->location,
                        $row->description,
                    ];

                    $rowNumber = $startRow + $rowIndex;

                    foreach ($rowData as $colIndex => $value) {
                        $colLetter = Coordinate::stringFromColumnIndex($colIndex + 1);
                        $phpSheet->setCellValue($colLetter . $rowNumber, $value);

                        // Centrar contenido
                        $phpSheet->getStyle($colLetter . $rowNumber)
                            ->getAlignment()
                            ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
                            ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
                    }
                }

                // Estilo filas de datos
                $lastDataRow = $startRow + $data->count() - 1;
                $phpSheet->getStyle("A{$startRow}:I{$lastDataRow}")
                    ->getFont()
                    ->setBold(true)
                    ->setColor(new Color('FF000000')); // Negro

                // Pie de página - Gerencia y Área en columna A, una debajo de otra
                $lastRow = $data->count() + 6;
                $footerRow = $lastRow + 2;

                // Primera línea: Gerencia
                $phpSheet->setCellValue("A{$footerRow}", "Gerencia: Telemática");
                $phpSheet->getStyle("A{$footerRow}")
                    ->getFont()
                    ->setItalic(true)
                    ->setSize(10)
                    ->setColor(new Color('FF555555')); // Gris oscuro
                $phpSheet->getStyle("A{$footerRow}")
                    ->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);

                // Segunda línea: Área
                $phpSheet->setCellValue("A" . ($footerRow + 1), "Área: Seguridad Tecnológica");
                $phpSheet->getStyle("A" . ($footerRow + 1))
                    ->getFont()
                    ->setItalic(true)
                    ->setSize(10)
                    ->setColor(new Color('FF555555')); // Gris oscuro
                $phpSheet->getStyle("A" . ($footerRow + 1))
                    ->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);

                // Opcional: ajustar altura de filas para que se vean bien
                $phpSheet->getRowDimension($footerRow)->setRowHeight(18);
                $phpSheet->getRowDimension($footerRow + 1)->setRowHeight(18);

                // Fecha de exportación en pie de página (derecha, en rojo)
                $date = now()->format('d/m/Y H:i');

                $phpSheet->setCellValue("H{$lastRow}", "Fecha de Exportación: {$date}");
                $phpSheet->getStyle("H{$lastRow}")->getFont()
                    ->setItalic(true)
                    ->setSize(10)
                    ->setColor(new Color('FF0000')); // Rojo
                $phpSheet->getStyle("H{$lastRow}")->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);

                // Ajustar ancho automático
                foreach (range('A', 'I') as $col) {
                    $phpSheet->getColumnDimension($col)->setAutoSize(true);
                }

                // 🔐 Proteger la hoja: permite edición solo en ciertas celdas
                $phpSheet->getProtection()->setSheet(true);
                $phpSheet->getStyle("A{$startRow}:I{$lastDataRow}")
                    ->getProtection()
                    ->setLocked(\PhpOffice\PhpSpreadsheet\Style\Protection::PROTECTION_UNPROTECTED);
            },
        ];
    }

    public function drawings()
    {
        $drawing = new Drawing();
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
