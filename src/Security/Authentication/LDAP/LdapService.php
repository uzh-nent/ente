<?php

declare(strict_types=1);

namespace App\Security\Authentication\LDAP;

use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Ldap\Entry;
use Symfony\Component\Ldap\Exception\LdapException;
use Symfony\Component\Ldap\LdapInterface;

readonly class LdapService implements LdapServiceInterface
{
    private const DC = 'DC=d,DC=uzh,DC=ch';
    private const DN_USERS = 'OU=Users,OU=Users UZH,' . self::DC;

    public function __construct(
        private LdapInterface $ldap,
        #[Autowire(env: 'LDAP_USERNAME')]
        private string $username,
        #[Autowire(env: 'LDAP_PASSWORD')]
        private string $password
    ) {
    }

    public function loadMemberOf(string $shortname): ?array
    {
        $entry = $this->loadUserEntryByShortname($shortname);
        if (!$entry) {
            return null;
        }

        return $this->getArrayValueAttribute($entry, 'memberOf');
    }

    private function loadUserEntryByShortname(string $shortname): ?Entry
    {
        $shortname = $this->ldap->escape($shortname, '', LdapInterface::ESCAPE_FILTER);
        return $this->loadEntry(self::DN_USERS, 'sAMAccountName=' . $shortname);
    }

    private function loadEntry(string $dn, string $query): ?Entry
    {
        $this->ldap->bind($this->username, $this->password);
        $search = $this->ldap->query($dn, $query);

        try {
            $entries = $search->execute();

            if (count($entries) !== 1) {
                return null;
            }

            return $entries[0];
        } catch (LdapException $exception) {
            if (!str_starts_with($exception->getMessage(), 'LDAP error was [32] No such object.')) {
                return null;
            }

            throw $exception;
        }
    }

    /**
     * @return string[]
     */
    private static function getArrayValueAttribute(Entry $entry, string $attributeName): array
    {
        $attribute = $entry->getAttribute($attributeName);
        if (!is_array($attribute)) {
            return [];
        }

        return $attribute;
    }
}
