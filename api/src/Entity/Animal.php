<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'Animal')]
class Animal
{
    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: Espece::class, inversedBy: 'animaux')]
    #[ORM\JoinColumn(name: 'id_Espece', referencedColumnName: 'id_Espece', nullable: false)]
    private Espece $espece;

    #[ORM\Id]
    #[ORM\Column(name: 'nomBapteme_Animal', length: 50)]
    private string $nomBapteme;

    #[ORM\Column(name: 'dateNaissance_Animal', type: 'date', nullable: true)]
    private ?\DateTimeInterface $dateNaissance = null;

    #[ORM\Column(name: 'dateDeces_Animal', type: 'date', nullable: true)]
    private ?\DateTimeInterface $dateDeces = null;

    #[ORM\Column(name: 'genre_Animal', length: 50)]
    private string $genre;

    public function getEspece(): Espece { return $this->espece; }
    public function setEspece(Espece $espece): static { $this->espece = $espece; return $this; }
    public function getNomBapteme(): string { return $this->nomBapteme; }
    public function setNomBapteme(string $nomBapteme): static { $this->nomBapteme = $nomBapteme; return $this; }
    public function getDateNaissance(): ?\DateTimeInterface { return $this->dateNaissance; }
    public function setDateNaissance(?\DateTimeInterface $dateNaissance): static { $this->dateNaissance = $dateNaissance; return $this; }
    public function getDateDeces(): ?\DateTimeInterface { return $this->dateDeces; }
    public function setDateDeces(?\DateTimeInterface $dateDeces): static { $this->dateDeces = $dateDeces; return $this; }
    public function getGenre(): string { return $this->genre; }
    public function setGenre(string $genre): static { $this->genre = $genre; return $this; }
}
