<?php

namespace App\Services;

use App\Entity\Invoice;
use App\Enum\InvoiceReceiver;
use App\Services\Interfaces\ExportServiceInterface;
use App\Services\Interfaces\InvoiceServiceInterface;
use Doctrine\Persistence\ManagerRegistry;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
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
        $invoices = $this->findInvoices($from, $to, InvoiceReceiver::PATIENT);

        $fullPath = __DIR__ . "/../../assets/resources/invoice/invoice_patients_template.xlsx";
        $spreadsheet = $this->readSpreadsheet($fullPath);

        $templateSheet = $spreadsheet->getSheet(0);
        $summarySheet = $spreadsheet->getSheet($spreadsheet->getSheetCount() - 1);

        $period = $from->format('d.m.Y') . ' - ' . $to->format('d.m.Y');
        $summaryRows = [];
        $fullTotal = 0.0;
        foreach (array_reverse($invoices) as $invoice) {
            $probe = $invoice->getProbe();

            $invoiceSheet = clone $templateSheet;
            $invoiceSheet->setTitle(self::parseSafeTitle($invoiceSheet, $probe->getIdentifier()));
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


    public function invoiceOrderers(\DateTimeImmutable $from, \DateTimeImmutable $to): Response
    {
        $invoices = $this->findInvoices($from, $to, InvoiceReceiver::ORDERER);
        $invoicesPerOrderer = [];
        foreach ($invoices as $invoice) {
            $probe = $invoice->getProbe();
            if (!$probe) {
                continue;
            }

            $address = $invoice->getAddress();
            $ordererFullAddress = $probe->getOrdererOrg() ? $probe->getOrdererOrgFullAddress() : $probe->getOrdererPracFullAddress();
            $key = $address . "DIVIDER" . $ordererFullAddress;
            if (!isset($invoicesPerOrderer[$key])) {
                $invoicesPerOrderer[$key] = [];
            }

            $invoicesPerOrderer[$key][] = $invoice;
        }

        $fullPath = __DIR__ . "/../../assets/resources/invoice/invoice_orderers_template.xlsx";
        $spreadsheet = $this->readSpreadsheet($fullPath);

        $templateSheet = $spreadsheet->getSheet(0);
        $summarySheet = $spreadsheet->getSheet($spreadsheet->getSheetCount() - 1);

        $period = $from->format('d.m.Y') . ' - ' . $to->format('d.m.Y');
        $summaryRows = [];
        $fullTotal = 0.0;
        foreach ($invoicesPerOrderer as $invoices) {
            $firstInvoice = $invoices[0] ?? null;
            if (!$firstInvoice) {
                continue;
            }

            $firstProbe = $firstInvoice->getProbe();
            if (!$firstProbe) {
                continue;
            }

            $ordererShortAddress = $firstProbe->getOrdererOrg() ? $firstProbe->getOrdererOrgShortAddress() : $firstProbe->getOrdererPracShortAddress();

            $invoiceSheet = clone $templateSheet;
            $invoiceSheet->setTitle(self::parseSafeTitle($invoiceSheet, $ordererShortAddress));
            $spreadsheet->addSheet($invoiceSheet, 1);

            $invoiceTotal = $this->fillOrdererInvoiceSheet($invoiceSheet, $invoices, $period);
            $summaryRows[] = [count($invoicesPerOrderer) - count($summaryRows), $ordererShortAddress, $firstInvoice->getAddress(), $invoiceTotal];
            $fullTotal += $invoiceTotal;
        }

        $this->fillOrdererSummarySheet($summarySheet, array_reverse($summaryRows), $fullTotal, $period);
        $spreadsheet->removeSheetByIndex(0);

        $writer = IOFactory::createWriter($spreadsheet, IOFactory::WRITER_XLSX);
        return $this->exportService->createExcelResponse($writer, "invoices_orderers.xlsx");
    }

    /**
     * @return Invoice[]
     */
    private function findInvoices(\DateTimeImmutable $from, \DateTimeImmutable $to, InvoiceReceiver $receiver): array
    {
        return $this->managerRegistry
            ->getRepository(Invoice::class)
            ->createQueryBuilder('i')
            ->andWhere('i.date IS NOT NULL')
            ->andWhere('i.date >= :from')
            ->andWhere('i.date <= :to')
            ->andWhere('i.receiver = :receiver')
            ->setParameter('from', $from)
            ->setParameter('to', $to)
            ->setParameter('receiver', $receiver->value)
            ->orderBy('i.date', 'ASC')
            ->getQuery()
            ->getResult();
    }

    private function fillInvoiceSheet(Worksheet $invoiceSheet, Invoice $invoice, string $period): float
    {
        $invoiceSheet->setCellValue('C1', $invoice->getDate()->format('d.m.Y'));
        $invoiceSheet->setCellValue('E1', "Periode: " . $period);

        $probe = $invoice->getProbe();
        if (!$probe) {
            return 0.0;
        }

        $ordererFullAddress = $probe->getOrdererOrg() ? $probe->getOrdererOrgFullAddress() : $probe->getOrdererPracFullAddress();
        $invoiceSheet->setCellValue('A4', $invoice->getAddress());
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
            $invoiceSheet->setCellValue('D10', " " . $lineItem['tarif']);
            $invoiceSheet->setCellValue('E10', " " . $lineItem['position']);
            $invoiceSheet->setCellValue('F10', " " . $lineItem['tp']);
            $invoiceSheet->setCellValue('G10', $lineItem['tp'] * $lineItem['tpw']);
            self::setAmountCellStyle($invoiceSheet, 'G10');
        }

        return $totalAmount;
    }

    /**
     * @param Worksheet $invoiceSheet
     * @param Invoice[] $invoices
     * @param string $period
     * @return float
     */
    private function fillOrdererInvoiceSheet(Worksheet $invoiceSheet, array $invoices, string $period): float
    {
        $invoiceSheet->setCellValue('E1', "Periode: " . $period);

        $firstInvoice = $invoices[0] ?? null;
        if (!$firstInvoice) {
            return 0.0;
        }

        $firstProbe = $firstInvoice->getProbe();
        if (!$firstProbe) {
            return 0.0;
        }

        $ordererFullAddress = $firstProbe->getOrdererOrg() ? $firstProbe->getOrdererOrgFullAddress() : $firstProbe->getOrdererPracFullAddress();
        $invoiceSheet->setCellValue('A1', "Auftraggeber:\n" . $ordererFullAddress);
        $invoiceSheet->setCellValue('H1', "Rechnungsaddresse:\n" . $firstInvoice->getAddress());

        $totalTP = 0.0;
        $totalAmount = 0.0;
        foreach ($invoices as $invoice) {
            foreach ($invoice->getLineItems() as $lineItem) {
                $totalTP += $lineItem['tp'];
                $totalAmount += $lineItem['tp'] * $lineItem['tpw'];
            }
        }
        $invoiceSheet->setCellValue('I5', $totalTP);
        $invoiceSheet->setCellValue('J5', $totalAmount);
        self::setAmountCellStyle($invoiceSheet, 'J5');

        foreach (array_reverse($invoices) as $index => $invoice) {
            /** @var Invoice $invoice */
            $invoiceSheet->insertNewRowBefore(4);
            if ($index > 0) {
                $invoiceSheet->insertNewRowBefore(4);
            }

            $probe = $invoice->getProbe();
            if (!$probe) {
                continue;
            }

            $invoiceSheet->setCellValue('A4', $probe->getIdentifier());
            $invoiceSheet->setCellValue('B4', $probe->getRequisitionIdentifier());
            $invoiceSheet->setCellValue('C4', $probe->getSpecimenCollectionDate()?->format('d.m.Y'));
            if ($probe->getPatient()) {
                $invoiceSheet->setCellValue('D4', $probe->getPatientFamilyName() . ", " . $probe->getPatientGivenName());
                $invoiceSheet->setCellValue('E4', $probe->getPatientBirthDate()?->format('d.m.Y'));
            } else {
                $invoiceSheet->setCellValue('D4', $probe->getAnimalName());
            }

            if (count($invoice->getLineItems()) === 1) {
                $lineItem = $invoice->getLineItems()[0];
                $invoiceSheet->setCellValue('F4', " " . $lineItem['tarif']);
                $invoiceSheet->setCellValue('G4', " " . $lineItem['position']);
                $invoiceSheet->setCellValue('H4', $lineItem['service']);
                $invoiceSheet->setCellValue('I4', " " . $lineItem['tp']);
                $invoiceSheet->setCellValue('J4', $lineItem['tp'] * $lineItem['tpw']);
                self::setAmountCellStyle($invoiceSheet, 'J4');
                continue;
            }

            // add total row
            $totalPatientTP = 0.0;
            $totalPatientAmount = 0.0;
            foreach ($invoice->getLineItems() as $lineItem) {
                $totalPatientTP += $lineItem['tp'];
                $totalPatientAmount += $lineItem['tp'] * $lineItem['tpw'];
            }
            $invoiceSheet->setCellValue('H4', "Subtotal");
            $invoiceSheet->setCellValue('I4', " " . $totalPatientTP);
            $invoiceSheet->setCellValue('J4', $totalPatientAmount);
            self::setAmountCellStyle($invoiceSheet, 'J4');

            foreach (array_reverse($invoice->getLineItems()) as $lineItem) {
                $invoiceSheet->insertNewRowBefore(5);

                $invoiceSheet->setCellValue('F5', " " . $lineItem['tarif']);
                $invoiceSheet->setCellValue('G5', " " . $lineItem['position']);
                $invoiceSheet->setCellValue('H5', $lineItem['service']);
                $invoiceSheet->setCellValue('I5', " " . $lineItem['tp']);
                $invoiceSheet->setCellValue('J5', $lineItem['tp'] * $lineItem['tpw']);
                self::setAmountCellStyle($invoiceSheet, 'J5');
            }
        }

        return $totalAmount;
    }

    /**
     * @param array{array{string, string|null, float}} $summaryRows
     */
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

    /**
     * @param array{array{int, string, string, float}} $summaryRows
     */
    private function fillOrdererSummarySheet(Worksheet $summarySheet, array $summaryRows, float $fullTotal, string $period): void
    {
        $summarySheet->setCellValue('D5', $fullTotal);
        self::setAmountCellStyle($summarySheet, 'D5');

        $summarySheet->setCellValue('D1', "Periode: " . $period);

        foreach (array_reverse($summaryRows) as $summaryRow) {
            $summarySheet->insertNewRowBefore(4);
            $summarySheet->setCellValue('A4', $summaryRow[0]);
            $summarySheet->setCellValue('B4', $summaryRow[1]);
            $summarySheet->setCellValue('C4', $summaryRow[2]);
            $summarySheet->setCellValue('D4', $summaryRow[3]);
            self::setAmountCellStyle($summarySheet, 'D4');
        }
    }

    private static function setAmountCellStyle(Worksheet $worksheet, string $cellCoordinate): void
    {
        $worksheet->getStyle($cellCoordinate)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
    }

    private static function parseSafeTitle(Worksheet $worksheet, string $proposedTitle): string
    {
        $safeTitle = preg_replace('/[^0-9a-zA-Z -,]/', '', $proposedTitle);
        return substr($safeTitle, 0, $worksheet::SHEET_TITLE_MAXIMUM_LENGTH - 5); // -5 to account for duplicated sheet titles that receive a counter at the end
    }

    public function readSpreadsheet(string $fullPath): Spreadsheet
    {
        $reader = IOFactory::createReaderForFile($fullPath);

        return $reader->load($fullPath);
    }
}
