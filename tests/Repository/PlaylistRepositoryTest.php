<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace App\Tests\Repository;

use App\Entity\Playlist;
use App\Repository\PlaylistRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Description of PlaylistRepositoryTest
 *
 * @author Donddario
 */
class PlaylistRepositoryTest extends KernelTestCase {
    
    private PlaylistRepository $playlistRepository;

    protected function setUp(): void
    {
        parent::setUp();
        self::bootKernel();
        $entityManager = self::getContainer()->get('doctrine')->getManager();
        $this->playlistRepository = $entityManager->getRepository(Playlist::class);
    }

    /**
     * Test sur l'ajout d'une playlist
     * 
     * @return void
     */
    public function testAddPlaylist(): void
    {
        $playlist = new Playlist();
        $playlist->setName('test playlist');

        $this->playlistRepository->add($playlist);

        $savedPlaylist = $this->playlistRepository->findOneBy(['name' => 'test playlist']);
        $this->assertNotNull($savedPlaylist);
        $this->assertEquals('test playlist', $savedPlaylist->getName());
    }

    /**
     * Test sur la suppression d'une playlist
     * 
     * @return void
     */
    public function testRemovePlaylist(): void
    {
        $playlist = new Playlist();
        $playlist->setName('test remove playlist');

        $this->playlistRepository->add($playlist);
        $this->playlistRepository->remove($playlist);

        $deletedPlaylist = $this->playlistRepository->findOneBy(['name' => 'test remove playlist']);
        $this->assertNull($deletedPlaylist);
    }

    /**
     * Test sur le tri par le nb de formations en DESC
     * 
     * @return void
     */
    public function testFindAllOrderByFormationCount(): void
    {
        // test tri des playlists par nombre de formations
        $playlists = $this->playlistRepository->findAllOrderByFormationCount('DESC');

        $this->assertIsArray($playlists);
        $this->assertNotEmpty($playlists);

        // vérification que la première playlist a un nombre supérieur ou égal à la suivante
        if (count($playlists) > 1) {
            $firstCount = count($playlists[0]->getFormations());
            $secondCount = count($playlists[1]->getFormations());
            $this->assertGreaterThanOrEqual($secondCount, $firstCount);
        }
    }

    /**
     * Tester si l'on trouve une playlist selon son nom
     * 
     * @return void
     */
    public function testFindAllOrderByName(): void
    {
        $playlist1 = new Playlist();
        $playlist2 = new Playlist();

        $playlist1->setName("a_test");
        $playlist2->setName("b_test");

        $playlistRepository = $this->playlistRepository;

        // Ajout des playlists dans la base
        $playlistRepository->add($playlist1);
        $playlistRepository->add($playlist2);

        // Vérification du tri ASC
        $findAsc = $playlistRepository->findAllOrderByName('ASC');
        $this->assertSame("a_test", $findAsc[0]->getName());
        $this->assertSame("b_test", $findAsc[1]->getName());

        // Vérification du tri DESC
        $findAsc = array_filter(
        $playlistRepository->findAllOrderByName('ASC'),
        fn($p) => $p->getName() === 'a_test' || $p->getName() === 'b_test'
        );

        $this->assertCount(2, $findAsc);
        $this->assertSame("a_test", array_values($findAsc)[0]->getName());
        $this->assertSame("b_test", array_values($findAsc)[1]->getName());

        $playlistRepository->remove($playlist1);
        $playlistRepository->remove($playlist2);
    }

    /**
     * Test si l'on trouve la playlist selon une sous chaine du nom
     * 
     * @return void
     */
    public function testFindByContainValue(): void
    {
        $playlist = new Playlist();
        $playlist->setName('test search playlist');
        $this->playlistRepository->add($playlist);

        $playlists = $this->playlistRepository->findByContainValue('name', 'search');

        $this->assertIsArray($playlists);
        $this->assertNotEmpty($playlists);

        foreach ($playlists as $result) {
            $this->assertStringContainsStringIgnoringCase('search', $result->getName());
        }
    }
}
