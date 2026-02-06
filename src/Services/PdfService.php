<?php

namespace App\Services;

use App\Entity\Probe;
use App\Entity\Report;
use App\Enum\AdministrativeGender;
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
use Famoser\PdfGenerator\Frontend\LayoutEngine\Allocate\AllocationVisitor;
use Famoser\PdfGenerator\Frontend\Resource\Font;
use Famoser\PdfGenerator\Frontend\Resource\Image;
use Symfony\Contracts\Translation\TranslatorInterface;

class PdfService implements PdfServiceInterface
{
    private float $fontSize = 4;
    private float $smallFontSize = 4 / 1.3;
    private float $tinyFontSize = 4 / 1.6;
    private float $spacer = 6;
    private TextStyle $textStyle;
    private TextStyle $emphasisTextStyle;
    private TextStyle $secondaryTextStyle;
    private TextStyle $boldTextStyle;

    private string $worksheetResourcesDir;
    private string $reportResourcesDir;

    public function __construct(string $rootDir, private readonly TranslatorInterface $translator, private readonly string $organizationName)
    {
        $this->worksheetResourcesDir = $rootDir . '/assets/resources/worksheet';
        $this->reportResourcesDir = $rootDir . '/assets/resources/report';

        $normalFont = Font::createFromDefault(Font\FontFamily::Helvetica);
        $this->textStyle = new TextStyle($normalFont);

        $boldTextStyle = Font::createFromDefault(Font\FontFamily::Helvetica, Font\FontWeight::Bold);
        $this->boldTextStyle = new TextStyle($boldTextStyle);

        $emphasisTextStyle = Font::createFromDefault(Font\FontFamily::Helvetica, Font\FontWeight::Bold, Font\FontStyle::Oblique);
        $this->emphasisTextStyle = new TextStyle($emphasisTextStyle);

        $secondaryTextStyle = Font::createFromDefault(Font\FontFamily::Helvetica, Font\FontWeight::Normal, Font\FontStyle::Oblique);
        $this->secondaryTextStyle = new TextStyle($secondaryTextStyle);
    }

    public function generateWorksheet(Probe $probe): string
    {
        $document = new Document(pageSize: [210, 297], margin: 15);
        $contentWidth = 210 - 2 * 15;

        $flow = new Flow(FlowDirection::COLUMN);

        $this->addWorksheetHeader($probe, $flow);
        $this->addSpace($flow, $this->spacer);

        $this->addDivider($flow, $contentWidth);
        $this->addSpace($flow, $this->spacer / 8);
        $this->addWorksheetProbeMeta($probe, $flow, $contentWidth);
        $this->addSpace($flow, $this->spacer / 8);
        $this->addDivider($flow, $contentWidth);
        $this->addSpace($flow, $this->spacer / 8);
        $this->addWorksheetServiceTimeMeta($probe, $flow);

        $this->addWorksheetAnalysisContent($probe, $flow);

        $document->add($flow);

        for ($i = 0; $i < $document->getPageCount(); $i++) {
            $printer = $document->createPrinter(-5, $i);
            $printer->printText($this->organizationName, $this->textStyle, $this->smallFontSize);
        }

        return $document->save();
    }

    public function generateReport(Report $report): string
    {
        $document = new Document(pageSize: [210, 297], margin: [32, 80, 22, 38]);
        $contentWidth = 210 - (32 + 22);

        $layoutJson = file_get_contents($this->reportResourcesDir . "/layout.json");
        $layout = json_decode($layoutJson, true);

        $addresses = [];
        if ($report->getProbe()->getOrdererOrg()) {
            $addresses[] = $report->getProbe()->getOrdererOrgFullAddress();
        }
        if ($report->getProbe()->getOrdererPrac()) {
            $addresses[] = $report->getProbe()->getOrdererPracFullAddress();
        }
        if ($report->getCopyToAddresses()) {
            foreach ($report->getCopyToAddresses() as $copyToAddress) {
                $addresses[] = join("\n", [$copyToAddress['name'], $copyToAddress['addressLines'], $copyToAddress['cityLine']]);
            }
        }

        foreach ($addresses as $i => $address) {
            if ($i > 0) {
                $document->addPage();
            }

            $flow = new Flow(FlowDirection::COLUMN, $this->spacer / 2);

            $this->addAddress($document, $layout, $address);
            $this->addSpace($flow, $this->spacer * 3);
            $this->addReportHeader($report, $flow);
            $this->addDivider($flow, $contentWidth);
            $this->addServiceRequest($report->getProbe(), $flow, $report->getCopyToAddresses());
            $this->addDivider($flow, $contentWidth);

            $this->addReportProbeMeta($report, $flow, $contentWidth);
            $this->addDivider($flow, $contentWidth);
            $this->addReportServiceTimeElement($report, $flow);
            $this->addSpace($flow, $this->spacer);

            $this->addReportResultHeader($flow);
            foreach ($report->getResults() as $result) {
                $this->addResult($result, $flow);
            }

            $this->addSpace($flow, $this->spacer);
            $document->add($flow);

            $this->addReportSignature($report, $document, $layout, $contentWidth);
        }

        for ($i = 0; $i < $document->getPageCount(); $i++) {
            $this->printReportLayout($document, $i, $report, $layout, $contentWidth);
        }

        return $document->save();
    }

    private function addReportHeader(Report $report, Flow $flow): void
    {
        $innerFlow = new Flow(FlowDirection::ROW);

        $text = new Text();
        $text->addSpan($report->getProbe()->getIdentifier() . " - " . $report->getTitle() . "  ", $this->boldTextStyle, $this->fontSize * 1.6);
        $text->addSpan($report->getDate()->format("d.m.Y") . " / " . $report->getCreatedBy()->getAbbreviation(), $this->textStyle, $this->fontSize);

        $innerFlow->add($text);

        $flow->add($innerFlow);
    }

    private function addReportProbeMeta(Report $report, Flow $flow, float $width): void
    {
        $gap = $this->spacer;
        $innerFlow = new Flow(FlowDirection::ROW, $gap);

        $specimenMetaElement = $this->createSpecimenMetaElement($report->getProbe());
        $specimenMetaColumnWidth = 66;
        $specimenMetaElement->setWidth($specimenMetaColumnWidth);
        $innerFlow->add($specimenMetaElement);

        $specimenSourceElement = $this->createSpecimenSourceElement($report->getProbe());
        if ($specimenSourceElement) {
            $specimenSourceElement->setWidth($width - $specimenMetaColumnWidth - $gap);
            $innerFlow->add($specimenSourceElement);
        }

        $flow->add($innerFlow);
    }

    private function addReportResultHeader(Flow $flow): void
    {
        $text = new Text();
        $text->addSpan($this->translator->trans("report.results", [], "report"), $this->boldTextStyle, $this->fontSize * 1.3);
        $flow->add($text);
    }

    /**
     * @param mixed[] $layout
     */
    private function addReportSignature(Report $report, Document $document, array $layout, float $contentWidth): void
    {
        $text = new Text();
        $text->addSpan($layout['greeting'] . "\n", $this->textStyle, $this->fontSize);
        $text->addSpan($report->getCreatedBy()->getName(), $this->textStyle, $this->fontSize);
        $block = new Block($text);
        $block->setWidth($contentWidth - 20);
        $document->add($text);

        if ($report->getClaimCertification()) {
            $printer = $document->createPrinter();
            $certificationPrinter = $printer->position(left: $contentWidth - 15, top: -15 + $this->spacer / 2);
            $certificationImagePath = $this->reportResourcesDir . "/certification.png";
            $certificationPrinter->printImage(15, 15, $certificationImagePath);
        }
    }

    /**
     * @param mixed[] $layout
     */
    private function printReportLayout(Document $document, int $pageIndex, Report $report, array $layout, float $contentWidth): void
    {
        $printer = $document->setPosition(0, $pageIndex)->createPrinter();
        $noMarginPrinter = $printer->position(-32, -80); // minus the left / top margins

        // UZH logo
        $path = $this->reportResourcesDir . '/logo.png';
        $imagePlacement = $this->createImagePlacement($path, 50);
        $logoPrinter = $noMarginPrinter->position(12.2, 4);
        $logoPrinter->print($imagePlacement);

        // vetsuisse logo
        $path = $this->reportResourcesDir . '/logo_faculty.png';
        $imagePlacement = $this->createImagePlacement($path, 15);
        $facultyLogoPrinter = $noMarginPrinter->position(9, 165);
        $facultyLogoPrinter->print($imagePlacement);

        // department
        $flow = new Flow(FlowDirection::COLUMN);
        $text = new Text();
        $text->addSpan($layout['department'], $this->boldTextStyle, $this->fontSize, 0.8);
        $flow->add($text);
        $this->addSpace($flow, $this->spacer / 8);
        $text = new Text();
        $text->addSpan($layout['name'], $this->textStyle, $this->fontSize, 0.8);
        $flow->add($text);

        // address
        $this->addSpace($flow, $this->spacer / 2);
        $text = new Text();
        $text->addSpan($layout['organization'] . "\n" . $layout['department'] . "\n" . $layout['address'] . "\n" . $layout['webpage'], $this->textStyle, $this->smallFontSize, 0.8);
        $flow->add($text);

        // contact
        $this->addSpace($flow, $this->spacer / 2);
        $text = new Text();
        $text->addSpan($this->translator->trans("report.contact", [], "report") . ":\n", $this->textStyle, $this->smallFontSize, 0.8);
        foreach ($layout['responsible'] as $responsible) {
            $text->addSpan($responsible['name'], $this->boldTextStyle, $this->smallFontSize, 0.8);
            if (isset($responsible['function'])) {
                $text->addSpan(" (" . $responsible['function'] . ")", $this->textStyle, $this->smallFontSize, 0.8);
            }
            if (isset($responsible['email'])) {
                $text->addSpan("\n  " . $responsible['email'], $this->textStyle, $this->smallFontSize, 0.8);
            }
            $text->addSpan("\n", $this->textStyle, $this->smallFontSize, 0.8);
        }
        $flow->add($text);

        // contact
        $text = new Text();
        $text->addSpan($layout['contact'], $this->textStyle, $this->smallFontSize, 0.8);
        $flow->add($text);

        // size & print
        $allocationVisitor = new AllocationVisitor(89 - 22, 270);
        $allocation = $flow->accept($allocationVisitor);
        $logoPrinter = $noMarginPrinter->position(121, 8); // 121 + 89 = 210 = A4 width
        $logoPrinter->place($allocation);

        // footer
        $flow = new Flow(FlowDirection::COLUMN);
        $text = new Text(Text\Structure::Paragraph, Text\Alignment::ALIGNMENT_JUSTIFIED);
        $text->addSpan($layout['conditions'], $this->textStyle, $this->tinyFontSize, 1);
        $flow->add($text);
        $text = new Text(Text\Structure::Paragraph, Text\Alignment::ALIGNMENT_RIGHT);
        $text->addSpan(($pageIndex + 1) . "/" . $document->getPageCount(), $this->textStyle, $this->fontSize, 1);
        $flow->add($text);

        // size & print footer
        $allocationVisitor = new AllocationVisitor($contentWidth, 270);
        $allocation = $flow->accept($allocationVisitor);
        $footerPrinter = $printer->position(top: 179 + $this->spacer); // 297 - 80 - 38 (the two vertical margins)
        $footerPrinter->place($allocation);
    }

    /**
     * @param mixed[] $layout
     */
    private function addAddress(Document $document, array $layout, string $address): void
    {
        $flow = new Flow(FlowDirection::COLUMN);

        $text = new Text();
        $text->addSpan($layout['organization'] . ", " . $layout['department'] . "\n" .
            str_replace("\n", ", ", $layout['address']), $this->textStyle, $this->tinyFontSize, 1); // around six points
        $flow->add($text);

        $this->addSpace($flow, $this->spacer / 4);
        $this->addDivider($flow, 74);
        $this->addSpace($flow, $this->spacer / 2);

        $text = new Text();
        $text->addSpan($address, $this->textStyle, $this->fontSize, 1);
        $addressBlock = new Block($text);
        $addressBlock->setWidth(74);
        $flow->add($addressBlock);

        // hardcoded address position
        $document->setPosition(-33); // 80-33 = 47; 47 is where the address should start
        $document->add($flow);
    }

    private function addWorksheetHeader(Probe $probe, Flow $flow): void
    {
        $text = new Text();
        $text->addSpan($probe->getIdentifier(), $this->boldTextStyle, $this->fontSize * 1.6 * 2);
        $flow->add($text);
    }

    private function addWorksheetProbeMeta(Probe $probe, Flow $flow, float $width): void
    {
        $gap = $this->spacer / 2;
        $innerFlow = new Flow(FlowDirection::ROW, $gap);

        $ordererElement = $this->createOrdererElement($probe);
        $ordererElement->setWidth($width / 3 - $gap);
        $innerFlow->add($ordererElement);

        $specimenMetaElement = $this->createSpecimenMetaElement($probe);
        $specimenMetaElement->setWidth($width / 3);
        $innerFlow->add($specimenMetaElement);

        $specimenSourceElement = $this->createSpecimenSourceElement($probe);
        if ($specimenSourceElement) {
            $specimenSourceElement->setWidth($width / 3 - $gap);
            $innerFlow->add($specimenSourceElement);
        }

        $flow->add($innerFlow);
    }

    private function addWorksheetServiceTimeMeta(Probe $probe, Flow $flow): void
    {
        $innerFlow = new Flow(FlowDirection::ROW);

        $content = [
            [$this->translator->trans("Received date", [], "trait_probe_service_time"), $probe->getReceivedDate()],
            [$this->translator->trans("Analysis start date", [], "trait_probe_service_time"), $probe->getAnalysisStartDate()]
        ];

        foreach ($content as [$label, $date]) {
            $element = $this->createLabeledValue($label, $date?->format("d.m.Y") ?? "");
            $element->setMargin([0, 0, $this->spacer, 0]);
            $innerFlow->add($element);
        }

        $flow->add($innerFlow);
    }

    private function addWorksheetAnalysisContent(Probe $probe, Flow $flow): void
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
                $path = $this->worksheetResourcesDir . '/' . $probe->getPathogen()->name . '.png';
                $imagePlacement = $this->createImagePlacement($path, 210 - 2 * 15);

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

    private function createImagePlacement(string $path, float $targetWidth): ImagePlacement
    {
        $imageInfo = getimagesize($path);
        $originalWidth = $imageInfo[0];
        $originalHeight = $imageInfo[1];

        $targetHeight = ($originalHeight / $originalWidth) * $targetWidth;

        $image = Image::createFromFile($path);
        return new ImagePlacement($targetWidth, $targetHeight, $image);
    }

    private function addSpace(Flow $flow, float $height = 10): void
    {
        $block = new ContentBlock();
        $block->setMargin([0, $height, 0, 0]);
        $flow->add($block);
    }

    private function addDivider(Flow $flow, float $width): void
    {
        $dividerStyle = new DrawingStyle(0.2);
        $divider = new Rectangle($width, 0, $dividerStyle);
        $flow->add(new ContentBlock($divider));
    }

    /**
     * @param array<array{'name': ?string, 'addressLines': ?string, 'cityLine': ?string}>|null $copyToAddresses
     */
    private function addServiceRequest(Probe $probe, Flow $flow, ?array $copyToAddresses): void
    {
        $label = $this->translator->trans("Service", [], "entity_probe");
        if ($probe->getLaboratoryFunction() === LaboratoryFunction::REFERENCE) {
            $value = AnalysisType::IDENTIFICATION->trans($this->translator) . " " . $probe->getPathogen()->trans($this->translator);
        } else {
            $analysisTypes = array_map(fn(AnalysisType $v) => $v->trans($this->translator), $probe->getAnalysisTypes());
            $value = Pathogen::ESCHERICHIA_COLI->trans($this->translator) . " " . join(", ", $analysisTypes);
        }
        $flow->add($this->createLabeledValue($label, $value, primary: true, labelWidth: 25));

        $label = $this->translator->trans("Orderer", [], "entity_probe");
        $value = $probe->getOrdererOrg() ? $probe->getOrdererOrgShortAddress() : $probe->getOrdererPracShortAddress();
        $flow->add($this->createLabeledValue($label, $value, primary: true, labelWidth: 25));

        $label = $this->translator->trans("Requisition identifier", [], "trait_probe_service_request");
        $flow->add($this->createLabeledValue($label, $probe->getRequisitionIdentifier(), primary: true, boldValue: true, labelWidth: 25));

        if ($copyToAddresses) {
            $shortAddresses = [];
            foreach ($copyToAddresses as $address) {
                $entries = array_filter([$address['name'], $address['cityLine']]);
                $shortAddresses[] = join(", ", $entries);
            }

            $label = $this->translator->trans("report.copy_to", [], "report");
            $flow->add($this->createLabeledValue($label, implode("; ", $shortAddresses), primary: true, labelWidth: 25));
        }
    }

    private function createSpecimenMetaElement(Probe $probe): AbstractElement
    {
        $flow = new Flow(FlowDirection::COLUMN, 0.2);

        $label = $this->translator->trans("entity.title", [], "entity_probe");
        $text = $this->createLabel($label);
        $flow->add($text);
        $this->addSpace($flow, $this->spacer / 2 - 0.2);

        $labelWidth = 26;
        $label = $this->translator->trans("Specimen collection date", [], "trait_probe_specimen_meta");
        $value = $probe->getSpecimenCollectionDate()?->format("d.m.Y") ?? "";
        $flow->add($this->createLabeledValue($label, $value, labelWidth: $labelWidth));

        // source
        $label = $this->translator->trans("Specimen source", [], "trait_probe_specimen_meta");
        if (!$probe->getSpecimenSource()) {
            $value = $probe->getSpecimenSourceText() ?? "";
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
        $flow->add($this->createLabeledValue($label, $value, labelWidth: $labelWidth));

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
        $flow->add($this->createLabeledValue($label, $value, labelWidth: $labelWidth));

        return $flow;
    }

    private function createSpecimenSourceElement(Probe $probe): ?AbstractElement
    {
        $ordererFlow = new Flow(FlowDirection::COLUMN);
        if ($probe->getSpecimenSource() === SpecimenSource::HUMAN) {
            $labelWidth = 23;

            $label = $this->translator->trans("entity.title", [], "entity_patient");
            $ordererFlow->add($this->createLabel($label));

            $contentBlock = new ContentBlock();
            $contentBlock->setMargin([0, $this->spacer / 2, 0, 0]);
            $ordererFlow->add($contentBlock);

            if ($probe->getPatientAhvNumber()) {
                $label = $this->translator->trans("Ahv number", [], "entity_patient");
                $ordererFlow->add($this->createLabeledValue($label, $probe->getPatientAhvNumberFormatted(), labelWidth: $labelWidth));
            }

            $innerFlow = new Flow(FlowDirection::ROW);
            $label = $this->translator->trans("Birth date", [], "entity_patient");
            $innerFlow->add($this->createLabeledValue($label, $probe->getPatientBirthDate()?->format('d.m.Y'), labelWidth: $labelWidth));

            if ($probe->getPatientGender()) {
                $label = $this->translator->trans("enum.title", [], "enum_administrative_gender");
                $value = $probe->getPatientGender()->trans($this->translator);
                $block = $this->createLabeledValue($label, $value, labelWidth: 18);
                $block->setMargin([$this->spacer / 2, 0, 0, 0]);
                $innerFlow->add($block);
            }
            $ordererFlow->add($innerFlow);

            $label = $this->translator->trans("Given name", [], "trait_person");
            $ordererFlow->add($this->createLabeledValue($label, $probe->getPatientGivenName(), labelWidth: $labelWidth));
            $label = $this->translator->trans("Family name", [], "trait_person");
            $ordererFlow->add($this->createLabeledValue($label, $probe->getPatientFamilyName(), labelWidth: $labelWidth));

            $ordererFlow->add($this->createValue($probe->getPatientAddress()));
        } elseif ($probe->getSpecimenSource() === SpecimenSource::ANIMAL) {
            $recipient = $this->createRecipientElement(
                $this->translator->trans("entity.title", [], "animal_keeper"),
                $probe->getAnimalKeeperFullAddress(),
            );
            $ordererFlow->add($recipient);

            if ($probe->getAnimalName()) {
                $this->addSpace($ordererFlow, $this->spacer / 2);
                $label = $this->translator->trans("Animal name", [], "trait_probe_specimen_meta");
                $ordererFlow->add($this->createLabeledValue($label, $probe->getAnimalName()));
            }
        } elseif ($probe->getSpecimenLocation()) {
            $recipient = $this->createRecipientElement(
                $this->translator->trans("Specimen location", [], "trait_probe_specimen_meta"),
                $probe->getSpecimenLocation()
            );
            $ordererFlow->add($recipient);
        } else {
            return null;
        }

        return $ordererFlow;
    }

    private function addReportServiceTimeElement(Report $report, Flow $flow): void
    {
        $innerFlow = new Flow(FlowDirection::ROW, 4);

        $label = $this->translator->trans("Received date", [], "trait_probe_service_time");
        $value = $report->getProbe()->getReceivedDate()?->format("d.m.Y") ?? "";
        $innerFlow->add($this->createLabeledValue($label, $value));

        $label = $this->translator->trans("Analysis start date", [], "trait_probe_service_time");
        $value = $report->getProbe()->getAnalysisStartDate()?->format("d.m.Y") ?? "";
        $innerFlow->add($this->createLabeledValue($label, $value));

        $label = $this->translator->trans("Date", [], "entity_report");
        $value = $report->getDate()?->format("d.m.Y") ?? "";
        $innerFlow->add($this->createLabeledValue($label, $value));

        $flow->add($innerFlow);
    }

    /**
     * @param array{'analysis': ?string, 'method'?: ?string, 'result': ?string, 'comment'?: ?string} $result
     */
    private function addResult(array $result, Flow $flow): void
    {
        $text = new Text();
        $text->addSpan($result['analysis'], $this->textStyle, $this->fontSize);
        if (isset($result['method'])) {
            $text->addSpan(" (" . $result['method'] . ")", $this->textStyle, $this->smallFontSize);
        }
        $text->addSpan(": \n", $this->textStyle, $this->fontSize);
        $text->addSpan($result["result"], $this->textStyle, $this->fontSize);
        if (isset($result['comment'])) {
            $text->addSpan("\n", $this->textStyle, $this->fontSize);
            $text->addSpan($result["comment"], $this->secondaryTextStyle, $this->smallFontSize, 1);
        }

        $block = new Block($text);
        $block->setMargin([0, $this->spacer / 2, 0, 0]);
        $flow->add($block);
    }

    private function createOrdererElement(Probe $probe): AbstractElement
    {
        $ordererFlow = new Flow(FlowDirection::COLUMN);
        if ($probe->getOrdererOrg()) {
            $recipient = $this->createRecipientElement(
                $this->translator->trans("meta.orderer_organization", [], "report"),
                $probe->getOrdererOrgFullAddress(),
                $probe->getOrdererOrgContact()
            );
        } else {
            $recipient = $this->createRecipientElement(
                $this->translator->trans("meta.orderer_practitioner", [], "report"),
                $probe->getOrdererPracFullAddress(),
                $probe->getOrdererPracContact()
            );
        }
        $ordererFlow->add($recipient);

        $this->addSpace($ordererFlow, $this->spacer / 2);
        $label = $this->translator->trans("Requisition identifier", [], "entity_probe");
        $ordererFlow->add($this->createLabeledValue($label, $probe->getRequisitionIdentifier()));

        return $ordererFlow;
    }

    private function createRecipientElement(string $label, string $address, ?string $contact = null): AbstractElement
    {
        $recipientFlow = new Flow(FlowDirection::COLUMN);

        $text = $this->createLabel($label);
        $recipientFlow->add($text);

        // address
        $contentBlock = $this->createValue($address);
        $recipientFlow->add($contentBlock);

        // contact
        if ($contact) {
            $contentBlock = $this->createValue($contact);
            $recipientFlow->add($contentBlock);
        }

        return $recipientFlow;
    }

    private function createLabel(string $label): AbstractElement
    {
        $text = new Text();
        $text->addSpan($label, $this->boldTextStyle, $this->smallFontSize);
        return $text;
    }

    private function createValue(?string $value = ""): AbstractElement
    {
        $text = new Text();
        $text->addSpan($value, $this->textStyle, $this->fontSize, 1);
        $contentBlock = new Block($text);
        $contentBlock->setMargin([0, $this->spacer / 2, 0, 0]);

        return $contentBlock;
    }

    private function createLabeledValue(string $label, ?string $value = "", bool $primary = false, bool $boldValue = false, ?float $labelWidth = null): AbstractElement
    {
        $labelFlow = new Flow();

        $text = new Text();
        $text->addSpan($label . ": ", $this->textStyle, $primary ? $this->fontSize : $this->smallFontSize, 1);
        $contentBlock = new Block($text);
        if ($labelWidth) {
            $contentBlock->setWidth($primary ? $labelWidth * 1.6 : $labelWidth);
        }
        if (!$primary) {
            $contentBlock->setMargin([0, $this->fontSize - $this->smallFontSize, 0, 0]);
        }
        $labelFlow->add($contentBlock);

        $text = new Text();
        $text->addSpan($value, $boldValue ? $this->boldTextStyle : $this->textStyle, $this->fontSize, 1);
        $labelFlow->add($text);

        return $labelFlow;
    }
}
