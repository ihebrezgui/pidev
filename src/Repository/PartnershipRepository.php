<?php

namespace App\Repository;

use App\Entity\Partnership;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Partnership|null find($id, $lockMode = null, $lockVersion = null)
 * @method Partnership|null findOneBy(array $criteria, array $orderBy = null)
 * @method Partnership[]    findAll()
 * @method Partnership[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PartnershipRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Partnership::class);
    }

    // Add your custom methods here
    public function findBySearchTerm(string $searchTerm)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.nome LIKE :searchTerm OR e.prenome LIKE :searchTerm OR e.email LIKE :searchTerm')
            ->setParameter('searchTerm', '%' . $searchTerm . '%')
            ->orderBy('e.ide', 'ASC')
            ->getQuery()
            ->getResult();
    }
    public function countByAge()
    {
        return $this->createQueryBuilder('e')
            ->select('e.domaine as age, COUNT(e) as count')
            ->groupBy('e.domaine')
            ->orderBy('e.domaine', 'ASC')  // Optional, sort by age if needed
            ->getQuery()
            ->getResult();
    }
    public function averageAge()
    {
        return $this->createQueryBuilder('e')
            ->select('AVG(e.domaine) as averageAge')
            ->getQuery()
            ->getSingleScalarResult();  // Returns a single value
    }
}