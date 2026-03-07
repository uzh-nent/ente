<?php

declare(strict_types=1);

namespace App\Api\Provider;

use ApiPlatform\Doctrine\Orm\Paginator;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Entity\ElmReport;
use App\Entity\Observation;
use App\Entity\Probe;
use App\Enum\AnalysisType;
use App\Enum\ElmApiStatus;
use App\Enum\MethodType;
use App\Enum\Pathogen;
use App\Enum\SpecimenSource;
use App\Helper\DoctrineHelper;
use App\Services\Interfaces\ElmServiceInterface;
use App\Services\Interfaces\ExportServiceInterface;
use Doctrine\Persistence\ManagerRegistry;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\HeaderBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Translation\TranslatableInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @implements ProviderInterface<Probe>
 */
readonly class ProbeProvider implements ProviderInterface
{
    public function __construct(
        /** @var ProviderInterface<Probe> */
        #[Autowire(service: 'api_platform.doctrine.orm.state.item_provider')]
        private ProviderInterface $itemProvider,
        /** @var ProviderInterface<Probe> */
        #[Autowire(service: 'api_platform.doctrine.orm.state.collection_provider')]
        private ProviderInterface $collectionProvider,
        private ExportServiceInterface $exportService,
        private TranslatorInterface $translator
    ) {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        if (!($operation instanceof GetCollection)) {
            return $this->itemProvider->provide($operation, $uriVariables, $context);
        }

        $probes = $this->collectionProvider->provide($operation, $uriVariables, $context);

        /** @var ?Request $request */
        $request = $context['request'] ?? null;
        if ($request?->headers->get('Accept') === ExportServiceInterface::MIME_EXCEL) {
            if ($probes instanceof Paginator) {
                $probes = iterator_to_array($probes->getIterator());
            }
            return $this->exportAsExcel($probes);
        }

        return $probes;
    }

    /**
     * @param Probe[] $probes
     * @return Response
     */
    private function exportAsExcel(array $probes): Response
    {
        [$observationHeader, $actualObservations, $nonstandardPathogenObservations] = $this->initializeObservationColumns($probes);

        $header = [
            $this->translator->trans("Identifier", [], "entity_probe"),
            $this->translator->trans("Requisition identifier", [], "entity_probe"),
            $this->translator->trans("enum.title", [], "enum_pathogen"),
            $this->translator->trans("enum.plural", [], "enum_analysis_type"),
            $this->translator->trans("enum.plural", [], "enum_method_type"),

            $this->translator->trans("Orderer", [], "entity_probe"),
            $this->translator->trans("Orderer prac", [], "entity_probe"),

            $this->translator->trans("Specimen collection date", [], "trait_probe_specimen_meta"),
            $this->translator->trans("Specimen source", [], "trait_probe_specimen_meta"),
            $this->translator->trans("entity.title", [], "entity_specimen"),
            $this->translator->trans("Specimen location", [], "trait_probe_specimen_meta"),
            $this->translator->trans("Anamnesis travels", [], "trait_probe_specimen_meta"),

            $this->translator->trans("entity.title", [], "entity_patient"),
            $this->translator->trans("Birth date", [], "entity_patient"),
            $this->translator->trans("entity.title", [], "entity_animal_keeper"),
            $this->translator->trans("Animal name", [], "trait_probe_specimen_meta"),

            $this->translator->trans("Analysis start date", [], "entity_probe"),

            ...$observationHeader
        ];

        $content = [];


        foreach ($probes as $probe) {
            $specimenSource = $probe->getSpecimenSource()?->trans($this->translator) ?? $probe->getSpecimenSourceText();
            if ($probe->getSpecimenSource() === SpecimenSource::FEED) {
                $specimenSource .= " " . $probe->getSpecimenFoodType()?->trans($this->translator) ?? $probe->getSpecimenTypeText();
            } elseif ($probe->getSpecimenSource() === SpecimenSource::ANIMAL) {
                $specimenSource .= " " . $probe->getSpecimenAnimalType()?->trans($this->translator) ?? $probe->getSpecimenTypeText();
            }

            $specimen = $probe->getSpecimen()?->getDisplayName() ?? $probe->getSpecimenText();
            if ($probe->getSpecimenIsolate()) {
                $specimen .= " (" . $this->translator->trans("Specimen isolate", [], "trait_probe_specimen_meta") . ")";
            }

            $observations = $this->getObservationColumns($probe->getObservations()->toArray(), $actualObservations, $nonstandardPathogenObservations);

            $content[] = [
                $probe->getIdentifier(),
                $probe->getRequisitionIdentifier(),
                $probe->getPathogen()?->trans($this->translator) ?? $probe->getPathogenName(),
                join(", ", array_map(fn(AnalysisType $analysisType) => $analysisType->transShort($this->translator), $probe->getAnalysisTypes())),
                join(", ", array_map(fn(MethodType $methodType) => $methodType->trans($this->translator), $probe->getMethodTypes())),

                $probe->getOrdererOrgName(),
                $probe->getOrdererPracFullName(),

                $probe->getSpecimenCollectionDate()?->format('Y-m-d') ?? '',
                $specimenSource,
                $specimen,
                $probe->getSpecimenLocation(),
                $probe->getAnamnesisTravels(),

                $probe->getPatientFullName(),
                $probe->getPatientBirthDate()?->format('Y-m-d') ?? '',
                $probe->getAnimalKeeperName(),
                $probe->getAnimalName(),

                $probe->getAnalysisStartDate()?->format('Y-m-d') ?? '',

                ...$observations,
            ];
        }

        return $this->exportService->exportAsExcel('probes.xlsx', $header, $content);
    }

    /**
     * @param Probe[] $probes
     * @return void
     */
    private function initializeObservationColumns(array $probes): array
    {
        // initialize with default order
        $possibleObservations = [];
        foreach (Pathogen::cases() as $case) {
            $possibleObservations[$case->value] = [];
            foreach (AnalysisType::cases() as $analysisCase) {
                $possibleObservations[$case->value][$analysisCase->value] = 0;
            }
        }

        // track what analysis are actually done
        $nonstandardObservations = [];
        foreach ($probes as $probe) {
            foreach ($probe->getObservations() as $observation) {
                if ($observation->getPathogen()) {
                    $possibleObservations[$observation->getPathogen()->value][$observation->getAnalysisType()->value]++;
                } else {
                    if (!isset($nonstandardObservations[$observation->getPathogenName()])) {
                        $nonstandardObservations[$observation->getPathogenName()] = 0;
                    }
                    $nonstandardObservations[$observation->getPathogenName()]++;
                }
            }
        }

        $header = [];
        $actualObservations = [];
        foreach ($possibleObservations as $pathogen => $analysisTypes) {
            foreach ($analysisTypes as $analysisType => $count) {
                if ($count > 0) {
                    $actualObservations[] = $pathogen . "_" . $analysisType;
                    $analysisPrefix = $analysisType === AnalysisType::IDENTIFICATION->value ? $this->translator->trans("short." . $pathogen, [], "enum_pathogen") . " " : "";
                    $header[] = $analysisPrefix . $this->translator->trans("short." . $analysisType, [], "enum_analysis_type");
                }
            }
        }
        foreach ($nonstandardObservations as $nonstandardPathogenObservation) {
            $header[] = $nonstandardPathogenObservation;
        }

        return [$header, $actualObservations, $nonstandardObservations];
    }

    /**
     * @param Observation[] $observations
     * @param string[] $actualObservations
     * @param string[] $nonstandardObservations
     */
    private function getObservationColumns(array $observations, array $actualObservations, array $nonstandardObservations): array
    {
        $result = array_fill(0, count($actualObservations) + count($nonstandardObservations), null);
        foreach ($observations as $observation) {
            if ($observation->getPathogen()) {
                $marker = $observation->getPathogen()->value . "_" . $observation->getAnalysisType()->value;
                $cellIndex = array_search($marker, $actualObservations);
            } else {
                $cellIndex = count($actualObservations) + array_search($observation->getPathogenName(), $nonstandardObservations);
            }

            $cellContentBlocks = [];
            if ($observation->getOrganism()) {
                $cellContentBlocks[] = $observation->getOrganism()->getDisplayName();
            } elseif ($observation->getInterpretation()) {
                $cellContentBlocks[] = $observation->getInterpretation()->trans($this->translator);
            }

            $cellContentBlocks[] = $observation->getInterpretationMeta()?->trans($this->translator);
            $cellContentBlocks[] = $observation->getInterpretationText();

            $result[$cellIndex] = join(", ", array_filter($cellContentBlocks));
        }

        return $result;
    }
}
