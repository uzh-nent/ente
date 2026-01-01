<?php

namespace App\Services;

use App\Entity\Probe;
use App\Entity\Report;
use App\Services\Interfaces\PdfServiceInterface;
use Famoser\PdfGenerator\Frontend\Content\Style\TextStyle;
use Famoser\PdfGenerator\Frontend\Document;
use Famoser\PdfGenerator\Frontend\Layout\AbstractElement;
use Famoser\PdfGenerator\Frontend\Layout\Flow;
use Famoser\PdfGenerator\Frontend\Layout\Style\FlowDirection;
use Famoser\PdfGenerator\Frontend\Layout\Text;
use Famoser\PdfGenerator\Frontend\Resource\Font;

class PdfService implements PdfServiceInterface
{
    private float $fontSize = 4;
    private TextStyle $textStyle;
    private TextStyle $boldTextStyle;


    public function __construct()
    {
        $normalFont = Font::createFromDefault();
        $this->textStyle = new TextStyle($normalFont);

        $headerFont = Font::createFromDefault(Font\FontFamily::Helvetica, Font\FontWeight::Bold);
        $this->boldTextStyle = new TextStyle($headerFont);
    }

    public function generateWorksheet(Probe $probe): string
    {
        $document = new Document();
        $flow = new Flow(FlowDirection::COLUMN);

        $header = $this->generateHeader($probe);
        $flow->add($header);

        $document->add($flow);
        return $document->save();
    }

    private function generateHeader(Probe $probe): AbstractElement
    {
        $text = new Text();
        $text->addSpan($probe->getIdentifier(), $this->boldTextStyle, $this->fontSize * 1.6 * 2);
        return $text;
    }

    public function generateReport(Report $report): string
    {
        // TODO
        return "";
    }
}
