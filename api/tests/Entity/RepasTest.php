<?php

namespace App\Tests\Entity;

use App\Entity\Repas;
use App\Entity\DateRepas;
use App\Entity\Espece;
use App\Entity\Menu;
use PHPUnit\Framework\TestCase;

class RepasTest extends TestCase
{
    private Repas $repas;

    protected function setUp(): void
    {
        $this->repas = new Repas();
    }

    public function testGetSetDateRepas(): void
    {
        $dateRepas = new DateRepas();
        $this->repas->setDateRepas($dateRepas);
        $this->assertSame($dateRepas, $this->repas->getDateRepas());
    }

    public function testGetSetEspece(): void
    {
        $espece = new Espece();
        $espece->setNom('Lion');
        $this->repas->setEspece($espece);
        $this->assertSame($espece, $this->repas->getEspece());
    }

    public function testGetSetNomBaptemeAnimal(): void
    {
        $this->repas->setNomBaptemeAnimal('Simba');
        $this->assertSame('Simba', $this->repas->getNomBaptemeAnimal());
    }

    public function testGetSetQuantite(): void
    {
        $this->repas->setQuantite(250);
        $this->assertSame(250, $this->repas->getQuantite());
    }

    public function testGetSetMenu(): void
    {
        $menu = new Menu();
        $menu->setAliment('Viande');
        $menu->setQteAliment(500);

        $this->repas->setMenu($menu);
        $this->assertSame($menu, $this->repas->getMenu());
    }

    public function testFluentInterface(): void
    {
        $dateRepas = new DateRepas();
        $espece = new Espece();
        $espece->setNom('Lion');
        $menu = new Menu();
        $menu->setAliment('Viande');
        $menu->setQteAliment(500);

        $result = $this->repas
            ->setDateRepas($dateRepas)
            ->setEspece($espece)
            ->setNomBaptemeAnimal('Simba')
            ->setQuantite(250)
            ->setMenu($menu);

        $this->assertSame($this->repas, $result);
    }
}
