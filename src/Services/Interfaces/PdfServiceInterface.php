<?php

namespace App\Services\Interfaces;

use App\Entity\Probe;
use App\Entity\Report;

interface PdfServiceInterface
{
    public function generateWorksheet(Probe $probe): string;
    public function generateReport(Report $report): string;
}
