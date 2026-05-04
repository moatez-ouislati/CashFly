<?php

namespace App\Tests\Entity;

use App\Entity\Entreprise;
use App\Entity\Tresorerie;
use PHPUnit\Framework\TestCase;

class TresorerieTest extends TestCase
{
    public function testDefaultDevise()
    {
        $tresorerie = new Tresorerie();
        $this->assertEquals('EUR', $tresorerie->getDevise());
    }

    public function testSoldeAsNumericString()
    {
        $tresorerie = new Tresorerie();
        $tresorerie->setSolde('1250.75');
        $this->assertIsString($tresorerie->getSolde());
        $this->assertEquals('1250.75', $tresorerie->getSolde());
    }

    public function testTypeCompteValidationValues()
    {
        $tresorerie = new Tresorerie();
        $validTypes = ['CAISSE', 'BANQUE', 'CARTE', 'WALLET'];
        
        foreach ($validTypes as $type) {
            $tresorerie->setTypeCompte($type);
            $this->assertEquals($type, $tresorerie->getTypeCompte());
        }
    }
}
