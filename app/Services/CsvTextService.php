<?php

namespace App\Services;

class CsvTextService
{
    public function extractFromCsv($file): array
    {

        $mappingKeywords = [
            'account' => ['account', 'own account name'],
            'iban' => ['iban', 'iban nr', 'account iban', 'own iban'],
            'date' => ['date', 'booking date', 'data'],
            'payer' => ['partner name'],
            'payeriban' => ['partner account number'],
            'amount' => ['amount', 'sum', 'suma'],
            'currency' => ['currency'],
            'description' => ['text'],
        ];

        $out = [];
        $content = file_get_contents($file->getRealPath());
        $content = mb_convert_encoding($content, 'UTF-8', 'UTF-16LE');
        $content = str_replace("\xEF\xBB\xBF", '', $content);
        $rows = array_map('str_getcsv', explode("\n", $content));

        $indexes = [];
        foreach ($rows[0] as $rowindex => $value) {

            $headerLower = trim(strtolower($value));
            foreach ($mappingKeywords as $index => $keywords) {
                foreach ($keywords as $keyword) {
                    if ($headerLower === $keyword) {
                        $indexes[$index] = $rowindex;
                        break 2;
                    }
                }
            }
        }

        foreach (array_slice($rows, 1) as $index => $value) {
            if (empty(array_filter($value))) {
                continue;
            }
            $transaction = [
                'account' => $value[$indexes['account']],
                'iban' => $value[$indexes['iban']] ?? null,
                'date' => ($value[$indexes['date']]) ?? null,
                'payer' => $value[$indexes['payer']] ?? null,
                'payeriban' => $value[$indexes['payeriban']] ?? null,
                'amount' => $value[$indexes['amount']] ?? null,
                'currency' => $value[$indexes['currency']] ?? null,
                'description' => $value[$indexes['description']] ?? null,
            ];

            $out[] = $transaction;
        }

        return $out;
    }


}
