-- phpMyAdmin SQL Dump
-- version 4.6.6deb4
-- https://www.phpmyadmin.net/
--
-- Client :  sqletud.u-pem.fr
-- Généré le :  Mer 09 Novembre 2022 à 20:35
-- Version du serveur :  5.7.30-log
-- Version de PHP :  7.0.33-0+deb9u7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `berachem.markria_db`
--

-- --------------------------------------------------------

--
-- Structure de la table `admin`
--

CREATE TABLE `admin` (
  `idAdmin` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `admin`
--

INSERT INTO `admin` (`idAdmin`, `password`, `name`) VALUES
('2001458436', '$2y$10$it0cUEoKsJxX1KsTmGkWUOeY7nzgNlB1oFKtmu/22FGdymIioFtxW\n', 'ScrumMaster');

-- --------------------------------------------------------

--
-- Structure de la table `discount`
--

CREATE TABLE `discount` (
  `numDiscount` int(11) NOT NULL,
  `numAuthorization` char(6) NOT NULL,
  `sens` char(1) NOT NULL,
  `unpaidWording` varchar(75) DEFAULT NULL COMMENT 'c''est le libellé du pourquoi c''est un impayé',
  `numUnpaidFile` char(5) DEFAULT NULL COMMENT 'num dossier impayé',
  `dateDiscount` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `discount`
--

INSERT INTO `discount` (`numDiscount`, `numAuthorization`, `sens`, `unpaidWording`, `numUnpaidFile`, `dateDiscount`) VALUES
(1, 'PL2593', '-', 'solde insuffisant', '1234', '2022-11-09 08:40:08'),
(2, 'D5F689', '+', '', '', '2022-10-19 07:36:12'),
(3, 'D5F689', '+', '', '', '2022-10-29 13:22:14'),
(11, 'PL2593', '-', 'erreur bancaire', 'DP236', '2022-08-16 10:49:45'),
(12, 'Y465L2', '+', '', '', '2020-08-01 10:49:45'),
(13, 'UI8568', '-', 'plafond bancaire atteint', '2365F', '2022-09-05 11:13:32');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `merchant`
--

INSERT INTO `merchant` (`raisonSociale`, `siren`, `currency`, `numCarte`, `network`, `password`, `idLogin`) VALUES
('Louis Vuitton Services', '347662454', 'USD', '4589', 'MC', '', 'louisvi'),
('Leroy Merlin Noisy', '384560942', 'EUR', '7485', 'AE', '$2y$10$bb5LScjly5Y.YtCavNcAmOnvTYDkMm8cJqDjai.JhzynTOwyyvd4m', '7745511214'),
('Gucci France', '632032348', 'EUR', '9685', 'VS', '', 'guccifrance'),
('McDonald Champs sur Marne', '722003936', 'EUR', '1796', 'VS', '$2y$10$9pm/PQ3lnu11mo57tgjzluOr.KYJdLMEPRU5klHN6zPjgXRGx4tmK', '8755269857');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `productowner`
--

CREATE TABLE `productowner` (
  `idProductowner` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `productowner`
--

INSERT INTO `productowner` (`idProductowner`, `name`, `password`) VALUES
('4526452419', 'M.Tran', '$2y$10$f.ZqIIR9x4uWp/oRJRbpfO0AiqFlXgFfEbLuOuI2UsoXVcrcxfOsy');

-- --------------------------------------------------------

--
-- Structure de la table `transaction`
--

CREATE TABLE `transaction` (
  `idTransaction` int(11) NOT NULL,
  `numAuthorization` char(6) NOT NULL,
  `dateTransaction` datetime NOT NULL,
  `endingFoursCardNumbers` char(4) NOT NULL,
  `currency` varchar(3) NOT NULL,
  `numSiren` char(9) NOT NULL,
  `amount` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `transaction`
--

INSERT INTO `transaction` (`idTransaction`, `numAuthorization`, `dateTransaction`, `endingFoursCardNumbers`, `currency`, `numSiren`, `amount`) VALUES
(1, 'D5F689', '2022-09-15 12:11:30', '1245', 'EUR', '384560942', 300000),
(2, 'B735S5', '2017-10-10 23:29:46', '1488', 'EUR', '722003936', 740000),
(3, 'PL2593', '2022-07-19 07:35:10', '1458', 'EUR', '722003936', 25000),
(4, 'Y465L2', '2022-11-19 04:31:18', '3657', 'EUR', '722003936', 1200000),
(5, 'UI8568', '2022-11-11 20:10:27', '9856', 'EUR', '632032348', 890000),
(6, '23FD89', '2022-10-13 10:24:16', '4156', 'EUR', '347662454', 213000),
(7, '78MP36', '2022-10-16 10:24:16', '6515', 'EUR', '347662454', 56000);

--
-- Index pour les tables exportées
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
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `discount`
--
ALTER TABLE `discount`
  MODIFY `numDiscount` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
--
-- AUTO_INCREMENT pour la table `transaction`
--
ALTER TABLE `transaction`
  MODIFY `idTransaction` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `discount`
--
ALTER TABLE `discount`
  ADD CONSTRAINT `transaction` FOREIGN KEY (`numAuthorization`) REFERENCES `transaction` (`numAuthorization`) ON UPDATE CASCADE;

--
-- Contraintes pour la table `transaction`
--
ALTER TABLE `transaction`
  ADD CONSTRAINT `siren` FOREIGN KEY (`numSiren`) REFERENCES `merchant` (`siren`) ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

