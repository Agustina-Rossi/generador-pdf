<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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

    private function buscarYCrearPDF($inputPdfPath, $cuilBuscado) {
        $pdf = new Fpdi();
        $totalPages = $pdf->setSourceFile(storage_path('app/' . $inputPdfPath));

        for ($page = 1; $page <= $totalPages; $page++) {
            $templateId = $pdf->importPage($page);
            $text = $pdf->getPageContent($page);

            if (strpos($text, $cuilBuscado) !== false) {
                $pdf->AddPage();
                $pdf->useTemplate($templateId);
            }
        }

        $outputPdfPath = 'output.pdf';
        $pdf->Output(storage_path('app/' . $outputPdfPath), 'F');
    }
}
