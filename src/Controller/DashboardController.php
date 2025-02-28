<?php

namespace App\Controller;

use App\Repository\JoueurRepository;
use App\Repository\MatcheRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    /**
     * @Route("/", name="app_matche_index", methods={"GET"})
     */
    public function index(MatcheRepository $matcheRepository, JoueurRepository $joueurRepository): Response
    {
        $joueurs = $joueurRepository->getClassementJoueurs();
        $user = $this->getUser();

        if ($user) {
            if ($user->getEquipe()) {
                $matches = $matcheRepository->findMatchsByEquipe($user->getEquipe());
            } else {
                $matches = $matcheRepository->findAllMatchs();
            }
        } else {
            $matches = $matcheRepository->findAllMatchs();
        }




        return $this->render('matche/index.html.twig', [
            'matches' => $matches,
            'joueurs' => $joueurs,
        ]);
    }
}
