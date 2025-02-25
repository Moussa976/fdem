<?php

namespace App\Form;

use App\Entity\FeuilleMatch;
use App\Entity\Joueur;
use App\Repository\JoueurRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FeuilleMatchType extends AbstractType
{
    private $joueurRepository;

    public function __construct(JoueurRepository $joueurRepository)
    {
        $this->joueurRepository = $joueurRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $matche = $options['matche']; 

        $joueursEquipe1 = $this->joueurRepository->findBy(['equipe' => $matche->getEquipe1()]);
        $joueursEquipe2 = $this->joueurRepository->findBy(['equipe' => $matche->getEquipe2()]);

        foreach (array_merge($joueursEquipe1, $joueursEquipe2) as $joueur) {
            $builder
                ->add('titulaire_' . $joueur->getId(), CheckboxType::class, [
                    'label' => false,
                    'required' => false,
                    'mapped' => false,
                ])
                ->add('remplacant_' . $joueur->getId(), CheckboxType::class, [
                    'label' => false,
                    'required' => false,
                    'mapped' => false,
                ])
                ->add('numeroMaillot_' . $joueur->getId(), IntegerType::class, [
                    'label' => false,
                    'required' => false,
                    'mapped' => false,
                    'attr' => ['class' => 'form-control', 'style' => 'width: 60px;'],
                ]);
        }

        // Ajout des dirigeants
        $builder
            ->add('dirigeantsEquipe1', TextType::class, [
                'mapped' => false,
                'required' => true,
                'label' => 'Capitaine ou dirigeant(s)',
                'attr'=> [
                    'class' => 'form-control border-primary'
                ],
            ])
            ->add('dirigeantsEquipe2', TextType::class, [
                'mapped' => false,
                'required' => true,
                'label' => 'Capitaine ou dirigeant(s)',
                'attr'=> [
                    'class' => 'form-control border-primary'
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => FeuilleMatch::class,
            'matche' => null,
            'joueursEquipe1' => [],
            'joueursEquipe2' => [],
        ]);
    }
}
