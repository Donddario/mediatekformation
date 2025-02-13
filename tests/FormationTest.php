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
class FormationTest extends TestCase {
    
    public function testGetPublishedAtStringReturnsEmptyWhenNoDate(): void
    {
        $formation = new Formation();
        $this->assertSame('', $formation->getPublishedAtString());
    }

    public function testGetPublishedAtStringReturnsFormattedDate(): void
    {
        $formation = new Formation();
        $date = new DateTime('2025-02-10');
        $formation->setPublishedAt($date);
        $this->assertSame('10/02/2025', $formation->getPublishedAtString());
    }
}
