<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'Menu')]
class Menu
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id_Menu')]
    private ?int $id = null;

    #[ORM\Column(name: 'aliment_Menu', length: 50)]
    private string $aliment;

    #[ORM\Column(name: 'qteAliment_Menu')]
    private int $qteAliment;

    #[ORM\ManyToMany(targetEntity: Espece::class, inversedBy: 'menus')]
    #[ORM\JoinTable(
        name: 'recommander',
        joinColumns: [new ORM\JoinColumn(name: 'id_Menu', referencedColumnName: 'id_Menu')],
        inverseJoinColumns: [new ORM\JoinColumn(name: 'id_Espece', referencedColumnName: 'id_Espece')]
    )]
    private Collection $especes;

    public function __construct()
    {
        $this->especes = new ArrayCollection();
    }

    public function getId(): ?int { return $this->id; }
    public function getAliment(): string { return $this->aliment; }
    public function setAliment(string $aliment): static { $this->aliment = $aliment; return $this; }
    public function getQteAliment(): int { return $this->qteAliment; }
    public function setQteAliment(int $qteAliment): static { $this->qteAliment = $qteAliment; return $this; }
    public function getEspeces(): Collection { return $this->especes; }

    public function addEspece(Espece $espece): static
    {
        if (!$this->especes->contains($espece)) {
            $this->especes->add($espece);
        }
        return $this;
    }

    public function removeEspece(Espece $espece): static
    {
        $this->especes->removeElement($espece);
        return $this;
    }
}
