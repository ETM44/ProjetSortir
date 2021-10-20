<?php

namespace App\Bll;

use App\Repository\EtatRepository;
use App\Repository\SortieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraints\DateTime;

class EtatUpdate {

    protected $sortieRepository;
    protected $etatRepository;
    protected $entityManager;

    public function __construct(SortieRepository $sortieRepository, EtatRepository $etatRepository, EntityManagerInterface $entityManager)
    {
        $this->sortieRepository = $sortieRepository;
        $this->etatRepository = $etatRepository;
        $this->entityManager = $entityManager;
    }

    public function listenAndUpdate()
    {
        $etatCloturee = $this->etatRepository->find(3);
        $sortieOuvertToClotures = $this->sortieRepository->findOuvertToCloturee();
        foreach ($sortieOuvertToClotures as $sortie){
            $sortie->setEtat($etatCloturee);
            $this->entityManager->persist($sortie);
        }

        $etatEnCours = $this->etatRepository->find(4);
        $sortieClotureToEnCours = $this->sortieRepository->findClotureeToEnCours();
        foreach ($sortieClotureToEnCours as $sortie){
            $sortie->setEtat($etatEnCours);
            $this->entityManager->persist($sortie);
        }

        $etatPassee = $this->etatRepository->find(5);
        $sortieEnCoursToPassee = $this->sortieRepository->findEnCoursToPassee();
        foreach ($sortieEnCoursToPassee as $sortie){
            if($sortie->getDuree() > 2) {
                $duree = '- '.(2 - $sortie->getDuree());
            } else {
                $duree = '+ '.(2 - $sortie->getDuree());
            }
            if(new \DateTime("now " . $duree . " hours") > $sortie->getDateHeureDebut()){
                $sortie->setEtat($etatPassee);
                $this->entityManager->persist($sortie);
            }
        }

        $this->entityManager->flush();
    }

}