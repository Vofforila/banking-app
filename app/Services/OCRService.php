<?php

namespace App\Services;

use Exception;
use thiagoalessio\TesseractOCR\TesseractOCR;

class OCRService
{
    public function extractText($file): string
    {
        $pages = [];
        $dir = null;

        try {
            $jobId = uniqid('ocr_');
            $dir = storage_path("app/ocr/{$jobId}");
            mkdir($dir, 0777, true);

            $inputPath = $file->getRealPath();
            $outputPattern = $dir . '/page-%03d.png';

            exec(
                '"C:\\Program Files\\ImageMagick-7.1.2-Q16-HDRI\\magick.exe"'
                . " -density 300 \"$inputPath\" -normalize -level 15%,85% -background white -alpha remove -alpha off -colorspace Gray -depth 8 -brightness-contrast 8x20 -sharpen 0x2 -morphology Dilate Octagon:1 \"$outputPattern\" 2>&1",
                $out,
                $code
            );

            if ($code !== 0) {
                throw new Exception('ImageMagick failed: ' . implode("\n", $out));
            }

            $pages = glob($dir . '/*.png') ?: [];
            sort($pages); // critical for page order

            $fullText = '';

            foreach ($pages as $page) {
                $pageText = (new TesseractOCR($page))
                    ->executable('C:\\Program Files\\Tesseract-OCR\\tesseract.exe')
                    ->lang('eng', 'ron')
                    ->psm(6)
                    ->oem(1)
                    ->run();

                $fullText .= $pageText . "\n\n";
            }

            dd($fullText);

            return trim($fullText);

        } catch (Exception $e) {
            // Log error
            throw $e;
        } finally {
            // Cleanup
            foreach ($pages as $page) {
                @unlink($page);
            }
            if ($dir && is_dir($dir)) {
                @rmdir($dir);
            }
        }
    }
}
