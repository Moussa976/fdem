<?php

namespace App\Controller;

use App\Entity\FeuilleMatch;
use App\Entity\Matche;
use App\Form\FeuilleMatchType;
use App\Form\ObservationArbitreType;
use App\Form\ReserveEquipe1Type;
use App\Form\ReserveEquipe2Type;
use App\Form\ReserveObservationType;
use App\Repository\JoueurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FeuilleMatchController extends AbstractController
{
    /**
     * @Route("/feuille-match/{id}/remplir", name="feuille_match_remplir")
     */
    public function remplirFeuille(Matche $matche, Request $request, JoueurRepository $joueurRepository, EntityManagerInterface $em): Response
    {
        // Vérifier que le match est "à venir"
        if ($matche->getStatut() !== 'à venir') {
            $this->addFlash('error', 'Vous ne pouvez pas remplir la feuille de match après le début.');
            return $this->redirectToRoute('app_matche_index');
        }

        // Récupérer la feuille de match existante ou en créer une nouvelle
        $feuilleMatch = $matche->getFeuilleMatch() ?? new FeuilleMatch();
        $feuilleMatch->setMatche($matche);

        // Récupérer les joueurs des deux équipes
        $joueursEquipe1 = $joueurRepository->findBy(['equipe' => $matche->getEquipe1()]);
        $joueursEquipe2 = $joueurRepository->findBy(['equipe' => $matche->getEquipe2()]);

        // Récupérer les données existantes de la feuille de match
        $dirigeantsEquipe1 = $feuilleMatch->getDirigeantsEquipe1() ?? [];
        $dirigeantsEquipe2 = $feuilleMatch->getDirigeantsEquipe2() ?? [];
        $titulairesEquipe1 = $feuilleMatch->getTitulairesEquipe1() ?? [];
        $remplacantsEquipe1 = $feuilleMatch->getRemplacantsEquipe1() ?? [];
        $titulairesEquipe2 = $feuilleMatch->getTitulairesEquipe2() ?? [];
        $remplacantsEquipe2 = $feuilleMatch->getRemplacantsEquipe2() ?? [];
        $numerosMaillot = $feuilleMatch->getNumerosMaillot() ?? [];

        // Création du formulaire
        $form = $this->createForm(FeuilleMatchType::class, $feuilleMatch, [
            'matche' => $matche,
            'joueursEquipe1' => $joueursEquipe1,
            'joueursEquipe2' => $joueursEquipe2,
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Récupération des dirigeants
            $dirigeantsEquipe1 = $form->get('dirigeantsEquipe1')->getData();
            $dirigeantsEquipe2 = $form->get('dirigeantsEquipe2')->getData();

            $feuilleMatch->setDirigeantsEquipe1($dirigeantsEquipe1 ? array_map('trim', explode(',', $dirigeantsEquipe1)) : []);
            $feuilleMatch->setDirigeantsEquipe2($dirigeantsEquipe2 ? array_map('trim', explode(',', $dirigeantsEquipe2)) : []);

            // Stockage des joueurs sélectionnés comme titulaires/remplaçants
            $titulairesEquipe1 = [];
            $remplacantsEquipe1 = [];
            $titulairesEquipe2 = [];
            $remplacantsEquipe2 = [];

            foreach ($joueursEquipe1 as $joueur) {
                if ($form->has('titulaire_' . $joueur->getId()) && $form->get('titulaire_' . $joueur->getId())->getData()) {
                    $titulairesEquipe1[] = $joueur->getId();
                }
                if ($form->has('remplacant_' . $joueur->getId()) && $form->get('remplacant_' . $joueur->getId())->getData()) {
                    $remplacantsEquipe1[] = $joueur->getId();
                }
            }

            foreach ($joueursEquipe2 as $joueur) {
                if ($form->has('titulaire_' . $joueur->getId()) && $form->get('titulaire_' . $joueur->getId())->getData()) {
                    $titulairesEquipe2[] = $joueur->getId();
                }
                if ($form->has('remplacant_' . $joueur->getId()) && $form->get('remplacant_' . $joueur->getId())->getData()) {
                    $remplacantsEquipe2[] = $joueur->getId();
                }
            }

            $feuilleMatch->setTitulairesEquipe1($titulairesEquipe1);
            $feuilleMatch->setRemplacantsEquipe1($remplacantsEquipe1);
            $feuilleMatch->setTitulairesEquipe2($titulairesEquipe2);
            $feuilleMatch->setRemplacantsEquipe2($remplacantsEquipe2);

            // Stockage des numéros de maillot
            $numerosMaillot = [];
            foreach (array_merge($joueursEquipe1, $joueursEquipe2) as $joueur) {
                if ($form->has('numeroMaillot_' . $joueur->getId())) {
                    $numero = $form->get('numeroMaillot_' . $joueur->getId())->getData();
                    if ($numero !== null) {
                        $numerosMaillot[$joueur->getId()] = $numero;
                    }
                }
            }
            $feuilleMatch->setNumerosMaillot($numerosMaillot);

            // Mettre à jour les numéros de maillot pour tous les joueurs des deux équipes
            foreach (array_merge($joueursEquipe1, $joueursEquipe2) as $joueur) {
                if (isset($numerosMaillot[$joueur->getId()])) {
                    $joueur->setNumero($numerosMaillot[$joueur->getId()]);
                    $em->persist($joueur);
                }
            }

            // Sauvegarde en base de données
            $em->persist($feuilleMatch);
            $em->flush();

            $this->addFlash('success', 'Feuille de match remplie avec succès.');
            return $this->redirectToRoute('app_matche_index');
        }

        return $this->render('feuille_match/remplir.html.twig', [
            'matche' => $matche,
            'form' => $form->createView(),
            'joueursEquipe1' => $joueursEquipe1,
            'joueursEquipe2' => $joueursEquipe2,
            'dirigeantsEquipe1' => $dirigeantsEquipe1,
            'dirigeantsEquipe2' => $dirigeantsEquipe2,
            'titulairesEquipe1' => $titulairesEquipe1,
            'remplacantsEquipe1' => $remplacantsEquipe1,
            'titulairesEquipe2' => $titulairesEquipe2,
            'remplacantsEquipe2' => $remplacantsEquipe2,
            'numerosMaillot' => $numerosMaillot,
        ]);
    }


    /**
     * @Route("/match/{id}/signer", name="signer_feuille_match", methods={"POST"})
     */
    public function signerFeuilleMatch(Matche $matche, Request $request, EntityManagerInterface $em): JsonResponse
    {
        $feuilleMatch = $matche->getFeuilleMatch();

        if (!$feuilleMatch) {
            return $this->json(['success' => false, 'message' => 'Aucune feuille de match trouvée.'], 404);
        }

        $type = $request->request->get('type'); // Récupérer le type de signature (equipe1, equipe2, arbitre)

        if ($type === 'avantequipe1') {
            $feuilleMatch->setSignatureavantEquipe1(true);
        } elseif ($type === 'avantequipe2') {
            $feuilleMatch->setSignatureavantEquipe2(true);
        } elseif ($type === 'apresequipe1') {
            $feuilleMatch->setSignatureapresEquipe1(true);
        } elseif ($type === 'apresequipe2') {
            $feuilleMatch->setSignatureapresEquipe2(true);
        } elseif ($type === 'arbitre') {
            $feuilleMatch->setSignatureArbitre(true);
        } else {
            return $this->json(['success' => false, 'message' => 'Type de signature invalide.'], 400);
        }

        $em->persist($feuilleMatch);
        $em->flush();

        return $this->json([
            'success' => true,
            'message' => 'Signature enregistrée avec succès.',
            'type' => $type
        ]);
    }

    /**
     * @Route("/match/{id}/reserve-equipe1", name="update_reserve_equipe1", methods={"GET", "POST"})
     */
    public function updateReserveEquipe1(Matche $matche, Request $request, EntityManagerInterface $em): Response
    {
        $feuilleMatch = $matche->getFeuilleMatch();
        $form = $this->createForm(ReserveEquipe1Type::class, $feuilleMatch);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($feuilleMatch);
            $em->flush();
            return $this->json(['success' => true, 'message' => 'Réserves de l\'équipe 1 mises à jour.']);
        }

        return $this->render('feuille_match/reserve_equipe1_modal.html.twig', [
            'formReserveEquipe1' => $form->createView(),
        ]);
    }

    /**
     * @Route("/match/{id}/reserve-equipe2", name="update_reserve_equipe2", methods={"GET", "POST"})
     */
    public function updateReserveEquipe2(FeuilleMatch $feuilleMatch, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(ReserveEquipe2Type::class, $feuilleMatch);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($feuilleMatch);
            $em->flush();
            return $this->json(['success' => true, 'message' => 'Réserves de l\'équipe 2 mises à jour.']);
        }

        return $this->render('feuille_match/reserve_equipe2_modal.html.twig', [
            'formReserveEquipe2' => $form->createView(),
        ]);
    }

    /**
     * @Route("/match/{id}/observation-arbitre", name="update_observation_arbitre", methods={"GET", "POST"})
     */
    public function updateObservationArbitre(FeuilleMatch $feuilleMatch, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(ObservationArbitreType::class, $feuilleMatch);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($feuilleMatch);
            $em->flush();
            return $this->json(['success' => true, 'message' => 'Observation de l\'arbitre mise à jour.']);
        }

        return $this->render('feuille_match/observation_arbitre_modal.html.twig', [
            'form' => $form->createView(),
        ]);
    }





}
