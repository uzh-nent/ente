<?php

namespace App\Services;

use App\Entity\Probe;
use App\Entity\Report;
use App\Enum\AnalysisType;
use App\Enum\LaboratoryFunction;
use App\Enum\Pathogen;
use App\Enum\SpecimenSource;
use App\Services\Interfaces\PdfServiceInterface;
use Famoser\PdfGenerator\Frontend\Content\ImagePlacement;
use Famoser\PdfGenerator\Frontend\Content\Rectangle;
use Famoser\PdfGenerator\Frontend\Content\Style\DrawingStyle;
use Famoser\PdfGenerator\Frontend\Content\Style\TextStyle;
use Famoser\PdfGenerator\Frontend\Document;
use Famoser\PdfGenerator\Frontend\Layout\AbstractElement;
use Famoser\PdfGenerator\Frontend\Layout\Block;
use Famoser\PdfGenerator\Frontend\Layout\ContentBlock;
use Famoser\PdfGenerator\Frontend\Layout\Flow;
use Famoser\PdfGenerator\Frontend\Layout\Style\FlowDirection;
use Famoser\PdfGenerator\Frontend\Layout\Text;
use Famoser\PdfGenerator\Frontend\Resource\Font;
use Famoser\PdfGenerator\Frontend\Resource\Image;
use Famoser\PdfGenerator\Tests\Resources\ResourcesProvider;
use Symfony\Component\Filesystem\Path;
use Symfony\Contracts\Translation\TranslatableInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use function PHPUnit\Framework\returnArgument;

class PdfService implements PdfServiceInterface
{
    private float $fontSize = 4;
    private float $smallFontSize = 4 / 1.3;
    private float $spacer = 6;
    private float $labelWidth = 24;
    private TextStyle $textStyle;
    private TextStyle $emphasisTextStyle;
    private TextStyle $boldTextStyle;

    private string $resourcesDir;

    private float $documentContentWidth = 210 - 2 * 15;


    public function __construct(string $rootDir, private readonly TranslatorInterface $translator, private readonly string $organizationName)
    {
        $this->resourcesDir = $rootDir . '/assets/resources/worksheet';

        $normalFont = Font::createFromDefault(Font\FontFamily::Times);
        $this->textStyle = new TextStyle($normalFont);

        $boldTextStyle = Font::createFromDefault(Font\FontFamily::Times, Font\FontWeight::Bold);
        $this->boldTextStyle = new TextStyle($boldTextStyle);

        $emphasisTextStyle = Font::createFromDefault(Font\FontFamily::Times, Font\FontWeight::Bold, Font\FontStyle::Italic);
        $this->emphasisTextStyle = new TextStyle($emphasisTextStyle);
    }

    public function generateWorksheet(Probe $probe): string
    {
        $document = new Document();

        $printer = $document->createPrinter(-5);
        $printer->printText($this->organizationName, $this->textStyle, $this->smallFontSize);
        $document->setPosition(0);

        $flow = new Flow(FlowDirection::COLUMN);

        $this->addHeader($probe, $flow);
        $this->addSpace($flow, $this->spacer);

        $this->addDivider($flow);
        $this->addSpace($flow, $this->spacer / 8);
        $this->addProbeMeta($probe, $flow);
        $this->addSpace($flow, $this->spacer / 8);
        $this->addDivider($flow);
        $this->addSpace($flow, $this->spacer / 8);
        $this->addServiceTimeMeta($probe, $flow);

        $this->addAnalysisContent($probe, $flow);

        $document->add($flow);

        return $document->save();
    }

    private function addHeader(Probe $probe, Flow $flow): void
    {
        $text = new Text();
        $text->addSpan($probe->getIdentifier(), $this->boldTextStyle, $this->fontSize * 1.6 * 2);
        $flow->add($text);
    }

    private function addProbeMeta(Probe $probe, Flow $flow): void
    {
        $gap = $this->spacer / 2;
        $innerFlow = new Flow(FlowDirection::ROW, $gap);

        $ordererElement = $this->createOrdererElement($probe);
        $ordererElement->setWidth($this->documentContentWidth / 3 - $gap);
        $innerFlow->add($ordererElement);

        $specimenMetaElement = $this->createSpecimenMetaElement($probe);
        $specimenMetaElement->setWidth($this->documentContentWidth / 3);
        $innerFlow->add($specimenMetaElement);

        $specimenMetaElement = $this->createSpecimenSourceElement($probe);
        $specimenMetaElement->setWidth($this->documentContentWidth / 3 - $gap);
        $innerFlow->add($specimenMetaElement);

        $flow->add($innerFlow);
    }

    private function addServiceTimeMeta(Probe $probe, Flow $flow): void
    {
        $innerFlow = new Flow(FlowDirection::ROW);

        $content = [
            [$this->translator->trans("Received date", [], "trait_probe_service_time"), $probe->getReceivedDate()],
            [$this->translator->trans("Analysis start date", [], "trait_probe_service_time"), $probe->getAnalysisStartDate()]
        ];

        foreach ($content as [$label, $date]) {
            $element = $this->createLabeledValueElement($label, $date?->format("d.m.Y") ?? "");
            $element->setMargin([0, 0, $this->spacer, 0]);
            $innerFlow->add($element);
        }

        $flow->add($innerFlow);
    }

    private function addAnalysisContent(Probe $probe, Flow $flow): void
    {
        foreach ($probe->getAnalysisTypes() as $analysisType) {
            $analysisLabel = $analysisType->trans($this->translator);
            $innerFlow = new Flow(FlowDirection::COLUMN);

            $text = new Text();
            if ($analysisType === AnalysisType::IDENTIFICATION) {
                $text->addSpan($analysisLabel . " ", $this->boldTextStyle, $this->fontSize);
                $pathogen = $probe->getPathogen() ? $probe->getPathogen()->trans($this->translator) : $probe->getPathogenName();
                $text->addSpan($pathogen, $this->emphasisTextStyle, $this->fontSize);
            } else {
                $pathogen = Pathogen::ESCHERICHIA_COLI->trans($this->translator);
                $text->addSpan($pathogen . " ", $this->emphasisTextStyle, $this->fontSize);
                $text->addSpan($analysisLabel, $this->boldTextStyle, $this->fontSize);
            }
            $innerFlow->add($text);

            if ($analysisType === AnalysisType::IDENTIFICATION && in_array($probe->getPathogen(), [Pathogen::SHIGELLA, Pathogen::YERSINIA, Pathogen::LISTERIA_MONOCYTOGENES])) {
                $path = $this->resourcesDir . '/' . $probe->getPathogen()->name . '.png';

                $imageInfo = getimagesize($path);
                $originalWidth = $imageInfo[0];
                $originalHeight = $imageInfo[1];

                $targetWidth = 210 - 2 * 15;
                $targetHeight = ($originalHeight / $originalWidth) * $targetWidth;

                $image = Image::createFromFile($path);
                $imagePlacement = new ImagePlacement($targetWidth, $targetHeight, $image);

                $block = new ContentBlock($imagePlacement);
                $block->setMargin([0, $this->spacer / 2, 0, 0]);
                $innerFlow->add($block);
            } else {
                $text = new Text();
                $result = $this->translator->trans("content.result", [], "report");
                $text->addSpan($result . ":", $this->textStyle);

                $block = new Block($text);
                $block->setMargin([$this->spacer, $this->spacer / 2, 0, 0]);
                $innerFlow->add($block);
            }
            $innerFlow->setMargin([0, $this->spacer, 0, $this->spacer * 2]);
            $flow->add($innerFlow);
        }
    }

    private function addSpace(Flow $flow, float $height = 10): void
    {
        $block = new ContentBlock();
        $block->setMargin([0, $height, 0, 0]);
        $flow->add($block);
    }

    private function addDivider(Flow $flow): void
    {
        $dividerStyle = new DrawingStyle(0.2);
        $divider = new Rectangle($this->documentContentWidth, 0, $dividerStyle);
        $flow->add(new ContentBlock($divider));
    }

    public function generateReport(Report $report): string
    {
        // TODO
        return "";
    }

    private function createSpecimenMetaElement(Probe $probe): AbstractElement
    {
        $flow = new Flow(FlowDirection::COLUMN, 0.2);

        $text = new Text();
        $label = $this->translator->trans("entity.title", [], "entity_probe");
        $text->addSpan($label, $this->boldTextStyle, $this->smallFontSize);
        $flow->add($text);
        $this->addSpace($flow, $this->spacer / 2 - 0.2);

        $label = $this->translator->trans("Specimen collection date", [], "trait_probe_specimen_meta");
        $value = $probe->getSpecimenCollectionDate()?->format("d.m.Y") ?? "";
        $flow->add($this->createLabeledValueElement($label, $value));

        // source
        $label = $this->translator->trans("Specimen source", [], "trait_probe_specimen_meta");
        if (!$probe->getSpecimenSource()) {
            $value = $probe->getSpecimenSourceText();
        } else {
            $value = $probe->getSpecimenSource()->trans($this->translator) . " ";

            if ($probe->getSpecimenSource() === SpecimenSource::FOOD) {
                $value .= $probe->getSpecimenFoodType()?->trans($this->translator) ?? $probe->getSpecimenTypeText();
            } elseif ($probe->getSpecimenSource() === SpecimenSource::ANIMAL) {
                $value .= $probe->getSpecimenAnimalType()?->trans($this->translator) ?? $probe->getSpecimenTypeText();
            } elseif ($probe->getSpecimenSource() !== SpecimenSource::HUMAN) {
                $value .= $probe->getSpecimenTypeText();
            }
        }
        $flow->add($this->createLabeledValueElement($label, $value));

        // specimen
        if ($probe->getSpecimenSource() === SpecimenSource::HUMAN) {
            $label = $this->translator->trans("entity.title", [], "entity_specimen");
            if ($probe->getSpecimen()) {
                $value = str_replace(" specimen", "", $probe->getSpecimen()->getDisplayName());
            } else {
                $value = $probe->getSpecimenText();
            }

            if ($probe->getSpecimenIsolate()) {
                $value .= " (" . $this->translator->trans("Specimen isolate", [], "trait_probe_specimen_meta") . ")";
            }
        } else {
            $label = $this->translator->trans("Specimen text", [], "trait_probe_specimen_meta");
            $value = $probe->getSpecimenText() ?? "";
        }
        $flow->add($this->createLabeledValueElement($label, $value));

        return $flow;
    }

    private function createSpecimenSourceElement(Probe $probe): AbstractElement
    {
        $ordererFlow = new Flow(FlowDirection::COLUMN);
        if ($probe->getSpecimenSource() === SpecimenSource::HUMAN) {
            $name = trim($probe->getPatientGivenName() . " " . $probe->getPatientFamilyName());
            if ($probe->getPatientGender()) {
                $name .= " (" . $probe->getPatientGender()->trans($this->translator) . ")";
            }
            $recipient = $this->createRecipientElement(
                $this->translator->trans("entity.title", [], "entity_patient"),
                [
                    $name,
                    $probe->getPatientAddressLines(),
                    $probe->getPatientCountryCode() . " " . $probe->getPatientPostalCode() . " " . $probe->getPatientCity()
                ],
                identifiers: [$probe->getPatientBirthDate()?->format('d.m.Y'), $probe->getPatientAhvNumber()]
            );
        } else if ($probe->getSpecimenSource() === SpecimenSource::ANIMAL) {
            $recipient = $this->createRecipientElement(
                $this->translator->trans("entity.title", [], "animal_keeper"),
                [
                    $probe->getAnimalKeeperName(),
                    $probe->getAnimalKeeperAddressLines(),
                    $probe->getAnimalKeeperCountryCode() . " " . $probe->getAnimalKeeperPostalCode() . " " . $probe->getAnimalKeeperCity()
                ],
            );
        } else {
            $recipient = $this->createRecipientElement(
                $this->translator->trans("Specimen location", [], "trait_probe_specimen_meta"),
                [$probe->getSpecimenLocation()]
            );
        }
        $ordererFlow->add($recipient);

        return $ordererFlow;
    }

    private function createOrdererElement(Probe $probe): AbstractElement
    {
        $ordererFlow = new Flow(FlowDirection::COLUMN);
        if ($probe->getLaboratoryFunction() === LaboratoryFunction::REFERENCE) {
            $recipient = $this->createRecipientElement(
                $this->translator->trans("meta.orderer_organization", [], "report"),
                [
                    $probe->getOrdererOrgName(),
                    $probe->getOrdererOrgAddressLines(),
                    $probe->getOrdererOrgCountryCode() . " " . $probe->getOrdererOrgPostalCode() . " " . $probe->getOrdererOrgCity()
                ],
                $probe->getOrdererOrgContact()
            );
        } else {
            $recipient = $this->createRecipientElement(
                $this->translator->trans("meta.orderer_practitioner", [], "report"),
                [
                    trim($probe->getOrdererPracGivenName() . " " . $probe->getOrdererPracFamilyName()),
                    $probe->getOrdererPracAddressLines(),
                    $probe->getOrdererPracCountryCode() . " " . $probe->getOrdererPracPostalCode() . " " . $probe->getOrdererPracCity()
                ],
                $probe->getOrdererPracContact()
            );
        }
        $ordererFlow->add($recipient);

        $this->addSpace($ordererFlow, $this->spacer / 2);
        $label = $this->translator->trans("Requisition identifier", [], "entity_probe");
        $ordererFlow->add($this->createLabeledValueElement($label, $probe->getRequisitionIdentifier()));

        return $ordererFlow;
    }

    private function createRecipientElement(string $label, array $address, ?string $contact = null, ?array $identifiers = null): AbstractElement
    {
        $recipientFlow = new Flow(FlowDirection::COLUMN);

        $text = new Text();
        $text->addSpan($label, $this->boldTextStyle, $this->smallFontSize);
        $recipientFlow->add($text);

        // identifiers (AHV-number etc)
        if ($identifiers && count($identifiers) > 0) {
            $text = new Text();
            $identifiersText = join("\n", array_filter($identifiers));
            $text->addSpan($identifiersText, $this->textStyle, $this->fontSize, 1);
            $contentBlock = new Block($text);
            $contentBlock->setMargin([0, $this->spacer / 2, 0, 0]);
            $recipientFlow->add($contentBlock);
        }

        // address
        $text = new Text();
        $addressText = join("\n", array_filter($address));
        $text->addSpan($addressText, $this->textStyle, $this->fontSize, 1);
        $contentBlock = new Block($text);
        $contentBlock->setMargin([0, $this->spacer / 2, 0, 0]);
        $recipientFlow->add($contentBlock);

        // contact
        if ($contact) {
            $text = new Text();
            $text->addSpan($contact, $this->textStyle, $this->fontSize, 1);
            $contentBlock = new Block($text);
            $contentBlock->setMargin([0, $this->spacer / 2, 0, 0]);
            $recipientFlow->add($contentBlock);
        }

        return $recipientFlow;
    }

    private function createLabeledValueElement(string $label, string $value): AbstractElement
    {
        $labelFlow = new Flow();

        $text = new Text();
        $text->addSpan($label . ": ", $this->textStyle, $this->smallFontSize);
        $contentBlock = new Block($text);
        $contentBlock->setWidth($this->labelWidth);
        $labelFlow->add($contentBlock);

        $text = new Text();
        $text->addSpan($value, $this->textStyle, $this->fontSize, 1);
        $labelFlow->add($text);

        return $labelFlow;
    }
}
