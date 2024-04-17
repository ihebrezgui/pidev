<?php

namespace App\Repository;

use App\Entity\Enseignent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

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
}