<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\NouveauLieuType;
use App\Repository\LieuRepository;
use App\Entity\Lieu;

class LieuController extends AbstractController
{

    /**
     * @Route("/lieu", name="lieu")
     */
    public function index(): Response
    {
        return $this->render('lieu/index.html.twig', [
            'controller_name' => 'LieuController',
        ]);
    }

    /**
     * @param LieuRepository $repository
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return mixed
     * @Route("lieu/ajouter/{sortie_id}", name="nouveauLieu")
     */
    public function addLieu(Request $request, EntityManagerInterface $em, $sortie_id)
    {
        $lieu = new Lieu();
        $nouveauLieuForm = $this->createForm(NouveauLieuType::class, $lieu);
        $nouveauLieuForm->handleRequest($request);

        if ($nouveauLieuForm->isSubmitted() && $nouveauLieuForm->isValid()) {

            //  $em = $this->getDoctrine()->getManager();
            $em->persist($lieu);
            $em->flush();
            $this->addFlash('success', 'Ce lieu a été ajouté à la liste des lieux de retrouvailles.');
        }
        return $this->redirectToRoute('app_modifierSortie', [
            "id" => $sortie_id
        ]);
        /*        return $this->render('sortie/modifierSortie.html.twig', [
                    'registrationForm' => $nouveauLieuForm->createView([
                        "sortie" => $sortie,
                        "id"=>$id,
                   ]),
                ]);*/


    }

    /**
     * @param LieuRepository $repository
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return mixed
     * @Route("lieu/ajouter", name="nouveauLieu")
     */
    public function addLieu2(Request $request, EntityManagerInterface $em)
    {
        $lieu = new Lieu();
        $nouveauLieuForm = $this->createForm(NouveauLieuType::class, $lieu);
        $nouveauLieuForm->handleRequest($request);

        if ($nouveauLieuForm->isSubmitted() && $nouveauLieuForm->isValid()) {

            //  $em = $this->getDoctrine()->getManager();
            $em->persist($lieu);
            $em->flush();
            $this->addFlash('success', 'Ce lieu a été ajouté à la liste des lieux de retrouvailles.');
        }
        return $this->redirectToRoute('creerSortie');
    }
}
