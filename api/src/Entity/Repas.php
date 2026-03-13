<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'Repas')]
class Repas
{
    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: DateRepas::class)]
    #[ORM\JoinColumn(name: 'id_Date_Repas', referencedColumnName: 'id_Date_Repas', nullable: false)]
    private DateRepas $dateRepas;

    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: Espece::class)]
    #[ORM\JoinColumn(name: 'id_Espece', referencedColumnName: 'id_Espece', nullable: false)]
    private Espece $espece;

    #[ORM\Id]
    #[ORM\Column(name: 'nomBapteme_Animal', length: 50)]
    private string $nomBaptemeAnimal;

    #[ORM\Column(name: 'qte_Repas')]
    private int $quantite;

    #[ORM\ManyToOne(targetEntity: Menu::class)]
    #[ORM\JoinColumn(name: 'id_Menu', referencedColumnName: 'id_Menu', nullable: false)]
    private Menu $menu;

    public function getDateRepas(): DateRepas { return $this->dateRepas; }
    public function setDateRepas(DateRepas $dateRepas): static { $this->dateRepas = $dateRepas; return $this; }
    public function getEspece(): Espece { return $this->espece; }
    public function setEspece(Espece $espece): static { $this->espece = $espece; return $this; }
    public function getNomBaptemeAnimal(): string { return $this->nomBaptemeAnimal; }
    public function setNomBaptemeAnimal(string $nomBaptemeAnimal): static { $this->nomBaptemeAnimal = $nomBaptemeAnimal; return $this; }
    public function getQuantite(): int { return $this->quantite; }
    public function setQuantite(int $quantite): static { $this->quantite = $quantite; return $this; }
    public function getMenu(): Menu { return $this->menu; }
    public function setMenu(Menu $menu): static { $this->menu = $menu; return $this; }
}
