-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 06, 2024 at 11:11 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ecoride`
--

-- --------------------------------------------------------

--
-- Table structure for table `avis`
--

CREATE TABLE `avis` (
  `ID_avis` int(11) NOT NULL,
  `ID_passager` int(11) NOT NULL,
  `ID_chauffeur` int(11) NOT NULL,
  `etoiles` int(11) NOT NULL,
  `description` varchar(255) NOT NULL,
  `statut` enum('en attente','validé','refusé') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `avis`
--

INSERT INTO `avis` (`ID_avis`, `ID_passager`, `ID_chauffeur`, `etoiles`, `description`, `statut`) VALUES
(100, 100, 101, 5, 'Excellent voyage, très confortable et ponctuel.', 'validé'),
(101, 102, 101, 4, 'Excellent voyage, trois confortables et ponctuels.', 'validé'),
(102, 104, 103, 5, 'Très sympathique et le voyage a été agréable.', 'validé');

-- --------------------------------------------------------

--
-- Table structure for table `chauffeur`
--

CREATE TABLE `chauffeur` (
  `ID` int(11) NOT NULL,
  `note` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `chauffeur`
--

INSERT INTO `chauffeur` (`ID`, `note`) VALUES
(101, 5),
(103, 4);

-- --------------------------------------------------------

--
-- Table structure for table `compte`
--

CREATE TABLE `compte` (
  `ID` int(11) NOT NULL,
  `courriel` varchar(50) NOT NULL,
  `mot_de_passe` varchar(255) NOT NULL,
  `role` enum('Administrateur','Employé','Utilisateur') NOT NULL,
  `pseudo` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `compte`
--

INSERT INTO `compte` (`ID`, `courriel`, `mot_de_passe`, `role`, `pseudo`) VALUES
(100, 'alice@example.com', 'alice123', 'Utilisateur', 'Alice'),
(101, 'bob@example.com', '$2y$10$YgO8ZzZl1Xae0NRN9o1.yX3A7r4YBqKueHtMZ5i0U6ZQiwNRqDmvZxO', 'Utilisateur', 'Bob'),
(102, 'charlie@example.com', '$2y$10$MZ5i0U6ZQiwNRqDmvZxO8ZzZl1Xae0NRN9o1.yX3A7r4YBqKueHt', 'Utilisateur', 'Charlie'),
(103, 'david@example.com', '$2y$10$3A7r4YBqKueHtMZ5i0U6ZQiwNRqDmvZxO8ZzZl1Xae0NRN9o1.yX', 'Utilisateur', 'David'),
(104, 'eve@example.com', '$2y$10$NRN9o1.yX3A7r4YBqKueHtMZ5i0U6ZQiwNRqDmvZxO8ZzZl1Xae0N', 'Utilisateur', 'Eve');

-- --------------------------------------------------------

--
-- Table structure for table `covoiturage`
--

CREATE TABLE `covoiturage` (
  `ID_covoiturage` int(11) NOT NULL,
  `ID_vehicule` int(11) NOT NULL,
  `date` date NOT NULL,
  `heure_depart` time NOT NULL,
  `heure_arrivee` time NOT NULL,
  `lieu_depart` varchar(50) NOT NULL,
  `lieu_arrivee` varchar(50) NOT NULL,
  `places_disponibles` int(11) NOT NULL,
  `prix` decimal(10,2) NOT NULL,
  `statut` enum('programmé','annulé','commencé','terminé') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `covoiturage`
--

INSERT INTO `covoiturage` (`ID_covoiturage`, `ID_vehicule`, `date`, `heure_depart`, `heure_arrivee`, `lieu_depart`, `lieu_arrivee`, `places_disponibles`, `prix`, `statut`) VALUES
(100, 100, '2023-12-01', '08:00:00', '12:00:00', 'Paris', 'Lille', 3, 5.00, 'programmé'),
(101, 101, '2023-12-02', '09:30:00', '13:00:00', 'Marseille', 'Nice', 2, 7.00, 'programmé'),
(102, 102, '2023-12-03', '07:45:00', '11:15:00', 'Lyon', 'Grenoble', 4, 4.50, 'programmé');

-- --------------------------------------------------------

--
-- Table structure for table `participants`
--

CREATE TABLE `participants` (
  `ID_participant` int(11) NOT NULL,
  `ID_covoiturage` int(11) DEFAULT NULL,
  `ID_utilisateur` int(11) DEFAULT NULL,
  `date_participation` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `utilisateur`
--

CREATE TABLE `utilisateur` (
  `ID` int(11) NOT NULL,
  `passager` tinyint(1) NOT NULL,
  `chauffeur` tinyint(1) NOT NULL,
  `credits` float NOT NULL DEFAULT 20
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `utilisateur`
--

INSERT INTO `utilisateur` (`ID`, `passager`, `chauffeur`, `credits`) VALUES
(100, 1, 0, 20),
(101, 1, 1, 50),
(102, 1, 0, 15.5),
(103, 1, 1, 30),
(104, 1, 0, 40.75);

-- --------------------------------------------------------

--
-- Table structure for table `vehicule`
--

CREATE TABLE `vehicule` (
  `ID_vehicule` int(11) NOT NULL,
  `ID_chauffeur` int(11) NOT NULL,
  `plaque` varchar(20) NOT NULL,
  `date_plaque` date NOT NULL,
  `marque` varchar(50) NOT NULL,
  `modele` varchar(50) NOT NULL,
  `couleur` varchar(20) NOT NULL,
  `nombre_de_places` int(11) NOT NULL,
  `fumeur` tinyint(1) NOT NULL,
  `animaux` tinyint(1) NOT NULL,
  `preferences` varchar(255) DEFAULT NULL,
  `eco` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vehicule`
--

INSERT INTO `vehicule` (`ID_vehicule`, `ID_chauffeur`, `plaque`, `date_plaque`, `marque`, `modele`, `couleur`, `nombre_de_places`, `fumeur`, `animaux`, `preferences`, `eco`) VALUES
(100, 101, 'ABC123', '2018-05-20', 'Renault', 'Clio', 'Rouge', 4, 0, 1, 'Silence pendant le voyage', 0),
(101, 101, 'DEF456', '2020-07-15', 'Tesla', 'Model 3', 'Noir', 5, 0, 0, 'Aucun repas autorisé', 1),
(102, 103, 'GHI789', '2017-09-10', 'Peugeot', '208', 'Bleu', 4, 1, 1, 'Musique douce', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `avis`
--
ALTER TABLE `avis`
  ADD PRIMARY KEY (`ID_avis`),
  ADD KEY `ID_passager` (`ID_passager`),
  ADD KEY `ID_chauffeur` (`ID_chauffeur`);

--
-- Indexes for table `chauffeur`
--
ALTER TABLE `chauffeur`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `compte`
--
ALTER TABLE `compte`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `courriel` (`courriel`);

--
-- Indexes for table `covoiturage`
--
ALTER TABLE `covoiturage`
  ADD PRIMARY KEY (`ID_covoiturage`),
  ADD KEY `ID_vehicule` (`ID_vehicule`);

--
-- Indexes for table `participants`
--
ALTER TABLE `participants`
  ADD PRIMARY KEY (`ID_participant`);

--
-- Indexes for table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `vehicule`
--
ALTER TABLE `vehicule`
  ADD PRIMARY KEY (`ID_vehicule`),
  ADD UNIQUE KEY `plaque` (`plaque`),
  ADD KEY `ID_chauffeur` (`ID_chauffeur`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `avis`
--
ALTER TABLE `avis`
  MODIFY `ID_avis` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=103;

--
-- AUTO_INCREMENT for table `compte`
--
ALTER TABLE `compte`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=105;

--
-- AUTO_INCREMENT for table `covoiturage`
--
ALTER TABLE `covoiturage`
  MODIFY `ID_covoiturage` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=103;

--
-- AUTO_INCREMENT for table `participants`
--
ALTER TABLE `participants`
  MODIFY `ID_participant` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=104;

--
-- AUTO_INCREMENT for table `vehicule`
--
ALTER TABLE `vehicule`
  MODIFY `ID_vehicule` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=103;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `avis`
--
ALTER TABLE `avis`
  ADD CONSTRAINT `avis_ibfk_1` FOREIGN KEY (`ID_passager`) REFERENCES `utilisateur` (`ID`),
  ADD CONSTRAINT `avis_ibfk_2` FOREIGN KEY (`ID_chauffeur`) REFERENCES `chauffeur` (`ID`);

--
-- Constraints for table `chauffeur`
--
ALTER TABLE `chauffeur`
  ADD CONSTRAINT `chauffeur_ibfk_1` FOREIGN KEY (`ID`) REFERENCES `utilisateur` (`ID`);

--
-- Constraints for table `covoiturage`
--
ALTER TABLE `covoiturage`
  ADD CONSTRAINT `covoiturage_ibfk_1` FOREIGN KEY (`ID_vehicule`) REFERENCES `vehicule` (`ID_vehicule`);

--
-- Constraints for table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD CONSTRAINT `utilisateur_ibfk_1` FOREIGN KEY (`ID`) REFERENCES `compte` (`ID`);

--
-- Constraints for table `vehicule`
--
ALTER TABLE `vehicule`
  ADD CONSTRAINT `vehicule_ibfk_1` FOREIGN KEY (`ID_chauffeur`) REFERENCES `chauffeur` (`ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
