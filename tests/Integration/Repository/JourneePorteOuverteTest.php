<?php

namespace App\Tests\Integration\Repository;

use App\Entity\JourneePorteOuverte;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class JourneePorteOuverteTest extends KernelTestCase
{
    private EntityManagerInterface $entityManager;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();
        
        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    public function testPersistence(): void
    {
        $jpo = new JourneePorteOuverte();
        $jpo->setTitre('Test JPO');
        $jpo->setDateEvenement(new \DateTime('2026-10-10'));
        $jpo->setLieu('Test Location');
        $jpo->setMaxParticipants(100);
        $jpo->setCurrentParticipants(0);
        
        $this->entityManager->persist($jpo);
        $this->entityManager->flush();
        
        $this->assertNotNull($jpo->getIdEvenement());
        
        $savedJpo = $this->entityManager->getRepository(JourneePorteOuverte::class)->find($jpo->getIdEvenement());
        
        $this->assertNotNull($savedJpo);
        $this->assertEquals('Test JPO', $savedJpo->getTitre());
        $this->assertEquals(100, $savedJpo->getMaxParticipants());
    }
    
    public function testIsFull(): void
    {
        $jpo = new JourneePorteOuverte();
        $jpo->setMaxParticipants(50);
        
        $jpo->setCurrentParticipants(49);
        $this->assertFalse($jpo->isFull());
        
        $jpo->setCurrentParticipants(50);
        $this->assertTrue($jpo->isFull());
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->entityManager->close();
    }
}
