<?php

namespace App\Tests\Builders;

use App\Entity\Probe;
use App\Entity\Specimen;
use App\Entity\User;
use App\Enum\AnalysisType;
use App\Enum\LaboratoryFunction;
use App\Enum\Pathogen;
use App\Enum\SpecimenSource;

/**
 * @extends AbstractBuilder<User>
 */
class UserBuilder extends AbstractBuilder
{
    public function __construct()
    {
        $user = new User();
        $user->setIsEnabled(true);
        $user->setMedicalValidation(true);
        $user->setName("Florian Moser");
        $user->setShortname("shorty");
        $user->setAbbreviation("FM");

        parent::__construct($user);
    }
}
