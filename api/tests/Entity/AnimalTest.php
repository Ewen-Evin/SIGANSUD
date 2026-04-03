<?php

namespace App\Tests\Entity;

use App\Entity\Animal;
use App\Entity\Espece;
use PHPUnit\Framework\TestCase;

class AnimalTest extends TestCase
{
    private Animal $animal;
    private Espece $espece;

    protected function setUp(): void
    {
        $this->animal = new Animal();
        $this->espece = new Espece();
        $this->espece->setNom('Lion');
    }

    public function testGetSetEspece(): void
    {
        $this->animal->setEspece($this->espece);
        $this->assertSame($this->espece, $this->animal->getEspece());
    }

    public function testGetSetNomBapteme(): void
    {
        $this->animal->setNomBapteme('Simba');
        $this->assertSame('Simba', $this->animal->getNomBapteme());
    }

    public function testGetSetGenre(): void
    {
        $this->animal->setGenre('Male');
        $this->assertSame('Male', $this->animal->getGenre());
    }

    public function testDateNaissanceNullParDefaut(): void
    {
        $this->assertNull($this->animal->getDateNaissance());
    }

    public function testGetSetDateNaissance(): void
    {
        $date = new \DateTime('2020-05-15');
        $this->animal->setDateNaissance($date);
        $this->assertSame($date, $this->animal->getDateNaissance());
    }

    public function testDateDecesNullParDefaut(): void
    {
        $this->assertNull($this->animal->getDateDeces());
    }

    public function testGetSetDateDeces(): void
    {
        $date = new \DateTime('2025-01-01');
        $this->animal->setDateDeces($date);
        $this->assertSame($date, $this->animal->getDateDeces());
    }

    public function testSetDateNaissanceNull(): void
    {
        $this->animal->setDateNaissance(new \DateTime());
        $this->animal->setDateNaissance(null);
        $this->assertNull($this->animal->getDateNaissance());
    }

    public function testFluentInterface(): void
    {
        $result = $this->animal
            ->setEspece($this->espece)
            ->setNomBapteme('Simba')
            ->setGenre('Male')
            ->setDateNaissance(new \DateTime())
            ->setDateDeces(null);

        $this->assertSame($this->animal, $result);
    }
}
