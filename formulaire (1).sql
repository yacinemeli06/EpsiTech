-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : lun. 29 juin 2026 à 11:01
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `formulaire`
--

-- --------------------------------------------------------

--
-- Structure de la table `cadeaux`
--

CREATE TABLE `cadeaux` (
  `id` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `points_requis` int(11) NOT NULL,
  `image` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `cadeaux`
--

INSERT INTO `cadeaux` (`id`, `nom`, `description`, `points_requis`, `image`) VALUES
(1, 'Nintendo Switch', 'Console portable Nintendo Switch offerte', 200, 'switch.png'),
(2, 'PlayStation 5', 'Console PlayStation 5 offerte', 400, 'ps5.png'),
(3, 'ROG ALLY', 'Console portable ROG ALLY offerte', 499, 'rogally.png'),
(4, 'Xbox Series X', 'Console Xbox Series X offerte', 550, 'xbox.png'),
(5, 'PC Prebuilt Budget', 'PC Prebuilt Budget offert', 750, 'pc2.png'),
(6, 'PC Prebuilt', 'PC Prebuilt High-end offert', 1100, 'pc1.png');

-- --------------------------------------------------------

--
-- Structure de la table `panier`
--

CREATE TABLE `panier` (
  `id` int(11) NOT NULL,
  `session_id` varchar(255) NOT NULL,
  `produit` varchar(100) NOT NULL,
  `prix` decimal(10,2) NOT NULL,
  `quantite` int(11) DEFAULT 1,
  `date_ajout` datetime DEFAULT current_timestamp(),
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `panier`
--

INSERT INTO `panier` (`id`, `session_id`, `produit`, `prix`, `quantite`, `date_ajout`, `user_id`) VALUES
(6, 'o1m11hfphnvvesonepntb3nln9', 'PC PREBUILT', 1100.00, 1, '2026-06-29 10:38:38', 4),
(7, 'o1m11hfphnvvesonepntb3nln9', 'PSP', 130.00, 1, '2026-06-29 10:41:37', 1);

-- --------------------------------------------------------

--
-- Structure de la table `produits`
--

CREATE TABLE `produits` (
  `id` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `prix` decimal(10,2) NOT NULL,
  `image` varchar(100) NOT NULL,
  `page` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `produits`
--

INSERT INTO `produits` (`id`, `nom`, `prix`, `image`, `page`) VALUES
(1, 'Playstation 3', 200.00, 'ps5.png', 'accueil.html'),
(2, 'ROG ALLY', 499.00, 'rogally.png', 'rog.html'),
(3, 'Nintendo Switch', 250.00, 'switch.png', 'switch.html'),
(4, 'PC Prebuilt', 1100.00, 'pc1.png', 'pc1.html'),
(5, 'PC Prebuilt Budget', 750.00, 'pc2.png', 'pc2.html'),
(6, 'Xbox Series X', 550.00, 'xbox.png', 'xbox.html'),
(7, 'GameCube', 150.00, 'gamecube.jpeg', 'gamecube.html'),
(8, 'PSP', 130.00, 'psp.jpeg', 'psp.html'),
(9, 'Nintendo 3DS', 120.00, '3ds.jpeg', '3ds.html'),
(10, 'Nintendo 2DS', 80.00, '2ds.jpeg', '2ds.html');

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

CREATE TABLE `utilisateur` (
  `id` int(11) NOT NULL,
  `nom` varchar(25) NOT NULL,
  `prenom` varchar(25) NOT NULL,
  `email` varchar(255) NOT NULL,
  `mot de passe` varchar(255) NOT NULL,
  `points` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `utilisateur`
--

INSERT INTO `utilisateur` (`id`, `nom`, `prenom`, `email`, `mot de passe`, `points`) VALUES
(1, 'Touazi', 'Yacine', 'yacinetouazi11@gmail.com', '$2y$10$2h9b9eSvn9CsjmwOS5uXReVQWFvajaP/Kcb1tbSQE0z7auZxzVXzO', 130),
(2, 'Touazi', 'Yacine', 'yacinetouazi@gmail.com', '$2y$10$rSNIwM7ekgZg.NON7eFTtevuaHC5Tqs.0fVkJLf9aVjSv20ykUg3m', 0),
(3, 'Touazi', 'Yacine', 'yacinetouaz@gmail.com', '$2y$10$PzgrlrjxYTnqLVmDBiJv0esI4CWrfH7nqqVacPIzMnLwgcQpOBz9G', 0),
(4, 'Touazi', 'Yacine', 'yacine@gmail.com', '$2y$10$wRnS99zCjw.iNjDV77.Q4OV1BYn56zAgOxBXwdmaf3eGg/IrdTcke', 1100);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `cadeaux`
--
ALTER TABLE `cadeaux`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `panier`
--
ALTER TABLE `panier`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `produits`
--
ALTER TABLE `produits`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `cadeaux`
--
ALTER TABLE `cadeaux`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `panier`
--
ALTER TABLE `panier`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `produits`
--
ALTER TABLE `produits`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
