<?php

namespace App\Form;

use App\Entity\Participant;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Validator\Type\RepeatedTypeValidatorExtension;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NewPasswordFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('password', RepeatedTypeValidatorExtension::class, [
                'type' => PasswordType::class,
                'invalid_message' =>'les deux champs doivent correspondre.',
                'options' =>  ['attr' =>['class' => 'password-field']],
                'required' => true,
                'first_options'  => ['Nouveau mot de passe :'],
                'second_options' => ['Confirmez votre mot de passe :'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Participant::class,
        ]);
    }
}
