# Back-office - SIGEANSUD

Application web Symfony 8 pour les gestionnaires du parc animalier.
Permet de gerer les soignants, les especes, les animaux et les menus.

## Prerequis

- PHP 8.5
- Composer
- L'API REST doit etre lancee sur `http://localhost:8000`

## Installation

```bash
cd backoffice
composer install
```

## Lancement

```bash
cd backoffice
php -S localhost:8001 -t public
```

Le back-office est disponible sur `http://localhost:8001`.

## Tests

```bash
cd backoffice
php bin/phpunit
```

## Comptes de test (gestionnaires)

| Login  | Mot de passe | Role         |
|--------|--------------|--------------|
| admin  | admin        | admin        |
| admin2 | admin        | gestionnaire |

## Pages disponibles

| Page              | URL                   | Description                          |
|-------------------|-----------------------|--------------------------------------|
| Connexion         | /login                | Authentification gestionnaire        |
| Dashboard         | /dashboard            | Tableau de bord                      |
| Soignants         | /soignants            | Liste, ajout, modification, suppression |
| Especes           | /especes              | Liste, ajout, modification, suppression |
| Menus             | /menus                | Liste, ajout, modification, suppression |
| Gestionnaires     | /gestionnaires        | CRUD comptes gestionnaires (admin)   |
| Profil            | /profil               | Informations du compte connecte      |

## Architecture

Le back-office ne possede pas sa propre BDD — il communique entierement via l'API REST (`ApiService`).

```
backoffice/
├── src/
│   ├── Controller/     # DashboardController, SoignantController, EspeceController,
│   │                   # MenuController, GestionnaireController, ProfilController, LoginController
│   ├── Service/
│   │   └── ApiService.php   # Client HTTP vers l'API REST
│   └── EventSubscriber/
│       └── AuthSubscriber.php  # Redirect si non connecte
└── tests/              # Tests unitaires PHPUnit
```
