<?php

namespace App\Form;

use App\Entity\Campus;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RechercherType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('rechercheCampus', EntityType::class,[
                'placeholder'=>'Choissisez votre campus',
                'label'=>'Campus',
                'class'=> Campus::class,
                'choice_label'=>'nom',
                'required'=>false])

            ->add('mots', SearchType::class, [
                'label'=>'Le nom contient',
                'required'=>false,
            ])

            ->add('dateHeureDebut', DateTimeType::class, [
                'label'=>'Entre',
                'required'=>false,
                'widget'=>'single_text',
                'attr'=>['id'=>'datepicker'],
            ])

            ->add('dateLimiteInscription', DateTimeType::class,[
                'label'=>'et',
                'required'=>false,
                'widget'=>'single_text',
                'attr'=>['id'=>'datepicker'],
            ])

            ->add('organisateur', CheckboxType::class, [
                'label'=>'Sortie dont je suis l\'organisateur/trice',
                'required'=>false,
            ])

            ->add('inscrit',CheckboxType::class, [
                'label'=>'Sorties auxquelles je suis inscrit',
                'required'=>false,
            ])

            ->add('pasInscrit',CheckboxType::class, [
                'label'=>'Sorties auxquelles je ne suis pas inscrit(e)',
                'required'=>false,
            ])


            ->add('dejaPasse', CheckboxType::class, [
                'label'=>'Sortie passées',
                'required'=>false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
