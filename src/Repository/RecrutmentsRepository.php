<?php

namespace App\Repository;

use App\Entity\Recrutments;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Recrutments|null find($id, $lockMode = null, $lockVersion = null)
 * @method Recrutments|null findOneBy(array $criteria, array $orderBy = null)
 * @method Recrutments[]    findAll()
 * @method Recrutments[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RecrutmentsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Recrutments::class);
    }

    // Add your custom methods here
}