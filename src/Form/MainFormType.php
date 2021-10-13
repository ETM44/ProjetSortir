<?php

namespace App\Form;

use App\Entity\Sortie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MainFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('site', EntityType::class, [
                'class' => Site::class,
                'choice_label' => 'nom'
            ])
            ->add('nom')
            ->add('dateHeureDebut')
            ->add('dateHeureFin')
            ->add('duree')
            ->add('sortieOrganisateur', CheckboxType::class)
            ->add('sortieInscrit', CheckboxType::class)
            ->add('sortiePasInscrit', CheckboxType::class)
            ->add('sortiePassees', CheckboxType::class)
        ;
    }

    /*public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }*/
}
