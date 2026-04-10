# API REST - SIGEANSUD

API REST Symfony 8 (PHP 8.5) — backend commun pour le back-office et l'application mobile.

## Prerequis

- PHP 8.5
- MySQL (via Laragon ou autre)
- Composer

## Installation

```bash
cd api
composer install
```

Configurer la connexion BDD dans `api/.env.local` :

```
DATABASE_URL="mysql://root:@127.0.0.1:3306/sigansud"
```

Importer la base de donnees :

```bash
mysql -u root sigansud < api/sql/sigansud.sql
```

## Lancement

```bash
cd api
php -S 0.0.0.0:8000 -t public
```

> Utiliser `0.0.0.0` (et non `localhost`) pour que l'emulateur Android puisse acceder a l'API.

L'API est disponible sur `http://localhost:8000`.

## Tests

```bash
cd api
php bin/phpunit
```

## Structure

```
api/
├── src/
│   ├── Controller/     # LoginController, SoignantController, EspeceController,
│   │                   # MenuController, RepasController, GestionnaireController
│   └── Entity/         # Soignant, Espece, Animal, Menu, DateRepas, Repas, Gestionnaire
├── sql/
│   └── sigansud.sql    # Script creation BDD + donnees de test
└── tests/              # Tests unitaires PHPUnit
```

## Comptes de test (soignants)

| Matricule | Nom           | Mot de passe |
|-----------|---------------|--------------|
| SOI001    | Dupont Marie  | soignant     |
| SOI002    | Martin Pierre | soignant     |
| SOI003    | Durand Sophie | soignant     |

Voir la specification complete des endpoints : [specification_api.md](specification_api.md)
