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
use App\Api\Processor\ReportEmailProcessor;
use App\Api\Processor\ReportProcessor;
use App\Entity\Traits\AttributionTrait;
use App\Entity\Traits\CommentTrait;
use App\Entity\Traits\IdTrait;
use App\Entity\Traits\TimeTrait;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity]
#[ORM\HasLifecycleCallbacks]
#[ApiResource(
    processor: ReportEmailProcessor::class,
    denormalizationContext: ['groups' => ['comment:write', 'report-email:write']],
    normalizationContext: ['groups' => ['time:read', 'attribution:read', 'report-email:read']],
    paginationEnabled: false
)]
#[Get]
#[Post]
#[GetCollection]
#[ApiFilter(SearchFilter::class, properties: ['probe' => SearchFilterInterface::STRATEGY_EXACT])]
class ReportEmail
{
    use IdTrait;
    use AttributionTrait;
    use TimeTrait;

    #[ORM\ManyToOne(targetEntity: Probe::class)]
    #[ApiProperty(readableLink: false, writableLink: false)]
    #[Groups(['report-email:read', 'report-email:write'])]
    private ?Probe $probe = null;

    #[ORM\ManyToOne(targetEntity: Report::class)]
    #[ApiProperty(readableLink: false, writableLink: false)]
    #[Groups(['report-email:read', 'report-email:write'])]
    private ?Report $report = null;

    #[ORM\Column(type: Types::STRING)]
    #[Groups(['report-email:read', 'report-email:write'])]
    private string $receivers = '';

    #[ORM\Column(type: Types::STRING, nullable: true)]
    #[Groups(['report-email:read', 'report-email:write'])]
    private ?string $ccReceivers = null;

    #[ORM\Column(type: Types::STRING)]
    #[Groups(['report-email:read', 'report-email:write'])]
    private string $subject = '';

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['report-email:read', 'report-email:write'])]
    private string $body = '';

    #[ORM\Column(type: Types::DATE_IMMUTABLE, nullable: true)]
    #[Groups(['report-email:read', 'report-email:write'])]
    private ?\DateTimeImmutable $sentAt = null;

    public function getProbe(): ?Probe
    {
        return $this->probe;
    }

    public function setProbe(?Probe $probe): void
    {
        $this->probe = $probe;
    }

    public function getReport(): ?Report
    {
        return $this->report;
    }

    public function setReport(?Report $report): void
    {
        $this->report = $report;
    }

    public function getReceivers(): string
    {
        return $this->receivers;
    }

    /**
     * @return string[]
     */
    public function getReceiversArray(): array
    {
        return array_map(fn ($email) => trim($email), explode(",", $this->receivers));
    }

    public function setReceivers(string $receivers): void
    {
        $this->receivers = $receivers;
    }

    public function getCcReceivers(): ?string
    {
        return $this->ccReceivers;
    }

    /**
     * @return string[]
     */
    public function getCCReceiversArray(): array
    {
        if (!$this->ccReceivers || trim($this->ccReceivers) === '') {
            return [];
        }

        return array_map(fn ($email) => trim($email), explode(",", $this->ccReceivers));
    }

    public function setCcReceivers(?string $ccReceivers): void
    {
        $this->ccReceivers = $ccReceivers;
    }

    public function getSubject(): ?string
    {
        return $this->subject;
    }

    public function setSubject(?string $subject): void
    {
        $this->subject = $subject;
    }

    public function getBody(): ?string
    {
        return $this->body;
    }

    public function setBody(?string $body): void
    {
        $this->body = $body;
    }

    public function getSentAt(): ?\DateTimeImmutable
    {
        return $this->sentAt;
    }

    public function setSentAt(?\DateTimeImmutable $sentAt): void
    {
        $this->sentAt = $sentAt;
    }
}
