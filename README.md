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
- Android Studio (pour l'app mobile)
- Base de donnees `sigansud` importee depuis `sigansud.sql` (ou `api/sql/sigansud.sql`)

### 1. Lancer l'API (obligatoire, a faire en premier)
```bash
cd api
php -S 0.0.0.0:8000 -t public
```
> Note : utiliser `0.0.0.0` au lieu de `localhost` pour que l'emulateur Android puisse acceder a l'API.

### 2. Lancer le back-office (dans un autre terminal)
```bash
cd backoffice
php -S localhost:8001 -t public
```

### 3. Lancer l'app mobile
- Ouvrir le dossier `mobile/` dans Android Studio
- Lancer sur un emulateur (l'API est accessible via `10.0.2.2:8000`)
- Ou sur un telephone physique (modifier l'IP dans `RetrofitClient.java`)

### Comptes de test

#### Back-office (gestionnaires)
| Login   | Mot de passe | Role          |
|---------|-------------|---------------|
| admin   | admin       | admin         |
| admin2  | admin       | gestionnaire  |

#### API / App mobile (soignants)
| Matricule | Nom             | Mot de passe |
|-----------|-----------------|-------------|
| SOI001    | Dupont Marie    | soignant    |
| SOI002    | Martin Pierre   | soignant    |
| SOI003    | Durand Sophie   | soignant    |

### Ports
| Module      | URL                  |
|-------------|----------------------|
| API         | http://localhost:8000 |
| Back-office | http://localhost:8001 |

## Tests unitaires

### Lancer les tests de l'API
```bash
cd api
php bin/phpunit
```

### Lancer les tests du back-office
```bash
cd backoffice
php bin/phpunit
```

### Lancer tous les tests
```bash
cd api && php bin/phpunit && cd ../backoffice && php bin/phpunit
```

## Equipe

- Mateo
- Samuel
- Clayton
- Ewen
