# AP4 SIGEANSUD - Gestion des repas

Projet SIO 2 - Parc animalier SIGEANSUD.
Application de gestion des repas des animaux par espece.

## Structure du projet

```
AP4/
├── api/            # API REST Symfony (backend commun)
├── backoffice/     # Back-office Symfony (gestionnaires)
├── mobile/         # Application Android (soignants)
└── docs/           # Documentation
```

## Stack technique

- **API REST** : Symfony 7 (backend commun)
- **Back-office** : Symfony 7 (gestion soignants, especes, menus)
- **App mobile** : Android / Java (saisie des repas par les soignants)
- **BDD** : MySQL

## Lancement du projet

### Prerequis
- PHP 8.5, MySQL (Laragon), Symfony CLI
- Base de donnees `sigansud` importee depuis `api/sql/sigansud.sql`

### 1. Lancer l'API (obligatoire, a faire en premier)
```bash
cd api
php -S localhost:8000 -t public
```

### 2. Lancer le back-office (dans un autre terminal)
```bash
cd backoffice
php -S localhost:8001 -t public
```

### Connexion back-office
- **Login** : `admin`
- **Mot de passe** : `admin`

### Ports
| Module      | URL                  |
|-------------|----------------------|
| API         | http://localhost:8000 |
| Back-office | http://localhost:8001 |

## Equipe

- Mateo
- Samuel
- Clayton
- Ewen
