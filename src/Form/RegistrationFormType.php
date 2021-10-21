<?php

namespace App\Form;

use App\Entity\Participant;
use App\Entity\Site;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email')
            ->add('nom')
            ->add('prenom')
            ->add('telephone')
            ->add('pseudo')
            ->add('site', EntityType::class, [
                'label'=>'Site',
                'class' => Site::class,
                'choice_label' => 'nom',
                // expanded:false= liste déroulante
                'expanded'=>false,
                //multiple:false = sélection unique
                'multiple'=>false,
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'You should agree to our terms.',
                    ]),
                ],
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,'mapped'=>false,
                'attr'=>['autocomplete'=>'new-password'],
                'constraints'=>[
                    new NotBlank([
                        'message'=>'Vous devez entrer un mot de passe',
                    ]),
                    new Length([
                        'min'=>6,
                        'minMessage'=>'Le mot de passe doit contenir au moins {{limit}} caractères',
                        //max length allowed by Symfony for security reasons
                        'max'=>4096,
                    ]),
                ],
                'invalid_message'=>"mot de passe invalide",
                'required'=>false,
                'first_options'  => ['label' => 'Mot de passe :', 'attr'=> ['class' => 'text-muted f-w-400 form-control'],],
                'second_options' => ['label' => 'Confirmez votre mot de passe :', 'attr'=> ['class' => 'text-muted f-w-400 form-control'],],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Participant::class,
        ]);
    }
}
