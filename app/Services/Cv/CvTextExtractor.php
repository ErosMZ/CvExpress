<?php

namespace App\Services\Cv;

use Illuminate\Support\Facades\Storage;

class CvTextExtractor
{
    private const MIN_TEXT_LENGTH = 150;

    public function extract(string $storagePath): string
    {
        $absolutePath = Storage::disk('local')->path($storagePath);

        $text = $this->extractWithParser($absolutePath);

        if (strlen(trim($text)) < self::MIN_TEXT_LENGTH) {
            $text = $this->extractWithOcr($absolutePath);
        }

        return $this->cleanText($text);
    }

    private function extractWithParser(string $path): string
    {
        try {
            $parser = new \Smalot\PdfParser\Parser();
            $pdf    = $parser->parseFile($path);
            return $pdf->getText();
        } catch (\Throwable $e) {
            return '';
        }
    }

    private function extractWithOcr(string $path): string
    {
        // OCR requires: apt-get install tesseract-ocr poppler-utils
        // and composer require spatie/pdf-to-image thiagoalessio/tesseract_ocr
        // Only activate if those packages are installed.
        if (!class_exists(\Spatie\PdfToImage\Pdf::class)) {
            return '';
        }

        try {
            $pdf       = new \Spatie\PdfToImage\Pdf($path);
            $pageCount = $pdf->pageCount();
            $texts     = [];

            for ($i = 1; $i <= min($pageCount, 5); $i++) {
                $imgPath = sys_get_temp_dir() . '/cv_ocr_page_' . $i . '_' . uniqid() . '.png';
                $pdf->selectPage($i)->save($imgPath);

                $ocr   = new \thiagoalessio\TesseractOCR\TesseractOCR($imgPath);
                $texts[] = $ocr->lang('spa', 'eng')->run();

                @unlink($imgPath);
            }

            return implode("\n", $texts);
        } catch (\Throwable $e) {
            return '';
        }
    }

    private function cleanText(string $text): string
    {
        // Normaliza saltos de línea, quita caracteres de control, colapsa espacios excesivos
        $text = preg_replace('/\r\n|\r/', "\n", $text);
        $text = preg_replace('/[^\S\n]+/', ' ', $text);
        $text = preg_replace('/\n{3,}/', "\n\n", $text);
        return trim($text);
    }
}
