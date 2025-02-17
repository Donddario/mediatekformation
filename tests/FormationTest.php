<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace App\Tests;

use App\Entity\Formation;
use DateTime;
use PHPUnit\Framework\TestCase;

/**
 * Description of FormationTest
 *
 * @author Donddario
 */
class FormationTest extends TestCase 
{
   /**
    * Test que la date ne retourne rien
    * 
    * @return void
    */ 
    public function testGetPublishedAtStringReturnsEmptyWhenNoDate(): void
    {
        $formation = new Formation();
        $this->assertSame('', $formation->getPublishedAtString());
    }

    /**
     * Test que la date retourne quelque chose
     * 
     * @return void
     */
    public function testGetPublishedAtStringReturnsFormattedDate(): void
    {
        $formation = new Formation();
        $date = new DateTime('2025-02-10');
        $formation->setPublishedAt($date);
        $this->assertSame('10/02/2025', $formation->getPublishedAtString());
    }
}
