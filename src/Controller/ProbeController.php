<?php

namespace App\Controller;

use App\Entity\Probe;
use App\Entity\Report;
use App\Helper\DoctrineHelper;
use App\Services\Interfaces\FileServiceInterface;
use App\Services\Interfaces\PdfServiceInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class ProbeController extends AbstractController
{
    #[Route('/probe/new', name: 'probe_new')]
    public function new(): Response
    {
        return $this->render('probe/new.html.twig');
    }

    #[Route('/probe/new.js', name: 'probe_new_js')]
    public function newJs(): Response
    {
        $response = $this->render('probe/new.js.twig');

        return $this->sendJavascript($response);
    }

    #[Route('/probes/active', name: 'probe_active')]
    public function active(): Response
    {
        return $this->render('probe/active.html.twig');
    }

    #[Route('/probes/active.js', name: 'probe_active_js')]
    public function activeJs(): Response
    {
        $response = $this->render('probe/active.js.twig');

        return $this->sendJavascript($response);
    }

    #[Route('/probes/active/{probe}/view', name: 'probe_active_view')]
    public function activeView(Probe $probe): Response
    {
        if ($probe->getFinishedAt()) {
            return $this->redirectToRoute('probe_all_view', ['probe' => $probe->getId()]);
        }

        return $this->render('probe/active_view.html.twig', ['probe' => $probe]);
    }

    #[Route('/probes/active/{probe}.js', name: 'probe_view_js')]
    public function viewJs(Probe $probe): Response
    {
        $response = $this->render('probe/view.js.twig', ['probe' => $probe]);

        return $this->sendJavascript($response);
    }

    #[Route('/probes/active/{probe}/worksheet.pdf', name: 'probe_worksheet_pdf')]
    public function worksheetPdf(Probe $probe, PdfServiceInterface $pdfService): Response
    {
        $pdf = $pdfService->generateWorksheet($probe);

        return new Response($pdf, Response::HTTP_OK, ['Content-Type' => 'application/pdf']);
    }

    #[Route('/probes/active/{probe}/report.pdf', name: 'probe_report_pdf_test')]
    public function reportPdfTest(Probe $probe, PdfServiceInterface $pdfService, FileServiceInterface $fileService, ManagerRegistry $registry, TranslatorInterface $translator): Response
    {
        $report = new Report();
        $report->setProbe($probe);
        $report->setDate(new \DateTimeImmutable());
        $report->setPayload([]);
        $report->setAddresses(["Florian Moser\nOchsengasse 66\n4123 Allschwil", "Peter Schütz\nOchsengasse 66\n4123 Allschwil"]);
        $report->setTitle("Schlussbericht");
        /** @phpstan-ignore-next-line */
        $report->attribute($this->getUser());

        /** @phpstan-ignore-next-line */
        $report->setPayload([
            "certified" => true,
            "results" => [[
                "analysis" => "Identifizierung / Typisierung von " . $probe->getPathogen()->trans($translator),
                "method" => 99,
                "result" => "Keine Aussage",
                "comment" => null,
            ]]
        ]);

        $pdf = $pdfService->generateReport($report);
        $preferredFilename = $report->getProbe()->getIdentifier() . " - " . $report->getTitle() . " - " . $report->getDate()->format("Y.m.d") . ".pdf";
        $filename = $fileService->saveFile(FileServiceInterface::REPORT_FOLDER, $preferredFilename, $pdf);
        $report->setFilename($filename);
        DoctrineHelper::persistAndFlush($registry, $report);

        return $this->redirectToRoute("report_download", ['report' => $report->getId(), 'filename' => $filename]);
    }

    #[Route('/probes/all', name: 'probe_all')]
    public function all(): Response
    {
        return $this->render('probe/all.html.twig');
    }

    #[Route('/probes/all.js', name: 'probe_all_js')]
    public function allJs(): Response
    {
        $response = $this->render('probe/all.js.twig');

        return $this->sendJavascript($response);
    }

    #[Route('/probes/all/{probe}/view', name: 'probe_all_view')]
    public function allView(Probe $probe): Response
    {
        if (!$probe->getFinishedAt()) {
            return $this->redirectToRoute('probe_active_view', ['probe' => $probe->getId()]);
        }

        return $this->render('probe/all_view.html.twig', ['probe' => $probe]);
    }

    private function sendJavascript(Response $response): Response
    {
        $response->headers->set('Content-Type', 'text/javascript');
        return $response;
    }
}
