<?php

namespace App\Api\Processor;

use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\Invoice;
use App\Entity\User;
use App\Services\Interfaces\FileServiceInterface;
use App\Services\Interfaces\PdfServiceInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * @implements ProcessorInterface<Invoice, Invoice>
 */
readonly class InvoiceProcessor implements ProcessorInterface
{
    /**
     * @param ProcessorInterface<Invoice, Invoice> $persistProcessor
     */
    public function __construct(
        #[Autowire(service: 'api_platform.doctrine.orm.state.persist_processor')]
        private ProcessorInterface $persistProcessor,
        #[Autowire(service: 'api_platform.doctrine.orm.state.remove_processor')]
        private ProcessorInterface $removeProcessor,
        private TokenStorageInterface $tokenStorage,
        private PdfServiceInterface $pdfService,
        private FileServiceInterface $fileService,
        private ManagerRegistry $registry
    ) {
    }

    /**
     * @param Invoice $data
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): mixed
    {
        if ($operation instanceof Delete) {
            return $this->removeProcessor->process($data, $operation, $uriVariables, $context);
        }

        /** @var User $user */
        $user = $this->tokenStorage->getToken()->getUser();
        $data->attribute($user);

        $result = $this->persistProcessor->process($data, $operation, $uriVariables, $context);
        if ($result->getInvoiceIdentifier() && !$result->getReimbursementVoucherFilename()) {
        }

        return $result;
    }
}
