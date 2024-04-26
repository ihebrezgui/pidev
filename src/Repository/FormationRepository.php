<?php

namespace App\Repository;

use App\Entity\Formation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Formation>
 *
 * @method Formation|null find($idFormation, $lockMode = null, $lockVersion = null)
 * @method Formation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Formation[]    findAll()
 * @method Formation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FormationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Formation::class);
    }
    public function findAll()
    {
        return $this->createQueryBuilder('f')
                ->select('f.idFormation, f.typeF, f.img, f.prix, f.duree, f.status')
                ->orderBy('f.idFormation', 'ASC')
                ->getQuery()
                ->getResult();
    }
    public function findAveragePrice()
    {
    return $this->createQueryBuilder('f')
        ->select('AVG(f.prix) as averagePrice')
        ->getQuery()
        ->getSingleScalarResult();
    }
    // src/Repository/FormationRepository.php

public function search($keyword)
{
    return $this->createQueryBuilder('f')
        ->andWhere('f.name LIKE :val OR f.description LIKE :val')
        ->setParameter('val', '%' . $keyword . '%')
        ->getQuery()
        ->getResult();
}


//    /**
//     * @return Formation[] Returns an array of Formation objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('f.idFormation', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Formation
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
