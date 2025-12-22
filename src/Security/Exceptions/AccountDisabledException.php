<?php

namespace App\Security\Exceptions;

use Symfony\Component\Security\Core\Exception\AccountStatusException;

class AccountDisabledException extends AccountStatusException
{
}
