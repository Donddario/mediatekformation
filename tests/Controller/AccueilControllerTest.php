<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Description of AccueilControllerTest
 *
 * @author Donddario
 */
class AccueilControllerTest extends WebTestCase
{
    /**
     * Test d'accès à l'index
     * @return void
     */
    public function testIndex(): void
    {
        $client = static::createClient();
        $client->request('GET', '/');
        $this->assertResponseIsSuccessful();

    }
}
