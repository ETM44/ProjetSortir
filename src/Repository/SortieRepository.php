<?php

namespace App\Repository;

use App\Bo\MainSearch;
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

    public function findSortieOuverteWithFilter($mainSearch)
    {
        return $this->createQueryBuilder('s')
            ->leftJoin('s.inscriptions', 'i')
            ->addSelect('i')
            ->leftJoin('i.participant','p')
            ->addSelect('p')
            ->leftJoin('s.organisateur','o')
            ->addSelect('o')
            ->leftJoin('o.site','os')
            ->addSelect('os')
            ->leftJoin('s.etat', 'e')
            ->addSelect('e')
            ->andWhere('UPPER(s.nom) LIKE UPPER(:nom)')
            ->setParameter('nom', '%'.$mainSearch->getNom().'%')
            ->andWhere('UPPER(os.nom) = UPPER(:nomsite)')
            ->setParameter('nomsite', $mainSearch->getSite()->getNom())
            ->andWhere('s.dateHeureDebut > :dateHeureDebut')
            ->setParameter('dateHeureDebut', $mainSearch->getDateHeureDebut())
            ->andWhere('s.dateHeureDebut < :dateHeureFin')
            ->setParameter('dateHeureFin', $mainSearch->getDateHeureFin())
            ->andWhere('e.id = 2')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findParticipantsInscritsWithFilter($idUser, MainSearch $mainSearch)
    {
        $statement = $this->statement($mainSearch);

        $sortieOrganisateur = [];
        if($mainSearch->getSortieOrganisateur()) {
            $sortieOrganisateur = $this->statement($mainSearch)
                ->andWhere('s.organisateur = :idUser')
                ->setParameter('idUser', $idUser)
                ->getQuery()
                ->getResult();
        }

        $sortieInscrit = [];
        if($mainSearch->getSortieInscrit()) {
            $sortieInscrit = $this->statement($mainSearch)
                ->andWhere('p.id = :idUser')
                ->andWhere('s.organisateur <> :idUser')
                ->setParameter('idUser', $idUser)
                ->getQuery()
                ->getResult();
        }

        $sortiePasInscrit = [];
        if($mainSearch->getSortiePasInscrit()) {

            $notStatement = $this->createQueryBuilder('z')
                ->select('z.id')
                ->leftJoin('z.inscriptions', 'l')
                ->leftJoin('l.participant', 'd')
                ->andWhere('d.id = :idUser')
            ;

            $sortiePasInscrit = $this->statement($mainSearch)
                ->andWhere($statement->expr()->notIn('s.id',$notStatement->getDQL()))
                ->andWhere('s.organisateur <> :idUser')
                ->setParameter('idUser', $idUser)
                ->getQuery()
                ->getResult();
        }

        return array_merge($sortieOrganisateur,$sortieInscrit,$sortiePasInscrit);
    }

    private function statement(MainSearch $mainSearch)
    {
        $statement = $this->createQueryBuilder('s')
            ->leftJoin('s.inscriptions', 'i')
            ->addSelect('i')
            ->leftJoin('i.participant','p')
            ->addSelect('p')
            ->leftJoin('s.organisateur','o')
            ->addSelect('o')
            ->leftJoin('o.site','os')
            ->addSelect('os')
            ->leftJoin('s.etat', 'e')
            ->addSelect('e')
            ->andWhere('UPPER(s.nom) LIKE UPPER(:nom)')
            ->setParameter('nom', '%'.$mainSearch->getNom().'%')
            ->andWhere('UPPER(os.nom) = UPPER(:nomsite)')
            ->setParameter('nomsite', $mainSearch->getSite()->getNom())
            ->andWhere('s.dateHeureDebut > :dateHeureDebut')
            ->setParameter('dateHeureDebut', $mainSearch->getDateHeureDebut())
            ->andWhere('s.dateHeureDebut < :dateHeureFin')
            ->setParameter('dateHeureFin', $mainSearch->getDateHeureFin())
        ;

        if($mainSearch->getSortiePassees()) {
            $statement->andWhere('e.id = 5');
        } else {
            $statement->andWhere('e.id <> 5');
        }

        return $statement;
    }

    public function findOuvertToCloturee() {
        return $this->createQueryBuilder('s')
            ->leftJoin('s.etat','e')
            ->addSelect('e')
            ->andWhere('e.id = 2') //id de état "ouvert" = 2
            ->andWhere('s.dateLimiteInscription < :now')
            ->setParameter('now', new \DateTime('now + 2 hours'))
            ->getQuery()
            ->getResult();
        ;
    }

    public function findClotureeToEnCours() {
        return $this->createQueryBuilder('s')
            ->leftJoin('s.etat','e')
            ->addSelect('e')
            ->andWhere('e.id = 3') //id de état "cloturée" = 3
            ->andWhere('s.dateHeureDebut < :now')
            ->setParameter('now', new \DateTime('now + 2 hours'))
            ->getQuery()
            ->getResult();
        ;
    }

    public function findEnCoursToPassee() {
        return $this->createQueryBuilder('s')
            ->leftJoin('s.etat','e')
            ->addSelect('e')
            ->andWhere('e.id = 4') //id de état "En cours" = 4
            ->andWhere('s.dateHeureDebut < :now')
            ->setParameter('now', new \DateTime('now + 2 hours'))
            ->getQuery()
            ->getResult();
        ;
    }

    public function findOneByid($id)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.id=:id')
            ->setParameter('id', $id)
            ->join('s.lieu', 'l')
            ->addSelect('l')
            ->getQuery()
            ->getOneOrNullResult();
    }


    /*public function findParticipantsInscrits($id)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.id=:id')
            ->setParameter('id', $id)
            ->join('p.inscription', 'i')
            ->addSelect('i')
            ->andWhere('')
            //->setParameter('',$id)
            ->join('p.i.sortie', 's')
            ->addSelect('')
            ->addSelect('')
            ->getQuery()
            ->getOneOrNullResult();
    }*/

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
