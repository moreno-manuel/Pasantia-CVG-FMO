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
        $this->data = Camera::with(['nvr', 'conditionAttention'])
            ->select('id', 'mac', 'mark', 'model', 'name', 'ip', 'location', 'description', 'status', 'nvr_id')
            ->get()
            ->groupBy(function ($camera) {
                return $camera->nvr ? $camera->nvr->name : 'Sin NVR Asignado';
            })
            ->map(function ($cameras) {
                // Ordenar las cámaras por nombre dentro de cada grupo
                return $cameras->sortBy('name');
            })
            ->sortKeys();
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $phpSheet = $event->sheet->getDelegate();
                $groupedData = $this->data; // Colección agrupada por NVR

                // Altura de filas para logo y título
                $phpSheet->getRowDimension('1')->setRowHeight(30);
                $phpSheet->getRowDimension('2')->setRowHeight(30);

                // Título
                $phpSheet->mergeCells("A1:H2");
                $phpSheet->setCellValue('A1', 'Inventario de Cámaras');
                $phpSheet->getStyle('A1')->getFont()->setBold(true)->setSize(18);
                $phpSheet->getStyle('A1')->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
                    ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

                // Encabezados
                $headers = ['Mac', 'Marca', 'Modelo', 'Nombre', 'Localidad', 'IP', 'Descripción', 'Status'];
                $headerRow = 4;

                foreach ($headers as $colIndex => $header) {
                    $colLetter = Coordinate::stringFromColumnIndex($colIndex + 1);
                    $phpSheet->setCellValue($colLetter . $headerRow, $header);

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
                        ->getStartColor()->setARGB('FF555555'); // Gris oscuro
                }

                // Iniciar impresión de datos desde fila 5
                $currentRow = $headerRow + 1;

                // Recorrer cada grupo de NVR
                foreach ($groupedData as $nvrName => $cameras) {
                    // Imprimir nombre del NVR como subtítulo
                    $phpSheet->mergeCells("A{$currentRow}:H{$currentRow}");
                    $phpSheet->setCellValue("A{$currentRow}", "NVR: {$nvrName}");

                    $phpSheet->getStyle("A{$currentRow}")
                        ->getFont()
                        ->setBold(true)
                        ->setSize(14);

                    $phpSheet->getStyle("A{$currentRow}")
                        ->getAlignment()
                        ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT)
                        ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

                    $currentRow++;

                    // Imprimir cámaras de este NVR
                    foreach ($cameras as $camera) {
                        $rowData = [
                            $camera->mac,
                            $camera->mark,
                            $camera->model,
                            $camera->name,
                            $camera->location,
                            $camera->ip,
                            $camera->description,
                            $camera->status
                        ];

                        foreach ($rowData as $colIndex => $value) {
                            $colLetter = Coordinate::stringFromColumnIndex($colIndex + 1);
                            $phpSheet->setCellValue($colLetter . $currentRow, $value);

                            $phpSheet->getStyle($colLetter . $currentRow)
                                ->getAlignment()
                                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
                                ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
                        }

                        $lastcondition = $camera->conditionAttention()->latest('created_at')->first();


                        if (optional($lastcondition)->status === 'Por atender') {
                            $phpSheet->getStyle("A{$currentRow}:H{$currentRow}")
                                ->getFill()
                                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                                ->getStartColor()->setARGB('FFFF0000'); // Rojo

                            $phpSheet->getStyle("A{$currentRow}:H{$currentRow}")
                                ->getFont()
                                ->setColor(new Color('FFFFFFFF')); // Texto blanco
                        }

                        $currentRow++;
                    }

                    // Espacio entre grupos
                    $currentRow++;
                }

                // Pie de página - Fecha
                $date = now()->format('d/m/Y H:i');
                $phpSheet->setCellValue("H{$currentRow}", "Fecha de Exportación: {$date}");
                $phpSheet->getStyle("H{$currentRow}")
                    ->getFont()
                    ->setItalic(true)
                    ->setSize(10)
                    ->setColor(new Color('FF0000')); // Rojo

                $phpSheet->getStyle("H{$currentRow}")
                    ->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);

                // Pie - Gerencia y Área
                $footerRow = $currentRow + 1;
                $phpSheet->setCellValue("A{$footerRow}", "Gerencia: Telemática");
                $phpSheet->getStyle("A{$footerRow}")
                    ->getFont()
                    ->setItalic(true)
                    ->setSize(10)
                    ->setColor(new Color('FF555555'));

                $phpSheet->setCellValue("A" . ($footerRow + 1), "Área: Seguridad Tecnológica");
                $phpSheet->getStyle("A" . ($footerRow + 1))
                    ->getFont()
                    ->setItalic(true)
                    ->setSize(10)
                    ->setColor(new Color('FF555555'));

                // Alturas
                $phpSheet->getRowDimension($footerRow)->setRowHeight(18);
                $phpSheet->getRowDimension($footerRow + 1)->setRowHeight(18);

                // Ajustar ancho automático
                foreach (range('A', 'H') as $col) {
                    $phpSheet->getColumnDimension($col)->setAutoSize(true);
                }

                // 🔐 Proteger hoja
                $phpSheet->getProtection()->setSheet(true);
                $phpSheet->getStyle("A5:H" . ($currentRow))
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
        $drawing->setPath(public_path('images/LogosCVG_Ferro.png'));
        $drawing->setHeight(50);
        $drawing->setCoordinates('A1');
        $drawing->setOffsetX(5);
        $drawing->setOffsetY(5);
        return $drawing;
    }
}
