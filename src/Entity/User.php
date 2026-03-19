<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private ?string $email = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column]
    private ?int $id_user = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $civilite = null;

    #[ORM\Column(length: 100)]
    private ?string $name = null;

    #[ORM\Column(length: 40)]
    private ?string $firstname = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $work = null;

    #[ORM\Column(nullable: true)]
    private ?int $teldirect = null;

    #[ORM\Column(nullable: true)]
    private ?bool $receivesms = null;

    #[ORM\Column]
    private ?bool $statutcreatewebinar = null;

    #[ORM\Column(nullable: true)]
    private ?bool $statutparticipatewebinar = null;

    #[ORM\Column(nullable: true)]
    private ?bool $needvalidationforwebinar = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Ensure the session doesn't contain actual password hashes by CRC32C-hashing them, as supported since Symfony 7.3.
     */
    public function __serialize(): array
    {
        $data = (array) $this;
        $data["\0".self::class."\0password"] = hash('crc32c', $this->password);

        return $data;
    }

    public function getIdUser(): ?int
    {
        return $this->id_user;
    }

    public function setIdUser(int $id_user): static
    {
        $this->id_user = $id_user;

        return $this;
    }

    public function getCivilite(): ?string
    {
        return $this->civilite;
    }

    public function setCivilite(?string $civilite): static
    {
        $this->civilite = $civilite;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): static
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getWork(): ?string
    {
        return $this->work;
    }

    public function setWork(?string $work): static
    {
        $this->work = $work;

        return $this;
    }

    public function getTeldirect(): ?int
    {
        return $this->teldirect;
    }

    public function setTeldirect(?int $teldirect): static
    {
        $this->teldirect = $teldirect;

        return $this;
    }

    public function isReceivesms(): ?bool
    {
        return $this->receivesms;
    }

    public function setReceivesms(?bool $receivesms): static
    {
        $this->receivesms = $receivesms;

        return $this;
    }

    public function isStatutcreatewebinar(): ?bool
    {
        return $this->statutcreatewebinar;
    }

    public function setStatutcreatewebinar(bool $statutcreatewebinar): static
    {
        $this->statutcreatewebinar = $statutcreatewebinar;

        return $this;
    }

    public function isStatutparticipatewebinar(): ?bool
    {
        return $this->statutparticipatewebinar;
    }

    public function setStatutparticipatewebinar(?bool $statutparticipatewebinar): static
    {
        $this->statutparticipatewebinar = $statutparticipatewebinar;

        return $this;
    }

    public function isNeedvalidationforwebinar(): ?bool
    {
        return $this->needvalidationforwebinar;
    }

    public function setNeedvalidationforwebinar(?bool $needvalidationforwebinar): static
    {
        $this->needvalidationforwebinar = $needvalidationforwebinar;

        return $this;
    }
}
