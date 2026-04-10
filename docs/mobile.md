# Application Mobile - SIGEANSUD

Application Android (Java) pour les soignants du parc animalier.

## Prerequis

- Android Studio
- SDK Android minimum API 26
- L'API REST lancee sur le PC (`php -S 0.0.0.0:8000 -t public` dans `api/`)

## Lancement

1. Ouvrir le dossier `mobile/` dans Android Studio
2. Lancer sur un emulateur ou un telephone physique

## Configuration reseau

L'URL de l'API est configuree dans `app/src/main/java/com/sigansud/app/api/RetrofitClient.java` :

- **Emulateur Android Studio** : `http://10.0.2.2:8000/api/` (par defaut)
- **Telephone physique** : remplacer par l'IP locale du PC (ex: `http://192.168.1.15:8000/api/`)

> Le serveur API doit etre lance avec `0.0.0.0` et non `localhost` pour que l'emulateur y accede.

## Comptes de test (soignants)

| Matricule | Nom           | Mot de passe |
|-----------|---------------|--------------|
| SOI001    | Dupont Marie  | soignant     |
| SOI002    | Martin Pierre | soignant     |
| SOI003    | Durand Sophie | soignant     |

## Fonctionnalites

- Authentification du soignant (matricule + mot de passe)
- Consultation des especes affectees au soignant
- Consultation des animaux par espece
- Consultation des menus recommandes par espece
- Saisie des repas (espece, animal, menu, quantite)
- Historique des repas par animal

## Ecrans

| Ecran              | Description                                              |
|--------------------|----------------------------------------------------------|
| LoginActivity      | Connexion avec matricule/mot de passe (POST /api/login)  |
| MainActivity       | Tableau de bord avec 4 options                           |
| AnimauxActivity    | Liste des especes puis animaux par espece                |
| MenusActivity      | Menus recommandes par espece                             |
| SaisieRepasActivity| Formulaire de saisie d'un repas                          |
| HistoriqueActivity | Historique des repas par animal                          |

## Architecture

```
app/src/main/java/com/sigansud/app/
├── activities/     # Ecrans (Login, Main, Animaux, SaisieRepas, Historique, Menus)
├── adapters/       # Adapters RecyclerView (Espece, Menu, Repas)
├── api/            # Retrofit (RetrofitClient, ApiService, ApiErrorHandler)
├── models/         # Modeles de donnees (Soignant, Espece, Animal, Menu, Repas)
└── utils/          # Utilitaires (SessionManager, DateUtils, ApiErrorHandler)
```
