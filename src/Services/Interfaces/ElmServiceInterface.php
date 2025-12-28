<?php

namespace App\Services\Interfaces;

use App\Entity\ElmReport;

interface ElmServiceInterface
{
    public function send(ElmReport $report): void;
    public function checkProgress(ElmReport $report): void;
}
