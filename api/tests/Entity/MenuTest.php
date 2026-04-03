<?php

namespace App\Tests\Entity;

use App\Entity\Menu;
use App\Entity\Espece;
use PHPUnit\Framework\TestCase;

class MenuTest extends TestCase
{
    private Menu $menu;

    protected function setUp(): void
    {
        $this->menu = new Menu();
    }

    public function testIdNullParDefaut(): void
    {
        $this->assertNull($this->menu->getId());
    }

    public function testGetSetAliment(): void
    {
        $this->menu->setAliment('Viande de boeuf');
        $this->assertSame('Viande de boeuf', $this->menu->getAliment());
    }

    public function testGetSetQteAliment(): void
    {
        $this->menu->setQteAliment(500);
        $this->assertSame(500, $this->menu->getQteAliment());
    }

    public function testEspecesCollectionVide(): void
    {
        $this->assertCount(0, $this->menu->getEspeces());
    }

    public function testAddEspece(): void
    {
        $espece = new Espece();
        $espece->setNom('Lion');

        $this->menu->addEspece($espece);
        $this->assertCount(1, $this->menu->getEspeces());
        $this->assertTrue($this->menu->getEspeces()->contains($espece));
    }

    public function testAddEspeceDoublon(): void
    {
        $espece = new Espece();
        $espece->setNom('Lion');

        $this->menu->addEspece($espece);
        $this->menu->addEspece($espece);
        $this->assertCount(1, $this->menu->getEspeces());
    }

    public function testRemoveEspece(): void
    {
        $espece = new Espece();
        $espece->setNom('Lion');

        $this->menu->addEspece($espece);
        $this->menu->removeEspece($espece);
        $this->assertCount(0, $this->menu->getEspeces());
    }

    public function testFluentInterface(): void
    {
        $result = $this->menu
            ->setAliment('Poisson')
            ->setQteAliment(300);

        $this->assertSame($this->menu, $result);
    }
}
