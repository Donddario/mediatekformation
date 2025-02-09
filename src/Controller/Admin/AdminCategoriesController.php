<?php

namespace App\Controller\Admin;

use App\Entity\Categorie;
use App\Form\CategorieType;
use App\Repository\CategorieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Contrôleur pour gérer les catégories dans le backoffice.
 */
#[Route('/admin/categories', name: 'admin.categories.')]
class AdminCategoriesController extends AbstractController
{
    private CategorieRepository $categorieRepository;
    private EntityManagerInterface $entityManager;

    public function __construct(
        CategorieRepository $categorieRepository,
        EntityManagerInterface $entityManager
    ) {
        $this->categorieRepository = $categorieRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * Liste toutes les catégories avec options d'ajout et suppression.
     *
     * @param Request $request
     * @return Response
     */
    #[Route('/', name: 'index')]
    public function index(Request $request): Response
    {
        $categories = $this->categorieRepository->findAll();

        // formulaire pour ajouter une nouvelle catégorie
        
        $categorie = new Categorie();
        $form = $this->createForm(CategorieType::class, $categorie);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Vérifie si la catégorie existe déjà
            $existingCategorie = $this->categorieRepository->findOneBy(['name' => $categorie->getName()]);
            if ($existingCategorie) {
                $this->addFlash('error', 'Cette catégorie existe déjà.');
            } else {
                $this->entityManager->persist($categorie);
                $this->entityManager->flush();

                $this->addFlash('success', 'Catégorie ajoutée.');

                return $this->redirectToRoute('admin.categories.index');
            }
        }

        return $this->render('admin/categories/index.html.twig', [
            'categories' => $categories,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Supprime une catégorie après confirmation.
     *
     * @param Request $request
     * @param Categorie $categorie
     * @return Response
     */
    #[Route('/supprimer/{id}', name: 'supprimer', methods: ['POST'])]
    public function supprimer(Request $request, Categorie $categorie): Response
    {
        if ($this->isCsrfTokenValid('supprimer'.$categorie->getId(), $request->request->get('_token'))) {
            // On vérifie si la catégorie contient des formations
            if (count($categorie->getFormations()) > 0) {
                $this->addFlash('error', 'Suppression impossible : cette catégorie contient au moins une formation.');
            } else {
                $this->entityManager->remove($categorie);
                $this->entityManager->flush();

                $this->addFlash('success', 'Catégorie supprimée.');
            }
        }

        return $this->redirectToRoute('admin.categories.index');
    }
}
