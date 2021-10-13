<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use http\Env\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
     * @Route("/creerSortie/getCP/{id}", name="creerSortie_getCP")
     */
    public function getCP(Request $request, $id): Response
    {
        $tab=[
            "1"=>"29000",
            "2"=>"44000",
            "3"=>"35000"
    ];
        return $this->json('{"code":'.$tab[$id].'}');
    }

    /**
     * @Route("/afficherSortie", name="afficherSortie")
     */
    public function afficherSortie(SortieRepository $sr): Response
    {
        $sortie = $sr->findOnById();
        $title= "Afficher une sortie - {{sortie.nom}}";
        $tab = compact("title", "sortie");

        return $this->render('afficherSortie.html.twig', $tab);
    }
}
