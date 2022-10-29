-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:3306
-- Généré le : sam. 15 oct. 2022 à 17:30
-- Version du serveur : 8.0.30
-- Version de PHP : 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `projet_qualite_dev`
--

-- --------------------------------------------------------

--
-- Structure de la table `admin`
--

CREATE TABLE `admin` (
  `idAdmin` varchar(255) NOT NULL,
  `mdp` varchar(255) NOT NULL,
  `nom` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Déchargement des données de la table `admin`
--

INSERT INTO `admin` (`idAdmin`, `mdp`, `nom`) VALUES
('2001458436', '2001458436', 'ScrumMaster');

-- --------------------------------------------------------

--
-- Structure de la table `commercant`
--

CREATE TABLE `commercant` (
  `nom` varchar(30) NOT NULL,
  `siren` char(9) NOT NULL,
  `nbtransaction` int NOT NULL,
  `devise` varchar(3) NOT NULL,
  `montantTotal` int NOT NULL,
  `numCarte` char(16) NOT NULL,
  `réseau` char(2) NOT NULL,
  `mdp` varchar(350) NOT NULL,
  `idConnexion` varchar(350) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Déchargement des données de la table `commercant`
--

INSERT INTO `commercant` (`nom`, `siren`, `nbtransaction`, `devise`, `montantTotal`, `numCarte`, `réseau`, `mdp`, `idConnexion`) VALUES
('Leroy Merlin Noisy', '384560942', 0, 'EUR', 0, '7485', 'VS', '2001458436', '2001458436'),
('McDonald Champs sur Marne', '722003936', 0, 'EUR', 0, '1796', 'VS', '8755269857', '8755269857');

-- --------------------------------------------------------

--
-- Structure de la table `productowner`
--

CREATE TABLE `productowner` (
  `id` varchar(255) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `mdp` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Déchargement des données de la table `productowner`
--

INSERT INTO `productowner` (`id`, `nom`, `mdp`) VALUES
('4526452419', 'M.Tran', '4526452419');

-- --------------------------------------------------------

--
-- Structure de la table `remise`
--

CREATE TABLE `remise` (
  `numRemise` int NOT NULL,
  `idTransaction` int NOT NULL,
  `sans` char(1) NOT NULL,
  `libelleImpayer` char(20) NOT NULL,
  `numDossierImpayer` char(5) NOT NULL,
  `dateRemise` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Déchargement des données de la table `remise`
--

INSERT INTO `remise` (`numRemise`, `idTransaction`, `sans`, `libelleImpayer`, `numDossierImpayer`, `dateRemise`) VALUES
(1, 1, '+', 'jesaispas', '', '2022-10-02'),
(2, 2, '+', 'jesisvrmpas', '', '2022-01-02'),
(3, 3, '-', 'jesaistjpas', '12345', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `transaction`
--

CREATE TABLE `transaction` (
  `id` int NOT NULL,
  `numAutorisation` char(6) NOT NULL,
  `date` date NOT NULL,
  `cb` char(4) NOT NULL,
  `montant` int NOT NULL,
  `devise` char(3) NOT NULL,
  `numSiren` char(9) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Déchargement des données de la table `transaction`
--

INSERT INTO `transaction` (`id`, `numAutorisation`, `date`, `cb`, `montant`, `devise`, `numSiren`) VALUES
(1, '123456', '2022-09-14', '1245', 1000, 'EUR', '384560942'),
(2, 'abcdef', '2022-10-10', '1458', 2000, 'EUR', '722003936'),
(3, '1542za', '2017-10-26', '1488', 5, 'EUR', '722003936');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`idAdmin`);

--
-- Index pour la table `commercant`
--
ALTER TABLE `commercant`
  ADD PRIMARY KEY (`siren`);

--
-- Index pour la table `productowner`
--
ALTER TABLE `productowner`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `remise`
--
ALTER TABLE `remise`
  ADD PRIMARY KEY (`numRemise`),
  ADD KEY `transaction` (`idTransaction`);

--
-- Index pour la table `transaction`
--
ALTER TABLE `transaction`
  ADD PRIMARY KEY (`id`),
  ADD KEY `siren` (`numSiren`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `remise`
--
ALTER TABLE `remise`
  MODIFY `numRemise` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `transaction`
--
ALTER TABLE `transaction`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `remise`
--
ALTER TABLE `remise`
  ADD CONSTRAINT `transaction` FOREIGN KEY (`idTransaction`) REFERENCES `transaction` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Contraintes pour la table `transaction`
--
ALTER TABLE `transaction`
  ADD CONSTRAINT `siren` FOREIGN KEY (`numSiren`) REFERENCES `commercant` (`siren`) ON DELETE RESTRICT ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
