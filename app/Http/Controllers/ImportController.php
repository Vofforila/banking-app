<?php

namespace App\Http\Controllers;

use App\Services\CsvTextService;
use App\Services\OCRService;
use App\Services\PdfTextService;
use Illuminate\Http\Request;

class ImportController extends Controller
{
    public function store(Request $request, CsvTextService $csv, OCRService $ocr, PdfTextService $pdfTextService)
    {
        $text = "";
        $file = $request->file('file');

        if (!$file) {
            dd('No file uploaded');
        }

        $mimeType = $file->getMimeType();
        $extension = strtolower($file->getClientOriginalExtension());

        $isPdf = $mimeType === 'application/pdf' || $extension === 'pdf';
        $isImage = str_starts_with($mimeType, 'image/') ||
            in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp', 'tiff']);

        $isCsv = $mimeType === 'text/csv' ||
            $extension === 'csv' ||
            $mimeType === 'application/csv';

        $isJson = $mimeType === 'application/json' ||
            $extension === 'json';

        if ($isImage) {
            $text = $ocr->extractText($file);
        } elseif ($isCsv) {
            $text = $csv->extractFromCsv($file);
        } elseif ($isJson) {
            $text = 'JSON file';
        } elseif ($isPdf) {
            $text = $pdfTextService->extract($file);
        }

        return $text;

        //        $text = $ocr->extractText($file);

        //        $transactions = $parser->parse($text);
        //
        //        if (app()->environment('local')) {
        //            dd($transactions, $text);
        //        }
        //
        //        return response()->json(['transactions' => $transactions]);
    }
}
