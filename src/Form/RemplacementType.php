<?php

namespace App\Form;

use App\Entity\Joueur;
use App\Entity\Remplacement;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RemplacementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('joueur_sortant', EntityType::class, [
                'label' => 'Nom du joueur sortant :',
                'class' => Joueur::class,
                'choices' => $options['joueursSortants'], // Correction ici
                'choice_label' => function ($joueur) {
                    return $joueur->getEquipe()->getNom().' - '.$joueur->getNom() . ' (N°' . $joueur->getNumero() . ')';
                },
                'attr' => ['class' => 'form-control'],
                'placeholder' => 'Sélectionner un joueur sortant',
            ])
            ->add('joueur_entrant', EntityType::class, [
                'label' => 'Nom du joueur entrant :',
                'class' => Joueur::class,
                'choices' => $options['joueursEntrants'], // Correction ici
                'choice_label' => function ($joueur) {
                    return $joueur->getEquipe()->getNom().' - '.$joueur->getNom() . ' (N°' . $joueur->getNumero() . ')';
                },
                'attr' => ['class' => 'form-control'],
                'placeholder' => 'Sélectionner un joueur entrant',
            ])
            ->add('minute', IntegerType::class, [
                'label' => 'Minute(s) : (le temps joué)',
                'attr' => ['class' => 'form-control', 'placeholder' => 'Insérer le temps joué (ex. 30)'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Remplacement::class,
            'joueursSortants' => [], // Option pour les joueurs sortants
            'joueursEntrants' => [], // Option pour les joueurs entrants
        ]);
    }
}
