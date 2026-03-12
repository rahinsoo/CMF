<?php

namespace App\Entity;

use App\Repository\WebinarRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * Entité représentant un Webinar (conférence en ligne sur un métier).
 * Chaque webinar est associé à un professionnel présentant son métier.
 */
#[ORM\Entity(repositoryClass: WebinarRepository::class)]
class Webinar
{
    /**
     * Identifiant unique du webinar (généré automatiquement).
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * Titre du webinar (ex: "Découvrez le métier d'infirmier").
     */
    #[ORM\Column(length: 255)]
    private ?string $titre = null;

    /**
     * Nom du métier présenté lors de ce webinar.
     */
    #[ORM\Column(length: 255)]
    private ?string $metier = null;

    /**
     * Description détaillée du webinar (optionnelle).
     */
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    /**
     * Date et heure de début du webinar.
     */
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateDebut = null;

    /**
     * URL de la vidéo associée au webinar (optionnelle).
     */
    #[ORM\Column(length: 500, nullable: true)]
    private ?string $videoUrl = null;

    /**
     * Chemin vers l'image/avatar du professionnel ou du webinar (optionnel).
     */
    #[ORM\Column(length: 500, nullable: true)]
    private ?string $image = null;

    /**
     * Indique si le webinar est actif (visible sur le site).
     */
    #[ORM\Column]
    private ?bool $estActif = true;

    /**
     * Liste des commentaires postés dans la zone de discussion du webinar.
     *
     * @var Collection<int, Commentaire>
     */
    #[ORM\OneToMany(targetEntity: Commentaire::class, mappedBy: 'webinar', orphanRemoval: true)]
    #[ORM\OrderBy(['createdAt' => 'ASC'])]
    private Collection $commentaires;

    /**
     * Liste des inscriptions associées à ce webinar.
     *
     * @var Collection<int, Inscription>
     */
    #[ORM\OneToMany(targetEntity: Inscription::class, mappedBy: 'webinar')]
    private Collection $inscriptions;

    /**
     * Constructeur : initialise les collections.
     */
    public function __construct()
    {
        $this->commentaires = new ArrayCollection();
        $this->inscriptions = new ArrayCollection();
    }

    /**
     * Retourne l'identifiant du webinar.
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Retourne le titre du webinar.
     */
    public function getTitre(): ?string
    {
        return $this->titre;
    }

    /**
     * Définit le titre du webinar.
     */
    public function setTitre(string $titre): static
    {
        $this->titre = $titre;
        return $this;
    }

    /**
     * Retourne le nom du métier présenté.
     */
    public function getMetier(): ?string
    {
        return $this->metier;
    }

    /**
     * Définit le nom du métier présenté.
     */
    public function setMetier(string $metier): static
    {
        $this->metier = $metier;
        return $this;
    }

    /**
     * Retourne la description du webinar.
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * Définit la description du webinar.
     */
    public function setDescription(?string $description): static
    {
        $this->description = $description;
        return $this;
    }

    /**
     * Retourne la date de début du webinar.
     */
    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->dateDebut;
    }

    /**
     * Définit la date de début du webinar.
     */
    public function setDateDebut(\DateTimeInterface $dateDebut): static
    {
        $this->dateDebut = $dateDebut;
        return $this;
    }

    /**
     * Retourne l'URL de la vidéo du webinar.
     */
    public function getVideoUrl(): ?string
    {
        return $this->videoUrl;
    }

    /**
     * Définit l'URL de la vidéo du webinar.
     */
    public function setVideoUrl(?string $videoUrl): static
    {
        $this->videoUrl = $videoUrl;
        return $this;
    }

    /**
     * Retourne le chemin de l'image du webinar.
     */
    public function getImage(): ?string
    {
        return $this->image;
    }

    /**
     * Définit le chemin de l'image du webinar.
     */
    public function setImage(?string $image): static
    {
        $this->image = $image;
        return $this;
    }

    /**
     * Indique si le webinar est actif.
     */
    public function isEstActif(): ?bool
    {
        return $this->estActif;
    }

    /**
     * Définit si le webinar est actif.
     */
    public function setEstActif(bool $estActif): static
    {
        $this->estActif = $estActif;
        return $this;
    }

    /**
     * Retourne la collection des commentaires du webinar.
     *
     * @return Collection<int, Commentaire>
     */
    public function getCommentaires(): Collection
    {
        return $this->commentaires;
    }

    /**
     * Ajoute un commentaire au webinar.
     */
    public function addCommentaire(Commentaire $commentaire): static
    {
        if (!$this->commentaires->contains($commentaire)) {
            $this->commentaires->add($commentaire);
            $commentaire->setWebinar($this);
        }
        return $this;
    }

    /**
     * Supprime un commentaire du webinar.
     */
    public function removeCommentaire(Commentaire $commentaire): static
    {
        if ($this->commentaires->removeElement($commentaire)) {
            if ($commentaire->getWebinar() === $this) {
                $commentaire->setWebinar(null);
            }
        }
        return $this;
    }

    /**
     * Retourne la collection des inscriptions au webinar.
     *
     * @return Collection<int, Inscription>
     */
    public function getInscriptions(): Collection
    {
        return $this->inscriptions;
    }

    /**
     * Ajoute une inscription au webinar.
     */
    public function addInscription(Inscription $inscription): static
    {
        if (!$this->inscriptions->contains($inscription)) {
            $this->inscriptions->add($inscription);
            $inscription->setWebinar($this);
        }
        return $this;
    }

    /**
     * Supprime une inscription du webinar.
     */
    public function removeInscription(Inscription $inscription): static
    {
        if ($this->inscriptions->removeElement($inscription)) {
            if ($inscription->getWebinar() === $this) {
                $inscription->setWebinar(null);
            }
        }
        return $this;
    }
}
