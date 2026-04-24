<?php

namespace App\Http\Controllers;

use App\Models\Transactions;
use App\Models\UserCategory;
use App\Services\AccountService;
use App\Services\CsvTextService;
use App\Services\OCRService;
use App\Services\PdfTextService;
use Illuminate\Http\Request;

class ImportController extends Controller
{
    public function storeTransactions(Request $request, CsvTextService $csv, OCRService $ocr, PdfTextService $pdfTextService)
    {
        $transactions = "";
        $request->validate([
            'statement' => 'required|file|mimes:csv,pdf,jpg,jpeg|max:10240',
        ]);
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
                type: $amount < 0 ? 'expenses' : 'income',
                amount: $amount,
                currency: $transaction['currency'],
                date: $transaction['date'],
                category: UserCategory::detect(
                    $transaction['payer'],
                    $transaction['description'],
                    auth()->id()
                ),
                iban: $transaction['iban'],
                payer: $transaction['payer'],
                payeriban: $transaction['payeriban'],
                description: $transaction['description'],
            );

        }


        app(AccountService::class)->syncAccounts(auth()->id());
        return redirect()->route('transactions.index');

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
