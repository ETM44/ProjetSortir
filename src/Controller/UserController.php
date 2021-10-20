<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ParticipantRepository;
use App\Form\ModifierProfilFormType;
use App\Entity\UpdatePassword;


class UserController extends AbstractController
{

    /**
     * @Route("user/modifierProfil", name="modifier_profil")
     */
    public function modifierProfil(ParticipantRepository $pr, Request $request, EntityManagerInterface $em): Response
    {

        $participant = $pr->find($this->getUser()->getId());

        $form = $this->createForm(ModifierProfilFormType::class, $participant);
        $form->handlerequest($request);
        //dd($form);



        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($participant);
            $em->flush();
            $this->addFlash('success', 'Votre profil a bien été modifié.');
            return $this->redirectToRoute("main");
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
    public function afficherProfil(ParticipantRepository $participantRepository, $id=0):Response {
        $user = $participantRepository->find($id);
        return $this->render('user/AfficherUnProfil.html.twig',[
                                "user"=>$user
        ]);
    }

   /* public function updatePassword(Request $request, UserPasswordHasherInterface $phi, ObjectManager $manager): Response
    {
        $updatePassword = new updatePassword();
        $user = $this->getUser();

        $form = $this->createFormBuilder($updatePassword)
            ->add('oldPassword', PasswordType::class, [
                'required' => true,
            ])
            ->add('newPassword', PasswordType::class, [
                'required' =>true,
            ]);
        $form->getForm();
       
        if ($form->isSubmitted() && $form->isValid()) {
            if (!password_verify($updatePassword->getOldPassword(), $user->getHash())) {
                $form->get('oldPassword')->addError(new FormError('L’ancien mot de passe ne correspond pas'));
            } else {
                $newPassword = $updatePassword->getNewPassword();

                $hash = $phi->encodePassword($user, $newPassword);

                $user->setHash($hash);
                $manager->persist($user);
                $manager->flush();

                $this->addFlash(
                    'success',
                    'votre mot de passe a bien été mise à jour'
                );

                return $this->redirectToRoute('modifier_profil');
            }
        }

        return $this->render('user/ModifierProfil.html.twig', [
            'form' => $form->createView(),
            'updatePassword'=>$this->$updatePassword
        ]);
    }*/

}
