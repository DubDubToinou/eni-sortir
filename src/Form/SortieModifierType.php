<?php

namespace App\Form;

use App\Entity\Lieu;
use App\Entity\Sortie;
use App\Entity\Ville;
use App\Repository\VilleRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SortieModifierType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label'=>'Titre',
                'attr'=>[
                    'placeholder'=>'Titre'
                ]
            ])
            ->add('dateHeureDebut', DateTimeType::class,[
                'label'=>'Date et  heure de début',
                'widget'=>'single_text',
                'attr'=>[
                    'id'=>'datepicker',
                ]
            ])
            ->add('duree', TimeType::class,[
                'label'=>'Durée éstimée',
                'widget'=>'single_text',
                'attr'=>['id'=>'datepicker']
            ])
            ->add('dateLimiteInscription', DateTimeType::class, [
                'label'=>'Date limite d\'inscription',
                'widget'=>'single_text',
                'attr'=>['id'=>'datepicker'],
            ])
            ->add('nbInscriptionsMax', IntegerType::class, [
                'label'=>'Nombre de places',
                'attr'=>[
                    'placeholder'=>'Nombre de places'
                ]
            ])
            ->add('infosSortie', TextareaType::class, [
                'label'=>'Description de la sortie',
                'attr'=>[
                    'placeholder'=>'Description de la sortie'
                ]
            ])
            ->add('ville', EntityType::class, [
                'class'=>Ville::class,
                'label'=>'Ville ',
                'placeholder'=>'-- Selectionner la ville --',
                'choice_label' => function (Ville $ville) {
                    return $ville->getCodePostal() . ' - ' . $ville->getNom();
                },
                'query_builder'=> function (VilleRepository $villeRepository) {
                    return $villeRepository->createQueryBuilder('v')->orderBy('v.codePostal', 'ASC');
                },
                'required'=>false,
                'mapped'=>false,
            ])

            ->add('lieu', EntityType::class, [
                'class'=>Lieu::class,
                'placeholder'=>'-- Choissisez un lieu --',
                'choice_label'=>'nom',
                'choices'=>[],
            ]);

            $formModifier = function (FormInterface $form, Ville $ville = null){
                $lieu = (null === $ville) ? [] : $ville->getLieux();
                $form->add('lieu', EntityType::class,[
                    'class' => Lieu::class,
                    'choices' => $lieu,
                    'choice_label' => 'nom',
                    'placeholder' => '-- Choissisez une ville --',
                    'label' => 'Lieu : '
                ]);
            };

            $builder->get('ville')->addEventListener(
                FormEvents::POST_SUBMIT,
                function (FormEvent $eventVille) use ($formModifier){
                    $ville = $eventVille->getForm()->getData();
                    $formModifier($eventVille->getForm()->getParent(), $ville);
                }
            );
    }

            //->add('organisateur')
            //->add('participants')
            //->add('etat')
            //->add('lieu')
            //->add('campus')

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}
