<?php

declare(strict_types=1);

namespace App\Security\Authentication\LDAP;

use App\Entity\User;

class LdapServiceMock implements LdapServiceInterface
{
    public function loadMemberOf(string $shortname): ?array
    {
        if ($shortname === User::SUPER_SHORTNAME) {
            return ['CN=FSAFETY_O_NENT,OU=Groups,OU=FSAFETY,OU=UZH,DC=d,DC=uzh,DC=ch'];
        }

        return null;
    }
}
