-- phpMyAdmin SQL Dump
-- version 4.6.6deb4
-- https://www.phpmyadmin.net/
--
-- Client :  sqletud.u-pem.fr
-- Généré le :  Mer 09 Novembre 2022 à 20:32
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

--
-- Index pour les tables exportées
--

--
-- Index pour la table `discount`
--
ALTER TABLE `discount`
  ADD PRIMARY KEY (`numDiscount`),
  ADD KEY `transaction` (`numAuthorization`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `discount`
--
ALTER TABLE `discount`
  MODIFY `numDiscount` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `discount`
--
ALTER TABLE `discount`
  ADD CONSTRAINT `transaction` FOREIGN KEY (`numAuthorization`) REFERENCES `transaction` (`numAuthorization`) ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

