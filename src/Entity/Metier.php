<?php

namespace App\Entity;

use App\Repository\MetierRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * Entité représentant un Métier.
 * Utilisée pour la barre de recherche avec autocomplete et la liste des métiers.
 */
#[ORM\Entity(repositoryClass: MetierRepository::class)]
class Metier
{
    /**
     * Identifiant unique du métier (généré automatiquement).
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * Nom du métier (ex: "Infirmier", "Architecte", "Développeur web").
     */
    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    /**
     * Description du métier (optionnelle).
     */
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    /**
     * Secteur d'activité du métier (ex: "Santé", "Informatique", "BTP").
     */
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $secteur = null;

    /**
     * Retourne l'identifiant du métier.
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Retourne le nom du métier.
     */
    public function getNom(): ?string
    {
        return $this->nom;
    }

    /**
     * Définit le nom du métier.
     */
    public function setNom(string $nom): static
    {
        $this->nom = $nom;
        return $this;
    }

    /**
     * Retourne la description du métier.
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * Définit la description du métier.
     */
    public function setDescription(?string $description): static
    {
        $this->description = $description;
        return $this;
    }

    /**
     * Retourne le secteur d'activité du métier.
     */
    public function getSecteur(): ?string
    {
        return $this->secteur;
    }

    /**
     * Définit le secteur d'activité du métier.
     */
    public function setSecteur(?string $secteur): static
    {
        $this->secteur = $secteur;
        return $this;
    }

    /**
     * Représentation textuelle du métier (son nom).
     */
    public function __toString(): string
    {
        return $this->nom ?? '';
    }
}
