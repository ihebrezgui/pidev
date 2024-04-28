<?php

namespace App\Repository;

use App\Entity\Quiz;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Quiz>
 *
 * @method Quiz|null find($id, $lockMode = null, $lockVersion = null)
 * @method Quiz|null findOneBy(array $criteria, array $orderBy = null)
 * @method Quiz[]    findAll()
 * @method Quiz[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QuizRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Quiz::class);
    }

    public function save(Quiz $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Quiz $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    public function findByFormationType(string $type,int $id): array
    {
        // Retrieves quizzes linked to formations of a specific type using the idFormation field
        return $this->createQueryBuilder('q')
            ->join('App\Entity\Formation', 'f', 'WITH', 'q.idFormation = f.id')
            ->where('f.typeF = :type')
            ->setParameter('type', $type)
            ->getQuery()
            ->getResult();
    }
    

    public function generateOrFetchQuiz(Formation $formation): Quiz
    {
        // Check if the formation already has quizzes
        $quizzes = $formation->getQuizzes();
        if (!$quizzes->isEmpty()) {
            // Return the first quiz as an example
            return $quizzes->first();
        }

        // If no quizzes are associated, create a new quiz
        $quiz = new Quiz();
        $quiz->setFormation($formation);
        $quiz->setTitle("New Quiz for " . $formation->getTypeF());
        $quiz->setDescription("Generated quiz based on the formation's type.");

        // Persist the new quiz in the database
        $this->entityManager->persist($quiz);
        $this->entityManager->flush();

        return $quiz;
    }

//    /**
//     * @return Quiz[] Returns an array of Quiz objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('q')
//            ->andWhere('q.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('q.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Quiz
//    {
//        return $this->createQueryBuilder('q')
//            ->andWhere('q.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
