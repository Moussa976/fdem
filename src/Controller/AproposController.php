<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AproposController extends AbstractController
{
    /**
     * @Route("/apropos", name="app_apropos")
     */
    public function apropos(): Response
    {
        return $this->render('apropos/apropos.html.twig', [
            'controller_name' => 'AproposController',
        ]);
    }

    /**
     * @Route("/poliquesetmentions", name="app_poliquesetmentions")
     */
    public function poliquesetmentions(): Response
    {
        return $this->render('apropos/politiques.html.twig', [
            'controller_name' => 'AproposController',
        ]);
    }
}
