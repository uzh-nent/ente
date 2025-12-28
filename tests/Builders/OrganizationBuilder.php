<?php

namespace App\Tests\Builders;

use App\Entity\Organization;
use App\Tests\Builders\Traits\AddressBuilder;

/**
 * @extends AbstractBuilder<Organization>
 */
class OrganizationBuilder extends AbstractBuilder
{
    use AddressBuilder;

    public function __construct()
    {
        $organization = new Organization();
        $organization->setName('Test Organization');

        parent::__construct($organization);

        $this->fillAddress();
    }
}
