<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity]
#[ORM\Table(name: 'Soignant')]
class Soignant implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\Column(name: 'matricule_Soignant', length: 50)]
    private string $matricule;

    #[ORM\Column(name: 'nom_Soignant', length: 50)]
    private string $nom;

    #[ORM\Column(name: 'prenom_Soignant', length: 50)]
    private string $prenom;

    #[ORM\Column(name: 'tel_soignant', length: 10, nullable: true)]
    private ?string $tel = null;

    #[ORM\Column(name: 'adresse_Soignant', length: 250, nullable: true)]
    private ?string $adresse = null;

    #[ORM\Column(name: 'mot_de_passe', length: 255)]
    private string $password;

    #[ORM\ManyToMany(targetEntity: Espece::class, inversedBy: 'soignants')]
    #[ORM\JoinTable(
        name: 'specialiser',
        joinColumns: [new ORM\JoinColumn(name: 'matricule_Soignant', referencedColumnName: 'matricule_Soignant')],
        inverseJoinColumns: [new ORM\JoinColumn(name: 'id_Espece', referencedColumnName: 'id_Espece')]
    )]
    private Collection $especes;

    public function __construct()
    {
        $this->especes = new ArrayCollection();
    }

    public function getMatricule(): string { return $this->matricule; }
    public function setMatricule(string $matricule): static { $this->matricule = $matricule; return $this; }
    public function getNom(): string { return $this->nom; }
    public function setNom(string $nom): static { $this->nom = $nom; return $this; }
    public function getPrenom(): string { return $this->prenom; }
    public function setPrenom(string $prenom): static { $this->prenom = $prenom; return $this; }
    public function getTel(): ?string { return $this->tel; }
    public function setTel(?string $tel): static { $this->tel = $tel; return $this; }
    public function getAdresse(): ?string { return $this->adresse; }
    public function setAdresse(?string $adresse): static { $this->adresse = $adresse; return $this; }
    public function getPassword(): string { return $this->password; }
    public function setPassword(string $password): static { $this->password = $password; return $this; }
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

    // UserInterface
    public function getRoles(): array { return ['ROLE_SOIGNANT']; }
    public function eraseCredentials(): void {}
    public function getUserIdentifier(): string { return $this->matricule; }
}
