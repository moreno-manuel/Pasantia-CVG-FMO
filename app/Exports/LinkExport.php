<?php

namespace App\Exports;

use App\Models\networkInfrastructure\Link;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class LinkExport implements ShouldAutoSize, WithDrawings, WithEvents
{
    protected $data;

    public function __construct()
    {
        $this->data = Link::select('mac', 'mark', 'model', 'name', 'ssid', 'ip', 'location', 'description', 'status')->get();
    }

    public function data()
    {
        return $this->data;
    }

    public function drawings()
    {
        $drawing = new Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('Logo de la empresa');
        $drawing->setPath(public_path('images/logo.png'));
        $drawing->setHeight(50);
        $drawing->setCoordinates('A1'); // Logo en A1
        return $drawing;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet;
                $data = $this->data;

                // Título centrado en fila 2
                $sheet->mergeCells('A2:I2');
                $sheet->setCellValue('A2', 'Inventario de Enlaces');
                $sheet->getStyle('A2')->getFont()
                    ->setBold(true)
                    ->setSize(16);
                $sheet->getStyle('A2')->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

                // Fecha de exportación en pie de página (derecha, en rojo)
                $date = now()->format('d/m/Y H:i');
                $lastRow = $data->count() + 6; // Fila para el pie
                $sheet->setCellValue("I{$lastRow}", "Fecha de Exportación: {$date}");
                $sheet->getStyle("I{$lastRow}")->getFont()
                    ->setItalic(true)
                    ->setSize(10)
                    ->setColor(new Color('FF0000')); // Rojo
                $sheet->getStyle("I{$lastRow}")->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);

                // Encabezados
                $headers = ['Mac', 'Marca', 'Modelo', 'Nombre', 'Ssid', 'IP', 'Localidad', 'Descripción', 'Status'];
                $headerRow = 3;

                foreach ($headers as $colIndex => $header) {
                    $colLetter = Coordinate::stringFromColumnIndex($colIndex + 1);
                    $sheet->setCellValue($colLetter . $headerRow, $header);

                    // Estilo encabezado
                    $sheet->getStyle($colLetter . $headerRow)
                        ->getFill()
                        ->setFillType(Fill::FILL_SOLID)
                        ->getStartColor()->setARGB('FF555555'); // Gray 700

                    $sheet->getStyle($colLetter . $headerRow)
                        ->getFont()
                        ->setBold(true)
                        ->setColor(new Color('FFFFFFFF')); // Blanco

                    $sheet->getStyle($colLetter . $headerRow)
                        ->getAlignment()
                        ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
                        ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
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
                        $row->status,
                    ];

                    foreach ($rowData as $colIndex => $value) {
                        $colLetter = Coordinate::stringFromColumnIndex($colIndex + 1);
                        $sheet->setCellValue($colLetter . ($startRow + $rowIndex), $value);

                        // Centrar contenido
                        $sheet->getStyle($colLetter . ($startRow + $rowIndex))
                            ->getAlignment()
                            ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
                            ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
                    }
                }

                // Estilo filas de datos
                $lastDataRow = $startRow + $data->count() - 1;
                $dataRange = "A{$startRow}:G{$lastDataRow}";

                $sheet->getStyle($dataRange)
                    ->getFont()
                    ->setBold(true)
                    ->setColor(new Color('FF000000')); // Blanco

                // Ajustar ancho automático
                foreach (range('A', 'I') as $col) {
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }

                // 🔐 Proteger la hoja: permite edición solo en ciertas celdas
                $sheet->getProtection()->setSheet(true); // Activa protección de hoja
                $sheet->getStyle($dataRange)->getProtection()
                    ->setLocked(\PhpOffice\PhpSpreadsheet\Style\Protection::PROTECTION_UNPROTECTED); // Permitir edición en datos
            },
        ];
    }
}
