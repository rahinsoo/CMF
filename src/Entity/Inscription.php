<?php

namespace App\Entity;

use App\Repository\InscriptionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * Entité représentant l'inscription d'un élève/étudiant à un webinar.
 * Contient les informations personnelles du participant.
 */
#[ORM\Entity(repositoryClass: InscriptionRepository::class)]
class Inscription
{
    /**
     * Identifiant unique de l'inscription (généré automatiquement).
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * Nom de famille du participant.
     */
    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    /**
     * Prénom du participant.
     */
    #[ORM\Column(length: 255)]
    private ?string $prenom = null;

    /**
     * Adresse e-mail du participant.
     */
    #[ORM\Column(length: 255)]
    private ?string $email = null;

    /**
     * Établissement scolaire du participant (optionnel).
     */
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $ecole = null;

    /**
     * Région du participant (optionnelle).
     */
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $region = null;

    /**
     * Métier que le participant souhaite découvrir (optionnel).
     */
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $metierRecherche = null;

    /**
     * Webinar auquel le participant est inscrit (optionnel).
     */
    #[ORM\ManyToOne(targetEntity: Webinar::class, inversedBy: 'inscriptions')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Webinar $webinar = null;

    /**
     * Date et heure de création de l'inscription.
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
     * Retourne l'identifiant de l'inscription.
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Retourne le nom du participant.
     */
    public function getNom(): ?string
    {
        return $this->nom;
    }

    /**
     * Définit le nom du participant.
     */
    public function setNom(string $nom): static
    {
        $this->nom = $nom;
        return $this;
    }

    /**
     * Retourne le prénom du participant.
     */
    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    /**
     * Définit le prénom du participant.
     */
    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;
        return $this;
    }

    /**
     * Retourne l'e-mail du participant.
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * Définit l'e-mail du participant.
     */
    public function setEmail(string $email): static
    {
        $this->email = $email;
        return $this;
    }

    /**
     * Retourne l'école du participant.
     */
    public function getEcole(): ?string
    {
        return $this->ecole;
    }

    /**
     * Définit l'école du participant.
     */
    public function setEcole(?string $ecole): static
    {
        $this->ecole = $ecole;
        return $this;
    }

    /**
     * Retourne la région du participant.
     */
    public function getRegion(): ?string
    {
        return $this->region;
    }

    /**
     * Définit la région du participant.
     */
    public function setRegion(?string $region): static
    {
        $this->region = $region;
        return $this;
    }

    /**
     * Retourne le métier recherché par le participant.
     */
    public function getMetierRecherche(): ?string
    {
        return $this->metierRecherche;
    }

    /**
     * Définit le métier recherché par le participant.
     */
    public function setMetierRecherche(?string $metierRecherche): static
    {
        $this->metierRecherche = $metierRecherche;
        return $this;
    }

    /**
     * Retourne le webinar auquel le participant est inscrit.
     */
    public function getWebinar(): ?Webinar
    {
        return $this->webinar;
    }

    /**
     * Définit le webinar auquel le participant est inscrit.
     */
    public function setWebinar(?Webinar $webinar): static
    {
        $this->webinar = $webinar;
        return $this;
    }

    /**
     * Retourne la date de création de l'inscription.
     */
    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * Définit la date de création de l'inscription.
     */
    public function setCreatedAt(\DateTimeInterface $createdAt): static
    {
        $this->createdAt = $createdAt;
        return $this;
    }
}
