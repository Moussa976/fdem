<?php

namespace App\Form;

use App\Entity\Equipe;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserRegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Email : ',
                'attr' => [
                    'class' => 'form-control', 'placeholder' => 'email@exemple.fr'
                ],
            ])
            ->add('password', PasswordType::class, [
                'label' => 'Mot de passe : ',
                'attr' => [
                    'class' => 'form-control', 'placeholder' => '*********************'
                ],
            ])
            ->add('roles', ChoiceType::class, [
                'label' => 'Rôles : ',
                'choices' => [
                    'Équipe' => 'ROLE_EQUIPE',
                    'Administrateur' => 'ROLE_ADMIN',
                ],
                'expanded' => true,
                'multiple' => true,
            ])
            ->add('equipe', EntityType::class, [
                'class' => Equipe::class,
                'choice_label' => 'nom',
                'label' => 'Équipe :',
                'attr' => [
                    'class' => 'form-control', 'required' => false
                ],
                'placeholder' => 'Choisissez une équipe',  // Option pour une valeur vide initiale
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
