<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace App\Tests\Repository;

use App\Entity\Categorie;
use App\Repository\CategorieRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Description of CategorieRepositoryTest
 *
 * @author Donddario
 */

class CategorieRepositoryTest extends KernelTestCase
{
    private CategorieRepository $categorieRepository;

    protected function setUp(): void
    {
        parent::setUp();
        self::bootKernel();
        $entityManager = self::getContainer()->get('doctrine')->getManager();
        $this->categorieRepository = $entityManager->getRepository(Categorie::class);
    }

    public function testAddCategorie(): void
    {
        $categorie = new Categorie();
        $categorie->setName('test catégorie');

        $this->categorieRepository->add($categorie);

        $savedCategorie = $this->categorieRepository->findOneBy(['name' => 'test catégorie']);
        $this->assertNotNull($savedCategorie);
        $this->assertEquals('test catégorie', $savedCategorie->getName());
    }

    public function testRemoveCategorie(): void
    {
        $categorie = new Categorie();
        $categorie->setName('test remove catégorie');

        $this->categorieRepository->add($categorie);
        $this->categorieRepository->remove($categorie);

        $deletedCategorie = $this->categorieRepository->findOneBy(['name' => 'test remove catégorie']);
        $this->assertNull($deletedCategorie);
    }

    public function testFindAllForOnePlaylist(): void
    {
        $categories = $this->categorieRepository->findAllForOnePlaylist(1);

        $this->assertIsArray($categories);
        $this->assertNotEmpty($categories);
        
        foreach ($categories as $categorie) {
            $this->assertInstanceOf(Categorie::class, $categorie);
        }
    }
}
