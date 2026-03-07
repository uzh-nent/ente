<?php

namespace App\Services\Interfaces;

use Symfony\Component\HttpFoundation\Response;

interface ExportServiceInterface
{
    public const string MIME_EXCEL = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
    /**
     * @param string[]   $header
     * @param string[][] $content
     */
    public function exportAsExcel(string $filename, array $header, array $content): Response;
}
