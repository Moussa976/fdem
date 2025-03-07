<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserRegistrationFormType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class UserRegistrationController extends AbstractController
{

    /**
     * @Route("/register", name="app_register")
     */
    public function register(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher, UserRepository $userRepository): Response
    {
        $user = new User();
        $form = $this->createForm(UserRegistrationFormType::class, $user);
        $users = $userRepository->findAll();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            // Vérifier si l'utilisateur a une équipe attribuée
            if ($user->getEquipe() !== null) {
                $existingUser = $userRepository->findOneBy(['equipe' => $user->getEquipe()]);
                if ($existingUser) {
                    // $this->addFlash('danger', 'Cette équipe "'.$user->getEquipe()->getNom().'" est déjà attribuée à un autre utilisateur.');
                    $this->addFlash('danger', 'Cette équipe est déjà attribuée à un autre utilisateur.');
                    return $this->redirectToRoute('app_register');
                }
            }

            // Hachage du mot de passe
            $user->setPassword($passwordHasher->hashPassword($user, $form->get('password')->getData()));

            // Sauvegarde de l'utilisateur
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Nouveau utilisateur inscrit avec succès !');
            return $this->redirectToRoute('app_register');
        }

        return $this->render('security/register.html.twig', [
            'registrationForm' => $form->createView(),
            'users' => $users,
        ]);
    }
}
