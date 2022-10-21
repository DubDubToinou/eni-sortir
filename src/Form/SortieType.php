<?php

namespace App\Form;

use App\Entity\Lieu;
use App\Entity\Participant;
use App\Entity\Sortie;
use App\Entity\Ville;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class SortieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Titre'
            ])
            ->add('dateHeureDebut', DateTimeType::class, [
                'html5' => true,
                'label' => 'Date et heure de début',
                'placeholder' => array('year' => 'Année', 'month' => 'Mois', 'day' => 'Jour' )
            ])
            ->add('duree', TimeType::class, [
                'label' => 'Durée éstimée'
            ])
            ->add('dateLimiteInscription', DateTimeType::class, [
                'label' => 'Date limite d\'inscription',
                'placeholder' => array('year' => 'Année', 'month' => 'Mois', 'day' => 'Jour' )
            ])
            ->add('nbInscriptionsMax', IntegerType::class, [
                'label' => 'Nombre de places'
            ])
            ->add('infosSortie', TextAreaType::class, [
                'label' => 'Description'
            ])
            ->add('ville', EntityType::class, [
                'class' => Ville::class,
                'choice_label' => 'nom',
                'mapped' => false,
                'required' => false
            ]);

        $builder->get('ville')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) {
                $form = $event->getForm();
                $form->getParent()->add('lieu', EntityType::class, [
                    'class' => Lieu::class,
                    'mapped' => true,
                    'required' => true,
                    'choices' => $form->getData()->getLieux()
                ]);
            }
        );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}
