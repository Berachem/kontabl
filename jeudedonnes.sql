-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:3306
-- Généré le : mer. 09 nov. 2022 à 18:03
-- Version du serveur : 8.0.30
-- Version de PHP : 7.4.32

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
  `password` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Déchargement des données de la table `admin`
--

INSERT INTO `admin` (`idAdmin`, `password`, `name`) VALUES
('2001458436', '$2y$10$it0cUEoKsJxX1KsTmGkWUOeY7nzgNlB1oFKtmu/22FGdymIioFtxW\n', 'ScrumMaster');

-- --------------------------------------------------------

--
-- Structure de la table `discount`
--

CREATE TABLE `discount` (
  `numDiscount` int NOT NULL,
  `numAuthorization` char(6) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `sens` char(1) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `unpaidWording` char(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `numUnpaidFile` char(5) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `dateDiscount` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Déchargement des données de la table `discount`
--

INSERT INTO `discount` (`numDiscount`, `numAuthorization`, `sens`, `unpaidWording`, `numUnpaidFile`, `dateDiscount`) VALUES
(1, 'abcdef', '-', 'libelle_impayer', '1234', NULL),
(2, '123456', '+', '', '', '2022-10-03 00:00:00'),
(3, '123456', '+', '', '', '2022-10-17 00:00:00');

-- --------------------------------------------------------

--
-- Structure de la table `merchant`
--

CREATE TABLE `merchant` (
  `raisonSociale` varchar(30) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `siren` char(9) NOT NULL,
  `currency` varchar(3) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `numCarte` char(16) NOT NULL,
  `network` char(2) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `password` varchar(350) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `idLogin` varchar(350) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Déchargement des données de la table `merchant`
--

INSERT INTO `merchant` (`raisonSociale`, `siren`, `currency`, `numCarte`, `network`, `password`, `idLogin`) VALUES
('Leroy Merlin Noisy', '384560942', 'EUR', '7485', 'VS', '$2y$10$bb5LScjly5Y.YtCavNcAmOnvTYDkMm8cJqDjai.JhzynTOwyyvd4m', '7745511214'),
('McDonald Champs sur Marne', '722003936', 'EUR', '1796', 'VS', '$2y$10$9pm/PQ3lnu11mo57tgjzluOr.KYJdLMEPRU5klHN6zPjgXRGx4tmK', '8755269857');

-- --------------------------------------------------------

--
-- Structure de la table `merchant_temp`
--

CREATE TABLE `merchant_temp` (
  `raisonSociale` varchar(30) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `siren` char(9) NOT NULL,
  `currency` varchar(3) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `numCarte` char(16) NOT NULL,
  `network` char(2) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `password` varchar(350) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `idLogin` varchar(350) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `productowner`
--

CREATE TABLE `productowner` (
  `idProductowner` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Déchargement des données de la table `productowner`
--

INSERT INTO `productowner` (`idProductowner`, `name`, `password`) VALUES
('4526452419', 'M.Tran', '$2y$10$f.ZqIIR9x4uWp/oRJRbpfO0AiqFlXgFfEbLuOuI2UsoXVcrcxfOsy');

-- --------------------------------------------------------

--
-- Structure de la table `transaction`
--

CREATE TABLE `transaction` (
  `idTransaction` int NOT NULL,
  `numAuthorization` char(6) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `dateTransaction` datetime NOT NULL,
  `endingFoursCardNumbers` char(4) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `currency` varchar(3) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `numSiren` char(9) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `amount` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Déchargement des données de la table `transaction`
--

INSERT INTO `transaction` (`idTransaction`, `numAuthorization`, `dateTransaction`, `endingFoursCardNumbers`, `currency`, `numSiren`, `amount`) VALUES
(1, '123456', '2022-09-14 00:00:00', '1245', 'EUR', '384560942', 0),
(2, '1542za', '2017-10-26 00:00:00', '1488', 'EUR', '722003936', 0),
(3, 'abcdef', '2022-10-10 00:00:00', '1458', 'EUR', '722003936', 0);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`idAdmin`);

--
-- Index pour la table `discount`
--
ALTER TABLE `discount`
  ADD PRIMARY KEY (`numDiscount`),
  ADD KEY `transaction` (`numAuthorization`);

--
-- Index pour la table `merchant`
--
ALTER TABLE `merchant`
  ADD PRIMARY KEY (`siren`);

--
-- Index pour la table `merchant_temp`
--
ALTER TABLE `merchant_temp`
  ADD PRIMARY KEY (`siren`);

--
-- Index pour la table `productowner`
--
ALTER TABLE `productowner`
  ADD PRIMARY KEY (`idProductowner`);

--
-- Index pour la table `transaction`
--
ALTER TABLE `transaction`
  ADD PRIMARY KEY (`idTransaction`),
  ADD UNIQUE KEY `numAuthorization` (`numAuthorization`),
  ADD KEY `siren` (`numSiren`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `discount`
--
ALTER TABLE `discount`
  MODIFY `numDiscount` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT pour la table `transaction`
--
ALTER TABLE `transaction`
  MODIFY `idTransaction` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `discount`
--
ALTER TABLE `discount`
  ADD CONSTRAINT `transaction` FOREIGN KEY (`numAuthorization`) REFERENCES `transaction` (`numAuthorization`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Contraintes pour la table `transaction`
--
ALTER TABLE `transaction`
  ADD CONSTRAINT `siren` FOREIGN KEY (`numSiren`) REFERENCES `merchant` (`siren`) ON DELETE RESTRICT ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
