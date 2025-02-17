<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace App\Tests\Repository;

use App\Entity\Formation;
use App\Repository\FormationRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Description of FormationRepositoryTest
 *
 * @author Donddario
 */
class FormationRepositoryTest extends KernelTestCase
{
    
     private FormationRepository $formationRepository;

    protected function setUp(): void
    {
        self::bootKernel();
        $entityManager = self::getContainer()->get('doctrine')->getManager();
        $this->formationRepository = $entityManager->getRepository(Formation::class);
    }

    /**
     * Test d'ajout d'une formation
     * 
     * @return void
     */
    public function testAddFormation(): void
    {
        $formation = new Formation();
        $formation->setTitle('Test Formation');
        $formation->setPublishedAt(new \DateTime());

        $this->formationRepository->add($formation);

        $savedFormation = $this->formationRepository->findOneBy(['title' => 'Test Formation']);
        $this->assertNotNull($savedFormation);
        $this->assertEquals('Test Formation', $savedFormation->getTitle());
    }

    /**
     * Test de suppression d'une formation
     * 
     * @return void
     */
    public function testRemoveFormation(): void
    {
        $formation = new Formation();
        $formation->setTitle('Formation to Remove');
        $formation->setPublishedAt(new \DateTime());

        $this->formationRepository->add($formation);
        $this->formationRepository->remove($formation);

        $deletedFormation = $this->formationRepository->findOneBy(['title' => 'Formation to Remove']);
        $this->assertNull($deletedFormation);
    }

    /**
     * Liste toutes les formations selon le titre en ASC
     * 
     * @return void
     */
    public function testFindAllOrderBy(): void
    {
        $formations = $this->formationRepository->findAllOrderBy('title', 'ASC');
        $this->assertIsArray($formations);
        $this->assertNotEmpty($formations);
    }

    /**
     * Test sur la recherche d'une formation par un titre
     * 
     * @return void
     */
    public function testFindByContainValue(): void
    {
        $formations = $this->formationRepository->findByContainValue('title', 'Test');
        $this->assertIsArray($formations);
        foreach ($formations as $formation) {
            $this->assertStringContainsStringIgnoringCase('test', $formation->getTitle());
        }
    }

    /**
     * Vérifie que la méthode retourne un tableau de 3 formations
     * 
     * @return void
     */
    public function testFindAllLasted(): void
    {
        $formations = $this->formationRepository->findAllLasted(3);
        $this->assertIsArray($formations);
        $this->assertLessThanOrEqual(3, count($formations));
    }

    /**
     * Test si la méthode renvoi un tableau des formations pour une playlist spécifique
     * 
     * @return void
     */
    public function testFindAllForOnePlaylist(): void
    {
        $formations = $this->formationRepository->findAllForOnePlaylist(1);
        $this->assertIsArray($formations);
    }
}
