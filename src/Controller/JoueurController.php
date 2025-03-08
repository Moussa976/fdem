<?php

namespace App\Controller;

use App\Entity\Joueur;
use App\Form\JoueurType;
use App\Repository\JoueurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Entity\Equipe;
use Symfony\Component\Validator\Constraints\File;

/**
 * @Route("/joueur")
 */
class JoueurController extends AbstractController
{
    /**
     * @Route("/", name="app_joueur_index", methods={"GET"})
     */
    public function index(JoueurRepository $joueurRepository): Response
    {
        return $this->render('joueur/index.html.twig', [
            'joueurs' => $joueurRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_joueur_new", methods={"GET", "POST"})
     */
    public function new(Request $request, JoueurRepository $joueurRepository): Response
    {
        $joueur = new Joueur();
        $form = $this->createForm(JoueurType::class, $joueur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $joueurRepository->add($joueur, true);

            $this->addFlash('success', 'Joueur ' . $joueur->getNom() . ' inscrit avec succès !');
            return $this->redirectToRoute('app_joueur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('joueur/new.html.twig', [
            'joueur' => $joueur,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/show/{id}", name="app_joueur_show", methods={"GET"})
     */
    public function show(Joueur $joueur, JoueurRepository $joueurRepository): Response
    {
        $statJoueur = $joueurRepository->getStatistiquesJoueur($joueur->getId());
        return $this->render('joueur/show.html.twig', [
            'joueur' => $joueur,
            'statJoueur' => $statJoueur,
        ]);
    }

    /**
     * @Route("/edit/{id}", name="app_joueur_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Joueur $joueur, JoueurRepository $joueurRepository): Response
    {
        $form = $this->createForm(JoueurType::class, $joueur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $joueurRepository->add($joueur, true);

            $this->addFlash('success', 'Joueur ' . $joueur->getNom() . ' modifié avec succès !');
            return $this->redirectToRoute('app_joueur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('joueur/edit.html.twig', [
            'joueur' => $joueur,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/delete/{id}", name="app_joueur_delete", methods={"POST"})
     */
    public function delete(Request $request, Joueur $joueur, JoueurRepository $joueurRepository): Response
    {
        $this->addFlash('success', 'Joueur ' . $joueur->getNom() . ' supprimé avec succès !');
        if ($this->isCsrfTokenValid('delete' . $joueur->getId(), $request->request->get('_token'))) {
            $joueurRepository->remove($joueur, true);
        }

        return $this->redirectToRoute('app_joueur_index', [], Response::HTTP_SEE_OTHER);
    }


    /**
     * @Route("/import-joueurs", name="app_import_joueurs")
     */
    public function importJoueurs(Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createFormBuilder()
            ->add('fichier', FileType::class, [
                'label' => 'Insérer un fichier excel (.xlsx) ici : ',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'mimeTypes' => [
                            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', // .xlsx
                            'application/vnd.ms-excel', // .xls
                        ],
                        'mimeTypesMessage' => 'Veuillez télécharger un fichier Excel valide.',
                    ])
                ],
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $fichier = $form->get('fichier')->getData();

            if ($fichier) {
                $this->traiterFichierExcel($fichier, $entityManager);
                $this->addFlash('success', 'Importation réussie !');
                return $this->redirectToRoute('app_import_joueurs');
            } else {
                $this->addFlash('danger', 'Veuillez insérer un fichier excel !');
                return $this->redirectToRoute('app_import_joueurs');
            }
        }

        return $this->render('joueur/import.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    private function traiterFichierExcel($fichier, EntityManagerInterface $entityManager)
    {
        $spreadsheet = IOFactory::load($fichier);
        $worksheet = $spreadsheet->getActiveSheet();

        foreach ($worksheet->getRowIterator(2) as $row) { // Commence à la ligne 2 (ignorer les en-têtes)
            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(false);

            $data = [];
            foreach ($cellIterator as $cell) {
                $data[] = $cell->getValue();
            }

            // Supposons que le fichier Excel a deux ou trois colonnes : Nom, (Numéro - optionnel), ID_Équipe
            if (count($data) >= 2) {
                $nom = $data[0];
                $numero = isset($data[1]) && is_numeric($data[1]) ? (int) $data[1] : null; // Numéro facultatif
                $equipeId = isset($data[2]) ? (int) $data[2] : (int) $data[1]; // Si numéro absent, décalage des colonnes

                $equipe = $entityManager->getRepository(Equipe::class)->find($equipeId);
                if ($equipe) {
                    $joueur = new Joueur();
                    $joueur->setNom($nom);
                    $joueur->setNumero($numero); // Peut être NULL
                    $joueur->setEquipe($equipe);

                    $entityManager->persist($joueur);
                }
            }
        }

        $entityManager->flush();
    }


}
