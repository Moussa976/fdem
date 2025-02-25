<?php

namespace App\Form;

use App\Entity\But;
use App\Entity\Joueur;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ButType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('joueur', EntityType::class, [
                'label' => 'Nom du joueur :',
                'class' => Joueur::class,
                'choices' => $options['joueurs'], // Utiliser la liste des joueurs du match
                'choice_label' => function ($joueur) {
                    return $joueur->getEquipe()->getNom().' - '.$joueur->getNom() . ' (N°' . $joueur->getNumero() . ')';
                },
                'attr' => ['class' => 'form-control'],
                'placeholder' => 'Sélectionner un joueur',
            ])
            ->add('minute', IntegerType::class, [
                'label' => 'Minute(s) : (le temps joué)',
                'attr' => ['class' => 'form-control', 'placeholder' => 'Insérer le temps joué (ex. 30)'],
            ])
            ->add('type', ChoiceType::class, [
                'label' => 'Type de but : ',
                'choices' => [
                    'Normal' => 'normal',
                    'Prolongation' => 'prolongation',
                    'TAB (Tirs au but)' => 'tab',
                ],
                'attr' => ['class' => 'form-control'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => But::class,
            'joueurs' => [], // On attend les joueurs comme option
        ]);
    }
}
