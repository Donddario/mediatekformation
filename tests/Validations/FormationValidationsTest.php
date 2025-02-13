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
    
    public function getErrors(Formation $formation, int $expectedErrorCount=0)
    {
        self::bootKernel();
        $validator = static::getContainer()->get(ValidatorInterface::class);
        $errors = $validator->validate($formation);

        $this->assertCount($expectedErrorCount, $errors);

        return $errors;
    }

    public function testDateIsFutureShouldFail(): void
    {
        $formation = new Formation();
        $formation->setPublishedAt(new DateTime('tomorrow')); // date posterieure (fail)

        $errors = $this->getErrors($formation, 1);
        $this->assertSame('La date de publication ne peut être postérieure à aujourd\'hui.', $errors[0]->getMessage());
    }

    public function testDateIsTodayShouldSucceed(): void
    {
        $formation = new Formation();
        $formation->setPublishedAt(new DateTime('today')); // date du jour (succeed)

        // aucune errors normalement
        $this->getErrors($formation, 0);
    }

    public function testDateIsPastShouldSucceed(): void
    {
        $formation = new Formation();
        $formation->setPublishedAt(new DateTime('2020-01-01')); // date anterieure (succeed)

        // aucune erros normalement
        $this->getErrors($formation, 0);
    }
}
