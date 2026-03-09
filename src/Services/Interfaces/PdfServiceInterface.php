<?php

namespace App\Services\Interfaces;

use App\Entity\Invoice;
use App\Entity\Probe;
use App\Entity\Report;

interface PdfServiceInterface
{
    public function generateWorksheet(Probe $probe): string;
    public function generateReport(Report $report): string;

    /**
     * @param Invoice[] $invoices
     */
    public function generateReceipts(array $invoices, string $generatedByAbbreviation): string;
}
