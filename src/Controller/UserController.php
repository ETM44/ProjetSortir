<?php

namespace App\Controller;

use App\Entity\Participant;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasher;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ParticipantRepository;
use App\Form\ModifierProfilFormType;
use App\Form\NewPasswordFormType;
use App\Entity\UpdatePassword;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Validator\Validator\ValidatorInterface;


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
     * @Route("user/update-pass", name="update_pass")
     */
    public function updatePass(Request $request, UserPasswordHasherInterface $passwordEncoder, EntityManagerInterface $em,ValidatorInterface  $validator): Response
    {
        $participant = $this->getUser();


        $newMdpForm = $this->createForm(NewPasswordFormType::class, $participant);
        $newMdpForm->handlerequest($request);
        $json = [];
        // check old password
        if (password_verify($newMdpForm->get('plainPassword')->getData(), $participant->getPassword())) {
            // issubmitted isvalid
            if ($newMdpForm->isSubmitted() && $newMdpForm->isValid()) {

                $participant = $newMdpForm->getData();

                $participant->setPassword(
                    $passwordEncoder->hashPassword(
                        $participant,
                        $newMdpForm->get('newPassword')->getData()
                    )
                );
                // persist
                $em->persist($participant);
                // flush
                $em->flush();
                $success = ['success' => 'Votre mot de passe a bien été modifié.'];
                array_push($json, $success);
            } else {
                $error = ['error' => 'Les deux entrées ne sont pas identiques.'];
            }
        } else {
            $error = ['error' => 'Mot de passe incorrect.'];
            array_push($json, $error);
        }
        $violations = $validator->validate($newMdpForm);
        foreach ($violations as $error){
            array_push($json,['error'=>$error->getMessage()]);
        }

        // info json

        return $this->json(json_encode($json));
    }

    /**
     * @Route("user/modifierProfil", name="modifier_profil")
     */
    public function modifierProfil(ParticipantRepository $pr, Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $passwordEncoder): Response
    {
        $participant = $pr->find($this->getUser()->getId());
        $form = $this->createForm(ModifierProfilFormType::class, $participant);
        $form->handlerequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (password_verify($form->get('plainPassword')->getData(), $participant->getPassword())) {
                $participant = $form->getData();
                $em->persist($participant);
                $em->flush();
                $this->addFlash('success', 'Votre profil a bien été modifié.');
                return $this->redirectToRoute("app_monProfil");
            } else {
                $this->addFlash('warning', 'Mot de passe invalide !');
            }
        }
        if (!$participant) {
            return $this->render("security/login.html.twig");
        }
//créer formulaire newMdp
        $newMdpForm = $this->createForm(NewPasswordFormType::class, $participant);
        $newMdpForm->handlerequest($request);

/*// vérifier formulaire dans la modale
        if ($newMdpForm->isSubmitted() && $newMdpForm->isValid()) {
            //dd(password_verify($newMdpForm->get('plainPassword')->getData(),$participant->getPassword()));
            if (password_verify($newMdpForm->get('plainPassword')->getData(), $participant->getPassword())) {
                $participant = $newMdpForm->getData();

                $participant->setPassword(
                    $passwordEncoder->hashPassword(
                        $participant,
                        $newMdpForm->get('newPassword')->getData()
                    )
                );

                $em->persist($participant);
                $em->flush();
                $this->addFlash('success', 'Votre mot de passe a bien été modifié.');
            } else {
                $this->addFlash('warning', 'Erreur : votre mot de passe n\'a pas pu être modifié');
            }
        }
//fin modale*/
        return $this->render("user/ModifierProfil.html.twig", [
            "title" => "Modifier mon profil :",
            "participant" => $participant,
            "ModifierProfilFormType" => $form->createView(),
            "newMdpForm" => $newMdpForm->createView(),
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
