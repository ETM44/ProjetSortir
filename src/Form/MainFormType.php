<?php

namespace App\Form;

use App\Entity\Site;
use App\Bo\MainSearch;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\DateTime;

class MainFormType extends AbstractType
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
            ->add('sortieOrganisateur', CheckboxType::class, [
                'required' => false,
                'data' => true,
                'label' => "Sorties dont je suis l'organisateur/trice"
            ])
            ->add('sortieInscrit', CheckboxType::class, [
                'required' => false,
                'data' => true,
                'label' => 'Sorties auxquelles je suis inscrit/e'
            ])
            ->add('sortiePasInscrit', CheckboxType::class, [
                'required' => false,
                'data' => true,
               // 'attr' => array('checked'   => 'checked'),
                'label' => 'Sorties auxquelles je ne suis pas inscrit/e'
            ])
            ->add('sortiePassees', CheckboxType::class, [
                'required' => false,
                'label' => 'Sorties passées'
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
