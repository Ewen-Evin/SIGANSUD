<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'Date_Repas')]
class DateRepas
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id_Date_Repas')]
    private ?int $id = null;

    public function getId(): ?int { return $this->id; }
}
