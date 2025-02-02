<?php

namespace App\Controller\Admin;

use App\Entity\Formation;
use App\Form\FormationType;
use App\Repository\FormationRepository;
use App\Repository\CategorieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/formations', name: 'admin_formations_')]
class FormationsAdminController extends AbstractController
{
    #[Route('/admin/formations/tri/{champ}/{ordre}/{table}', name: 'admin_formations_sort')]
    public function sort(string $champ, string $ordre, FormationRepository $formationRepository, CategorieRepository $categorieRepository, string $table = ""): Response
    {
        $formations = $formationRepository->findAllOrderBy($champ, $ordre, $table);
        $categories = $categorieRepository->findAll();

        return $this->render('admin/formations/index.html.twig', [
            'formations' => $formations,
            'categories' => $categories,
        ]);
    }
    
    #[Route('/admin/formations/recherche/{champ}/{table}', name: 'admin_formations_filter')]
    public function filter(string $champ, Request $request, FormationRepository $formationRepository, CategorieRepository $categorieRepository, string $table = ""): Response
    {
        $valeur = $request->get('recherche');
        $formations = $formationRepository->findByContainValue($champ, $valeur, $table);
        $categories = $categorieRepository->findAll();

        return $this->render('admin/formations/index.html.twig', [
            'formations' => $formations,
            'categories' => $categories,
            'valeur' => $valeur,
            'table' => $table,
        ]);
    }

    #[Route('/', name: 'index')]
    public function index(FormationRepository $formationRepository, CategorieRepository $categorieRepository): Response
    {
        $formations = $formationRepository->findAll();
        $categories = $categorieRepository->findAll();

        return $this->render('admin/formations/index.html.twig', [
            'formations' => $formations,
            'categories' => $categories,
        ]);
    }

    #[Route('/ajouter', name: 'add')]
    public function add(Request $request, EntityManagerInterface $entityManager): Response
    {
        $formation = new Formation();
        $form = $this->createForm(FormationType::class, $formation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($formation);
            $entityManager->flush();
            
            return $this->redirectToRoute('admin_formations_index');
        }

        return $this->render('admin/formations/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/modifier/{id}', name: 'edit')]
    public function edit(Formation $formation, Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(FormationType::class, $formation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            
            return $this->redirectToRoute('admin_formations_index');
        }

        return $this->render('admin/formations/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/supprimer/{id}', name: 'delete')]
    public function delete(Formation $formation, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($formation);
        $entityManager->flush();

        return $this->redirectToRoute('admin_formations_index');
    }
}
