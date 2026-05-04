<?php

namespace App\Tests\Entity;

use App\Entity\Entreprise;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class EntrepriseTest extends TestCase
{
    public function testEntrepriseSettersAndGetters()
    {
        $entreprise = new Entreprise();
        $user = new User();
        
        $entreprise->setNom('Test Entreprise');
        $entreprise->setSecteur('IT');
        $entreprise->setFormeJuridique('SARL');
        $entreprise->setCapital('100000.00');
        $entreprise->setProprietaire($user);
        $entreprise->setLatitude(45.0);
        $entreprise->setLongitude(5.0);
        $entreprise->setAdresse('123 Test St');

        $this->assertEquals('Test Entreprise', $entreprise->getNom());
        $this->assertEquals('IT', $entreprise->getSecteur());
        $this->assertEquals('SARL', $entreprise->getFormeJuridique());
        $this->assertEquals('100000.00', $entreprise->getCapital());
        $this->assertEquals($user, $entreprise->getProprietaire());
        $this->assertEquals(45.0, $entreprise->getLatitude());
        $this->assertEquals(5.0, $entreprise->getLongitude());
        $this->assertEquals('123 Test St', $entreprise->getAdresse());
    }

    public function testToString()
    {
        $entreprise = new Entreprise();
        $entreprise->setNom('CashFly Corp');
        $this->assertEquals('CashFly Corp', (string) $entreprise);
    }
}
