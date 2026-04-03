# Application Mobile - SIGEANSUD

Application Android (Java) pour les soignants du parc animalier.

## Fonctionnalites

- Authentification du soignant (matricule + mot de passe)
- Consultation des especes affectees au soignant
- Consultation des animaux par espece
- Consultation des menus recommandes par espece
- Saisie des repas (espece, animal, menu, quantite)
- Historique des repas par animal

## Prerequis

- Android Studio
- SDK Android minimum API 26
- API REST lancee sur le PC (`php -S 0.0.0.0:8000 -t public` dans `api/`)

## Configuration reseau

L'URL de l'API est configuree dans `app/src/main/java/com/sigansud/app/api/RetrofitClient.java` :

- **Emulateur Android Studio** : `http://10.0.2.2:8000/api/` (par defaut)
- **Telephone physique** : remplacer par l'IP locale du PC (ex: `http://192.168.1.15:8000/api/`)

**Important** : le serveur API doit etre lance avec `0.0.0.0` et non `localhost` pour que l'emulateur puisse y acceder :

```bash
cd api
php -S 0.0.0.0:8000 -t public
```

## Comptes de test

| Matricule | Nom             | Mot de passe |
|-----------|-----------------|-------------|
| SOI001    | Dupont Marie    | soignant    |
| SOI002    | Martin Pierre   | soignant    |
| SOI003    | Durand Sophie   | soignant    |

## Architecture

```
app/src/main/java/com/sigansud/app/
├── activities/       # Ecrans (Login, Main, Animaux, SaisieRepas, Historique, Menus)
├── adapters/         # Adapters RecyclerView (Espece, Menu, Repas)
├── api/              # Retrofit (RetrofitClient, ApiService, ApiErrorHandler)
├── models/           # Modeles de donnees (Soignant, Espece, Animal, Menu, Repas)
└── utils/            # Utilitaires (SessionManager, DateUtils, ApiErrorHandler)
```

## Ecrans

1. **LoginActivity** : connexion avec matricule/mot de passe via POST /api/login
2. **MainActivity** : tableau de bord avec 4 options (animaux, menus, saisie repas, historique)
3. **AnimauxActivity** : liste des especes puis animaux par espece
4. **MenusActivity** : menus recommandes par espece
5. **SaisieRepasActivity** : formulaire de saisie d'un repas (espece, animal, menu, quantite)
6. **HistoriqueActivity** : historique des repas par animal
