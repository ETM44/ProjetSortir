<?php

namespace App\Form;

use App\Entity\Sortie;
use App\Entity\Lieu;
use App\Entity\Ville;
use App\Entity\Site;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\DomCrawler\Field\TextareaFormField;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreerSortieFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => "Nom de la sortie :"
            ])
            ->add('dateHeureDebut', DateTimeType::class, [
                'label' => "Date et heure de la sortie :",
                'data' => new \DateTime("now")
            ])
            ->add('dateLimiteInscription', DateTimeType::class, [
                'label' => "Date limite pour vous inscrire :",
                'data' => new \DateTime("now")
            ])
            ->add('nbInscriptionsMax', IntegerType::class, [
                'label' => "Nombre de places"
            ])
            ->add('duree', IntegerType::class, [
                'label' => "Duree"
            ])
            ->add('infosSortie', TextareaType::class, [
                'label' => "Description et infos :"
            ])
            ->add('site', EntityType::class, [
                'class' => Site::class,
                'choice_label'=> 'nom'
            ])
            ->add('lieu', EntityType::class, [
                'class' => Ville::class,
                'choice_label' => 'lieu.ville'
            ])
            
            ->add('lieu', EntityType::class, [
                'class' => Lieu::class,
                'choice_label'=> function($lieu){
                return $lieu-> getNom();
                }
            ])
            ->add('Enregistrer', SubmitType::class, [
                'attr' => ['class' => 'btn btn-warning']
            ])
            ->add('annuler', ResetType::class)

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}
