<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'Espece')]
class Espece
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id_Espece')]
    private ?int $id = null;

    #[ORM\Column(name: 'nom_Espece', length: 50)]
    private string $nom;

    #[ORM\OneToMany(targetEntity: Animal::class, mappedBy: 'espece')]
    private Collection $animaux;

    #[ORM\ManyToMany(targetEntity: Soignant::class, mappedBy: 'especes')]
    private Collection $soignants;

    #[ORM\ManyToMany(targetEntity: Menu::class, mappedBy: 'especes')]
    private Collection $menus;

    public function __construct()
    {
        $this->animaux = new ArrayCollection();
        $this->soignants = new ArrayCollection();
        $this->menus = new ArrayCollection();
    }

    public function getId(): ?int { return $this->id; }
    public function getNom(): string { return $this->nom; }
    public function setNom(string $nom): static { $this->nom = $nom; return $this; }
    public function getAnimaux(): Collection { return $this->animaux; }
    public function getSoignants(): Collection { return $this->soignants; }
    public function getMenus(): Collection { return $this->menus; }
}
