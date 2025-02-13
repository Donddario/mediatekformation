<?php

namespace App\Controller\Admin;

use App\Entity\Playlist;
use App\Form\PlaylistType;
use App\Repository\PlaylistRepository;
use App\Repository\FormationRepository;
use App\Repository\CategorieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Controleur pour gérer les playlists dans le backoffice.
 */
#[Route('/admin/playlists', name: 'admin.playlists.')]
class AdminPlaylistsController extends AbstractController
{
    private PlaylistRepository $playlistRepository;
    private EntityManagerInterface $entityManager;
    private FormationRepository $formationRepository;
    private CategorieRepository $categorieRepository;

    public function __construct(
        PlaylistRepository $playlistRepository,
        EntityManagerInterface $entityManager,
        FormationRepository $formationRepository,
        CategorieRepository $categorieRepository
    ) {
        $this->playlistRepository = $playlistRepository;
        $this->entityManager = $entityManager;
        $this->formationRepository = $formationRepository;
        $this->categorieRepository = $categorieRepository;
    }

    /**
     * Liste les playlists avec options de tri et filtres.
     *
     * @param Request $request
     * @return Response
     */
    #[Route('/', name: 'index')]
    public function index(Request $request): Response
    {
        // Récupération des paramètres de tri et filtre
        $champ = $request->query->get('champ', 'name');
        $ordre = $request->query->get('ordre', 'ASC');

        $categories = $this->categorieRepository->findAll();
        $playlists = $this->playlistRepository->findAll();

        return $this->render('admin/playlists/index.html.twig', [
            'playlists' => $playlists,
            'categories' => $categories,
            'champ' => $champ,
            'ordre' => $ordre,
        ]);
    }

    /**
     * Ajouter une nouvelle playlist.
     *
     * @param Request $request
     * @param PlaylistRepository $playlistRepository
     * @return Response
     */
    #[Route('/ajouter', name: 'ajouter')]
    public function ajouter(Request $request, PlaylistRepository $playlistRepository): Response
    {
        $playlist = new Playlist();
        $form = $this->createForm(PlaylistType::class, $playlist);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($playlist);
            $this->entityManager->flush();

            $this->addFlash('success', 'Playlist "'.$playlist->getName().'" ajoutée avec succès.');

            return $this->redirectToRoute('admin.playlists.index');
        }

        return $this->render('admin/playlists/ajouter.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Modifier une playlist existante.
     *
     * @param Request $request
     * @param Playlist $playlist
     * @param PlaylistRepository $playlistRepository
     * @return Response
     */
    #[Route('/modifier/{id}', name: 'modifier')]
    public function modifier(Request $request, Playlist $playlist, PlaylistRepository $playlistRepository): Response
    {
        $form = $this->createForm(PlaylistType::class, $playlist);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            $this->addFlash('success', 'Playlist "'.$playlist->getName().'" modifiée avec succès.');

            return $this->redirectToRoute('admin.playlists.index');
        }

        return $this->render('admin/playlists/modifier.html.twig', [
            'form' => $form->createView(),
            'playlist' => $playlist,
        ]);
    }

    /**
     * Supprimer une playlist après confirmation.
     *
     * @param Request $request
     * @param Playlist $playlist
     * @param PlaylistRepository $playlistRepository
     * @return Response
     */
    #[Route('/supprimer/{id}', name: 'supprimer', methods: ['POST'])]
    public function supprimer(Request $request, Playlist $playlist, PlaylistRepository $playlistRepository): Response
    {
        if ($this->isCsrfTokenValid('supprimer'.$playlist->getId(), $request->request->get('_token'))) {
            // Si la Playlist contient des formations
            if (count($playlist->getFormations()) > 0) {
                $this->addFlash('error', 'Suppression impossible : la Playlist contient des formations.');
            } else {
                $this->entityManager->remove($playlist);
                $this->entityManager->flush();

                $this->addFlash('success', 'Playlist "'.$playlist->getName().'" supprimée.');
            }
        }

        return $this->redirectToRoute('admin.playlists.index');
    }
}
