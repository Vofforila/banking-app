<?php

namespace App\Services;

class CsvTextService
{
    public function extractFromCsv($file): string
    {
        $out = [];

        while (($data = fgetcsv($file->getRealPath(), 1000, ',')) !== false) {
            $out[] = $data;

        }

        dd($out);

        return $out;
    }
}
