<?php

namespace App\Tests\Entity;

use App\Entity\User;
use App\Entity\Entreprise;
use App\Entity\Tresorerie;
use PHPUnit\Framework\TestCase;

class BusinessLogicTest extends TestCase
{
    public function testUserRoleMapping()
    {
        $user = new User();
        
        $user->setDbRole('administrateur');
        $this->assertContains('ROLE_ADMIN', $user->getRoles());

        $user->setDbRole('proprietaire');
        $this->assertContains('ROLE_PROPRIETAIRE', $user->getRoles());

        $user->setDbRole('investisseur');
        $this->assertContains('ROLE_INVESTISSEUR', $user->getRoles());
    }

    public function testInactiveUserLoginPrevention()
    {
        $user = new User();
        $user->setActive(false);
        
        $this->expectException(\Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException::class);
        $user->isEqualTo($user);
    }

    public function testEntrepriseRelation()
    {
        $owner = new User();
        $entreprise = new Entreprise();
        $entreprise->setProprietaire($owner);

        $this->assertSame($owner, $entreprise->getProprietaire());
    }

    public function testTresorerieInitialBalance()
    {
        $treso = new Tresorerie();
        $treso->setSolde('1000.50');
        
        $this->assertEquals('1000.50', $treso->getSolde());
    }
}
