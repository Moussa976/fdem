<?php

namespace App\Form;

use App\Entity\Equipe;
use App\Entity\Matche;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MatcheType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('ladate', DateTimeType::class, [
                'widget' => 'single_text',
                'label' => 'Date du match :',
                'attr' => [
                    'class' => 'form-control'
                ],
            ])
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'Championnat' => 'Championnat',
                    'Barrage' => 'Barrage',
                    '1/4 de finale' => '1/4 de finale',
                    '1/2 finale' => '1/2 finale',
                    'Finale' => 'Finale',
                ],
                'label' => 'Type de match :',
                'attr' => [
                    'class' => 'form-control'
                ],
                'placeholder' => 'Choisissez le type de match',  // Option pour une valeur vide initiale
            ])
            ->add('equipe1', EntityType::class, [
                'class' => Equipe::class,
                'choice_label' => 'nom',
                'label' => 'Équipe recevante :',
                'attr' => [
                    'class' => 'form-control'
                ],
                'placeholder' => 'Choisissez une équipe',  // Option pour une valeur vide initiale
            ])
            ->add('equipe2', EntityType::class, [
                'class' => Equipe::class,
                'choice_label' => 'nom',
                'label' => 'Équipe visiteuse :',
                'attr' => [
                    'class' => 'form-control'
                ],
                'placeholder' => 'Choisissez une équipe',  // Option pour une valeur vide initiale
            ])->add('arbitreCentral', TextType::class, [
                'label' => 'Arbitre central :',
                'attr'=> [
                    'class' => 'form-control'
                ],
            ])
            ->add('arbitreAssistant1', TextType::class, [
                'label' => 'Arbitre assistant 1 :',
                'attr'=> [
                    'class' => 'form-control'
                ],
            ])
            ->add('arbitreAssistant2', TextType::class, [
                'label' => 'Arbitre assistant 2 :',
                'attr'=> [
                    'class' => 'form-control'
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Matche::class,
        ]);
    }
}
