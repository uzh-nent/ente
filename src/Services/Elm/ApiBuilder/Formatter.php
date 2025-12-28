<?php

namespace App\Services\Elm\ApiBuilder;

use App\Entity\LeadingCode;
use App\Entity\Organism;
use App\Entity\Specimen;
use App\Enum\AdministrativeGender;
use App\Enum\CodeSystem;
use App\Enum\Interpretation;
use App\Helper\ArrayHelper;
use App\Services\Elm\ApiBuilder\Dto\AddressDto;
use App\Services\Elm\ApiBuilder\Dto\PersonDto;

readonly class Formatter
{
    public function address(AddressDto $entity, ?string $use = null): array
    {
        $address = self::normalizeNullableArray([
            "use" => $use,
            "city" => $entity->getCity(),
            "postalCode" => $entity->getPostalCode()
        ]);

        if ($entity->getAddressLines()) {
            // aufdermauer mail 08.12.
            // "Da die BAG-Zielsysteme nicht mit mehreren Adresszeilen umgehen können (...)
            // müssen diese (adresszeilen) concateniert und innerhalb eines kombinierten Strings als einzelnes line-elelment übermittelt werden."
            $address['line'] = [$entity->getAddressLines()];
        }

        if ($entity->getCountryCode()) {
            // https://elm.wiki.bagapps.ch/Dokumente/CH-ELM_appendix_2_FHIR_Document_Breakdown.pdf v3.0
            // "when providing the country code, both the country-
            //element and _country-extension must be present"
            $address["country"] = $entity->getCountryCode();
            $address["_country"] = [
                "extension" => [
                    [
                        "url" => "http://hl7.org/fhir/StructureDefinition/iso21090-SC-coding",
                        "valueCoding" => [
                            "system" => "urn:iso:std:iso:3166",
                            "code" => $entity->getCountryCode()
                        ]
                    ]
                ]
            ];
        }

        return $address;
    }

    public function name(PersonDto $entity): array
    {
        return self::normalizeNullableArray([
            "family" => $entity->getFamilyName(),
            "given" => [$entity->getGivenName()],
        ]);
    }

    public function date(?\DateTimeImmutable $date): ?string
    {
        return $date?->format('Y-m-d');
    }

    public function reference(array $resource): array
    {
        return [
            "reference" => $resource["resourceType"] . "/" . $resource["id"]
        ];
    }

    public function normalizeNullableArray(array $array): array
    {
        /** empty entries must not be delivered to the API TODO cite */
        return ArrayHelper::stripNullEntries($array);
    }

    public function gender(?AdministrativeGender $gender): ?string
    {
        // https://fhir.ch/ig/ch-elm/1.13.0/StructureDefinition-ChElmPatientInitials-definitions.html
        // ch-pat-2: gender 'unknown' is currently not used in Switzerland in eCH and the EPR (gender.empty() or gender!='unknown')
        return match ($gender) {
            AdministrativeGender::MALE => "male",
            AdministrativeGender::FEMALE => "female",
            AdministrativeGender::OTHER => "other",
            default => null // "unknown",
        };
    }

    public function codedIdentifier(Specimen|Organism|LeadingCode $codedIdentifier): array
    {
        return [
            "system" => $this->codeSystem($codedIdentifier->getSystem()),
            "code" => $codedIdentifier->getCode(),
            "display" => $codedIdentifier->getDisplayName(),
        ];
    }

    private function codeSystem(?CodeSystem $system): string
    {
        return match ($system) {
            CodeSystem::SNOMED => "http://snomed.info/sct",
            CodeSystem::LOINC => "http://loinc.org",
            default => null
        };
    }

    public function codedInterpretation(?Interpretation $interpretation): array
    {
        [$code, $display] = match ($interpretation) {
            Interpretation::POS => ["POS", "Positive"],
            Interpretation::NEG => ["NEG", "Negative"],
            default => [null, null]
        };

        return [
            "system" => "http://terminology.hl7.org/CodeSystem/v3-ObservationInterpretation",
            "code" => $code,
            "display" => $display
        ];
    }

    public function datetime(?\DateTimeImmutable $dateTimeImmutable): ?string
    {
        return $dateTimeImmutable?->format('c');
    }
}
