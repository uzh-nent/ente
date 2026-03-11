<?php

namespace App\Controller;

use App\Entity\Invoice;
use App\Entity\Probe;
use App\Entity\User;
use App\Helper\DoctrineHelper;
use App\Services\Interfaces\InvoiceServiceInterface;
use App\Services\Interfaces\PdfServiceInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Attribute\Route;

class InvoiceController extends AbstractController
{
    #[Route('/invoices', name: 'invoice_index')]
    public function new(): Response
    {
        return $this->render('invoice/index.html.twig');
    }

    #[Route('/invoices/patients', name: 'invoice_patients')]
    public function patients(Request $request, InvoiceServiceInterface $invoiceService): Response
    {
        [$after, $before] = $this->parsePeriod($request);

        return $invoiceService->invoicePatients($after, $before);
    }

    #[Route('/invoices/orderers', name: 'invoice_orderers')]
    public function orderers(Request $request, InvoiceServiceInterface $invoiceService): Response
    {
        [$after, $before] = $this->parsePeriod($request);

        return $invoiceService->invoiceOrderers($after, $before);
    }

    #[Route('/invoices/set_identifiers', name: 'invoice_set_identifiers')]
    public function setInvoiceIdentifiers(Request $request, ManagerRegistry $managerRegistry): Response
    {
        $body = json_decode($request->getContent(), true);
        $probeIdentifiers = explode("\n", $body['probeIdentifiers']);
        $invoiceIdentifiers = explode("\n", $body['invoiceIdentifiers']);

        $errors = [];
        $successful = 0;
        foreach ($probeIdentifiers as $index => $identifier) {
            if (empty($identifier)) {
                continue;
            }

            $probe = $managerRegistry->getRepository(Probe::class)->findOneBy(['identifier' => $identifier]);
            if (!$probe) {
                $errors[] = "Probe $identifier not found.";
                continue;
            }

            if (count($probe->getInvoices()) === 0) {
                $errors[] = "No invoice defined for probe $identifier.";
                continue;
            }

            if (count($probe->getInvoices()) > 1) {
                $errors[] = "Multiple invoices defined for probe $identifier.";
                continue;
            }

            $invoice = $probe->getInvoices()->first();
            $invoice->setInvoiceIdentifier($invoiceIdentifiers[$index]);
            DoctrineHelper::persistAndFlush($managerRegistry, $invoice);
            $successful++;
        }

        return $this->json(['successful' => $successful, 'errors' => $errors]);
    }

    #[Route('/invoices/receipts', name: 'invoice_receipts')]
    public function receipts(Request $request, ManagerRegistry $managerRegistry, PdfServiceInterface $pdfService): Response
    {
        $body = json_decode($request->getContent(), true);
        $invoiceIdentifiers = explode("\n", $body['invoiceIdentifiers']);
        $nonEmptyIdentifiers = array_filter($invoiceIdentifiers);
        $invoices = $managerRegistry->getRepository(Invoice::class)->findBy(['invoiceIdentifier' => $nonEmptyIdentifiers]);

        /** @var User $user */
        $user = $this->getUser();

        $file = $pdfService->generateReceipts($invoices, $user->getAbbreviation());
        return $this->createPdfResponse($file, "receipts.pdf");
    }

    public function createPdfResponse(string $content, string $filename): Response
    {
        $response = new Response($content);

        $response->headers->set('Content-Type', 'application/pdf');
        $response->headers->set('Content-Disposition', 'attachment;filename="' . $filename . '"');
        $response->headers->set('Cache-Control', 'max-age=0');

        return $response;
    }

    /**
     * @param Request $request
     * @return array{\DateTimeImmutable, \DateTimeImmutable}
     */
    private function parsePeriod(Request $request): array
    {
        $period = $request->query->all()['period'] ?? [];

        try {
            $after = new \DateTimeImmutable($period['after'] ?? null);
            $before = new \DateTimeImmutable($period['before'] ?? null);
        } catch (\Exception) {
            throw new BadRequestHttpException('Invalid date range.');
        }

        return [$after, $before];
    }
}
