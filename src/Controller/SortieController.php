<?php

namespace App\Controller;


use App\Entity\Category;
use App\Repository\SortieRepository;
use App\Repository\EtatRepository;
use App\Repository\VilleRepository;
use App\Repository\InscriptionRepository;
use App\Form\CreerSortieFormType;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Sortie;
use App\Entity\Site;
use App\Entity\Lieu;
use App\Form\NouveauLieuType;
use App\Form\UpdateSortieType;
use App\Entity\Ville;

use Symfony\Component\WebLink\Link;

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
     * @Route("sortie/creerSortie", name="creerSortie")
     */
    public function creerSortie (Request $request, EntityManagerInterface  $em, EtatRepository $er, VilleRepository $vr): Response
    {
        $sortie = new Sortie();
        $form = $this->createForm(CreerSortieFormType::class, $sortie);
        $form->handleRequest($request);
        $orga = $this->getUser();
        $villes = $vr->findAll();
        
        if ($form->isSubmitted() && $form->isValid()) {
                $sortie->setInfosSortie($sortie->getInfosSortie());
                $sortie->setEtat($er->findOneBy(["id"=>1]));
                $sortie->setOrganisateur($this->getUser());
                $em->persist($sortie);
                $em->flush();
                $this->addFlash('success', 'Votre sortie a bien été créée. 😢 ');
                return $this->redirectToRoute("main");
            }

        return $this->render("sortie/creerSortie.html.twig", [
            "title" => "Creer une sortie :",
            "CreerSortieForm" => $form->createView(),
            'orga' => $orga,
            'villes' => $villes,
            'sortie' => $sortie
        ]);

    }


    /**
     * @Route("sortie/annuler/{id}", name="app_annulerSortie")
     * @param $id
     * @param SortieRepository $repository
     * @return Response
     */
    
    public function annulerSortie($id,SortieRepository $repository, EtatRepository $etatrepo, Request $request, EntityManagerInterface $em): Response{
        $sortie=$repository->find($id);
        $etatSortie=$etatrepo->find(6);
//Si l'utilisateur en session n'est pas l'organisateur de la sortie, on lui refuse l'accès
        if($this->getUser()->getId() !== $sortie->getOrganisateur()->getId() ) {
            $this->addFlash('warning', 'Accès refusé : vous n\'avez pas les droits.');
            return $this->redirectToRoute("main");
        }else {

            $newSortie = new Sortie();
            $form = $this->createFormBuilder($newSortie)
                ->add('infosSortie', TextareaType::class)
                ->add('Confirmer', SubmitType::class)
                ->add('Annuler', ResetType::class)
                ->getForm();
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $sortie->setInfosSortie($newSortie->getInfosSortie());
                $sortie->setEtat($etatSortie);
                $em->flush();
                $this->addFlash('success', 'Votre sortie a bien été annulée. 😢 ');
                return $this->redirectToRoute("main");
            }

            return $this->render("sortie/annulerSortie.html.twig", [
                "title" => "Annuler une sortie",
                "sortie" => $sortie,
                "form" => $form->createView()
            ]);
        }
    }

    /**
     *  @Route("sortie/reactiver/{id}", name="app_reactiverSortie")
     * @param $id
     * @param SortieRepository $repository
     * @param EtatRepository $etatrepo
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function reactiverSortie($id,SortieRepository $repository, EtatRepository $etatrepo, EntityManagerInterface $em):Response{
        $sortie=$repository->find($id);
        $etatSortie=$etatrepo->find(1);

        $sortie->setEtat($etatSortie);
        $em->flush();
        $this->addFlash('success', 'Votre sortie a bien été remise en route ! 😊 ');
        return $this->redirectToRoute("app_modifierSortie", [
            "sortie"=>$sortie,
            "id"=>$id,
        ]);
    }

    /**
     * @Route("sortie/modifier/{id}", name="app_modifierSortie")
     * @param $id
     * @param SortieRepository $repository
     * @return Response
     */
    public function modifierSortie($id=0,SortieRepository $repository,Request $request, EntityManagerInterface $em)
    {
      //  $newSortie = new Sortie();
        $sortie = $repository->find($id);
        $updateSortieForm = $this->createForm(UpdateSortieType::class, $sortie);
//Si l'utilisateur en session n'est pas l'organisateur de la sortie, on lui refuse l'accès

       if($this->getUser() && $this->getUser()->getId() !== $sortie->getOrganisateur()->getId() ) {
           $this->addFlash('warning', 'Accès refusé : vous n\'avez pas les droits.');
           return $this->redirectToRoute("main");
       }else{
        $updateSortieForm->handleRequest($request);

        ///////
           $lieu = new Lieu();
    $nouveauLieuForm = $this->createForm(NouveauLieuType::class, $lieu);
   // $nouveauLieuForm->handleRequest($request);
/////////////////
        if ($updateSortieForm->isSubmitted() && $updateSortieForm->isValid()) {

            $em->persist($sortie);
            $em->flush();
            $this->addFlash('success', 'Votre sortie a bien été modifiée.');
            return $this->redirectToRoute("afficherSortie");
        }

        return $this->render("sortie/modifierSortie.html.twig", [
            "title" => "Modifier la sortie :",
            "sortie" => $sortie,
            "updateSortieForm" => $updateSortieForm->createView(),
            "nouveauLieuForm"=>$nouveauLieuForm->createView()
            ]);
        }
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
     * @Route("sortie/afficherSortie/{id}", name="afficherSortie")
     */
    public function afficherSortie(SortieRepository $sr, InscriptionRepository $ir, $id=0): Response
    {
        $sortie = $sr->find($id);
        $participants = $ir->findParticipantsInscrits($id);
        //dd($participants);
        $lieu = $sortie->getLieu();
        //dd($sortie);
        $title= "Afficher une sortie";
        $tab = compact("title", "sortie", "lieu", "participants");
        //dd($sortie);

        return $this->render('sortie/afficherSortie.html.twig', $tab);
    }

}
