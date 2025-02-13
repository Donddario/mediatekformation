<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * Description of PlaylistControllerTest
 *
 * @author Donddario
 */
class PlaylistControllerTest extends WebTestCase
{
    /**
     * Accessibilité de la page playlist
     */
    public function testIndex(){
       $client = static::createClient();
       $client->request('GET', '/playlists');
       $this->assertResponseStatusCodeSame(Response::HTTP_OK);
   }
   
   /**
    * Tri des playlists par ASC DESC
    */
    public function testTrierPlaylists()
    {
        $client = static::createClient();
        $client->request('GET', 'playlists/tri/name/DESC');
        $this->assertSelectorTextContains('h5', 'Visual Studio 2019 et C#');
        $client->request('GET', '/playlists/tri/name/ASC');
        $this->assertSelectorTextContains('h5', 'Bases de la programmation (C#)');
    }
    
    /**
     * Filtre les playlists par nom
     */
    public function testFiltrerPlaylists()
    {
        $client = static::createClient();
        $client->request('GET', '/playlists');
        $crawler = $client->submitForm('Filtrer', [
            'recherche' => 'Python'
        ]);
        $this->assertCount(1, $crawler->filter('h5'));
        $this->assertSelectorTextContains('h5', 'Programmation sous Python');
    }
    
    /**
     * Tri les playlists par catégories
     */
    public function testFiltrerByCategories()
    {
        $client = static::createClient();
        $client->request('GET', '/playlists');
        $crawler = $client->submitForm('Filtrer', [
            'recherche' => 'Python'
        ]);
        $this->assertCount(1, $crawler->filter('h5'));
        $this->assertSelectorTextContains('h5', 'Programmation sous Python');
    }
    
    /**
     * Tri par nombres de formations avec ASC DESC
     */
    public function testTrierNbFormations()
    {
        $client = static::createClient();
        $client->request('GET', '/playlists/tri/formationsCount/DESC');
        $this->assertSelectorTextContains('h5', 'Bases de la programmation (C#)');
        $client->request('GET', '/playlists/tri/formationsCount/ASC');
        $this->assertSelectorTextContains('h5', 'Cours Informatique embarquée');
    }
    
    /**
     * Regarder une playlist spécifique
     */
    public function testShowOne()
    {
        $client = static::createClient();
        $client->request('GET', '/playlists');
        $client->clickLink("Voir détail");
        $response = $client->getResponse();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $uri = $client->getRequest()->server->get("REQUEST_URI");
        $this->assertEquals('/playlists/playlist/13', $uri);
    }
}
