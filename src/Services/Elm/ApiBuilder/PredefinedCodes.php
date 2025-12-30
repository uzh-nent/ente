<?php

namespace App\Services\Elm\ApiBuilder;

readonly class PredefinedCodes
{
    public static function observationCategoryLaboratory(): array
    {
        return [
            "system" => "http://terminology.hl7.org/CodeSystem/observation-category",
            "code" => "laboratory",
            "display" => "Laboratory"
        ];
    }

    public static function dataAbsentReason(): array
    {
        return [
            "system" => "http://terminology.hl7.org/CodeSystem/data-absent-reason",
            "code" => "not-applicable"
        ];
    }

    public static function loincLaboratoryReport(): array
    {
        return [
            "system" => "http://loinc.org",
            "code" => "11502-2",
            "display" => "Laboratory report"
        ];
    }

    public static function snomedLaboratoryReport(): array
    {
        return [
            "system" => "http://snomed.info/sct",
            "version" => "http://snomed.info/sct/2011000195101",
            "code" => "4241000179101",
            "display" => "Laborbericht"
        ];
    }

    public static function loincMicrobiologyStudies(): array
    {
        return [
            "system" => "http://loinc.org",
            "code" => "18725-2",
            "display" => "Microbiology studies (set)"
        ];
    }
}
