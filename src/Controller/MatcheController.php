<?php

namespace App\Controller;

use App\Entity\FeuilleMatch;
use App\Entity\Matche;
use App\Form\MatcheType;
use App\Repository\FeuilleMatchRepository;
use App\Repository\JoueurRepository;
use App\Repository\MatcheRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

/**
 * @Route("/matche")
 */
class MatcheController extends AbstractController
{


    /**
     * @Route("/new", name="app_matche_new", methods={"GET", "POST"})
     */
    public function new(Request $request, MatcheRepository $matcheRepository): Response
    {
        $matche = new Matche();
        $feuilleMatch = new FeuilleMatch();

        $form = $this->createForm(MatcheType::class, $matche);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $matche->setStatut('à venir');
            $matche->setFeuilleMatch($feuilleMatch); // Ajouter une nouvelle feuille de match à Matche
            $matche->getFeuilleMatch()->setMatche($matche); // Ajouter le match dans feuille de match
            // dd($matche, $feuilleMatch);
            $matcheRepository->add($matche, true);
            $this->addFlash('success', 'Match créé !');
            return $this->redirectToRoute('app_matche_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('matche/new.html.twig', [
            'matche' => $matche,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_matche_show", methods={"GET"})
     */
    public function show(Matche $matche, EntityManagerInterface $em, Security $security): Response
    {
        if ($security->isGranted('ROLE_SUPERADMIN')) {
            if ($matche->getStatut() === 'à venir' && $matche->getLadate() <= new \DateTime()) {
                $matche->setStatut('en cours');
                $em->flush();
            }

            return $this->render('matche/show.html.twig', [
                'matche' => $matche,
            ]);
        } else {
            return $this->redirectToRoute('app_matche_apercufeuille_pdf', ['id' => $matche->getId()]);
        }
    }

    /**
     * @Route("/edit/{id}", name="app_matche_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Matche $matche, MatcheRepository $matcheRepository): Response
    {
        $form = $this->createForm(MatcheType::class, $matche);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $matcheRepository->add($matche, true);

            return $this->redirectToRoute('app_matche_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('matche/edit.html.twig', [
            'matche' => $matche,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_matche_delete", methods={"POST"})
     */
    public function delete(Request $request, Matche $matche, MatcheRepository $matcheRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $matche->getId(), $request->request->get('_token'))) {
            $matcheRepository->remove($matche, true);
        }

        return $this->redirectToRoute('app_matche_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/terminer/{id}", name="app_matche_terminer")
     */
    public function terminer(Matche $matche, EntityManagerInterface $em): Response
    {
        $matche->setStatut('terminé');
        $em->flush();

        $this->addFlash('success', 'Match marqué comme terminé.');
        return $this->redirectToRoute('app_matche_apercufeuille_pdf', ['id' => $matche->getId()]);
    }

    /**
     * @Route("/feuille-pdf/{id}", name="app_matche_feuille_pdf")
     */
    public function feuillePdf(Matche $matche, JoueurRepository $joueurRepository): Response
    {
        $joueursEquipe1 = $joueurRepository->findBy(['equipe' => $matche->getEquipe1()]);
        $joueursEquipe2 = $joueurRepository->findBy(['equipe' => $matche->getEquipe2()]);

        $joueursIndexed = [];
        foreach (array_merge($joueursEquipe1, $joueursEquipe2) as $joueur) {
            $joueursIndexed[$joueur->getId()] = $joueur;
        }

        $html = $this->renderView('matche/feuille_match_pdf.html.twig', [
            'matche' => $matche,
            'joueurs' => $joueursIndexed,
        ]);

        $html = mb_convert_encoding($html, 'UTF-8', 'UTF-8'); // ✅ Encodage UTF-8

        $pdfOptions = new Options();
        $pdfOptions->set('isRemoteEnabled', true);
        $pdfOptions->set('isHtml5ParserEnabled', true);
        $pdfOptions->set('isPhpEnabled', true);
        $pdfOptions->set('defaultFont', 'DejaVu Sans'); // ✅ Utilisation d’une police compatible avec les emojis

        $dompdf = new Dompdf($pdfOptions);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        return new Response(
            $dompdf->output(),
            200,
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="fdm - ' . $matche->getEquipe1()->getNom() . ' VS ' . $matche->getEquipe2()->getNom() . '.pdf"',
            ]
        );
    }



    /**
     * @Route("/apercufeuille-pdf/{id}", name="app_matche_apercufeuille_pdf")
     */
    public function apercufeuillePdf(Matche $matche, JoueurRepository $joueurRepository): Response
    {
        // Récupérer tous les joueurs des deux équipes
        $joueursEquipe1 = $joueurRepository->findBy(['equipe' => $matche->getEquipe1()]);
        $joueursEquipe2 = $joueurRepository->findBy(['equipe' => $matche->getEquipe2()]);

        // Indexer les joueurs par leur ID pour accès facile dans Twig
        $joueursIndexed = [];
        foreach (array_merge($joueursEquipe1, $joueursEquipe2) as $joueur) {
            $joueursIndexed[$joueur->getId()] = $joueur;
        }

        return $this->renderForm('matche/apercufeuille_match_pdf.html.twig', [
            'matche' => $matche,
            'joueurs' => $joueursIndexed, // On passe le tableau indexé par ID
        ]);
    }
}
