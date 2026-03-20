<?php

namespace App\Tests\Entity;

use App\Entity\Gestionnaire;
use PHPUnit\Framework\TestCase;

class GestionnaireTest extends TestCase
{
    private Gestionnaire $gestionnaire;

    protected function setUp(): void
    {
        $this->gestionnaire = new Gestionnaire();
    }

    public function testIdNullParDefaut(): void
    {
        $this->assertNull($this->gestionnaire->getId());
    }

    public function testGetSetLogin(): void
    {
        $this->gestionnaire->setLogin('admin');
        $this->assertSame('admin', $this->gestionnaire->getLogin());
    }

    public function testGetSetNom(): void
    {
        $this->gestionnaire->setNom('Administrateur');
        $this->assertSame('Administrateur', $this->gestionnaire->getNom());
    }

    public function testGetSetPrenom(): void
    {
        $this->gestionnaire->setPrenom('Systeme');
        $this->assertSame('Systeme', $this->gestionnaire->getPrenom());
    }

    public function testGetSetPassword(): void
    {
        $this->gestionnaire->setPassword('hashed');
        $this->assertSame('hashed', $this->gestionnaire->getPassword());
    }

    public function testRoleParDefaut(): void
    {
        $this->assertSame('gestionnaire', $this->gestionnaire->getRole());
    }

    public function testGetSetRole(): void
    {
        $this->gestionnaire->setRole('admin');
        $this->assertSame('admin', $this->gestionnaire->getRole());
    }

    public function testGetRolesGestionnaire(): void
    {
        $this->gestionnaire->setRole('gestionnaire');
        $roles = $this->gestionnaire->getRoles();
        $this->assertContains('ROLE_GESTIONNAIRE', $roles);
        $this->assertNotContains('ROLE_ADMIN', $roles);
    }

    public function testGetRolesAdmin(): void
    {
        $this->gestionnaire->setRole('admin');
        $roles = $this->gestionnaire->getRoles();
        $this->assertContains('ROLE_GESTIONNAIRE', $roles);
        $this->assertContains('ROLE_ADMIN', $roles);
    }

    public function testGetUserIdentifier(): void
    {
        $this->gestionnaire->setLogin('admin');
        $this->assertSame('admin', $this->gestionnaire->getUserIdentifier());
    }

    public function testFluentInterface(): void
    {
        $result = $this->gestionnaire
            ->setLogin('admin')
            ->setNom('Admin')
            ->setPrenom('Sys')
            ->setRole('admin')
            ->setPassword('pass');

        $this->assertSame($this->gestionnaire, $result);
    }
}
