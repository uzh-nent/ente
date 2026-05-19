<?php

namespace App\Services\Interfaces;

use App\Entity\ReportEmail;

interface EmailServiceInterface
{
    public function send(ReportEmail $email, ?string &$error = null): bool;
}
