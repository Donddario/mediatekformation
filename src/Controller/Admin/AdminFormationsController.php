<?php

namespace App\Controller\Admin;

use App\Entity\Formation;
use App\Form\FormationType;
use App\Repository\FormationRepository;
use App\Repository\PlaylistRepository;
use App\Repository\CategorieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Contrôleur pour gérer les formations dans le backoffice.
 */
#[Route('/admin/formations', name: 'admin.formations.')]
class AdminFormationsController extends AbstractController
{
    private FormationRepository $formationRepository;
    private EntityManagerInterface $entityManager;
    private PlaylistRepository $playlistRepository;
    private CategorieRepository $categorieRepository;

    public function __construct(
        FormationRepository $formationRepository,
        EntityManagerInterface $entityManager,
        PlaylistRepository $playlistRepository,
        CategorieRepository $categorieRepository
    ) {
        $this->formationRepository = $formationRepository;
        $this->entityManager = $entityManager;
        $this->playlistRepository = $playlistRepository;
        $this->categorieRepository = $categorieRepository;
    }

    /**
     * Liste toutes les formations avec options de tri et filtre.
     *
     * @param Request $request
     * @return Response
     */
    #[Route('/', name: 'index')]
    public function index(Request $request): Response
    {
        // Récupération des paramètres de tri et filtre
        $champ = $request->query->get('champ', 'publishedAt');
        $ordre = $request->query->get('ordre', 'DESC');
        $table = $request->query->get('table', '');

        $formations = $this->formationRepository->findAllOrderBy($champ, $ordre, $table);
        $categories = $this->categorieRepository->findAll();

        return $this->render('admin/formations/index.html.twig', [
            'formations' => $formations,
            'categories' => $categories,
            'champ' => $champ,
            'ordre' => $ordre,
            'table' => $table,
        ]);
    }

    /**
     * Ajouter une nouvelle formation.
     *
     * @param Request $request
     * @return Response
     */
    #[Route('/ajouter', name: 'ajouter')]
    public function ajouter(Request $request, FormationRepository $formationRepository): Response
    {
        $formation = new Formation();
        $form = $this->createForm(FormationType::class, $formation);
        $form->handleRequest($request);
               
        if ($form->isSubmitted() && $form->isValid()) {
            // On vérifie si la date est conforme
            if ($formation->getPublishedAt() > new \DateTime()) {
                $this->addFlash('error', 'Erreur : La date de publication ne peut être postérieure à aujourd\'hui.');
            } else {
                $this->entityManager->persist($formation);
                $this->entityManager->flush();

                $this->addFlash('success', 'La formation a été ajoutée avec succès.');

                return $this->redirectToRoute('admin.formations.index');
            }
        }

        return $this->render('admin/formations/ajouter.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Modifier une formation existante.
     *
     * @param Request $request
     * @param Formation $formation
     * @return Response
     */
    #[Route('/modifier/{id}', name: 'modifier')]
    public function modifier(Request $request, Formation $formation, FormationRepository $formationRepository): Response
    {
        $form = $this->createForm(FormationType::class, $formation);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            // Validation de la date
            if ($formation->getPublishedAt() > new \DateTime()) {
                $this->addFlash('error', 'Erreur : La date de publication ne peut être postérieure à aujourd\'hui.');
            } else {
                $this->entityManager->flush();

                $this->addFlash('success', 'La formation a été modifiée avec succès.');

                return $this->redirectToRoute('admin.formations.index');
            }
        }

        return $this->render('admin/formations/modifier.html.twig', [
            'form' => $form->createView(),
            'formation' => $formation,
        ]);
    }

    /**
     * Supprimer une formation après confirmation.
     *
     * @param Request $request
     * @param Formation $formation
     * @return Response
     */
    #[Route('/supprimer/{id}', name: 'supprimer', methods: ['POST'])]
    public function supprimer(Request $request, Formation $formation, FormationRepository $formationRepository): Response
    {
        if ($this->isCsrfTokenValid('supprimer'.$formation->getId(), $request->request->get('_token'))) {
            // On supprime la formation de sa playlist dans laquelle elle se trouvait
            if ($formation->getPlaylist()) {
                $formation->setPlaylist(null);
            }

            $this->entityManager->remove($formation);
            $this->entityManager->flush();

            $this->addFlash('success', 'Formation supprimée.');
        }

        return $this->redirectToRoute('admin.formations.index');
    }
}
