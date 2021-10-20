<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ParticipantRepository;
use App\Form\ModifierProfilFormType;
use App\Entity\UpdatePassword;


class UserController extends AbstractController
{
    /**
     * @Route("user/monProfil", name="app_monProfil")
     */
    public function afficherMonProfil(ParticipantRepository $pr): Response
    {
        $participant = $pr->find($this->getUser()->getId());
        return $this->render('user/afficherMonProfil.html.twig', [
            "participant" => $participant
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

                $participant = $form->getData();
                $em->persist($participant);
                $em->flush();
                $this->addFlash('success', 'Votre profil a bien été modifié.');
                return $this->redirectToRoute("main");
            }
        if(!$participant){
            return $this->render("security/login.html.twig");
        }

        return $this->render("user/ModifierProfil.html.twig", [
            "title" => "Modifier mon profil :",
            "participant" => $participant,
            "ModifierProfilFormType" => $form->createView(),
        ]);
    }


    /**
     * @Route("user/profil/{id}", name="app_afficherProfil")
     */
    public function afficherProfil(ParticipantRepository $participantRepository, $id = 0): Response
    {
        $user = $participantRepository->find($id);
        return $this->render('user/AfficherUnProfil.html.twig', [
            "user" => $user
        ]);
    }


}
