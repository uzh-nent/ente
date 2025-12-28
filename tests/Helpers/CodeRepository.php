<?php

namespace App\Tests\Helpers;

use App\Entity\LeadingCode;
use App\Entity\Organism;
use App\Entity\Specimen;
use App\Enum\CodeSystem;

class CodeRepository
{
    public static function getLeadingCodeSalSerovar(): LeadingCode
    {
        $leadingCode = new LeadingCode();
        $leadingCode->setCode('65756-9');
        $leadingCode->setDisplayName('Salmonella sp serovar [Type] in Isolate');
        $leadingCode->setSystem(CodeSystem::LOINC);

        return $leadingCode;
    }

    public static function getSpecimenMicrobial(): Specimen
    {
        $specimen = new Specimen();
        $specimen->setCode('119303007');
        $specimen->setDisplayName('Microbial isolate specimen');
        $specimen->setSystem(CodeSystem::SNOMED);

        return $specimen;
    }

    public static function getOrganismSalComplete(): Organism
    {
        $organism = new Organism();
        $organism->setCode('302702005');
        $organism->setDisplayName('Salmonella 1,13,23:g,m,s,t:1,5 (organism)');
        $organism->setSystem(CodeSystem::SNOMED);

        return $organism;
    }
}
