<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * Description of FormationControllerTest
 *
 * @author Donddario
 */
class FormationControllerTest extends WebTestCase
{
    /**
     * Accessibilité de la page
     */
    public function testIndex()
    {
        $client = static::createClient();
        $client->request('GET', '/formations');
        $this->assertResponseIsSuccessful();
    }
    
    /**
     * Tri ASC DESC des formations
     */
    public function testTrierFormations()
    {
        $client = static::createClient();
        $client->request('GET', '/formations/tri/title/DESC');
        $this->assertSelectorTextContains('h5', 'UML : Diagramme de paquetages');
        $client->request('GET', '/formations/tri/title/ASC');
        $this->assertSelectorTextContains('h5', 'Android Studio (complément n°1) : Navigation Drawer et Fragment');
    }
    
    /**
     * Filtre selon le nom de la formation recherchée
     */
    public function testFilterFormations()
    {
        $client = static::createClient();
        $client->request('GET', '/formations');

        $crawler = $client->submitForm('filtrer', [
            'recherche' => 'Cours Programmation Objet'
        ]);
        $this->assertCount(1, $crawler->filter('h5'));
        $this->assertSelectorTextContains('h5', 'Cours Programmation Objet');
    }
    
    /**
     * Filtre selon le nom des playlists
     */
    public function testFiltrerPlaylist()
    {
        $client = static::createClient();
        $client->request('GET', '/formations');
        
        $crawler = $client->submitForm('filtrer', [
            'recherche' => 'Cours Curseurs'
        ]);
        $this->assertCount(2, $crawler->filter('h5'));
        $this->assertSelectorTextContains('h5', 'Cours Curseurs');
    }
    
    /**
     * Tri ASC DESC playlists
     */
    public function testTrierPlaylists()
    {
        $client = static::createClient();
        $client->request('GET', '/formations/tri/name/DESC/playlist');
        $this->assertSelectorTextContains('h5', 'C# : ListBox en couleur');
        $client->request('GET', '/formations/tri/name/ASC/playlist');
        $this->assertSelectorTextContains('h5', 'Bases de la programmation n°74 - POO : collections');
    }
    
    /**
     * Tri sur date publication
     */
    public function testTrierPublishedAt()
    {
        $client = static::createClient();
        $client->request('GET', '/formations/tri/publishedAt/DESC');
        $this->assertSelectorTextContains('h5', 'Eclipse n°8 : Déploiement');
        $client->request('GET', '/formations/tri/publishedAt/ASC');
        $this->assertSelectorTextContains('h5', 'Cours UML (1 à 7 / 33) : introduction et cas d\'utilisation');
    }
    
    /**
     * Tri sur catégories
     */
    public function testFiltrerCategories()
    {
        $client = static::createClient();
        $client->request('GET', '/formations');
        $crawler = $client->submitForm('filtrer', [
            'recherche' => 'Python'
        ]);
        $this->assertCount(19, $crawler->filter('h5'));
        $this->assertSelectorTextContains('h5', 'Python n°18 : Décorateur singleton');
    }

    /**
     * Regarder une catégorie spécifique
     */
    public function testShowOne()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/formations');
        $link = $crawler->filter('td a img')->closest('a')->link();
        $crawler = $client->click($link);
        $response = $client->getResponse();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $uri = $client->getRequest()->getRequestUri();
        $this->assertStringContainsString('/formations/formation/', $uri);
        $this->assertSelectorTextContains('h4','Eclipse n°8 : Déploiement');
    }
}
