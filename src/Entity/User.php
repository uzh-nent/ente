<?php

namespace App\Entity;

use App\Entity\Traits\IdTrait;
use App\Entity\Traits\TimeTrait;
use App\Repository\UserRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\HasLifecycleCallbacks]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    use IdTrait;
    use TimeTrait;

    #[ORM\Column(type: Types::STRING, unique: true)]
    private ?string $shortname;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    private ?string $password = null;

    #[ORM\Column(type: Types::BOOLEAN)]
    private bool $medicalValidation = false;

    public function getUserIdentifier(): string
    {
        return $this->shortname;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function eraseCredentials(): void
    {
        // never plain password stored
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';
        if ($this->shortname === 'flomos') {
            $roles[] = 'ROLE_ADMIN';
            $roles[] = 'ROLE_ALLOWED_TO_SWITCH';
        }

        return array_unique($roles);
    }
}
