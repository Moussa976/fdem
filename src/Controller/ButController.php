<?php

namespace App\Controller;

use App\Entity\But;
use App\Repository\ButRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/but")
 */
class ButController extends AbstractController
{
    /**
     * @Route("/", name="app_but_index", methods={"GET"})
     */
    public function index(ButRepository $butRepository): Response
    {
        return $this->render('but/index.html.twig', [
            'buts' => $butRepository->findAll(),
        ]);
    }


    /**
     * @Route("/{id}", name="app_but_delete", methods={"POST"})
     */
    public function delete(Request $request, But $but, ButRepository $butRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$but->getId(), $request->request->get('_token'))) {
            $butRepository->remove($but, true);
        }

        return $this->redirectToRoute('app_but_index', [], Response::HTTP_SEE_OTHER);
    }
}
