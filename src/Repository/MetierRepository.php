<?php

namespace App\Repository;

use App\Entity\Metier;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Repository pour l'entité Metier.
 * Fournit des méthodes de requête personnalisées pour les métiers.
 *
 * @extends ServiceEntityRepository<Metier>
 */
class MetierRepository extends ServiceEntityRepository
{
    /**
     * Constructeur du repository.
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Metier::class);
    }

    /**
     * Recherche des métiers dont le nom contient le terme donné.
     * Utilisé pour l'autocomplete de la barre de recherche.
     *
     * @return Metier[]
     */
    public function findByNomLike(string $terme): array
    {
        return $this->createQueryBuilder('m')
            ->andWhere('LOWER(m.nom) LIKE LOWER(:terme)')
            ->setParameter('terme', '%' . $terme . '%')
            ->orderBy('m.nom', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }

    /**
     * Retourne tous les métiers triés par nom alphabétique.
     *
     * @return Metier[]
     */
    public function findAllOrderedByNom(): array
    {
        return $this->createQueryBuilder('m')
            ->orderBy('m.nom', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
