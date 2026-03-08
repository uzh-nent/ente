<?php

namespace App\Services;

use App\Entity\Invoice;
use App\Enum\InvoiceReceiver;
use App\Services\Interfaces\ExportServiceInterface;
use App\Services\Interfaces\InvoiceServiceInterface;
use Doctrine\Persistence\ManagerRegistry;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Symfony\Component\HttpFoundation\Response;

readonly class InvoiceService implements InvoiceServiceInterface
{
    public function __construct(private ManagerRegistry $managerRegistry, private ExportServiceInterface $exportService)
    {
    }

    public function invoicePatients(\DateTimeImmutable $from, \DateTimeImmutable $to): Response
    {
        /** @var Invoice[] $invoices */
        $invoices = $this->managerRegistry
            ->getRepository(Invoice::class)
            ->createQueryBuilder('i')
            ->andWhere('i.date IS NOT NULL')
            ->andWhere('i.date >= :from')
            ->andWhere('i.date <= :to')
            ->andWhere('i.receiver = :receiver')
            ->setParameter('from', $from)
            ->setParameter('to', $to)
            ->setParameter('receiver', InvoiceReceiver::PATIENT->value)
            ->orderBy('i.date', 'ASC')
            ->getQuery()
            ->getResult();

        $fullPath = __DIR__ . "/../../assets/resources/invoice/invoice_patients_template.xlsx";
        $reader = IOFactory::createReaderForFile($fullPath);

        $spreadsheet = $reader->load($fullPath);

        $templateSheet = $spreadsheet->getSheet(0);
        $summarySheet = $spreadsheet->getSheet($spreadsheet->getSheetCount() - 1);

        $period = $from->format('d.m.Y') . ' - ' . $to->format('d.m.Y');
        $summaryRows = [];
        $fullTotal = 0.0;
        foreach (array_reverse($invoices) as $invoice) {
            $probe = $invoice->getProbe();

            $invoiceSheet = clone $templateSheet;
            $invoiceSheet->setTitle($probe->getIdentifier());
            $spreadsheet->addSheet($invoiceSheet, 1);

            $invoiceTotal = $this->fillInvoiceSheet($invoiceSheet, $invoice, $period);
            $summaryRows[] = [$probe->getIdentifier(), $probe->getRequisitionIdentifier(), $invoiceTotal];
            $fullTotal += $invoiceTotal;
        }

        $this->fillSummarySheet($summarySheet, array_reverse($summaryRows), $fullTotal, $period);
        $spreadsheet->removeSheetByIndex(0);

        $writer = IOFactory::createWriter($spreadsheet, IOFactory::WRITER_XLSX);
        return $this->exportService->createExcelResponse($writer, "invoices_patients.xlsx");
    }

    private function fillInvoiceSheet(Worksheet $invoiceSheet, Invoice $invoice, string $period): float
    {
        $invoiceSheet->setCellValue('C1', (new \DateTimeImmutable())->format('d.m.Y'));
        $invoiceSheet->setCellValue('E1', "Periode: " . $period);

        $probe = $invoice->getProbe();
        if (!$probe) {
            return 0.0;
        }

        $ordererFullAddress = $probe->getOrdererOrg() ? $probe->getOrdererOrgFullAddress() : $probe->getOrdererPracFullAddress();
        $receiverFullAddress = $invoice->getReceiver() === InvoiceReceiver::PATIENT ? $probe->getPatientFullAddress() : $ordererFullAddress;
        $invoiceSheet->setCellValue('A4', $receiverFullAddress);
        $invoiceSheet->setCellValue('E4', $ordererFullAddress);

        $invoiceSheet->setCellValue('B6', $probe->getPatientBirthDate()?->format('d.m.Y'));
        $invoiceSheet->setCellValue('D5', $probe->getIdentifier());
        $invoiceSheet->setCellValue('D6', $probe->getRequisitionIdentifier());
        $invoiceSheet->setCellValue('D7', $probe->getSpecimenCollectionDate()?->format('d.m.Y'));

        $totalTP = 0.0;
        $totalAmount = 0.0;
        foreach ($invoice->getLineItems() as $lineItem) {
            $totalTP += $lineItem['tp'];
            $totalAmount += $lineItem['tp'] * $lineItem['tpw'];
        }
        $invoiceSheet->setCellValue('F11', $totalTP);
        $invoiceSheet->setCellValue('G11', $totalAmount);
        self::setAmountCellStyle($invoiceSheet, 'G11');

        foreach (array_reverse($invoice->getLineItems()) as $lineItem) {
            $invoiceSheet->insertNewRowBefore(10);
            $invoiceSheet->setCellValue('A10', $lineItem['service']);
            $invoiceSheet->setCellValue('D10', $lineItem['tarif']);
            $invoiceSheet->setCellValue('E10', " " . $lineItem['position']);
            $invoiceSheet->setCellValue('F10', " " . $lineItem['tp']);
            $invoiceSheet->setCellValue('G10', $lineItem['tp'] * $lineItem['tpw']);
            self::setAmountCellStyle($invoiceSheet, 'G10');
        }

        return $totalAmount;
    }

    private function fillSummarySheet(Worksheet $summarySheet, array $summaryRows, float $fullTotal, string $period): void
    {
        $summarySheet->setCellValue('D5', $fullTotal);
        self::setAmountCellStyle($summarySheet, 'D5');

        $summarySheet->setCellValue('C1', "Periode: " . $period);

        foreach (array_reverse($summaryRows) as $summaryRow) {
            $summarySheet->insertNewRowBefore(4);
            $summarySheet->setCellValue('A4', $summaryRow[0]);
            $summarySheet->setCellValue('B4', $summaryRow[1]);
            $summarySheet->setCellValue('D4', $summaryRow[2]);
            self::setAmountCellStyle($summarySheet, 'D4');
        }
    }

    private static function setAmountCellStyle(Worksheet $worksheet, string $cellCoordinate): void
    {
        $worksheet->getStyle($cellCoordinate)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
    }
}
