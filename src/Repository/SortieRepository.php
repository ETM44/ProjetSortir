<?php

namespace App\Repository;

use App\Entity\Sortie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Sortie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sortie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sortie[]    findAll()
 * @method Sortie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SortieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sortie::class);
    }

    public function  findOneByid($id)
    {
        return $this->createQueryBuilder('s')
         ->andWhere('s.id=:id')
         ->setParameter('id',$id)
         ->join('s.lieu','l')
         ->addSelect('l')
         ->getQuery()
         ->getOneOrNullResult();

    }
    public function  findParticipantsInscrits($id)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.id=:id')
            ->setParameter('id',$id)
            ->join('p.inscription','i')
            ->addSelect('i')
            ->andWhere('')
            //->setParameter('',$id)
            ->join('p.i.sortie','s')
            ->addSelect('')
            ->addSelect('')
            ->getQuery()
            ->getOneOrNullResult();

    }

    // /**
    //  * @return Sortie[] Returns an array of Sortie objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Sortie
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
