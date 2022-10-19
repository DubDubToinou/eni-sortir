<?php

namespace App\Form;

use App\Entity\Lieu;
use App\Entity\Ville;
use App\Repository\VilleRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LieuType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('rue')
            ->add('Ville', EntityType::class, [
                'class'=>Ville::class,
                'choice_label' => function (Ville $ville) {
                    return $ville->getCodePostal() . ' - ' . $ville->getNom();
                },
                'label'=>'Ville',
                'query_builder'=> function (VilleRepository $villeRepository){
                return $villeRepository->createQueryBuilder('v')->orderBy('v.codePostal', 'ASC');
                }
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Lieu::class,
        ]);
    }
}
