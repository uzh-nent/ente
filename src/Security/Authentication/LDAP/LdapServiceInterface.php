<?php

namespace App\Security\Authentication\LDAP;

interface LdapServiceInterface
{
    /**
     * @return null|string[]
     */
    public function loadMemberOf(string $shortname): ?array;
}
