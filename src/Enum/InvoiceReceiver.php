<?php

namespace App\Enum;

enum InvoiceReceiver : string
{
    case ORDERER = 'ORDERER';
    case PATIENT = 'PATIENT';
}
