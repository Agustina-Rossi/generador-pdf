<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use setasign\Fpdi\Tcpdf\Fpdi;

class PdfController extends Controller {
    public function index() {
        return view('pdf.form');
    }

    public function buscar(Request $request) {
        $request->validate([
            'pdfFile' => 'required|mimes:pdf|max:2048',
            'cuil' => 'required|string',
        ]);

        $pdfFile = $request->file('pdfFile');
        $cuil = $request->input('cuil');

        $filePath = $pdfFile->storeAs('uploads', $pdfFile->getClientOriginalName());

        $this->buscarYCrearPDF($filePath, $cuil);

        return redirect()->back()->with('success', 'Proceso completado.');
    }

    private function buscarYCrearPDF($inputPdfPath, $cuilBuscado)
    {
        $pdf = new \setasign\Fpdi\Tcpdf\Fpdi();
    
        // Obtener la ruta completa del archivo utilizando Storage
        $filePath = Storage::path($inputPdfPath);
    
        $totalPages = $pdf->setSourceFile($filePath);
    
        // Inicializar el objeto para extraer texto
        $pdfToText = new Pdf();
    
        for ($page = 1; $page <= $totalPages; $page++) {
            $templateId = $pdf->importPage($page);
    
            // Extraer texto de la página
            $text = $pdfToText->setPdf(storage_path('app/' . $inputPdfPath))
                              ->text();
    
            // Buscar el número de CUIL en el texto
            if (strpos($text, $cuilBuscado) !== false) {
                $pdf->AddPage();
                $pdf->useTemplate($templateId);
            }
        }
    
        // Salvar el PDF de salida
        $outputPdfPath = 'output.pdf';
        $pdf->Output(storage_path('app/' . $outputPdfPath), 'F');
    }
}
