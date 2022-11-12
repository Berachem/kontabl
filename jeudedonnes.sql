-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:3306
-- Généré le : sam. 12 nov. 2022 à 13:36
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
  `password` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL
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
  `numTransaction` int NOT NULL,
  `sens` char(1) NOT NULL,
  `unpaidWording` varchar(75) DEFAULT NULL COMMENT 'c''est le libellé du pourquoi c''est un impayé',
  `numUnpaidFile` char(5) DEFAULT NULL COMMENT 'num dossier impayé',
  `dateDiscount` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Déchargement des données de la table `discount`
--

INSERT INTO `discount` (`numDiscount`, `numTransaction`, `sens`, `unpaidWording`, `numUnpaidFile`, `dateDiscount`) VALUES
(1, 2, '-', 'solde insuffisant', '1234', '2022-11-09 08:40:08'),
(2, 3, '+', '', '', '2022-10-19 07:36:12'),
(3, 1, '+', '', '', '2022-10-29 13:22:14'),
(11, 6, '-', 'erreur bancaire', 'DP236', '2022-08-16 10:49:45'),
(12, 4, '+', '', '', '2020-08-01 10:49:45'),
(13, 7, '-', 'plafond bancaire atteint', '2365F', '2022-09-05 11:13:32'),
(14, 6, '+', NULL, NULL, '2022-11-09 09:45:37'),
(15, 7, '+', NULL, NULL, '2022-11-25 16:30:26'),
(16, 5, '+', NULL, NULL, '2022-11-01 14:10:12'),
(17, 10, '-', 'Ceiling reached', 'BAD4A', NULL),
(18, 8, '-', 'Account empty', 'A1FTG', NULL),
(19, 9, '', 'Bad review', 'RFTYH', NULL),
(20, 11, '', 'Unknow error', 'MPS5V', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `merchant`
--

CREATE TABLE `merchant` (
  `raisonSociale` varchar(30) NOT NULL,
  `siren` char(9) NOT NULL,
  `currency` varchar(3) NOT NULL,
  `numCarte` char(4) NOT NULL,
  `network` char(2) NOT NULL,
  `password` varchar(350) NOT NULL,
  `idLogin` varchar(350) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Déchargement des données de la table `merchant`
--

INSERT INTO `merchant` (`raisonSociale`, `siren`, `currency`, `numCarte`, `network`, `password`, `idLogin`) VALUES
('Action Contre la faim', '123456789', 'EUR', '5879', 'fr', '$2y$10$yr.XKAmVAxKTpkYHOzN4/uJEnzQz0tRW7zq0.P01w5pvohrgqAoaK', 'test'),
('Louis Vuitton Services', '347662454', 'USD', '4589', 'MC', '$2y$10$rJJzcYpZ7TPh.uRl2ix7f.umGB3oykXjKFFDIYXI2ZYIY1R40eAaS', 'louisvi'),
('Leroy Merlin Noisy', '384560942', 'EUR', '7485', 'AE', '$2y$10$bb5LScjly5Y.YtCavNcAmOnvTYDkMm8cJqDjai.JhzynTOwyyvd4m', '7745511214'),
('Gucci France', '632032348', 'EUR', '9685', 'VS', '$2y$10$ykiEOKAU5d7RkIh6jkXNPe5RxiV.c.y4APmkg.ZCLHJm9DNp3tQMy', 'guccifrance'),
('McDonald Champs sur Marne', '722003936', 'EUR', '1796', 'VS', '$2y$10$9pm/PQ3lnu11mo57tgjzluOr.KYJdLMEPRU5klHN6zPjgXRGx4tmK', '8755269857'),
('Burger King', '987654321', 'USD', '8565', 'en', '$2y$10$hNOOjneT/qvL3szZvdCb9.kz0g5VrmZ5U0HThVPGUfvJN4MX00/CC\r\n', 'anglais');

-- --------------------------------------------------------

--
-- Structure de la table `merchant_temp`
--

CREATE TABLE `merchant_temp` (
  `raisonSociale` varchar(30) NOT NULL,
  `siren` char(9) NOT NULL,
  `currency` varchar(3) NOT NULL,
  `numCarte` char(16) NOT NULL,
  `network` char(2) NOT NULL,
  `password` varchar(350) NOT NULL,
  `idLogin` varchar(350) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `productowner`
--

CREATE TABLE `productowner` (
  `idProductowner` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
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
  `numAuthorization` char(6) NOT NULL,
  `dateTransaction` datetime NOT NULL,
  `endingFoursCardNumbers` char(4) NOT NULL,
  `currency` varchar(3) NOT NULL,
  `numSiren` char(9) NOT NULL,
  `amount` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Déchargement des données de la table `transaction`
--

INSERT INTO `transaction` (`idTransaction`, `numAuthorization`, `dateTransaction`, `endingFoursCardNumbers`, `currency`, `numSiren`, `amount`) VALUES
(1, 'D5F689', '2022-09-15 12:11:30', '1245', 'EUR', '384560942', 300000),
(2, 'B735S5', '2017-10-10 23:29:46', '1488', 'EUR', '722003936', 740000),
(3, 'PL2593', '2022-07-19 07:35:10', '1458', 'EUR', '722003936', 25000),
(4, 'Y465L2', '2022-11-19 04:31:18', '3657', 'EUR', '722003936', 1200000),
(5, 'UI8568', '2022-11-11 20:10:27', '9856', 'EUR', '632032348', 890000),
(6, '23FD89', '2022-10-13 10:24:16', '4156', 'EUR', '347662454', 213000),
(7, '78MP36', '2022-10-16 10:24:16', '6515', 'EUR', '347662454', 56000),
(8, 'THD4GR', '2022-11-10 13:06:26', '4578', 'EUR', '632032348', 10000),
(9, 'UJLDAE', '2022-11-10 13:06:26', '7458', 'EUR', '632032348', 5400),
(10, 'RFZE48', '2022-11-10 13:07:32', '4512', 'USD', '347662454', 8000),
(11, 'GBT47M', '2022-11-10 13:07:32', '9632', 'USD', '347662454', 5200),
(12, 'DE74RF', '2022-11-12 13:33:52', '4596', 'EUR', '123456789', 1000),
(13, 'GT41DE', '2022-11-12 13:33:52', '7846', 'EUR', '987654321', 2000);

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
  ADD KEY `numT` (`numTransaction`);

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
  MODIFY `numDiscount` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT pour la table `transaction`
--
ALTER TABLE `transaction`
  MODIFY `idTransaction` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `discount`
--
ALTER TABLE `discount`
  ADD CONSTRAINT `numT` FOREIGN KEY (`numTransaction`) REFERENCES `transaction` (`idTransaction`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `transaction`
--
ALTER TABLE `transaction`
  ADD CONSTRAINT `siren` FOREIGN KEY (`numSiren`) REFERENCES `merchant` (`siren`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
