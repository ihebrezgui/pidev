<?php

namespace App\Repository;

use App\Entity\Utilisateur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use App\Interface\CustomUserInterface;
/**
 * @method Utilisateur|null find($id, $lockMode = null, $lockVersion = null)
 * @method Utilisateur|null findOneBy(array $criteria, array $orderBy = null)
 * @method Utilisateur[]    findAll()
 * @method Utilisateur[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UtilisateurRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Utilisateur::class);
    }

    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
        {
            if (!$user instanceof Utilisateur) {
                throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $user::class));
            }
    
            $user->setPassword($newHashedPassword);
            $this->getEntityManager()->persist($user);
            $this->getEntityManager()->flush();
        }
    
        public function updateUser(?Utilisateur $user, bool $true)
        {
            $em = $this->getEntityManager();
            $query = $em->createQuery(
                'UPDATE App\Entity\Utilisateur u SET u.nom = :nom,u.prenom = :prenom, u.email = :email, u.num_tel = :numtel WHERE u.id = :id'
            );
            $query->setParameter('id', $user->getId());
            $query->setParameter('nom',  $user->getNom());
            $query->setParameter('prenom',  $user->getPrenom());
            $query->setParameter('email',  $user->getEmail());
            $query->setParameter('num tel',  $user->getNumTel());
            
    
            return $query->getResult();
        }
        public function updateUserPassword(?Utilisateur $user, bool $true)
        {
            $em = $this->getEntityManager();
            $query = $em->createQuery(
                'UPDATE App\Entity\User u SET u.password = :password WHERE u.id = :id'
            );
            $query->setParameter('id', $user->getId());
            $query->setParameter('password',  $user->getPassword());
            return $query->getResult();
        }
    
        public function remove(Utilisateur $entity, bool $flush = false): void
        {
            $this->getEntityManager()->remove($entity);
    
            if ($flush) {
                $this->getEntityManager()->flush();
            }
        }
    
        public function findUser($Value, $order)
        {
            $em = $this->getEntityManager();
            if ($order == 'DESC') {
                $query = $em->createQuery(
                    'SELECT r FROM App\Entity\Utilisateur r   where r.name like :suj OR r.email like :suj  order by r.id DESC '
                );
                $query->setParameter('suj', $Value . '%');
            } else {
                $query = $em->createQuery(
                    'SELECT r FROM App\Entity\Utilisateur r   where r.name like :suj OR r.email like :suj  order by r.id ASC '
                );
                $query->setParameter('suj', $Value . '%');
            }
            return $query->getResult();
        }
    
     
        public function getUserById($id)
        {
            return $this->createQueryBuilder('u')
                ->andWhere('u.id = :id')
                ->setParameter('id', $id)
                ->getQuery()
                ->getOneOrNullResult();
        }
        public function getUserByEmail($email)
        {
            return $this->createQueryBuilder('u')
                ->andWhere('u.email = :email')
                ->setParameter('email', $email)
                ->getQuery()
                ->getOneOrNullResult();
        }
    
        public function getUserByResetCode($resetCode)
        {
            return $this->createQueryBuilder('u')
                ->andWhere('u.resetCode = :resetCode')
                ->setParameter('resetCode', $resetCode)
                ->getQuery()
                ->getOneOrNullResult();
        }
        public function searchUtilisateurs($searchQuery)
        {
            return $this->createQueryBuilder('u')
                ->where('u.email LIKE :searchQuery')
                ->orWhere('u.nom LIKE :searchQuery')
                ->orWhere('u.prenom LIKE :searchQuery')
                ->setParameter('searchQuery', '%' . $searchQuery . '%')
                ->getQuery()
                ->getResult();
        }
    public function getUsersByRole()
    {
        return $this->createQueryBuilder('u')
            ->select('u.role, COUNT(u.id) as userCount')
            ->groupBy('u.role')
            ->getQuery()
            ->getResult();
    }
}
