<?php

namespace App\Controller;

use App\Entity\Probe;
use App\Enum\LaboratoryFunction;
use App\Services\Interfaces\FileServiceInterface;
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
