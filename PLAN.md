# Plan de developpement - AP4 SIGEANSUD

## Planning des seances
| Seance | Date  | Objectifs |
|--------|-------|-----------|
| 1      | 05/12 | Analyse, MCD, MOT/diagramme de sequence |
| 2      | 12/12 | Setup projet, debut API + BDD |
| 3      | 06/03 | API REST + Back-office |
| 4      | 13/03 | Back-office + App mobile |
| 5      | 20/03 | App mobile + integration |
| 6      | 27/03 | Tests + corrections |
| 7      | 03/04 | Finalisation + deploiement IONOS |
| Doc    | 07/04 | Rendu documentation |
| Oral   | 10/04 | Soutenance |

---

## Phase 1 - Analyse et conception (seances 1-2)

### 1.1 Base de donnees
- [x] Valider/adapter le MCD fourni en annexe (partie entouree uniquement)
- [x] Creer le MLD (modele logique) a partir du MCD
- [x] Ecrire le script SQL de creation de la BDD MySQL (api/sql/sigansud.sql)
- [x] Inserer un jeu de donnees de test coherent

### 1.2 Documentation initiale
- [x] Realiser le MOT ou diagramme de sequence (valide par le prof en seance 1)
- [x] Rediger la specification de l'API REST (docs/specification_api.md)
- [ ] Creer le diagramme de classes
- [ ] Maquetter les ecrans (back-office web + app mobile)

### 1.3 Organisation
- [ ] Mettre en place Trello/Tuleap/Jira pour le suivi Agile
- [ ] Definir la repartition des taches par membre

---

## Phase 2 - API REST Symfony (seances 2-3)

### 2.1 Setup du projet API
- [ ] Installer les dependances Symfony (doctrine, serializer, validator, security)
- [ ] Configurer la connexion MySQL dans .env
- [ ] Creer les entites Doctrine depuis le MCD :
  - [ ] Soignant
  - [ ] Espece
  - [ ] Animal (PK composite : id_Espece + nomBapteme)
  - [ ] Menu
  - [ ] DateRepas
  - [ ] Repas (PK composite : date + espece + animal)
  - [ ] Recommander
  - [ ] Specialiser
- [ ] Generer et executer les migrations

### 2.2 Endpoints API
- [ ] POST /api/login - Authentification soignant
- [ ] GET /api/soignants - Liste des soignants
- [ ] GET /api/soignants/{id} - Detail soignant
- [ ] POST/PUT/DELETE /api/soignants - CRUD soignant
- [ ] GET /api/especes - Liste des especes
- [ ] GET /api/especes/{id}/animaux - Animaux d'une espece
- [ ] GET /api/especes/{id}/menu - Menu d'une espece
- [ ] POST/PUT/DELETE /api/menus - CRUD menus
- [ ] GET /api/soignants/{id}/especes - Especes du soignant
- [ ] POST /api/repas - Enregistrer un repas (heure + quantite)
- [ ] GET /api/repas?animal={id}&date={date} - Consulter les repas

### 2.3 Securite API
- [ ] Authentification JWT ou token simple
- [ ] Validation des donnees entrantes

---

## Phase 3 - Back-office Symfony (seances 3-4)

### 3.1 Setup du projet Back-office
- [ ] Initialiser le projet Symfony 7 dans backoffice/
- [ ] Installer Twig, formulaires, security, asset-mapper ou webpack
- [ ] Configurer l'authentification gestionnaire

### 3.2 Pages du back-office
- [ ] Dashboard / page d'accueil
- [ ] CRUD Soignants (liste, ajout, modification, suppression)
- [ ] Affectation des especes aux soignants (max 3)
- [ ] CRUD Menus par espece (aliment + quantite)
- [ ] Consultation des especes et animaux
- [ ] Consultation des repas donnes (historique)

### 3.3 Interface
- [ ] Template de base (navbar, layout)
- [ ] Integration CSS (Bootstrap ou autre)
- [ ] Messages flash (succes, erreur)

---

## Phase 4 - Application mobile Android (seances 4-5)

### 4.1 Setup du projet Android
- [ ] Creer le projet Android Studio (Java, API 26+)
- [ ] Configurer Retrofit/Volley pour les appels API
- [ ] Creer la base locale SQLite pour stocker le soignant connecte

### 4.2 Ecrans de l'application
- [ ] Ecran de connexion (matricule/mot de passe)
- [ ] Ecran d'accueil - liste des especes affectees au soignant
- [ ] Ecran liste des animaux d'une espece
- [ ] Ecran saisie d'un repas :
  - [ ] Selection de l'animal
  - [ ] Affichage du menu recommande
  - [ ] Saisie heure et quantite donnee
  - [ ] Validation et envoi a l'API
- [ ] Ecran historique des repas du jour

### 4.3 Fonctionnalites techniques
- [ ] Stockage soignant en SQLite local
- [ ] Gestion des erreurs reseau
- [ ] Affichage des messages de confirmation

---

## Phase 5 - Integration et tests (seances 5-6)

### 5.1 Integration
- [ ] Connecter le back-office a l'API REST
- [ ] Connecter l'app mobile a l'API REST
- [ ] Tester les flux complets (creation menu -> saisie repas -> consultation)

### 5.2 Tests
- [ ] Rediger le plan de tests avec donnees coherentes
- [ ] Tester chaque endpoint API (Postman ou similaire)
- [ ] Tester les formulaires du back-office
- [ ] Tester l'app mobile sur emulateur et smartphone

---

## Phase 6 - Deploiement et documentation (seances 6-7)

### 6.1 Deploiement IONOS
- [ ] Deployer l'API REST sur le serveur IONOS
- [ ] Deployer le back-office sur le serveur IONOS
- [ ] Configurer la BDD MySQL distante
- [ ] Configurer l'app mobile pour pointer vers le serveur IONOS

### 6.2 Documentation finale (rendu 07/04)
- [ ] MOT ou diagramme de sequence
- [ ] MCD final
- [ ] Diagramme de classes
- [ ] Specification de l'API REST
- [ ] Planning previsionnel detaille (taches par etudiant)
- [ ] Maquettes des deux applicatifs
- [ ] Plans de tests avec donnees coherentes
- [ ] Documentation utilisateur
- [ ] Sommaire et pagination du document

---

## Repartition suggeree (a valider en equipe)

| Membre  | Responsabilite principale |
|---------|--------------------------|
| Membre 1 | API REST (entites + endpoints) |
| Membre 2 | API REST (securite) + BDD |
| Membre 3 | Back-office Symfony |
| Membre 4 | Application mobile Android |

> Chacun doit maitriser les 3 parties (API, back-office, mobile).
