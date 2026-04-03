<?php

namespace App\Tests\Entity;

use App\Entity\DateRepas;
use PHPUnit\Framework\TestCase;

class DateRepasTest extends TestCase
{
    public function testIdNullParDefaut(): void
    {
        $dateRepas = new DateRepas();
        $this->assertNull($dateRepas->getId());
    }
}
