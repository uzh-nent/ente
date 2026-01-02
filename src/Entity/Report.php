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
use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use App\Api\Processor\ReportProcessor;
use App\Entity\Traits\AttributionTrait;
use App\Entity\Traits\CommentTrait;
use App\Entity\Traits\IdTrait;
use App\Entity\Traits\TimeTrait;
use App\Enum\CodeSystem;
use App\Enum\ReportReceiver;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity]
#[ORM\HasLifecycleCallbacks]
#[ApiResource(
    processor: ReportProcessor::class,
    denormalizationContext: ['groups' => ['comment:write', 'report:write']],
    normalizationContext: ['groups' => ['time:read', 'attribution:read', 'comment:read', 'report:read']],
    paginationEnabled: false
)]
#[Get]
#[Post]
#[GetCollection]
#[ApiFilter(SearchFilter::class, properties: ['probe' => SearchFilterInterface::STRATEGY_EXACT])]
#[ApiFilter(OrderFilter::class, properties: ['effectiveAt'])]
class Report
{
    use IdTrait;
    use CommentTrait;
    use AttributionTrait;
    use TimeTrait;

    #[ORM\ManyToOne(targetEntity: Organization::class)]
    #[ApiProperty(readableLink: false, writableLink: false)]
    #[Groups(['report:read', 'report:write'])]
    private ?Probe $probe = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    #[Groups(['report:read', 'report:write'])]
    private ?string $title = null;

    #[ORM\Column(type: Types::STRING, enumType: CodeSystem::class)]
    #[Groups(['coded-identifier:read'])]
    private ReportReceiver $receiver = ReportReceiver::PROBE_ORDERER_ORG;

    #[ORM\ManyToOne(targetEntity: Organization::class)]
    #[ApiProperty(readableLink: false, writableLink: false)]
    #[Groups(['report:read', 'report:write'])]
    private ?Organization $receiverOrg = null;

    #[ORM\ManyToOne(targetEntity: Practitioner::class)]
    #[ApiProperty(readableLink: false, writableLink: false)]
    #[Groups(['report:read', 'report:write'])]
    private ?Practitioner $receiverPrac = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE, nullable: true)]
    #[Groups(['report:read', 'report:write'])]
    private ?\DateTimeImmutable $date = null;

    /**
     * @var array<string, string[][]>|null
     */
    #[ORM\Column(type: Types::JSON, nullable: true)]
    #[Groups(['report:read', 'report:write'])]
    private ?array $payload = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ApiProperty(readableLink: false, writableLink: false)]
    #[Groups(['report:read', 'report:write'])]
    private ?User $validationBy = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    #[Groups(['report:read', 'report:write'])]
    private ?string $filename = null;

    public function getProbe(): ?Probe
    {
        return $this->probe;
    }

    public function setProbe(?Probe $probe): void
    {
        $this->probe = $probe;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }

    public function getReceiver(): ReportReceiver
    {
        return $this->receiver;
    }

    public function setReceiver(ReportReceiver $receiver): void
    {
        $this->receiver = $receiver;
    }

    public function getReceiverOrg(): ?Organization
    {
        return $this->receiverOrg;
    }

    public function setReceiverOrg(?Organization $receiverOrg): void
    {
        $this->receiverOrg = $receiverOrg;
    }

    public function getReceiverPrac(): ?Practitioner
    {
        return $this->receiverPrac;
    }

    public function setReceiverPrac(?Practitioner $receiverPrac): void
    {
        $this->receiverPrac = $receiverPrac;
    }

    public function getDate(): ?\DateTimeImmutable
    {
        return $this->date;
    }

    public function setDate(?\DateTimeImmutable $date): void
    {
        $this->date = $date;
    }

    /**
     * @return array<string, string[][]>|null
     */
    public function getPayload(): ?array
    {
        return $this->payload;
    }

    /**
     * @param array<string, string[][]>|null $payload
     */
    public function setPayload(?array $payload): void
    {
        $this->payload = $payload;
    }

    public function getValidationBy(): ?User
    {
        return $this->validationBy;
    }

    public function setValidationBy(?User $validationBy): void
    {
        $this->validationBy = $validationBy;
    }

    public function getFilename(): ?string
    {
        return $this->filename;
    }

    public function setFilename(?string $filename): void
    {
        $this->filename = $filename;
    }
}
