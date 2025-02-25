<?php

namespace App\Controller;

use App\Entity\But;
use App\Entity\Carton;
use App\Entity\Joueur;
use App\Entity\Matche;
use App\Entity\Remplacement;
use App\Form\ButType;
use App\Form\CartonType;
use App\Form\RemplacementType;
use App\Repository\ButRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EvenementMatchController extends AbstractController
{
    /**
     * @Route("/match/{id}/but", name="ajouter_but", methods={"GET", "POST"})
     */
    public function ajouterBut(Matche $matche, Request $request, EntityManagerInterface $em): Response
    {
        $feuilleMatch = $matche->getFeuilleMatch();

        if (!$feuilleMatch) {
            return $this->json(['success' => false, 'message' => 'Aucune feuille de match associée à ce match.']);
        }

        // Récupérer les IDs des joueurs titulaires + remplaçants des deux équipes
        $joueursIds = array_merge(
            $feuilleMatch->getTitulairesEquipe1(),
            $feuilleMatch->getRemplacantsEquipe1(),
            $feuilleMatch->getTitulairesEquipe2(),
            $feuilleMatch->getRemplacantsEquipe2()
        );

        $joueurs = $em->getRepository(Joueur::class)->findBy(['id' => $joueursIds]);

        $but = new But();
        $but->setMatche($matche);

        $form = $this->createForm(ButType::class, $but, [
            'joueurs' => $joueurs, // On passe les joueurs au formulaire
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($but);
            $em->flush();

            return $this->json(['success' => true, 'message' => 'But ajouté avec succès.']);
        }

        // Si ce n'est pas une requête AJAX, on affiche le modal normalement
        return $this->render('evenement_match/but_modal.html.twig', [
            'matche' => $matche,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/match/{id}/carton", name="ajouter_carton", methods={"GET", "POST"})
     */
    public function ajouterCarton(Matche $matche, Request $request, EntityManagerInterface $em): Response
    {
        $feuilleMatch = $matche->getFeuilleMatch();

        if (!$feuilleMatch) {
            return $this->json(['success' => false, 'message' => 'Aucune feuille de match associée à ce match.']);
        }

        $joueursIds = array_merge(
            $feuilleMatch->getTitulairesEquipe1(),
            $feuilleMatch->getRemplacantsEquipe1(),
            $feuilleMatch->getTitulairesEquipe2(),
            $feuilleMatch->getRemplacantsEquipe2()
        );

        $joueurs = $em->getRepository(Joueur::class)->findBy(['id' => $joueursIds]);

        $carton = new Carton();
        $carton->setMatche($matche);

        $form = $this->createForm(CartonType::class, $carton, [
            'joueurs' => $joueurs,
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($carton);
            $em->flush();

            return $this->json(['success' => true, 'message' => 'Carton ajouté avec succès.']);
        }

        return $this->render('evenement_match/carton_modal.html.twig', [
            'matche' => $matche,
            'formCarton' => $form->createView(),
        ]);
    }


    /**
     * @Route("/match/{id}/remplacement", name="ajouter_remplacement", methods={"GET", "POST"})
     */
    public function ajouterRemplacement(Matche $matche, Request $request, EntityManagerInterface $em): Response
    {
        $feuilleMatch = $matche->getFeuilleMatch();

        if (!$feuilleMatch) {
            return $this->json(['success' => false, 'message' => 'Aucune feuille de match associée à ce match.']);
        }

        // Récupération des joueurs sortants (titulaires) et entrants (remplaçants)
        // ** Fusionner les titulaires des deux équipes **
        $joueursSortants = $em->getRepository(Joueur::class)->findBy([
            'id' => array_merge($feuilleMatch->getTitulairesEquipe1(), $feuilleMatch->getTitulairesEquipe2())
        ]);

        // ** Fusionner les remplaçants des deux équipes **
        $joueursEntrants = $em->getRepository(Joueur::class)->findBy([
            'id' => array_merge($feuilleMatch->getRemplacantsEquipe1(), $feuilleMatch->getRemplacantsEquipe2())
        ]);

        $remplacement = new Remplacement();
        $remplacement->setMatche($matche);

        $form = $this->createForm(RemplacementType::class, $remplacement, [
            'joueursSortants' => $joueursSortants, // On passe bien les joueurs sortants
            'joueursEntrants' => $joueursEntrants, // On passe bien les joueurs entrants
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($remplacement);
            $em->flush();

            return $this->json(['success' => true, 'message' => 'Remplacement effectué avec succès.']);
        }

        return $this->render('evenement_match/remplacement_modal.html.twig', [
            'matche' => $matche,
            'formRemplacement' => $form->createView(),
        ]);
    }

    /**
     * @Route("/match/{id}/but/{butId}/supprimer", name="supprimer_but", methods={"POST"})
     */
    public function supprimerBut(Matche $matche, But $but, ButRepository $butRepository): JsonResponse
    {
        $butRepository->remove($but,true);
        $butRepository->flush();

        return $this->json(['success' => true, 'message' => 'But supprimé avec succès.']);
    }



    /**
     * @Route("/match/{id}/carton/{cartonId}/supprimer", name="supprimer_carton", methods={"DELETE"})
     */
    public function supprimerCarton(Matche $matche, Carton $carton, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($carton);
        $em->flush();

        return $this->json(['success' => true, 'message' => 'Carton supprimé avec succès.']);
    }

    /**
     * @Route("/match/{id}/remplacement/{remplacementId}/supprimer", name="supprimer_remplacement", methods={"DELETE"})
     */
    public function supprimerRemplacement(Matche $matche, Remplacement $remplacement, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($remplacement);
        $em->flush();

        return $this->json(['success' => true, 'message' => 'Remplacement supprimé avec succès.']);
    }



}
