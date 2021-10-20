<?php

namespace App\Form;

use App\Entity\Participant;
use App\Entity\Site;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ModifierProfilFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('pseudo', TextType::class, [
                'label' => "Pseudo :"
            ])
            ->add('prenom', TextType::class, [
                'label' => "Prénom :"
            ])
            ->add('nom', TextType::class, [
                'label' => "Nom :"
            ])
            ->add('telephone', TextType::class, [
                'label' => "Téléphone :"
            ])
            ->add('email', TextType::class, [
                'label' => "Email :"
            ])
            ->add('plainpassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'mapped'=>false,
                'attr'=>['autocomplete'=>'new-password'],
                "required"=>false,
                'first_options' => ['label' => "Nouveau mot de passe"],
                'second_options' => ['label' => "Confirmez votre mot de passe"],
            ])
            ->add('site', EntityType::class, [
                'class' => Site::class,
                'choice_label' => "nom",
                'label' => "Campus de rattachement"
            ])
            ->add('modifier', SubmitType::class)
            ->add('annuler', ResetType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Participant::class,
        ]);
    }
}
