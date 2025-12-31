<?php

namespace App\Services\Elm;

use App\Entity\ElmReport;
use App\Entity\Probe;
use App\Enum\LaboratoryFunction;
use App\Services\Elm\ApiBuilder\Dto\AddressDto;
use App\Services\Elm\ApiBuilder\Dto\PersonDto;
use App\Services\Elm\ApiBuilder\Dto\ResourceReference;
use App\Services\Elm\ApiBuilder\Formatter;
use App\Services\Elm\ApiBuilder\PredefinedCodes;

readonly class ApiBuilder
{
    public function __construct(private Formatter $formatter, private string $organizationId, private string $organizationGLN, private string $organizationName)
    {
    }

    private function createPatientResource(Probe $probe): array
    {
        $address = new AddressDto();
        $probe->writePatientAddressTo($address);

        $person = new PersonDto();
        $probe->writePatientPersonTo($person);

        $reference = new ResourceReference('Patient', $probe->getPatient()->getId());
        $patientResource = [
            "fullUrl" => $reference->fullUrl(),
            "resource" => [
                "resourceType" => $reference->type(),
                "id" => $reference->id(),
                "name" => [$this->formatter->name($person)],
                "gender" => $this->formatter->gender($probe->getPatientGender()),
                "birthDate" => $this->formatter->date($probe->getPatientBirthDate()),
                "address" => [$this->formatter->address($address, 'home')]
            ]
        ];

        if ($probe->getPatientAhvNumber()) {
            $patientResource['resource']["identifier"] = [
                [
                    "system" => "urn:oid:2.16.756.5.32", // system: AHV-Number
                    "value" => $probe->getPatientAhvNumber()
                ]
            ];
        }

        return $this->formatter->normalizeNullableArray($patientResource);
    }

    private function createSpecimenResource(Probe $probe, ElmReport $elmReport, array $patientResource): array
    {
        $reference = new ResourceReference('Specimen', $probe->getId());

        $specimenResource = [
            "fullUrl" => $reference->fullUrl(),
            "resource" => [
                "resourceType" => $reference->type(),
                "id" => $reference->id(),
                "subject" => $this->formatter->reference($patientResource["resource"]),
                "collection" => [
                    // here, could also send datetime; but not known from orderer (yet)
                    "collectedDateTime" => $this->formatter->date($probe->getSpecimenCollectionDate())
                ]
            ]
        ];

        if ($elmReport->getSpecimen()) {
            if ($elmReport->getLeadingCode()->getSpecimen() === $elmReport->getSpecimen()) {
                // specimen already defined by the leading code, hence do not send it
                // if we send it anyways, leads to a warning by the API
                // TODO: ask BAG to instead remove warning from API, and validate themselves. this seems unsafe; what it the leading code is wrongly configured?
            } else {
                $specimenResource['resource']["type"] = [
                    "coding" => [$this->formatter->codedIdentifier($elmReport->getSpecimen())]
                ];
            }
        }

        return $specimenResource;
    }

    private function createNENTOrganizationResource(): array
    {
        $reference = new ResourceReference('Organization', $this->organizationId);

        return [
            "fullUrl" => $reference->fullUrl(),
            "resource" => [
                "resourceType" => $reference->type(),
                "id" => $reference->id(),
                "identifier" => [
                    [
                        "system" => "urn:oid:2.51.1.3", // system: GLN-Number
                        "value" => $this->organizationGLN
                    ]
                ],
                "name" => $this->organizationName
            ]
        ];
    }

    private function createOrganizationOrdererResource(Probe $probe): array
    {
        $address = new AddressDto();
        $probe->writeOrdererOrgAddressTo($address);

        $reference = new ResourceReference('Organization', $probe->getOrdererOrg()->getId());

        $organizationOrdererResource = [
            "fullUrl" => $reference->fullUrl(),
            "resource" => [
                "resourceType" => $reference->type(),
                "id" => $reference->id(),
                "name" => $probe->getOrdererOrgName(),
                "address" => [$this->formatter->address($address)]
            ]
        ];

        return $this->formatter->normalizeNullableArray($organizationOrdererResource);
    }

    private function createPractitionerOrdererResource(Probe $probe): array
    {
        $address = new AddressDto();
        $probe->writeOrdererPracAddressTo($address);

        $person = new PersonDto();
        $probe->writeOrdererPracPersonTo($person);

        $reference = new ResourceReference('Practitioner', $probe->getOrdererPrac()->getId());
        $organizationOrdererResource = [
            "fullUrl" => $reference->fullUrl(),
            "resource" => [
                "resourceType" => $reference->type(),
                "id" => $reference->id(),
                "name" => $this->formatter->name($person),
                "address" => [$this->formatter->address($address)]
            ]
        ];

        return $this->formatter->normalizeNullableArray($organizationOrdererResource);
    }

    private function createReferencePractitionerRoleResource(Probe $probe, array $orderOrganizationResource): array
    {
        $reference = new ResourceReference('PractitionerRole', $probe->getOrdererOrg()->getId());

        return [
            "fullUrl" => $reference->fullUrl(),
            "resource" => [
                "resourceType" => $reference->type(),
                "id" => $reference->id(),
                "organization" => $this->formatter->reference($orderOrganizationResource["resource"]),
            ]
        ];
    }

    private function createPrimaryPractitionerRoleResource(Probe $probe, array $orderPractitionerResource): array
    {
        $reference = new ResourceReference('PractitionerRole', $probe->getOrdererOrg()->getId());

        return [
            "fullUrl" => $reference->fullUrl(),
            "resource" => [
                "resourceType" => $reference->type(),
                "id" => $reference->id(),
                "practitioner" => $this->formatter->reference($orderPractitionerResource["resource"]),
            ]
        ];
    }

    private function createObservationResource(ElmReport $elmReport, array $patientResource, array $organizationResource, array $specimenResource): array
    {
        $reference = new ResourceReference('Observation', $elmReport->getId());

        $observationResource = [
            "fullUrl" => $reference->fullUrl(),
            "resource" => [
                "resourceType" => $reference->type(),
                "id" => $reference->id(),
                "status" => "final", // predefined value
                "category" => [ // predefined value
                    ["coding" => [PredefinedCodes::observationCategoryLaboratory()]]
                ],
                "code" => [
                    "coding" => [$this->formatter->codedIdentifier($elmReport->getLeadingCode())]
                ],
                "subject" => $this->formatter->reference($patientResource["resource"]),
                "effectiveDateTime" => $this->formatter->datetime($elmReport->getEffectiveAt()),
                "performer" => [$this->formatter->reference($organizationResource["resource"])],
                "specimen" => $this->formatter->reference($specimenResource["resource"]),
            ]
        ];

        if ($elmReport->getInterpretation()) {
            $observationResource['resource']['interpretation'] = [[
                "coding" => [$this->formatter->codedInterpretation($elmReport->getInterpretation())]
            ]];
        }

        if ($elmReport->getOrganism()) {
            $observationResource['resource']['valueCodeableConcept'] = [
                "coding" => [$this->formatter->codedIdentifier($elmReport->getOrganism())]
            ];
        }

        if ($elmReport->getOrganismText()) {
            // TODO: test value string submission with LOINC 56475-7
            // maybe also need other values here, then
            $observationResource['resource']['valueString'] = $elmReport->getOrganismText();
        }

        return $observationResource;
    }

    private function createDiagnosticReportResource(ElmReport $elmReport, array $patientResource, array $organizationResource, array $specimenResource, array $observationResource, array $serviceRequestResource, array $rawCompositionResource): array
    {
        $reference = new ResourceReference('DiagnosticReport', $elmReport->getId());

        return [
            "fullUrl" => $reference->fullUrl(),
            "resource" => [
                "resourceType" => $reference->type(),
                "id" => $reference->id(),
                "extension" => [
                    [
                        "url" => "http://hl7.org/fhir/5.0/StructureDefinition/extension-DiagnosticReport.composition",
                        "valueReference" => $this->formatter->reference($rawCompositionResource["resource"])
                    ]
                ],
                "identifier" => [
                    [
                        "system" => "urn:ietf:rfc:3986",
                        "value" => "urn:uuid:" . $elmReport->getDiagnosticReportId()
                    ]
                ],
                "basedOn" => [$this->formatter->reference($serviceRequestResource["resource"])],
                "status" => "final", // predefined value
                "code" => [
                    "coding" => [PredefinedCodes::loincLaboratoryReport()]
                ],
                "subject" => $this->formatter->reference($patientResource["resource"]),
                "performer" => [$this->formatter->reference($organizationResource["resource"])],
                "specimen" => [$this->formatter->reference($specimenResource["resource"])],
                "result" => [$this->formatter->reference($observationResource["resource"])],
            ]
        ];
    }

    private function createServiceRequestResource(Probe $probe, array $patientResource, array $specimenResource, array $ordererPractitionerRoleResource, array $observationResource): array
    {
        $reference = new ResourceReference('ServiceRequest', $probe->getId());

        $serviceRequestResource = [
            "fullUrl" => $reference->fullUrl(),
            "resource" => [
                "resourceType" => $reference->type(),
                "id" => $reference->id(),
                "identifier" => [
                    [
                        "value" => $probe->getIdentifier()
                    ]
                ],
                "status" => "completed", // predefined value
                "intent" => "order", // predefined value
                "code" => $observationResource['resource']['code'], // for now equal to observation value. could also store laboratory--specific code here (see 4.8 CH ELM ServiceRequest)
                "subject" => $this->formatter->reference($patientResource["resource"]),
                "requester" => $this->formatter->reference($ordererPractitionerRoleResource["resource"]),
                "specimen" => [$this->formatter->reference($specimenResource["resource"])]
            ]
        ];

        if ($probe->getLaboratoryFunction() === LaboratoryFunction::REFERENCE) {
            $serviceRequestResource['resource']['requisition'] = ["value" => $probe->getRequisitionIdentifier()];
        }

        return $serviceRequestResource;
    }

    private function createRawCompositionResource(ElmReport $elmReport): array
    {
        $reference = new ResourceReference('Composition', $elmReport->getId());

        return [
            "fullUrl" => $reference->fullUrl(),
            "resource" => [
                "resourceType" => $reference->type(),
                "id" => $reference->id(),
            ]
        ];
    }

    private function createFullCompositionResource(array $rawCompositionResource, array $diagnosticReportResource, array $patientResource, array $organizationResource, array $observationResource): array
    {
        $resource = $rawCompositionResource["resource"];
        $resource['language'] = 'de-CH';
        $resource['identifier'] = $diagnosticReportResource['resource']['identifier'][0];
        $resource['status'] = 'final'; // predefined value
        $resource['type'] = [
            "coding" => [
                PredefinedCodes::snomedLaboratoryReport(),
                PredefinedCodes::loincLaboratoryReport()
            ],
        ];
        $resource['subject'] = $this->formatter->reference($patientResource["resource"]);
        $resource['date'] = $this->formatter->datetime(new \DateTimeImmutable());
        $resource['author'] = [$this->formatter->reference($organizationResource["resource"])];
        $resource['title'] = "Laborbericht vom " . date('d.m.Y');
        $resource['section'] = [
            [
                "title" => "Ergebnisse",
                "code" => [
                    // reference to microbiological studies here as  4.7 composition.section.code https://elm.wiki.bagapps.ch/Dokumente/FOPH_CH-ELM%20getting%20started.pdf
                    "coding" => [PredefinedCodes::loincMicrobiologyStudies()]
                ],
                "entry" => [$this->formatter->reference($observationResource["resource"])],
            ]
        ];

        $rawCompositionResource['resource'] = $resource;

        return $rawCompositionResource;
    }

    private function createBundleResource(ElmReport $elmReport, array $compositionResource, array $entries): array
    {
        $reference = new ResourceReference('Bundle', $elmReport->getId());

        return [
            "resourceType" => $reference->type(),
            "id" => $reference->id(),
            "identifier" => $compositionResource['resource']['identifier'],
            "type" => "document",
            "timestamp" => $this->formatter->datetime(new \DateTimeImmutable()),
            "entry" => $entries
        ];
    }

    private function createDocumentReferenceResource(ElmReport $elmReport, array $bundleResource): array
    {
        $reference = new ResourceReference('DocumentReference', $elmReport->getId());

        return [
            "resourceType" => $reference->type(),
            "id" => $reference->id(),
            "contained" => [$bundleResource],
            "identifier" => [$bundleResource['identifier']],
            "status" => "current",
            "content" => [
                [
                    "attachment" => [
                        "language" => "de-CH",
                        "url" => "#" . $bundleResource['id']
                    ]
                ]
            ]
        ];
    }

    private function createPractitionerRole(Probe $probe): array
    {
        $createOrganizationOrdererResources = function (Probe $probe) {
            $organizationOrdererResource = $this->createOrganizationOrdererResource($probe);
            $ordererPractitionerRoleResource = $this->createReferencePractitionerRoleResource($probe, $organizationOrdererResource);

            return [$ordererPractitionerRoleResource, $organizationOrdererResource];
        };

        $createPractitionerOrdererResources = function (Probe $probe) {
            $practitionerOrdererResource = $this->createPractitionerOrdererResource($probe);
            $ordererPractitionerRoleResource = $this->createPrimaryPractitionerRoleResource($probe, $practitionerOrdererResource);

            return [$ordererPractitionerRoleResource, $practitionerOrdererResource];
        };

        return match ($probe->getLaboratoryFunction()) {
            LaboratoryFunction::REFERENCE => $createOrganizationOrdererResources($probe),
            LaboratoryFunction::PRIMARY => $createPractitionerOrdererResources($probe),
        };
    }

    public function build(Probe $probe, ElmReport $elmReport): array
    {
        $patientResource = $this->createPatientResource($probe);
        $specimenResource = $this->createSpecimenResource($probe, $elmReport, $patientResource);
        $organizationResource = $this->createNENTOrganizationResource();

        [$ordererPractitionerRoleResource, $ordererResource] = $this->createPractitionerRole($probe);
        $observationResource = $this->createObservationResource($elmReport, $patientResource, $organizationResource, $specimenResource);
        $serviceRequestResource = $this->createServiceRequestResource($probe, $patientResource, $specimenResource, $ordererPractitionerRoleResource, $observationResource);
        $rawCompositionResource = $this->createRawCompositionResource($elmReport);
        $diagnosticReportResource = $this->createDiagnosticReportResource($elmReport, $patientResource, $organizationResource, $specimenResource, $observationResource, $serviceRequestResource, $rawCompositionResource);
        $compositionResource = $this->createFullCompositionResource($rawCompositionResource, $diagnosticReportResource, $patientResource, $organizationResource, $observationResource);

        $entries = [$compositionResource, $diagnosticReportResource, $patientResource, $observationResource, $specimenResource, $serviceRequestResource, $ordererPractitionerRoleResource, $organizationResource, $ordererResource];
        $bundleResource = $this->createBundleResource($elmReport, $compositionResource, $entries);

        return $this->createDocumentReferenceResource($elmReport, $bundleResource);
    }
}
