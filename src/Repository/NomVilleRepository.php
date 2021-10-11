<?php

namespace App\Repository;

use App\Entity\NomVille;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method NomVille|null find($id, $lockMode = null, $lockVersion = null)
 * @method NomVille|null findOneBy(array $criteria, array $orderBy = null)
 * @method NomVille[]    findAll()
 * @method NomVille[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NomVilleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, NomVille::class);
    }

    // /**
    //  * @return NomVille[] Returns an array of NomVille objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('n.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?NomVille
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
