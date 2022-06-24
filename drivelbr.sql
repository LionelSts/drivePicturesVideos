-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jun 23, 2022 at 09:24 AM
-- Server version: 5.7.31
-- PHP Version: 8.1.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `drivelbr`
--

-- --------------------------------------------------------

--
-- Table structure for table `attribuer`
--

CREATE TABLE `attribuer` (
  `email` varchar(128) NOT NULL,
  `nom_tag` varchar(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `caracteriser`
--

CREATE TABLE `caracteriser` (
  `id_fichier` int(11) NOT NULL,
  `nom_tag` varchar(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `categorie`
--

CREATE TABLE `categorie` (
  `nom_categorie` char(30) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `categorie`
--

INSERT INTO `categorie` (`nom_categorie`) VALUES
('Autre'),
('Edition'),
('Lieu');

-- --------------------------------------------------------

--
-- Table structure for table `corbeille`
--

CREATE TABLE `corbeille` (
  `id` int(11) NOT NULL,
  `nom_fichier` varchar(256) NOT NULL,
  `extension` varchar(10) NOT NULL,
  `auteur` varchar(128) NOT NULL,
  `date` datetime NOT NULL,
  `duree` time NOT NULL,
  `size` bigint(20) NOT NULL,
  `nom_stockage` varchar(65) NOT NULL,
  `supprime_date` datetime NOT NULL,
  `supprime_par` varchar(128) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `fichiers`
--

CREATE TABLE `fichiers` (
  `id` int(11) NOT NULL,
  `nom_fichier` varchar(256) NOT NULL,
  `extension` varchar(10) NOT NULL,
  `auteur` varchar(128) NOT NULL,
  `date` datetime NOT NULL,
  `duree` time NOT NULL,
  `size` bigint(20) NOT NULL,
  `nom_stockage` varchar(65) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tableau_de_bord`
--

CREATE TABLE `tableau_de_bord` (
  `id_modif` int(11) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modification` varchar(256) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Table structure for table `tags`
--

CREATE TABLE `tags` (
  `nom_tag` varchar(50) NOT NULL,
  `nom_categorie` varchar(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tags`
--

INSERT INTO `tags` (`nom_tag`, `nom_categorie`) VALUES
('Camping', 'Lieu'),
('2021', 'Edition'),
('2022', 'Edition'),
('BackStage', 'Lieu'),
('Village', 'Lieu'),
('Parking', 'Lieu'),
('Bus', 'Lieu'),
('2024', 'Edition'),
('Scène 1', 'Lieu'),
('2TH', 'Autre'),
('Sans tag', 'Autre');

-- --------------------------------------------------------

--
-- Table structure for table `utilisateurs`
--

CREATE TABLE `utilisateurs` (
  `mail` varchar(128) NOT NULL,
  `nom` varchar(32) NOT NULL,
  `prenom` varchar(32) NOT NULL,
  `mot_de_passe` varchar(256) NOT NULL,
  `descriptif` tinytext NOT NULL,
  `role` varchar(10) NOT NULL,
  `etat` char(20) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `utilisateurs`
--

INSERT INTO `utilisateurs` (`mail`, `nom`, `prenom`, `mot_de_passe`, `descriptif`, `role`, `etat`) VALUES
('mathieu.ranc@lesbriquesrouges.fr', 'Ranc', 'Mathieu', '$2y$10$/Pl.8.IJQ.NXPGE1A8x/6OHOUvYXsMBBxkzL/tWQ1Vh0newoVg4w2', 'Administrateur Drive', 'admin', 'en attente');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categorie`
--
ALTER TABLE `categorie`
  ADD PRIMARY KEY (`nom_categorie`);

--
-- Indexes for table `corbeille`
--
ALTER TABLE `corbeille`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fichiers`
--
ALTER TABLE `fichiers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tableau_de_bord`
--
ALTER TABLE `tableau_de_bord`
  ADD PRIMARY KEY (`id_modif`);

--
-- Indexes for table `tags`
--
ALTER TABLE `tags`
  ADD PRIMARY KEY (`nom_tag`);

--
-- Indexes for table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  ADD PRIMARY KEY (`mail`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `corbeille`
--
ALTER TABLE `corbeille`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `fichiers`
--
ALTER TABLE `fichiers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=108;

--
-- AUTO_INCREMENT for table `tableau_de_bord`
--
ALTER TABLE `tableau_de_bord`
  MODIFY `id_modif` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=130;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
