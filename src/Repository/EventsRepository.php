<?php

namespace App\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Events;

class EventsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Events::class);
    }
    public function Searchevent($searchQuery)
    {
        return $this->createQueryBuilder('e')
            ->where('e.nom LIKE :searchQuery')
            ->orWhere('e.nbrPlace = :nbrPlace')
            ->setParameter('searchQuery', '%'.$searchQuery.'%')
            ->setParameter('nbrPlace', $searchQuery)
            ->getQuery()
            ->getResult();
    }
    
    public function findAllOrderedByDate()
    {
        return $this->createQueryBuilder('e')
            ->orderBy('e.dateEvent', 'ASC')
            ->getQuery()
            ->getResult();
    }
   
    public function countEventsByMonth(): array
    {
        $events = $this->createQueryBuilder('e')
            ->select('SUBSTRING(e.dateEvent, 6, 2) AS month, COUNT(e.id_event) AS eventCount')
            ->groupBy('month')
            ->orderBy('month', 'ASC')
            ->getQuery()
            ->getResult();

        return $events;
    }
     // Custom method to search events by the "nom" field
     public function findByNom(string $nom): array
     {
         return $this->createQueryBuilder('e')
             ->andWhere('e.nom LIKE :nom')
             ->setParameter('nom', '%'.$nom.'%')
             ->getQuery()
             ->getResult();
     }
}