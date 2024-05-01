<?php

namespace App\Repository;

use App\Entity\Requestdonation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Requestdonation>
 *
 * @method Requestdonation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Requestdonation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Requestdonation[]    findAll()
 * @method Requestdonation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RequestRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Requestdonation::class);
    }
    public function getFormationStatistics(): array
    {
        return $this->createQueryBuilder('r')
            ->select('r.formation_souhaitee, COUNT(r) AS count')
            ->groupBy('r.formation_souhaitee')
            ->getQuery()
            ->getResult();
    }
//    /**
//     * @return Requestdonation[] Returns an array of Requestdonation objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Requestdonation
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
    public function findBySearchTerm(string $searchTerm): array
    {
        $queryBuilder = $this->createQueryBuilder('r');

        // Example: search by a field named 'name'
        $queryBuilder->andWhere('r.idrequest LIKE :searchTerm')
            ->setParameter('searchTerm', '%'.$searchTerm.'%');

        return $queryBuilder->getQuery()->getResult();
    }

}
