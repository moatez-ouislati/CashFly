<?php

namespace App\Tests\Entity;

use App\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testUserSettersAndGetters()
    {
        $user = new User();
        
        $user->setEmail('test@example.com');
        $user->setNom('Doe');
        $user->setPrenom('John');
        $user->setPassword('hashedpassword');
        $user->setCin(12345678);
        $user->setTel('55667788');
        $user->setRoles('proprietaire');

        $this->assertEquals('test@example.com', $user->getEmail());
        $this->assertEquals('Doe', $user->getNom());
        $this->assertEquals('John', $user->getPrenom());
        $this->assertEquals('hashedpassword', $user->getPassword());
        $this->assertEquals(12345678, $user->getCin());
        $this->assertEquals('55667788', $user->getTel());
        $this->assertEquals(['ROLE_PROPRIETAIRE'], $user->getRoles());
    }

    public function testUserIdentifier()
    {
        $user = new User();
        $user->setEmail('test@example.com');
        $this->assertEquals('test@example.com', $user->getUserIdentifier());
    }
}
