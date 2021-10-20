<?php

namespace App\Form;

use App\Bo\MainSearch;
use App\Entity\Site;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AccueilFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('site', EntityType::class, [
                'class' => Site::class,
                'choice_label' => 'nom'
            ])
            ->add('nom', null, [
                'label' => 'Le nom de la sortie contient:'
            ])
            ->add('dateHeureDebut', DateTimeType::class, [
                'label' => 'Entre',
                'data' => new \DateTime("now -1 week")
            ])
            ->add('dateHeureFin', DateTimeType::class, [
                'label' => 'et',
                'data' => new \DateTime("now +2 month")
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => MainSearch::class,
        ]);
    }
}
