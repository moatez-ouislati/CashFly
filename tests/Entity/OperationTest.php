<?php

namespace App\Tests\Entity;

use App\Entity\Operation;
use App\Entity\Tresorerie;
use PHPUnit\Framework\TestCase;

class OperationTest extends TestCase
{
    public function testOperationTypeConstraint()
    {
        $operation = new Operation();
        $operation->setType('revenu');
        $this->assertEquals('revenu', $operation->getType());
        
        $operation->setType('depense');
        $this->assertEquals('depense', $operation->getType());
    }

    public function testMontantPrecision()
    {
        $operation = new Operation();
        $operation->setMontant('9999.99');
        $this->assertEquals('9999.99', $operation->getMontant());
    }

    public function testTresorerieRelation()
    {
        $operation = new Operation();
        $tresorerie = new Tresorerie();
        $operation->setTresorerie($tresorerie);
        
        $this->assertSame($tresorerie, $operation->getTresorerie());
    }
}
