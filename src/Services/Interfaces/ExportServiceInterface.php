<?php

namespace App\Services\Interfaces;

use PhpOffice\PhpSpreadsheet\Writer\IWriter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

interface ExportServiceInterface
{
    public const string MIME_EXCEL = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
    /**
     * @param string[]   $header
     * @param string[][] $content
     * @param int[]      $proposedWidths
     */
    public function exportAsExcel(string $filename, array $header, array $content, array $proposedWidths): Response;
    public function createExcelResponse(IWriter $writer, string $filename): StreamedResponse;
}
