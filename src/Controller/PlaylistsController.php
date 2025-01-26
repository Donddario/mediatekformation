<?php

namespace App\Controller;

use App\Repository\CategorieRepository;
use App\Repository\FormationRepository;
use App\Repository\PlaylistRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Contrôleur pour gérer les playlists.
 *
 * @author emds
 */
class PlaylistsController extends AbstractController
{
    /**
     * Template pour la page des playlists.
     */
    private const TEMPLATE_PLAYLISTS = 'pages/playlists.html.twig';

    /**
     * Template pour la page d'une playlist spécifique.
     */
    private const TEMPLATE_PLAYLIST = 'pages/playlist.html.twig';

    /**
     * Repository pour les playlists.
     *
     * @var PlaylistRepository
     */
    private PlaylistRepository $playlistRepository;

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
     * @param PlaylistRepository $playlistRepository
     * @param CategorieRepository $categorieRepository
     * @param FormationRepository $formationRepository
     */
    public function __construct(
        PlaylistRepository $playlistRepository,
        CategorieRepository $categorieRepository,
        FormationRepository $formationRepository
    ) {
        $this->playlistRepository = $playlistRepository;
        $this->categorieRepository = $categorieRepository;
        $this->formationRepository = $formationRepository;
    }

    #[Route('/playlists', name: 'playlists')]
    public function index(): Response
    {
        $playlists = $this->playlistRepository->findAllOrderByName('ASC');
        $categories = $this->categorieRepository->findAll();

        return $this->render(self::TEMPLATE_PLAYLISTS, [
            'playlists' => $playlists,
            'categories' => $categories,
        ]);
    }

    #[Route('/playlists/tri/{champ}/{ordre}', name: 'playlists.sort')]
    public function sort(string $champ, string $ordre): Response
    {
        if ($champ === 'name') {
            $playlists = $this->playlistRepository->findAllOrderByName($ordre);
        } elseif ($champ === 'formationsCount') {
            $playlists = $this->playlistRepository->findAllOrderByFormationCount($ordre);
        } else {
            $playlists = $this->playlistRepository->findAll();
        }

        $categories = $this->categorieRepository->findAll();

        return $this->render(self::TEMPLATE_PLAYLISTS, [
            'playlists' => $playlists,
            'categories' => $categories,
        ]);
    }

    #[Route('/playlists/recherche/{champ}/{table}', name: 'playlists.findallcontain')]
    public function findAllContain(string $champ, Request $request, string $table = ""): Response
    {
        $valeur = $request->get('recherche');
        $playlists = $this->playlistRepository->findByContainValue($champ, $valeur, $table);
        $categories = $this->categorieRepository->findAll();

        return $this->render(self::TEMPLATE_PLAYLISTS, [
            'playlists' => $playlists,
            'categories' => $categories,
            'valeur' => $valeur,
            'table' => $table,
        ]);
    }

    #[Route('/playlists/playlist/{id}', name: 'playlists.showone')]
    public function showOne(int $id): Response
    {
        $playlist = $this->playlistRepository->find($id);
        $playlistCategories = $this->categorieRepository->findAllForOnePlaylist($id);
        $playlistFormations = $this->formationRepository->findAllForOnePlaylist($id);

        return $this->render(self::TEMPLATE_PLAYLIST, [
            'playlist' => $playlist,
            'playlistcategories' => $playlistCategories,
            'playlistformations' => $playlistFormations,
        ]);
    }
}
