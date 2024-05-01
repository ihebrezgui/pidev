<?php

namespace App\Repository;

use App\Entity\Givedonation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Givedonation>
 *
 * @method Givedonation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Givedonation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Givedonation[]    findAll()
 * @method Givedonation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GiveRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Givedonation::class);
    }

//    /**
//     * @return Givedonation[] Returns an array of Givedonation objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('g')
//            ->andWhere('g.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('g.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Givedonation
//    {
//        return $this->createQueryBuilder('g')
//            ->andWhere('g.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
    public function getProfessionsCount(): array
    {
        return $this->createQueryBuilder('g')
            ->select('g.profession as profession, COUNT(g.id) as count')
            ->groupBy('g.profession')
            ->getQuery()
            ->getResult();
    }

}
