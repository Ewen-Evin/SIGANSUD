-- ============================================
-- BDD SIGEANSUD - Gestion des repas
-- Partie entouree du MCD uniquement
-- ============================================

DROP DATABASE IF EXISTS sigansud;
CREATE DATABASE sigansud CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE sigansud;

-- ============================================
-- TABLES PRINCIPALES
-- ============================================

CREATE TABLE Menu(
   id_Menu INT AUTO_INCREMENT,
   aliment_Menu VARCHAR(50) NOT NULL,
   qteAliment_Menu INT NOT NULL,
   PRIMARY KEY(id_Menu)
) ENGINE=InnoDB;

CREATE TABLE Date_Repas(
   id_Date_Repas INT AUTO_INCREMENT,
   PRIMARY KEY(id_Date_Repas)
) ENGINE=InnoDB;

CREATE TABLE Espece(
   id_Espece INT AUTO_INCREMENT,
   nom_Espece VARCHAR(50) NOT NULL,
   PRIMARY KEY(id_Espece)
) ENGINE=InnoDB;

CREATE TABLE Soignant(
   matricule_Soignant VARCHAR(50),
   nom_Soignant VARCHAR(50) NOT NULL,
   prenom_Soignant VARCHAR(50) NOT NULL,
   tel_soignant VARCHAR(10),
   adresse_Soignant VARCHAR(250),
   mot_de_passe VARCHAR(255) NOT NULL,
   PRIMARY KEY(matricule_Soignant)
) ENGINE=InnoDB;

CREATE TABLE Animal(
   id_Espece INT,
   nomBapteme_Animal VARCHAR(50),
   dateNaissance_Animal DATE,
   dateDeces_Animal DATE DEFAULT NULL,
   genre_Animal VARCHAR(50) NOT NULL,
   PRIMARY KEY(id_Espece, nomBapteme_Animal),
   CONSTRAINT fk_animal_espece FOREIGN KEY(id_Espece) REFERENCES Espece(id_Espece)
) ENGINE=InnoDB;

-- ============================================
-- TABLES D'ASSOCIATION
-- ============================================

-- Repas donne a un animal (CIF : date + animal)
CREATE TABLE Repas(
   id_Date_Repas INT,
   id_Espece INT,
   nomBapteme_Animal VARCHAR(50),
   qte_Repas INT NOT NULL,
   id_Menu INT NOT NULL,
   PRIMARY KEY(id_Date_Repas, id_Espece, nomBapteme_Animal),
   CONSTRAINT fk_repas_date FOREIGN KEY(id_Date_Repas) REFERENCES Date_Repas(id_Date_Repas),
   CONSTRAINT fk_repas_animal FOREIGN KEY(id_Espece, nomBapteme_Animal) REFERENCES Animal(id_Espece, nomBapteme_Animal),
   CONSTRAINT fk_repas_menu FOREIGN KEY(id_Menu) REFERENCES Menu(id_Menu)
) ENGINE=InnoDB;

-- Menu recommande pour une espece
CREATE TABLE recommander(
   id_Menu INT,
   id_Espece INT,
   PRIMARY KEY(id_Menu, id_Espece),
   CONSTRAINT fk_reco_menu FOREIGN KEY(id_Menu) REFERENCES Menu(id_Menu),
   CONSTRAINT fk_reco_espece FOREIGN KEY(id_Espece) REFERENCES Espece(id_Espece)
) ENGINE=InnoDB;

-- Soignant specialise dans des especes (max 3)
CREATE TABLE specialiser(
   id_Espece INT,
   matricule_Soignant VARCHAR(50),
   PRIMARY KEY(id_Espece, matricule_Soignant),
   CONSTRAINT fk_spec_espece FOREIGN KEY(id_Espece) REFERENCES Espece(id_Espece),
   CONSTRAINT fk_spec_soignant FOREIGN KEY(matricule_Soignant) REFERENCES Soignant(matricule_Soignant)
) ENGINE=InnoDB;

-- ============================================
-- JEU DE DONNEES DE TEST
-- ============================================

-- Especes
INSERT INTO Espece (id_Espece, nom_Espece) VALUES
(1, 'Lion'),
(2, 'Girafe'),
(3, 'Elephant'),
(4, 'Zebre'),
(5, 'Crocodile'),
(6, 'Flamant rose');

-- Soignants
INSERT INTO Soignant (matricule_Soignant, nom_Soignant, prenom_Soignant, tel_soignant, adresse_Soignant, mot_de_passe) VALUES
('SOI001', 'Dupont', 'Marie', '0601020304', '12 rue des Acacias, Sigean', '$2y$12$/U/iCa/9XJ42OG3qycHYOunihXC2jVMaVY3T9d7RlP1/9Feu7.z1y'),
('SOI002', 'Martin', 'Pierre', '0605060708', '5 avenue du Parc, Sigean', '$2y$12$UbePij0BORwQAYKwWMUS..alqIQQLX6GiGoLzWfhwwYHPajr/Rsda'),
('SOI003', 'Durand', 'Sophie', '0609101112', '8 place de la Fontaine, Narbonne', '$2y$12$2DSwnFApkKGg.DR1dD1zyOSa8AoRgQlzPji2uzxVNn78LrkRnHdg2');

-- Specialisations (max 3 especes par soignant)
INSERT INTO specialiser (id_Espece, matricule_Soignant) VALUES
(1, 'SOI001'), -- Marie -> Lion
(2, 'SOI001'), -- Marie -> Girafe
(3, 'SOI002'), -- Pierre -> Elephant
(4, 'SOI002'), -- Pierre -> Zebre
(5, 'SOI002'), -- Pierre -> Crocodile
(6, 'SOI003'), -- Sophie -> Flamant rose
(2, 'SOI003'); -- Sophie -> Girafe

-- Animaux (cle composite : id_Espece + nomBapteme)
INSERT INTO Animal (id_Espece, nomBapteme_Animal, dateNaissance_Animal, dateDeces_Animal, genre_Animal) VALUES
(1, 'Simba', '2019-03-15', NULL, 'M'),
(1, 'Nala', '2020-06-20', NULL, 'F'),
(2, 'Melman', '2018-01-10', NULL, 'M'),
(2, 'Lola', '2021-04-05', NULL, 'F'),
(3, 'Dumbo', '2017-09-12', NULL, 'M'),
(4, 'Marty', '2020-11-30', NULL, 'M'),
(5, 'Croco', '2015-07-22', NULL, 'M'),
(6, 'Rosie', '2022-02-14', NULL, 'F'),
(6, 'Flamby', '2021-08-08', NULL, 'M');

-- Menus
INSERT INTO Menu (id_Menu, aliment_Menu, qteAliment_Menu) VALUES
(1, 'Viande de boeuf', 5),
(2, 'Feuilles acacia', 15),
(3, 'Herbe et foin', 30),
(4, 'Poisson frais', 3),
(5, 'Crevettes et graines', 1);

-- Recommandations menu/espece
INSERT INTO recommander (id_Menu, id_Espece) VALUES
(1, 1), -- Viande -> Lions
(2, 2), -- Feuilles acacia -> Girafes
(3, 3), -- Herbe -> Elephants
(3, 4), -- Herbe -> Zebres
(4, 5), -- Poisson -> Crocodiles
(5, 6); -- Crevettes -> Flamants

-- Dates de repas
INSERT INTO Date_Repas (id_Date_Repas) VALUES
(1), (2), (3);

-- Repas donnes
INSERT INTO Repas (id_Date_Repas, id_Espece, nomBapteme_Animal, qte_Repas, id_Menu) VALUES
(1, 1, 'Simba', 5, 1),
(1, 1, 'Nala', 4, 1),
(1, 2, 'Melman', 14, 2),
(1, 2, 'Lola', 12, 2),
(2, 3, 'Dumbo', 28, 3),
(2, 4, 'Marty', 8, 3),
(2, 5, 'Croco', 3, 4),
(3, 6, 'Rosie', 1, 5),
(3, 6, 'Flamby', 1, 5);
