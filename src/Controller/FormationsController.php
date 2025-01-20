<?php

namespace App\Controller;

use App\Repository\CategorieRepository;
use App\Repository\FormationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Contrôleur des formations.
 *
 * @author emds
 */
class FormationsController extends AbstractController
{
    /**
     * Template pour la page des formations.
     */
    private const TEMPLATE_FORMATIONS = 'pages/formations.html.twig';

    /**
     * Template pour la page d'une formation spécifique.
     */
    private const TEMPLATE_FORMATION = 'pages/formation.html.twig';

    /**
     * Repository pour les formations.
     *
     * @var FormationRepository
     */
    private FormationRepository $formationRepository;

    /**
     * Repository pour les catégories.
     *
     * @var CategorieRepository
     */
    private CategorieRepository $categorieRepository;

    /**
     * Constructeur pour initialiser les repositories.
     *
     * @param FormationRepository $formationRepository
     * @param CategorieRepository $categorieRepository
     */
    public function __construct(FormationRepository $formationRepository, CategorieRepository $categorieRepository)
    {
        $this->formationRepository = $formationRepository;
        $this->categorieRepository = $categorieRepository;
    }

    #[Route('/formations', name: 'formations')]
    public function index(): Response
    {
        $formations = $this->formationRepository->findAll();
        $categories = $this->categorieRepository->findAll();

        return $this->render(self::TEMPLATE_FORMATIONS, [
            'formations' => $formations,
            'categories' => $categories,
        ]);
    }

    #[Route('/formations/tri/{champ}/{ordre}/{table}', name: 'formations.sort')]
    public function sort(string $champ, string $ordre, string $table = ""): Response
    {
        $formations = $this->formationRepository->findAllOrderBy($champ, $ordre, $table);
        $categories = $this->categorieRepository->findAll();

        return $this->render(self::TEMPLATE_FORMATIONS, [
            'formations' => $formations,
            'categories' => $categories,
        ]);
    }

    #[Route('/formations/recherche/{champ}/{table}', name: 'formations.findallcontain')]
    public function findAllContain(string $champ, Request $request, string $table = ""): Response
    {
        $valeur = $request->get('recherche');
        $formations = $this->formationRepository->findByContainValue($champ, $valeur, $table);
        $categories = $this->categorieRepository->findAll();

        return $this->render(self::TEMPLATE_FORMATIONS, [
            'formations' => $formations,
            'categories' => $categories,
            'valeur' => $valeur,
            'table' => $table,
        ]);
    }

    #[Route('/formations/formation/{id}', name: 'formations.showone')]
    public function showOne(int $id): Response
    {
        $formation = $this->formationRepository->find($id);

        return $this->render(self::TEMPLATE_FORMATION, [
            'formation' => $formation,
        ]);
    }
}
