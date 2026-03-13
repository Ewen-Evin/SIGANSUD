<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity]
#[ORM\Table(name: 'Gestionnaire')]
class Gestionnaire implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id_Gestionnaire')]
    private ?int $id = null;

    #[ORM\Column(name: 'login_Gestionnaire', length: 50, unique: true)]
    private string $login;

    #[ORM\Column(name: 'mot_de_passe', length: 255)]
    private string $password;

    #[ORM\Column(name: 'nom_Gestionnaire', length: 50)]
    private string $nom;

    #[ORM\Column(name: 'prenom_Gestionnaire', length: 50)]
    private string $prenom;

    #[ORM\Column(name: 'role_Gestionnaire', length: 20)]
    private string $role = 'gestionnaire';

    public function getId(): ?int { return $this->id; }
    public function getLogin(): string { return $this->login; }
    public function setLogin(string $login): static { $this->login = $login; return $this; }
    public function getNom(): string { return $this->nom; }
    public function setNom(string $nom): static { $this->nom = $nom; return $this; }
    public function getPrenom(): string { return $this->prenom; }
    public function setPrenom(string $prenom): static { $this->prenom = $prenom; return $this; }
    public function getRole(): string { return $this->role; }
    public function setRole(string $role): static { $this->role = $role; return $this; }
    public function getPassword(): string { return $this->password; }
    public function setPassword(string $password): static { $this->password = $password; return $this; }

    // UserInterface
    public function getRoles(): array
    {
        $roles = ['ROLE_GESTIONNAIRE'];
        if ($this->role === 'admin') {
            $roles[] = 'ROLE_ADMIN';
        }
        return $roles;
    }
    public function eraseCredentials(): void {}
    public function getUserIdentifier(): string { return $this->login; }
}
