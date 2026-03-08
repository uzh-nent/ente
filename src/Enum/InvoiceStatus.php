<?php

namespace App\Enum;

enum InvoiceStatus : string
{
    case INVOICED = 'INVOICED';
    case IGNORED = 'IGNORED';
}
