<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ReportesController extends Controller
{
    public function previsualizarReportePDF(Request $request)
    {
        $datos = json_decode($request->input('datos'), true);
        $columnas = json_decode($request->input('columnas'), true);

        if (!$datos || !$columnas) {
            return response()->json(['error' => 'Datos no válidos para el reporte'], 400);
        }

        $datos = $this->limpiarDatos($datos);

        // Verificar el tipo de datos para determinar la vista
        $view = 'generico_pdf';
        if (isset($datos[0]['id_usuario'])) {
            $view = 'generico_pdf_usuarios';
        }

        $pdf = Pdf::loadView($view, compact('datos', 'columnas'))
                ->setPaper('letter', 'landscape');

        return $pdf->stream('reporte.pdf');
    }

    public function generarReporteExcel(Request $request)
    {
        $datos = json_decode($request->input('datos'), true);
        $columnas = json_decode($request->input('columnas'), true);

        if (!$datos || !$columnas) {
            return response()->json(['error' => 'Datos no válidos para el reporte'], 400);
        }

        $datos = $this->limpiarDatos($datos);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Aplicar formato a la tabla
        $sheet->fromArray($columnas, null, 'A1');
        $sheet->fromArray($datos, null, 'A2');

        // Aplicar estilos
        $headerStyle = [
            'font' => ['bold' => true],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FFf8f9fa']
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => 'FFdddddd']
                ]
            ]
        ];

        $rowStyle = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => 'FFdddddd']
                ]
            ]
        ];

        // Aplicar estilo a las cabeceras
        $sheet->getStyle('A1:E1')->applyFromArray($headerStyle);

        // Aplicar estilo a las filas
        $sheet->getStyle('A2:E' . (count($datos) + 1))->applyFromArray($rowStyle);

        $writer = new Xlsx($spreadsheet);
        $fileName = 'reporte.xlsx';
        $filePath = tempnam(sys_get_temp_dir(), $fileName);
        $writer->save($filePath);

        return response()->download($filePath, $fileName)->deleteFileAfterSend(true);
    }

    private function limpiarDatos(array $datos)
    {
        return array_map(function($fila) {
            return array_map(function($valor) {
                return is_array($valor) ? json_encode($valor) : (string) $valor;
            }, $fila);
        }, $datos);
    }
}
