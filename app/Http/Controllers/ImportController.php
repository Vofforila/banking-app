<?php

namespace App\Http\Controllers;

use App\Enums\TransactionCategory;
use App\Models\Transactions;
use App\Services\CsvTextService;
use App\Services\OCRService;
use App\Services\PdfTextService;
use Illuminate\Http\Request;

class ImportController extends Controller
{
    public function storeTransactions(Request $request, CsvTextService $csv, OCRService $ocr, PdfTextService $pdfTextService)
    {
        $transactions = "";
        $file = $request->file('statement');

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
            $transactions = $ocr->extractText($file);
        } elseif ($isCsv) {
            $transactions = $csv->extractFromCsv($file);
        } elseif ($isJson) {
            $transactions = 'JSON file';
        } elseif ($isPdf) {
            $transactions = $pdfTextService->extract($file);
        }


        foreach ($transactions as $transaction) {
            $amount = (float)str_replace(',', '', $transaction['amount']);

            Transactions::createTransaction(
                account: $transaction['account'],
                type: $amount < 0 ? 'expenses' : 'income',  // ← string 'expenses' or 'income'
                amount: $amount,                              // ← float
                currency: $transaction['currency'],
                date: $transaction['date'],
                category: TransactionCategory::detect(
                    $transaction['payer'],
                    $transaction['description']
                ),
                iban: $transaction['iban'],
                payer: $transaction['payer'],
                payeriban: $transaction['payeriban'],
                description: $transaction['description'],
            );
        }


        return $transactions;

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
