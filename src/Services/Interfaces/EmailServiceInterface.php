<?php

namespace App\Services\Interfaces;

use App\Entity\ReportEmail;

interface EmailServiceInterface
{
    public function sendReportEmail(ReportEmail $email, ?string &$error = null): bool;
}
