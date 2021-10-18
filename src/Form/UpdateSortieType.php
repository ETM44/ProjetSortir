<?php
namespace App\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\Sortie;
use App\Entity\Lieu;
use App\Entity\Site;


class UpdateSortieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom')
            ->add('dateHeureDebut')
            ->add('duree')
            ->add('dateLimiteInscription')
            ->add('nbInscriptionsMax')
            ->add('infosSortie')
            ->add('site', EntityType::class, [
                'label'=>'Site',
                'class' => Site::class,
                'choice_label' => 'nom',
                'expanded'=>false,
                'multiple'=>false,
            ])
            ->add('lieu', EntityType::class, [
                'label'=>'Lieu',
                'class' => Lieu::class,
                'choice_label' => 'nom',
                'expanded'=>false,
                'multiple'=>false,
                ])
            ->add('Enregistrer', SubmitType::class)
            ->add('PublierLaSortie', SubmitType::class)
            ->add('AnnulerLesModifications', ResetType::class)
            ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }

}