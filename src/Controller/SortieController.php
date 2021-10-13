<?php

namespace App\Controller;

use App\Repository\SortieRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Sortie;

class SortieController extends AbstractController
{
    /**
     * @Route("/sortie", name="sortie")
     */
    public function index(): Response
    {
        return $this->render('sortie/index.html.twig', [
            'controller_name' => 'SortieController',
        ]);
    }

    /**
     * @Route("/creerSortie", name="creerSortie")
     */
    public function creerSortie(): Response
    {

    }

    /**
     * @Route("sortie/annuler", name="app_annulerSortie")
     * @param $id
     * @param SortieRepository $repository
     * @return Response
     */
    
    public function annulerSortie($id,SortieRepository $repository): Response{
        $sortie=$repository->find($id);
       /* $em->remove($sortie);
        $em->flush();

        return $this->redirectToRoute("main");
       */
        return $this->render("sortie/annulerSortie.html.twig", [
            "title" => "Annuler une sortie",
            "sortie"=>$this.sortie,
            ]);
        
    }
}
