<?php

/*
 * This file is part of the baupen project.
 *
 * (c) Florian Moser <git@famoser.ch>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Entity;

use ApiPlatform\Doctrine\Common\Filter\SearchFilterInterface;
use ApiPlatform\Doctrine\Orm\Filter\ExistsFilter;
use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use App\Api\Processor\InvoiceProcessor;
use App\Entity\Traits\AttributionTrait;
use App\Entity\Traits\CommentTrait;
use App\Entity\Traits\IdTrait;
use App\Entity\Traits\TimeTrait;
use App\Enum\InvoiceReceiver;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity]
#[ORM\HasLifecycleCallbacks]
#[ApiResource(
    processor: InvoiceProcessor::class,
    denormalizationContext: ['groups' => ['comment:write', 'invoice:write']],
    normalizationContext: ['groups' => ['time:read', 'attribution:read', 'comment:read', 'invoice:read', 'probe:read']],
)]
#[Get]
#[Post]
#[GetCollection]
#[ApiFilter(SearchFilter::class, properties: ['probe' => SearchFilterInterface::STRATEGY_EXACT, 'date' => SearchFilterInterface::STRATEGY_EXACT])]
#[ApiFilter(OrderFilter::class, properties: ['date'])]
#[ApiFilter(ExistsFilter::class, properties: ['invoiceIdentifier'])]
class Invoice
{
    use IdTrait;
    use CommentTrait;
    use AttributionTrait;
    use TimeTrait;

    #[ORM\ManyToOne(targetEntity: Probe::class)]
    #[ApiProperty(readableLink: false, writableLink: false)]
    #[Groups(['invoice:read', 'invoice:write'])]
    private ?Probe $probe = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE, nullable: true)]
    #[Groups(['invoice:read', 'invoice:write'])]
    private ?\DateTimeImmutable $date = null;

    #[ORM\Column(type: Types::STRING, enumType: InvoiceReceiver::class, nullable: true)]
    #[Groups(['probe:read', 'invoice:write'])]
    private ?InvoiceReceiver $receiver = null;

    /**
     * @var null|array<array{'service': ?string, 'tarif': ?string, 'position'?: ?string, 'tp': ?float, 'tpw'?: ?float}>
     */
    #[ORM\Column(type: Types::JSON, nullable: true)]
    #[Groups(['invoice:read', 'invoice:write'])]
    private ?array $lineItems = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    #[Groups(['invoice:read', 'invoice:write'])]
    private ?string $invoiceIdentifier = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    #[Groups(['invoice:read', 'invoice:write'])]
    private ?string $reimbursementVoucherFilename = null;

    public function getProbe(): ?Probe
    {
        return $this->probe;
    }

    public function setProbe(?Probe $probe): void
    {
        $this->probe = $probe;
    }

    public function getDate(): ?\DateTimeImmutable
    {
        return $this->date;
    }

    public function setDate(?\DateTimeImmutable $date): void
    {
        $this->date = $date;
    }

    public function getReceiver(): ?InvoiceReceiver
    {
        return $this->receiver;
    }

    public function setReceiver(?InvoiceReceiver $receiver): void
    {
        $this->receiver = $receiver;
    }

    public function getLineItems(): ?array
    {
        return $this->lineItems;
    }

    public function setLineItems(?array $lineItems): void
    {
        $this->lineItems = $lineItems;
    }

    public function getInvoiceIdentifier(): ?string
    {
        return $this->invoiceIdentifier;
    }

    public function setInvoiceIdentifier(?string $invoiceIdentifier): void
    {
        $this->invoiceIdentifier = $invoiceIdentifier;
    }

    public function getReimbursementVoucherFilename(): ?string
    {
        return $this->reimbursementVoucherFilename;
    }

    public function setReimbursementVoucherFilename(?string $reimbursementVoucherFilename): void
    {
        $this->reimbursementVoucherFilename = $reimbursementVoucherFilename;
    }
}
