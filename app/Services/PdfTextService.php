<?php

namespace App\Services;

use Smalot\PdfParser\Parser;

class PdfTextService
{
    public function extract($file): string
    {
        $parser = new Parser();

        $pdf = $parser->parseFile($file->getRealPath());

        $text = $pdf->getText();


        // cleanup
//        $text = preg_replace("/\s{2,}/", " ", $text);
//        $text = preg_replace("/\n{3,}/", "\n\n", $text);

        return trim($text);
    }
}
