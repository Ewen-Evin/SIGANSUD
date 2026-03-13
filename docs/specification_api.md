# Specification API REST - SIGEANSUD

Base URL : `http://localhost:8000/api` (dev) / `https://sigeansud.ionos.fr/api` (prod)

Toutes les reponses sont en **JSON**.

---

## Authentification

| Fonction | Adresse | Type | Entree | Sortie | Web | Android |
|----------|---------|------|--------|--------|-----|---------|
| Connexion soignant | /api/login | POST | matricule, mot_de_passe | token + infos soignant | NON | OUI |

---

## Soignants

| Fonction | Adresse | Type | Entree | Sortie | Web | Android |
|----------|---------|------|--------|--------|-----|---------|
| Liste des soignants | /api/soignants | GET | | liste soignants : matricule, nom, prenom, tel, adresse | OUI | NON |
| Detail d'un soignant | /api/soignants/{matricule} | GET | matricule | soignant : matricule, nom, prenom, tel, adresse | OUI | NON |
| Creer un soignant | /api/soignants | POST | nom, prenom, tel, adresse, mot_de_passe | matricule cree | OUI | NON |
| Modifier un soignant | /api/soignants/{matricule} | PUT | nom, prenom, tel, adresse | soignant modifie | OUI | NON |
| Supprimer un soignant | /api/soignants/{matricule} | DELETE | matricule | confirmation | OUI | NON |

---

## Especes

| Fonction | Adresse | Type | Entree | Sortie | Web | Android |
|----------|---------|------|--------|--------|-----|---------|
| Liste des especes | /api/especes | GET | | liste especes : id, nom | OUI | OUI |
| Especes d'un soignant | /api/soignants/{matricule}/especes | GET | matricule | liste especes du soignant | OUI | OUI |

---

## Specialiser (affectation soignant/espece)

| Fonction | Adresse | Type | Entree | Sortie | Web | Android |
|----------|---------|------|--------|--------|-----|---------|
| Affecter une espece a un soignant | /api/soignants/{matricule}/especes | POST | id_espece | confirmation | OUI | NON |
| Retirer une espece a un soignant | /api/soignants/{matricule}/especes/{id_espece} | DELETE | | confirmation | OUI | NON |

---

## Animaux

| Fonction | Adresse | Type | Entree | Sortie | Web | Android |
|----------|---------|------|--------|--------|-----|---------|
| Animaux d'une espece | /api/especes/{id_espece}/animaux | GET | id_espece | liste animaux : nomBapteme, dateNaissance, dateDeces, genre | OUI | OUI |

---

## Menus

| Fonction | Adresse | Type | Entree | Sortie | Web | Android |
|----------|---------|------|--------|--------|-----|---------|
| Liste des menus | /api/menus | GET | | liste menus : id, aliment, quantite | OUI | NON |
| Menu d'une espece | /api/especes/{id_espece}/menus | GET | id_espece | liste menus recommandes pour l'espece | OUI | OUI |
| Creer un menu | /api/menus | POST | aliment, quantite | menu cree | OUI | NON |
| Modifier un menu | /api/menus/{id_menu} | PUT | aliment, quantite | menu modifie | OUI | NON |
| Supprimer un menu | /api/menus/{id_menu} | DELETE | | confirmation | OUI | NON |
| Recommander un menu pour une espece | /api/especes/{id_espece}/menus | POST | id_menu | confirmation | OUI | NON |
| Retirer un menu d'une espece | /api/especes/{id_espece}/menus/{id_menu} | DELETE | | confirmation | OUI | NON |

---

## Repas

| Fonction | Adresse | Type | Entree | Sortie | Web | Android |
|----------|---------|------|--------|--------|-----|---------|
| Enregistrer un repas | /api/repas | POST | id_espece, nomBapteme, id_menu, quantite | repas cree | NON | OUI |
| Repas d'un animal | /api/especes/{id_espece}/animaux/{nomBapteme}/repas | GET | | liste repas : date, aliment, quantite | OUI | OUI |

---

## Resume des flux

```
ANDROID (soignant)                 API REST                    BACK-OFFICE (gestionnaire)
      |                               |                               |
      |-- POST /api/login ----------->|                               |
      |<-- token + infos -------------|                               |
      |                               |                               |
      |-- GET /soignants/{m}/especes ->|                              |
      |<-- liste especes --------------|                              |
      |                               |                               |
      |-- GET /especes/{id}/animaux -->|                              |
      |<-- liste animaux -------------|                               |
      |                               |                               |
      |-- GET /especes/{id}/menus ---->|                              |
      |<-- menu recommande ------------|                              |
      |                               |                               |
      |-- POST /api/repas ----------->|                               |
      |<-- confirmation ---------------|                              |
      |                               |                               |
      |                               |<--- GET /api/soignants -------|
      |                               |---- liste soignants --------->|
      |                               |                               |
      |                               |<--- POST /api/menus ----------|
      |                               |---- menu cree --------------->|
```
