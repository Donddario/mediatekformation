<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace App\Tests\Validations;

use App\Entity\Formation;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use DateTime;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Description of FormationValidationsTest
 *
 * @author Donddario
 */
class FormationValidationsTest extends KernelTestCase

{
    
    public function getErrors(Formation $formation, int $expectedErrorCount, string $message="")
    {
        self::bootKernel();
        $validator = static::getContainer()->get(ValidatorInterface::class);
        $errors = $validator->validate($formation);

        $this->assertCount($expectedErrorCount, $errors, $message);

        return $errors;
    }

    /**
     * Test sur une date postérieure
     * 
     * @return void
     */
    public function testDateIsFutureShouldFail(): void
    {
        $formation = new Formation();
        $formation->setPublishedAt(new DateTime('2027-02-02')); // date posterieure (fail)

        $this->getErrors($formation, 1, "Date postérieure à aujourd'hui");
        
    }

    /**
     * Test sur date du jour
     * 
     * @return void
     */
    public function testDateIsTodayShouldSucceed(): void
    {
        $formation = new Formation();
        $formation->setPublishedAt(new DateTime('today')); // date du jour (succeed)

        // aucune errors normalement
        $this->getErrors($formation, 0);
    }

    /**
     * Test sur date antérieure
     * 
     * @return void
     */
    public function testDateIsPastShouldSucceed(): void
    {
        $formation = new Formation();
        $formation->setPublishedAt(new DateTime('2020-01-01')); // date anterieure (succeed)

        // aucune erros normalement
        $this->getErrors($formation, 0);
    }
}
