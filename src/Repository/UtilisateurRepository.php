<?php

namespace App\Repository;

use App\Entity\Utilisateur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Utilisateur|null find($id, $lockMode = null, $lockVersion = null)
 * @method Utilisateur|null findOneBy(array $criteria, array $orderBy = null)
 * @method Utilisateur[]    findAll()
 * @method Utilisateur[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UtilisateurRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Utilisateur::class);
    }


    public function findBySearchQuery(string $searchQuery): array
    {
        $queryBuilder = $this->createQueryBuilder('u');

        // Search by nom, prenom, or email
        $queryBuilder->where('u.nom LIKE :search')
            ->orWhere('u.prenom LIKE :search')
            ->orWhere('u.email LIKE :search')
            ->setParameter('search', '%' . $searchQuery . '%')
            ->orderBy('u.nom', 'ASC');

        return $queryBuilder->getQuery()->getResult();
    }
}
