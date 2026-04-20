<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AiTransactionParser
{
    public function parse(string $csv): array
    {
        $prompt = $this->buildPrompt($csv);

        try {


            $chunks = array_chunk(explode("\n", $csv), 100);

            $results = [];

            foreach ($chunks as $chunk) {
                $text = implode("\n", $chunk);

                $response = Http::timeout(180)
                    ->post('http://localhost:11434/api/generate', [
                        'model' => 'llama3.1:latest',
                        'prompt' => $prompt,
                        'stream' => false,
                        'format' => 'json',
                        'options' => [
                            'temperature' => 0.0,
                            'top_p' => 0.9,
                            'num_ctx' => 16384,
                        ],
                    ]);

                $data = json_decode($response->body(), true);

                if (is_array($data)) {
                    $results = array_merge($results, $data);
                }
            }

            return $results;

        } catch (Exception $e) {
            Log::error('AiTransactionParser exception', ['message' => $e->getMessage()]);

            return [];
        }
    }

    private function buildPrompt(string $text): string
    {
        return <<<PROMPT
You are a precise bank statement transaction extractor.

Extract EVERY transaction from the following csv. The text contains bank statement data with inconsistent formatting. Focus on extracting the actual transaction entries.

Return ONLY a valid JSON array. No explanations, no markdown, no extra text.

Some csv will have the first or last line showing the data on each field in the file.

Output:
[
  {
    "iban": "string - account IBAN"
    "company name": "string - merchant name",
    "date": "YYYY-MM-DD",
    "amount": number, // negative for expenses, positive for deposits
    "currency type": "string - currency type like RON, EUR, USD, ...",
    "description": "string - full description if available, otherwise nothing"
  }
]

Rules:
- For dates like "31. Mar", convert to "2026-03-31".
- For dates like "16. Mar", convert to "2026-03-16".
- For dates like "17 1. Mar", convert to "2026-03-01".
- Amount must be a real number (not string).
- "amount": number (use dot as decimal separator, e.g. -72.37, never comma)
- If no transactions, return [].
- Be very strict about parsing - if you can't extract a valid transaction, skip it.
- The text contains multiple entries with different formats, so be comprehensive.
- Always output amount as a float with dot decimal separator

example:
"Current Account GEORGE","RO02RNCB0188167102130001","31.03.2026","Bolt Services RO S.R.L","","","","","-72.37","RON","Tranzactie comerciant - Tranz: Nr card 449391XXXXXX0007, token XX3115, device DNITHE46252313327341, Ref 901140604315, Suma platita 72.37 RON. Comision: 0 RON. Locatie: 99999999 RO Bolt Services RO S.R.L . Bucuresti. Data_Ora: 30-03-2026 11:57:39",""

[
  {
    "iban": "RO02RNCB0188167102130001"
    "company name": "Bolt Services RO S.R.L",
    "date": "202-03-31",
    "amount": -72.37
    "currency type": "RON"
    "description": "Tranzactie comerciant - Tranz: Nr card 449391XXXXXX0007, token XX3115, device DNITHE46252313327341, Ref 901140604315, Suma platita 72.37 RON. Comision: 0 RON. Locatie: 99999999 RO Bolt Services RO S.R.L . Bucuresti. Data_Ora: 30-03-2026 11:57:39"
  }
]



TEXT:
"""
{$text}
"""

JSON:
PROMPT;
    }


}
