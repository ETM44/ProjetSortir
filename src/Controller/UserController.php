<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ParticipantRepository;
use App\Form\ModifierProfilFormType;

class UserController extends AbstractController
{
    /**
     * @Route("/user", name="user")
     */
    public function index(): Response
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    /**
     * @Route("user/modifierProfil", name="modifier_profil")
     */
    public function modifierProfil(ParticipantRepository $pr, Request $request, EntityManagerInterface $em): Response
    {
        $participant = $pr->find($this->getUser()->getId());

        $form = $this->createForm(ModifierProfilFormType::class, $participant);
        $form->handlerequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($participant);
            $em->flush();
            $this->addFlash('success', 'Votre profil a bien été modifié.');
            return $this->redirectToRoute("main");
        }
        return $this->render("user/ModifierProfil.html.twig", [
            "title" => "Modifier mon profil :",
            "participant" => $participant,
            "ModifierProfilFormType" => $form->createView()
        ]);
    }


    /**
     * @Route("user/profil/{id}", name="app_afficherProfil")
     */
    public function afficherProfil(ParticipantRepository $participantRepository, $id=0):Response {
        $user = $participantRepository->find($id);
        return $this->render('user/AfficherUnProfil.html.twig',[
                                "user"=>$user
        ]);
    }
}
