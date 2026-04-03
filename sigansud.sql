-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Apr 03, 2026 at 11:39 AM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sigansud`
--

-- --------------------------------------------------------

--
-- Table structure for table `animal`
--

CREATE TABLE `animal` (
  `id_Espece` int NOT NULL,
  `nomBapteme_Animal` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `dateNaissance_Animal` date DEFAULT NULL,
  `dateDeces_Animal` date DEFAULT NULL,
  `genre_Animal` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `animal`
--

INSERT INTO `animal` (`id_Espece`, `nomBapteme_Animal`, `dateNaissance_Animal`, `dateDeces_Animal`, `genre_Animal`) VALUES
(1, 'Nala', '2020-06-20', NULL, 'F'),
(1, 'Simba', '2019-03-15', NULL, 'M'),
(2, 'Lola', '2021-04-05', NULL, 'F'),
(2, 'Melman', '2018-01-10', NULL, 'M'),
(3, 'Dumbo', '2017-09-12', NULL, 'M'),
(4, 'Marty', '2020-11-30', NULL, 'M'),
(5, 'Croco', '2015-07-22', NULL, 'M'),
(6, 'Flamby', '2021-08-08', NULL, 'M'),
(6, 'Rosie', '2022-02-14', NULL, 'F');

-- --------------------------------------------------------

--
-- Table structure for table `date_repas`
--

CREATE TABLE `date_repas` (
  `id_Date_Repas` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `date_repas`
--

INSERT INTO `date_repas` (`id_Date_Repas`) VALUES
(1),
(2),
(3);

-- --------------------------------------------------------

--
-- Table structure for table `espece`
--

CREATE TABLE `espece` (
  `id_Espece` int NOT NULL,
  `nom_Espece` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `espece`
--

INSERT INTO `espece` (`id_Espece`, `nom_Espece`) VALUES
(1, 'Lion'),
(2, 'Girafe'),
(3, 'Elephant'),
(4, 'Zebre'),
(5, 'Crocodile'),
(6, 'Flamant rose');

-- --------------------------------------------------------

--
-- Table structure for table `gestionnaire`
--

CREATE TABLE `gestionnaire` (
  `id_Gestionnaire` int NOT NULL,
  `login_Gestionnaire` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mot_de_passe` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nom_Gestionnaire` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prenom_Gestionnaire` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role_Gestionnaire` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'gestionnaire'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `gestionnaire`
--

INSERT INTO `gestionnaire` (`id_Gestionnaire`, `login_Gestionnaire`, `mot_de_passe`, `nom_Gestionnaire`, `prenom_Gestionnaire`, `role_Gestionnaire`) VALUES
(1, 'admin', '$2y$12$TX5EtklOyAPL/IKgZV.zt.6/hqB8nHrCmbUQDG6W6W/hlkpbzrQ6.', 'Administrateur', 'Systeme', 'admin'),
(2, 'admin2', '$2y$12$iE/Q0RJOUfUUlMUOkW8o6OmnMEdoSRXsqTu7D8EZIQ3bFvZBz6fxq', 'Ewen', 'Ewen', 'gestionnaire');

-- --------------------------------------------------------

--
-- Table structure for table `menu`
--

CREATE TABLE `menu` (
  `id_Menu` int NOT NULL,
  `aliment_Menu` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `qteAliment_Menu` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `menu`
--

INSERT INTO `menu` (`id_Menu`, `aliment_Menu`, `qteAliment_Menu`) VALUES
(1, 'Viande de boeuf', 5),
(2, 'Feuilles acacia', 15),
(3, 'Herbe et foin', 30),
(4, 'Poisson frais', 3),
(5, 'Crevettes et graines', 1);

-- --------------------------------------------------------

--
-- Table structure for table `recommander`
--

CREATE TABLE `recommander` (
  `id_Menu` int NOT NULL,
  `id_Espece` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `recommander`
--

INSERT INTO `recommander` (`id_Menu`, `id_Espece`) VALUES
(1, 1),
(2, 2),
(3, 3),
(3, 4),
(4, 5),
(5, 6);

-- --------------------------------------------------------

--
-- Table structure for table `repas`
--

CREATE TABLE `repas` (
  `id_Date_Repas` int NOT NULL,
  `id_Espece` int NOT NULL,
  `nomBapteme_Animal` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `qte_Repas` int NOT NULL,
  `id_Menu` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `repas`
--

INSERT INTO `repas` (`id_Date_Repas`, `id_Espece`, `nomBapteme_Animal`, `qte_Repas`, `id_Menu`) VALUES
(1, 1, 'Nala', 4, 1),
(1, 1, 'Simba', 5, 1),
(1, 2, 'Lola', 12, 2),
(1, 2, 'Melman', 14, 2),
(2, 3, 'Dumbo', 28, 3),
(2, 4, 'Marty', 8, 3),
(2, 5, 'Croco', 3, 4),
(3, 6, 'Flamby', 1, 5),
(3, 6, 'Rosie', 1, 5);

-- --------------------------------------------------------

--
-- Table structure for table `soignant`
--

CREATE TABLE `soignant` (
  `matricule_Soignant` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nom_Soignant` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prenom_Soignant` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tel_soignant` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `adresse_Soignant` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mot_de_passe` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `soignant`
--

INSERT INTO `soignant` (`matricule_Soignant`, `nom_Soignant`, `prenom_Soignant`, `tel_soignant`, `adresse_Soignant`, `mot_de_passe`) VALUES
('SOI001', 'Dupont', 'Marie', '0601020304', '12 rue des Acacias, Sigean', '$2y$12$71Q3nhK.WZpjzP2R/2EAquWD7cvvSslk6fiWNWjhws60PMYCAiQCG'),
('SOI002', 'Martin', 'Pierre', '0605060708', '5 avenue du Parc, Sigean', '$2y$12$71Q3nhK.WZpjzP2R/2EAquWD7cvvSslk6fiWNWjhws60PMYCAiQCG'),
('SOI003', 'Durand', 'Sophie', '0609101112', '8 place de la Fontaine, Narbonne', '$2y$12$71Q3nhK.WZpjzP2R/2EAquWD7cvvSslk6fiWNWjhws60PMYCAiQCG');

-- --------------------------------------------------------

--
-- Table structure for table `specialiser`
--

CREATE TABLE `specialiser` (
  `id_Espece` int NOT NULL,
  `matricule_Soignant` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `specialiser`
--

INSERT INTO `specialiser` (`id_Espece`, `matricule_Soignant`) VALUES
(1, 'SOI001'),
(2, 'SOI001'),
(3, 'SOI002'),
(4, 'SOI002'),
(5, 'SOI002'),
(2, 'SOI003'),
(6, 'SOI003');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `animal`
--
ALTER TABLE `animal`
  ADD PRIMARY KEY (`id_Espece`,`nomBapteme_Animal`);

--
-- Indexes for table `date_repas`
--
ALTER TABLE `date_repas`
  ADD PRIMARY KEY (`id_Date_Repas`);

--
-- Indexes for table `espece`
--
ALTER TABLE `espece`
  ADD PRIMARY KEY (`id_Espece`);

--
-- Indexes for table `gestionnaire`
--
ALTER TABLE `gestionnaire`
  ADD PRIMARY KEY (`id_Gestionnaire`),
  ADD UNIQUE KEY `login_Gestionnaire` (`login_Gestionnaire`);

--
-- Indexes for table `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`id_Menu`);

--
-- Indexes for table `recommander`
--
ALTER TABLE `recommander`
  ADD PRIMARY KEY (`id_Menu`,`id_Espece`),
  ADD KEY `fk_reco_espece` (`id_Espece`);

--
-- Indexes for table `repas`
--
ALTER TABLE `repas`
  ADD PRIMARY KEY (`id_Date_Repas`,`id_Espece`,`nomBapteme_Animal`),
  ADD KEY `fk_repas_animal` (`id_Espece`,`nomBapteme_Animal`),
  ADD KEY `fk_repas_menu` (`id_Menu`);

--
-- Indexes for table `soignant`
--
ALTER TABLE `soignant`
  ADD PRIMARY KEY (`matricule_Soignant`);

--
-- Indexes for table `specialiser`
--
ALTER TABLE `specialiser`
  ADD PRIMARY KEY (`id_Espece`,`matricule_Soignant`),
  ADD KEY `fk_spec_soignant` (`matricule_Soignant`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `date_repas`
--
ALTER TABLE `date_repas`
  MODIFY `id_Date_Repas` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `espece`
--
ALTER TABLE `espece`
  MODIFY `id_Espece` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `gestionnaire`
--
ALTER TABLE `gestionnaire`
  MODIFY `id_Gestionnaire` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `menu`
--
ALTER TABLE `menu`
  MODIFY `id_Menu` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `animal`
--
ALTER TABLE `animal`
  ADD CONSTRAINT `fk_animal_espece` FOREIGN KEY (`id_Espece`) REFERENCES `espece` (`id_Espece`);

--
-- Constraints for table `recommander`
--
ALTER TABLE `recommander`
  ADD CONSTRAINT `fk_reco_espece` FOREIGN KEY (`id_Espece`) REFERENCES `espece` (`id_Espece`),
  ADD CONSTRAINT `fk_reco_menu` FOREIGN KEY (`id_Menu`) REFERENCES `menu` (`id_Menu`);

--
-- Constraints for table `repas`
--
ALTER TABLE `repas`
  ADD CONSTRAINT `fk_repas_animal` FOREIGN KEY (`id_Espece`,`nomBapteme_Animal`) REFERENCES `animal` (`id_Espece`, `nomBapteme_Animal`),
  ADD CONSTRAINT `fk_repas_date` FOREIGN KEY (`id_Date_Repas`) REFERENCES `date_repas` (`id_Date_Repas`),
  ADD CONSTRAINT `fk_repas_menu` FOREIGN KEY (`id_Menu`) REFERENCES `menu` (`id_Menu`);

--
-- Constraints for table `specialiser`
--
ALTER TABLE `specialiser`
  ADD CONSTRAINT `fk_spec_espece` FOREIGN KEY (`id_Espece`) REFERENCES `espece` (`id_Espece`),
  ADD CONSTRAINT `fk_spec_soignant` FOREIGN KEY (`matricule_Soignant`) REFERENCES `soignant` (`matricule_Soignant`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
