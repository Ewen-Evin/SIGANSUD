<?php

namespace App\Tests\Entity;

use App\Entity\Espece;
use PHPUnit\Framework\TestCase;

class EspeceTest extends TestCase
{
    private Espece $espece;

    protected function setUp(): void
    {
        $this->espece = new Espece();
    }

    public function testIdNullParDefaut(): void
    {
        $this->assertNull($this->espece->getId());
    }

    public function testGetSetNom(): void
    {
        $this->espece->setNom('Lion');
        $this->assertSame('Lion', $this->espece->getNom());
    }

    public function testAnimauxCollectionVide(): void
    {
        $this->assertCount(0, $this->espece->getAnimaux());
    }

    public function testSoignantsCollectionVide(): void
    {
        $this->assertCount(0, $this->espece->getSoignants());
    }

    public function testMenusCollectionVide(): void
    {
        $this->assertCount(0, $this->espece->getMenus());
    }

    public function testFluentInterface(): void
    {
        $result = $this->espece->setNom('Tigre');
        $this->assertSame($this->espece, $result);
    }
}
