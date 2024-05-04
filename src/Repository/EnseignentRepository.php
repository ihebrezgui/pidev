<?php

namespace App\Repository;

use App\Entity\Enseignent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;
use Doctrine\ORM\Query;

/**
 * @method Enseignent|null find($id, $lockMode = null, $lockVersion = null)
 * @method Enseignent|null findOneBy(array $criteria, array $orderBy = null)
 * @method Enseignent[]    findAll()
 * @method Enseignent[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EnseignentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Enseignent::class);
    }

    // Add your custom methods here
    
    public function findBySearchTermQuery(string $searchTerm): Query
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.nome LIKE :searchTerm OR e.prenome LIKE :searchTerm OR e.email LIKE :searchTerm')
            ->setParameter('searchTerm', '%' . $searchTerm . '%')
            ->orderBy('e.ide', 'ASC')
            ->getQuery();
    }
    
public function countByAge()
{
    return $this->createQueryBuilder('e')
        ->select('e.agee as age, COUNT(e) as count')
        ->groupBy('e.agee')
        ->orderBy('e.agee', 'ASC')  // Optional, sort by age if needed
        ->getQuery()
        ->getResult();
}
public function averageAge()
{
    return $this->createQueryBuilder('e')
        ->select('AVG(e.agee) as averageAge')
        ->getQuery()
        ->getSingleScalarResult();  // Returns a single value
}

}