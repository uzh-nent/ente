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

use App\Entity\Traits\CommentTrait;
use App\Entity\Traits\IdTrait;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\HasLifecycleCallbacks]
class Report
{
    use IdTrait;
    use CommentTrait;

    #[ORM\ManyToOne(targetEntity: Organization::class)]
    private ?Probe $probe = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    private ?string $title = null;

    #[ORM\ManyToOne(targetEntity: Organization::class)]
    private ?Organization $receiver = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    private ?User $signedBy = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE, nullable: true)]
    private ?\DateTime $date = null;

    #[ORM\Column(type: Types::JSON, nullable: true)]
    private ?string $payload = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
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

    public function getReceiver(): ?Organization
    {
        return $this->receiver;
    }

    public function setReceiver(?Organization $receiver): void
    {
        $this->receiver = $receiver;
    }

    public function getSignedBy(): ?User
    {
        return $this->signedBy;
    }

    public function setSignedBy(?User $signedBy): void
    {
        $this->signedBy = $signedBy;
    }

    public function getDate(): ?\DateTime
    {
        return $this->date;
    }

    public function setDate(?\DateTime $date): void
    {
        $this->date = $date;
    }

    public function getPayload(): ?string
    {
        return $this->payload;
    }

    public function setPayload(?string $payload): void
    {
        $this->payload = $payload;
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
