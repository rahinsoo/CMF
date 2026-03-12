<?php

namespace App\Repository;

use App\Entity\Webinar;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Repository pour l'entité Webinar.
 * Fournit des méthodes de requête personnalisées pour les webinars.
 *
 * @extends ServiceEntityRepository<Webinar>
 */
class WebinarRepository extends ServiceEntityRepository
{
    /**
     * Constructeur du repository.
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Webinar::class);
    }

    /**
     * Retourne les prochains webinars actifs (date de début dans le futur ou aujourd'hui),
     * triés par date croissante.
     *
     * @return Webinar[]
     */
    public function findProchainsWebinars(): array
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.estActif = :actif')
            ->andWhere('w.dateDebut >= :maintenant')
            ->setParameter('actif', true)
            ->setParameter('maintenant', new \DateTime())
            ->orderBy('w.dateDebut', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Retourne tous les webinars actifs, triés par date décroissante.
     *
     * @return Webinar[]
     */
    public function findTousActifs(): array
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.estActif = :actif')
            ->setParameter('actif', true)
            ->orderBy('w.dateDebut', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Recherche des webinars dont le métier correspond au terme donné.
     *
     * @return Webinar[]
     */
    public function findByMetier(string $terme): array
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.estActif = :actif')
            ->andWhere('LOWER(w.metier) LIKE LOWER(:terme)')
            ->setParameter('actif', true)
            ->setParameter('terme', '%' . $terme . '%')
            ->orderBy('w.dateDebut', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
