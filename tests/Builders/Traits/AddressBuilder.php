<?php

namespace App\Tests\Builders\Traits;

trait AddressBuilder
{
    private function fillAddress(): void
    {
        $this->entity->setAddressLines('123 Fake Street');
        $this->entity->setCountryCode('CH');
        $this->entity->setCity('Allschwil');
        $this->entity->setPostalCode('4123');
    }
}
