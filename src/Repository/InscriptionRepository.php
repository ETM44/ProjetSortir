<?php

namespace App\Repository;

use App\Entity\Inscription;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Inscription|null find($id, $lockMode = null, $lockVersion = null)
 * @method Inscription|null findOneBy(array $criteria, array $orderBy = null)
 * @method Inscription[]    findAll()
 * @method Inscription[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InscriptionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Inscription::class);
    }

    public function findUserSortie($userId)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.participant = :userId')
            ->setParameter('userId', $userId)
            ->getQuery()
            ->getResult()
            ;
    }

    public function findUserIdAndSortieId($userId, $sortieId)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.participant = :userId')
            ->setParameter('userId', $userId)
            ->andWhere('i.sortie = :sortieId')
            ->setParameter('sortieId', $sortieId)
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }

    public function findParticipantsInscrits($id)
    {
        return $this->createQueryBuilder('i')
            ->join('i.participant', 'p')
            ->addSelect('p')
            ->join('i.sortie', 's')
            ->addSelect('s')
            ->join('s.etat', 'e')
            ->addSelect('e')
            ->andWhere('s.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getResult()
            ;
    }

    // /**
    //  * @return Inscription[] Returns an array of Inscription objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Inscription
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
