# API REST - SIGEANSUD

API REST Symfony pour la gestion des repas du parc animalier SIGEANSUD.

## Endpoints prevus

- Authentification soignant
- CRUD Soignants
- CRUD Especes
- CRUD Menus
- CRUD Animaux
- Saisie / consultation des repas

## Installation

```bash
composer install
cp .env .env.local  # configurer DATABASE_URL
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
symfony server:start
```
