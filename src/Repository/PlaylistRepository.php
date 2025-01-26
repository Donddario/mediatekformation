<?php

namespace App\Repository;

use App\Entity\Playlist;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Playlist>
 */
class PlaylistRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Playlist::class);
    }

    /**
     * Ajoute une entité Playlist à la base de données.
     *
     * @param Playlist $entity
     */
    public function add(Playlist $entity): void
    {
        $entityManager = $this->getEntityManager();
        $entityManager->persist($entity);
        $entityManager->flush();
    }

    /**
     * Supprime une entité Playlist de la base de données.
     *
     * @param Playlist $entity
     */
    public function remove(Playlist $entity): void
    {
        $entityManager = $this->getEntityManager();
        $entityManager->remove($entity);
        $entityManager->flush();
    }
    
    /**
    * Retourne les playlists triées par le nombre de formations.
    *
    * @param string $ordre
    * @return Playlist[]
    */
    public function findAllOrderByFormationCount(string $ordre): array
    {
        return $this->createQueryBuilder('p')
            ->select('p', 'COUNT(f) as HIDDEN formationCount')
            ->leftJoin('p.formations', 'f')
            ->groupBy('p.id')
            ->orderBy('formationCount', $ordre)
            ->getQuery()
            ->getResult();
    }


    /**
     * Retourne toutes les playlists triées par le nom.
     *
     * @param string $ordre
     * @return Playlist[]
     */
    public function findAllOrderByName(string $ordre): array
    {
        return $this->createQueryBuilder('p')
            ->leftJoin('p.formations', 'f')
            ->groupBy('p.id')
            ->orderBy('p.name', $ordre)
            ->getQuery()
            ->getResult();
    }



    /**
     * Retourne les playlists contenant une valeur dans un champ spécifique.
     * Si aucune valeur n'est donnée, retourne toutes les playlists triées.
     *
     * @param string $champ
     * @param string $valeur
     * @param string $table
     * @return Playlist[]
     */
    public function findByContainValue(string $champ, string $valeur, string $table = ""): array
    {
        if ($valeur === "") {
            return $this->findAllOrderByName('ASC');
        }

        $queryBuilder = $this->createQueryBuilder('p')
            ->leftJoin('p.formations', 'f')
            ->groupBy('p.id')
            ->orderBy('p.name', 'ASC');

        if ($table === "") {
            $queryBuilder->where("p.$champ LIKE :valeur");
        } else {
            $queryBuilder->leftJoin('f.categories', 'c')
                ->where("c.$champ LIKE :valeur");
        }

        return $queryBuilder
            ->setParameter('valeur', "%$valeur%")
            ->getQuery()
            ->getResult();
    }
}
