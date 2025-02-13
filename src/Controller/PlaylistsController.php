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
     * Template pour la page des playlists.
     */
    private const TEMPLATE_ADMIN_PLAYLISTS = 'admin/playlists/index.html.twig';
    
    /**
     * Template pour la page des playlists.
     */
    private const TEMPLATE_ADMIN_PLAYLIST = 'admin/playlists/playlist.html.twig';

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

    /**
     * Liste les playlists avec options de tri et filtre.
     * 
     * @return Response
     */
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

    /**
     * Trier les playlists.
     * 
     * @param Request $request
     * @param string $champ
     * @param string $ordre
     * @return Response
     */
    #[Route('/playlists/tri/{champ}/{ordre}', name: 'playlists.sort')]
    #[Route('/admin/playlists/tri/{champ}/{ordre}', name: 'admin.playlists.sort')]
    
    public function sort(Request $request, string $champ, string $ordre): Response
    {
        if ($champ === 'name') {
            $playlists = $this->playlistRepository->findAllOrderByName($ordre);
        } elseif ($champ === 'formationsCount') {
            $playlists = $this->playlistRepository->findAllOrderByFormationCount($ordre);
        } else {
            $playlists = $this->playlistRepository->findAll();
        }

        $categories = $this->categorieRepository->findAll();
        
        if ($request->get('_route') === 'playlists.sort') {
            
            return $this->render(self::TEMPLATE_PLAYLISTS, [
            'playlists' => $playlists,
            'categories' => $categories,
        ]);
        }

        return $this->render(self::TEMPLATE_ADMIN_PLAYLISTS, [
            'playlists' => $playlists,
            'categories' => $categories,
        ]);
    }

    /**
     * Rechercher une playlist.
     * 
     * @param string $champ
     * @param Request $request
     * @param string $table
     * @return Response
     */
    #[Route('/playlists/recherche/{champ}/{table}', name: 'playlists.findallcontain')]
    #[Route('/admin/playlists/recherche/{champ}/{table}', name: 'admin.playlists.findallcontain')]
    
    public function findAllContain(string $champ, Request $request, string $table = ""): Response
    {
        $valeur = $request->get('recherche');
        $playlists = $this->playlistRepository->findByContainValue($champ, $valeur, $table);
        $categories = $this->categorieRepository->findAll();
        
        if ($request->get('_route') === 'playlists.findallcontain') {
            
            return $this->render(self::TEMPLATE_PLAYLISTS, [
            'playlists' => $playlists,
            'categories' => $categories,
            'valeur' => $valeur,
            'table' => $table,
        ]);
        }

        return $this->render(self::TEMPLATE_ADMIN_PLAYLISTS, [
            'playlists' => $playlists,
            'categories' => $categories,
            'valeur' => $valeur,
            'table' => $table,
        ]);
    }

    /**
     * Voir une playlist spécifique.
     * 
     * @param int $id
     * @param Request $request
     * @return Response
     */
    #[Route('/playlists/playlist/{id}', name: 'playlists.showone')]
    #[Route('/admin/playlists/playlist/{id}', name: 'admin.playlists.showone')]
    
    public function showOne(int $id, Request $request): Response
    {
        $playlist = $this->playlistRepository->find($id);
        $playlistCategories = $this->categorieRepository->findAllForOnePlaylist($id);
        $playlistFormations = $this->formationRepository->findAllForOnePlaylist($id);
        
        if ($request->get('_route') === 'playlists.showone') {
            
            return $this->render(self::TEMPLATE_PLAYLIST, [
            'playlist' => $playlist,
            'playlistcategories' => $playlistCategories,
            'playlistformations' => $playlistFormations,
            ]);
        }

        return $this->render(self::TEMPLATE_ADMIN_PLAYLIST, [
            'playlist' => $playlist,
            'playlistcategories' => $playlistCategories,
            'playlistformations' => $playlistFormations,
        ]);
    }
}
