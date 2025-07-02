<?php

namespace App\Exports\ReportFinalExport;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

abstract class BaseReportExport implements WithEvents, WithDrawings, WithHeadings, WithStyles
{
    abstract public function collection();
    abstract public function getSheetName();
    abstract public function getTitle();

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $phpSheet = $event->sheet->getDelegate(); // Worksheet nativo
                $data = $this->collection();

                // Altura filas logo/título
                $phpSheet->getRowDimension('1')->setRowHeight(30);
                $phpSheet->getRowDimension('2')->setRowHeight(30);

                // Título fusionado
                $headerRow = 3;
                $lastColumn = Coordinate::stringFromColumnIndex(count($this->headings()));
                $phpSheet->mergeCells("A1:{$lastColumn}2");
                $phpSheet->setCellValue('A1', $this->getTitle());

                $phpSheet->getStyle('A1')->getFont()
                    ->setBold(true)
                    ->setSize(18);
                $phpSheet->getStyle('A1')->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
                    ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

                // Encabezados dinámicos
                $headers = $this->headings();
                foreach ($headers as $colIndex => $header) {
                    $colLetter = Coordinate::stringFromColumnIndex($colIndex + 1);
                    $phpSheet->setCellValue("{$colLetter}{$headerRow}", $header);

                    // Estilo encabezado
                    $phpSheet->getStyle("{$colLetter}{$headerRow}")
                        ->getFill()
                        ->setFillType(Fill::FILL_SOLID)
                        ->getStartColor()->setARGB('FF555555'); // Gray 700

                    $phpSheet->getStyle("{$colLetter}{$headerRow}")
                        ->getFont()
                        ->setBold(true)
                        ->setColor(new Color('FFFFFFFF')); // Blanco

                    $phpSheet->getStyle("{$colLetter}{$headerRow}")
                        ->getAlignment()
                        ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
                        ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
                }

                // Datos dinámicos
                $startRow = 4;
                $currentRow = $startRow;

                foreach ($data as $groupKey => $groupData) {
                    // Validar que $groupData sea un arreglo asociativo
                    if (!is_array($groupData) || empty($groupData)) {
                        continue;
                    }

                    // Llamar a mapGroup() solo si $groupData es válido
                    $this->mapGroup($phpSheet, $groupKey, $groupData, $currentRow);

                    // Solo incrementar $currentRow si hay cámaras
                    $totalItems = array_sum(array_map(
                        fn($loc) => (is_array($loc) && isset($loc['items']) && is_array($loc['items']))
                            ? count($loc['items'])
                            : 0,
                        $groupData
                    ));

                    $currentRow += $totalItems + 2; // Saltar según el número real de cámaras
                }

                // Totales generales
                $generalTotals = $this->calculateGeneralTotals($data);
                $phpSheet->setCellValue("A{$currentRow}", 'TOTAL GENERAL');

                $rowData = $this->mapGeneralTotals($generalTotals);
                foreach ($rowData as $colIndex => $value) {
                    $colLetter = Coordinate::stringFromColumnIndex($colIndex + 1);
                    $phpSheet->setCellValue("{$colLetter}{$currentRow}", $value === 0 ? "" : $value);

                    $phpSheet->getStyle("{$colLetter}{$currentRow}")
                        ->getFont()
                        ->setBold(true)
                        ->setSize(12); // texto en negrita
                }

                $phpSheet->getStyle("A{$currentRow}:H{$currentRow}")
                    ->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()->setARGB('FFE600'); // Amarillo claro

                $phpSheet->getStyle("B{$currentRow}:H{$currentRow}")
                    ->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

                // Pie de página
                $footerRow = $currentRow + 2;
                $phpSheet->setCellValue("A{$footerRow}", "Gerencia: Telemática");
                $phpSheet->setCellValue("A" . ($footerRow + 1), "Área: Seguridad Tecnológica");

                $phpSheet->getStyle("A{$footerRow}")
                    ->getFont()
                    ->setItalic(true)
                    ->setSize(10)
                    ->setColor(new Color('FF555555'));

                $phpSheet->getStyle("A" . ($footerRow + 1))
                    ->getFont()
                    ->setItalic(true)
                    ->setSize(10)
                    ->setColor(new Color('FF555555'));

                $phpSheet->getStyle("A{$footerRow}:$lastColumn{$footerRow}")
                    ->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);

                $phpSheet->getRowDimension($footerRow)->setRowHeight(18);
                $phpSheet->getRowDimension($footerRow + 1)->setRowHeight(18);

                // Fecha de exportación
                $date = now()->format('d/m/Y H:i');
                $phpSheet->setCellValue("H{$footerRow}", "Fecha: {$date}");
                $phpSheet->getStyle("H{$footerRow}")->getFont()
                    ->setItalic(true)
                    ->setSize(10)
                    ->setColor(new Color('FF0000')); // Rojo
                $phpSheet->getStyle("H{$footerRow}")
                    ->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);

                // Ancho automático
                foreach (range('A', 'I') as $col) {
                    $phpSheet->getColumnDimension($col)->setAutoSize(true);
                }

                // Protección de hoja
                $phpSheet->getProtection()->setSheet(true);
            },
        ];
    }

    /**
     * Método abstracto para mapear cada grupo (ej: por ubicación o por condición)
     */
    abstract public function mapGroup($phpSheet, $groupKey, array $groupData, int &$currentRow);

    /**
     * Calcular totales generales del informe
     */
    abstract protected function calculateGeneralTotals(array $data): array;

    /**
     * Mapear fila con totales generales
     */
    abstract public function mapGeneralTotals(array $totals): array;

    public function drawings()
    {
        $drawing = new Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('Logo de la empresa');
        $drawing->setPath(public_path('images/logo.png'));
        $drawing->setHeight(50);
        $drawing->setCoordinates('A1');
        return $drawing;
    }

    public function styles(Worksheet $sheet)
    {
        // Opcional: estilo adicional
    }
}
