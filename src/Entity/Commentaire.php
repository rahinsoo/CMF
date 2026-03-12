<?php

namespace App\Entity;

use App\Repository\CommentaireRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * Entité représentant un commentaire posté dans la zone de discussion d'un webinar.
 */
#[ORM\Entity(repositoryClass: CommentaireRepository::class)]
class Commentaire
{
    /**
     * Identifiant unique du commentaire (généré automatiquement).
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * Nom de l'auteur du commentaire.
     */
    #[ORM\Column(length: 255)]
    private ?string $auteur = null;

    /**
     * Contenu textuel du commentaire.
     */
    #[ORM\Column(type: Types::TEXT)]
    private ?string $contenu = null;

    /**
     * Webinar auquel appartient ce commentaire.
     */
    #[ORM\ManyToOne(targetEntity: Webinar::class, inversedBy: 'commentaires')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Webinar $webinar = null;

    /**
     * Date et heure de création du commentaire.
     */
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $createdAt = null;

    /**
     * Constructeur : initialise la date de création à maintenant.
     */
    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }

    /**
     * Retourne l'identifiant du commentaire.
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Retourne le nom de l'auteur.
     */
    public function getAuteur(): ?string
    {
        return $this->auteur;
    }

    /**
     * Définit le nom de l'auteur.
     */
    public function setAuteur(string $auteur): static
    {
        $this->auteur = $auteur;
        return $this;
    }

    /**
     * Retourne le contenu du commentaire.
     */
    public function getContenu(): ?string
    {
        return $this->contenu;
    }

    /**
     * Définit le contenu du commentaire.
     */
    public function setContenu(string $contenu): static
    {
        $this->contenu = $contenu;
        return $this;
    }

    /**
     * Retourne le webinar associé au commentaire.
     */
    public function getWebinar(): ?Webinar
    {
        return $this->webinar;
    }

    /**
     * Définit le webinar associé au commentaire.
     */
    public function setWebinar(?Webinar $webinar): static
    {
        $this->webinar = $webinar;
        return $this;
    }

    /**
     * Retourne la date de création du commentaire.
     */
    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * Définit la date de création du commentaire.
     */
    public function setCreatedAt(\DateTimeInterface $createdAt): static
    {
        $this->createdAt = $createdAt;
        return $this;
    }
}
