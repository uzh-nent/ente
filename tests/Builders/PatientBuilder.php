<?php

namespace App\Tests\Builders;

use App\Entity\Organization;
use App\Entity\Patient;
use App\Enum\AdministrativeGender;
use App\Tests\Builders\Traits\AddressBuilder;

/**
 * @extends AbstractBuilder<Patient>
 */
class PatientBuilder extends AbstractBuilder
{
    use AddressBuilder;

    public function __construct()
    {
        $patient = new Patient();
        $patient->setGivenName('Florian');
        $patient->setFamilyName('Moser');

        $patient->setGender(AdministrativeGender::MALE);
        $patient->setBirthDate(new \DateTimeImmutable('1980-01-01'));

        parent::__construct($patient);

        $this->fillAddress();
    }
}
