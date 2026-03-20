<?php

namespace App\Tests\Entity;

use App\Entity\Soignant;
use App\Entity\Espece;
use PHPUnit\Framework\TestCase;

class SoignantTest extends TestCase
{
    private Soignant $soignant;

    protected function setUp(): void
    {
        $this->soignant = new Soignant();
    }

    public function testGetSetMatricule(): void
    {
        $this->soignant->setMatricule('SOI001');
        $this->assertSame('SOI001', $this->soignant->getMatricule());
    }

    public function testGetSetNom(): void
    {
        $this->soignant->setNom('Dupont');
        $this->assertSame('Dupont', $this->soignant->getNom());
    }

    public function testGetSetPrenom(): void
    {
        $this->soignant->setPrenom('Marie');
        $this->assertSame('Marie', $this->soignant->getPrenom());
    }

    public function testGetSetTel(): void
    {
        $this->soignant->setTel('0612345678');
        $this->assertSame('0612345678', $this->soignant->getTel());
    }

    public function testTelNullParDefaut(): void
    {
        $this->assertNull($this->soignant->getTel());
    }

    public function testGetSetAdresse(): void
    {
        $this->soignant->setAdresse('12 rue de la Paix');
        $this->assertSame('12 rue de la Paix', $this->soignant->getAdresse());
    }

    public function testAdresseNullParDefaut(): void
    {
        $this->assertNull($this->soignant->getAdresse());
    }

    public function testGetSetPassword(): void
    {
        $this->soignant->setPassword('hashed_password');
        $this->assertSame('hashed_password', $this->soignant->getPassword());
    }

    public function testGetRoles(): void
    {
        $this->assertSame(['ROLE_SOIGNANT'], $this->soignant->getRoles());
    }

    public function testGetUserIdentifier(): void
    {
        $this->soignant->setMatricule('SOI001');
        $this->assertSame('SOI001', $this->soignant->getUserIdentifier());
    }

    public function testEspecesCollectionVide(): void
    {
        $this->assertCount(0, $this->soignant->getEspeces());
    }

    public function testAddEspece(): void
    {
        $espece = new Espece();
        $espece->setNom('Lion');

        $this->soignant->addEspece($espece);
        $this->assertCount(1, $this->soignant->getEspeces());
        $this->assertTrue($this->soignant->getEspeces()->contains($espece));
    }

    public function testAddEspeceDoublon(): void
    {
        $espece = new Espece();
        $espece->setNom('Lion');

        $this->soignant->addEspece($espece);
        $this->soignant->addEspece($espece); // doublon
        $this->assertCount(1, $this->soignant->getEspeces());
    }

    public function testRemoveEspece(): void
    {
        $espece = new Espece();
        $espece->setNom('Lion');

        $this->soignant->addEspece($espece);
        $this->soignant->removeEspece($espece);
        $this->assertCount(0, $this->soignant->getEspeces());
    }

    public function testMax3Especes(): void
    {
        $e1 = new Espece();
        $e1->setNom('Lion');
        $e2 = new Espece();
        $e2->setNom('Tigre');
        $e3 = new Espece();
        $e3->setNom('Ours');

        $this->soignant->addEspece($e1);
        $this->soignant->addEspece($e2);
        $this->soignant->addEspece($e3);

        $this->assertCount(3, $this->soignant->getEspeces());
    }

    public function testFluentInterface(): void
    {
        $result = $this->soignant
            ->setMatricule('SOI001')
            ->setNom('Dupont')
            ->setPrenom('Marie')
            ->setTel('0612345678')
            ->setAdresse('12 rue')
            ->setPassword('pass');

        $this->assertSame($this->soignant, $result);
    }

    public function testEraseCredentials(): void
    {
        $this->soignant->eraseCredentials();
        $this->assertTrue(true); // ne fait rien, ne doit pas planter
    }
}
