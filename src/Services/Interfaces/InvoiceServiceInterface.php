<?php

namespace App\Services\Interfaces;

use Symfony\Component\HttpFoundation\Response;

interface InvoiceServiceInterface
{
    public function invoicePatients(\DateTimeImmutable $from, \DateTimeImmutable $to): Response;
}
