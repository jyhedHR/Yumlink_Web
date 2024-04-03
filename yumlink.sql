-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : mer. 03 avr. 2024 à 02:16
-- Version du serveur : 10.4.28-MariaDB
-- Version de PHP : 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `yumlink`
--
CREATE DATABASE IF NOT EXISTS `yumlink` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `yumlink`;

-- --------------------------------------------------------

--
-- Structure de la table `admin`
--

CREATE TABLE `admin` (
  `emailA` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `admin`
--

INSERT INTO `admin` (`emailA`, `password`) VALUES
('admin@admin.com', 'admin');

-- --------------------------------------------------------

--
-- Structure de la table `adresse`
--

CREATE TABLE `adresse` (
  `idA` int(11) NOT NULL,
  `gouvernorat` varchar(50) NOT NULL,
  `ville` varchar(50) NOT NULL,
  `rue` varchar(70) NOT NULL,
  `codePostal` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `adresse`
--

INSERT INTO `adresse` (`idA`, `gouvernorat`, `ville`, `rue`, `codePostal`) VALUES
(1, 'ariana', 'haylghazela', 'khalij2', 9874),
(2, 'ariana', 'aaaa', 'aaaaa', 789),
(3, 'Ariana', 'menzah8', 'rue khalij', 888),
(5, 'ariana', 'babsaadoun', 'aaaa', 789),
(11, 'emir', 'emir', 'emir', 133),
(14, 'ariana', 'aaaaa', 'aaaaaaa', 1234),
(15, 'aaaaaa', 'aaaaa', 'aaa', 1457),
(16, 'Ariana', 'la goulette', 'rue saada', 1234),
(17, 'Ariana', 'ariana', 'rue de la liberté', 1234),
(18, 'tunis', 'tunis', 'azerty', 1234),
(19, 'tunis', 'tunis', 'azerty', 1234),
(20, 'Sousse', 'sousse', 'wrda', 5478),
(21, 'tunis', 'lafayette', 'koukou', 1478),
(22, 'ariana', 'menzah', 'azerty', 1234),
(23, 'ariana', 'menzah', 'aaaaaa', 7894),
(24, 'azerty', 'azert', 'aqzsed', 1234),
(25, 'bizerte', 'bizerte', 'aaaa', 1234),
(26, 'gafsa', 'aaaaaa', 'aaaaa', 1457),
(27, 'aaaa', 'aaaaa', 'aaaa', 1452),
(28, 'aaaa', 'aaaaaa', 'saada', 4567),
(29, 'tunis', 'tunis', 'asedr', 1234),
(30, 'Gafsa', 'haysourour', 'dcvsdcv', 2100);

-- --------------------------------------------------------

--
-- Structure de la table `article`
--

CREATE TABLE `article` (
  `id_article` int(11) NOT NULL,
  `idU` int(11) NOT NULL,
  `title_article` varchar(255) NOT NULL,
  `img_article` varchar(255) NOT NULL,
  `description_article` text NOT NULL,
  `nb_likes_article` int(11) NOT NULL,
  `date_published` datetime NOT NULL,
  `tags` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`tags`))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `article`
--

INSERT INTO `article` (`id_article`, `idU`, `title_article`, `img_article`, `description_article`, `nb_likes_article`, `date_published`, `tags`) VALUES
(3, 40, 'First post title', 'C:/Users/ammou/Desktop/integration/src/main/resources/com/example/yumlink/images/logo.png', 'This is the first post posted ! #healthy #diet #spicy', 0, '2024-03-05 18:22:12', '{\"tags\":[\"healthy\",\"diet\",\"spicy\"]}');

-- --------------------------------------------------------

--
-- Structure de la table `commande`
--

CREATE TABLE `commande` (
  `id_com` int(11) NOT NULL,
  `date` int(11) NOT NULL,
  `id_client` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `commentaire`
--

CREATE TABLE `commentaire` (
  `comment_id` int(11) NOT NULL,
  `id_article` int(11) NOT NULL,
  `idU` int(11) NOT NULL,
  `comment_text` text NOT NULL,
  `comment_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `commentaire`
--

INSERT INTO `commentaire` (`comment_id`, `id_article`, `idU`, `comment_text`, `comment_date`) VALUES
(25, 3, 40, 'hello nice post', '2024-03-05'),
(26, 3, 40, 'hello', '2024-03-05');

-- --------------------------------------------------------

--
-- Structure de la table `defis`
--

CREATE TABLE `defis` (
  `id_d` int(11) NOT NULL,
  `idU` int(11) NOT NULL,
  `nom_d` varchar(50) NOT NULL,
  `photo_d` varchar(255) NOT NULL,
  `dis_d` varchar(50) NOT NULL,
  `delai` date NOT NULL,
  `heure` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `defis`
--

INSERT INTO `defis` (`id_d`, `idU`, `nom_d`, `photo_d`, `dis_d`, `delai`, `heure`) VALUES
(79, 39, 'Tacos', 'C:\\Users\\ammou\\Desktop\\Beef-Tacos.jpg', 'Tacos', '2024-03-07', '10:53:14'),
(80, 39, 'Salade césar', 'C:\\Users\\ammou\\Desktop\\ttttt.jpeg', 'Salade césar', '2024-03-15', '10:53:14'),
(81, 39, 'Tacos', 'C:\\Users\\ammou\\Desktop\\Beef-Tacos.jpg', 'Tacos', '2024-02-13', '12:09:31');

-- --------------------------------------------------------

--
-- Structure de la table `doctrine_migration_versions`
--

CREATE TABLE `doctrine_migration_versions` (
  `version` varchar(191) NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `favorite_recipes`
--

CREATE TABLE `favorite_recipes` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `recipe_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ingredient`
--

CREATE TABLE `ingredient` (
  `id_ing` int(11) NOT NULL,
  `nom` varchar(20) NOT NULL,
  `quantite` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `ingredient`
--

INSERT INTO `ingredient` (`id_ing`, `nom`, `quantite`) VALUES
(1, 'mariem', 13),
(5, 'tttt', 1),
(6, 'b', 51),
(7, 'b', 50);

-- --------------------------------------------------------

--
-- Structure de la table `nutrition_recommandation`
--

CREATE TABLE `nutrition_recommandation` (
  `id_meals` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `panier`
--

CREATE TABLE `panier` (
  `idp` int(11) NOT NULL,
  `quantite` int(11) NOT NULL,
  `prixtotal` float NOT NULL,
  `id_produit` int(11) NOT NULL,
  `id_client` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `panier`
--

INSERT INTO `panier` (`idp`, `quantite`, `prixtotal`, `id_produit`, `id_client`) VALUES
(5, 7, 5461.4, 8, 39),
(6, 8, 3600, 6, 39),
(7, 3, 2340.6, 8, 39),
(8, 7, 483, 9, 39),
(9, 7, 483, 9, 39),
(10, 7, 483, 9, 43),
(11, 5, 3901, 8, 43);

-- --------------------------------------------------------

--
-- Structure de la table `participant`
--

CREATE TABLE `participant` (
  `idpart` int(10) NOT NULL,
  `idU` int(11) NOT NULL,
  `id_d` int(11) NOT NULL,
  `photo_p` varchar(255) NOT NULL,
  `vote` int(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `participant`
--

INSERT INTO `participant` (`idpart`, `idU`, `id_d`, `photo_p`, `vote`) VALUES
(19, 40, 79, 'C:\\Users\\ammou\\Desktop\\Beef-Tacos.jpg', 0),
(20, 29, 79, 'C:\\Users\\ammou\\Desktop\\Beef-Tacos.jpg', 1),
(21, 41, 79, 'C:\\Users\\ammou\\Desktop\\Beef-Tacos.jpg', 1),
(22, 40, 81, 'C:\\Users\\ammou\\Desktop\\Beef-Tacos.jpg', 0);

-- --------------------------------------------------------

--
-- Structure de la table `personne`
--

CREATE TABLE `personne` (
  `id` int(11) NOT NULL,
  `nom` varchar(50) NOT NULL,
  `prenom` varchar(50) NOT NULL,
  `age` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `personne`
--

INSERT INTO `personne` (`id`, `nom`, `prenom`, `age`) VALUES
(1, 'jyhed', 'horchani', 15),
(2, 'jyhed', 'horchani', 15),
(4, 'jj', 'sraya', 15),
(5, 'jyhed', 'horchani', 15),
(6, 'b', 'l', 15),
(7, 'jj', 'sraya', 15),
(8, 'jyhed', 'horchani', 15),
(9, 'b', 'l', 15),
(10, 'jj', 'sraya', 15),
(11, 'jyhed', 'horchani', 15),
(12, 'b', 'l', 15),
(13, 'jj', 'sraya', 15),
(14, 'jyhed', 'horchani', 15),
(15, 'b', 'l', 15),
(16, 'jj', 'sraya', 15),
(17, 'jyhed', 'horchani', 15),
(18, 'b', 'l', 15),
(19, 'jj', 'sraya', 15),
(20, 'essra', 'zitouni', 21),
(21, 'essra', 'zitouni', 21),
(22, 'essra', 'zitouni', 21),
(23, 'essra', 'horchani', 22),
(24, 'jyhed', 'horchani', 15),
(25, 'b', 'l', 15),
(26, 'jj', 'sraya', 15),
(27, 'jyhed', 'horchani', 15),
(28, 'b', 'l', 15),
(29, 'jj', 'sraya', 15),
(30, 'jyhed', 'horchani', 15),
(31, 'b', 'l', 15),
(32, 'jj', 'sraya', 15),
(33, 'jyhed', 'horchani', 15),
(34, 'b', 'l', 15),
(35, 'jj', 'sraya', 15);

-- --------------------------------------------------------

--
-- Structure de la table `produit`
--

CREATE TABLE `produit` (
  `id` int(11) NOT NULL,
  `nom` varchar(50) NOT NULL,
  `prix` double NOT NULL,
  `diescription` varchar(50) NOT NULL,
  `image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `produit`
--

INSERT INTO `produit` (`id`, `nom`, `prix`, `diescription`, `image`) VALUES
(6, 'banana', 450, 'mohsennnn', 'C:/Users/ammou/OneDrive/Images/logo2%20(2).png'),
(8, 'emna', 780.2, 'qsergfsrdg', 'C:/Users/ammou/OneDrive/Images/logo2.png'),
(9, 'mehrez', 69, 'drdsgqsvqsdvq', 'C:/Users/ammou/OneDrive/Images/Capture%20d’écran%202023-05-13%20233947.png');

-- --------------------------------------------------------

--
-- Structure de la table `recette`
--

CREATE TABLE `recette` (
  `id_r` int(11) NOT NULL,
  `idu` int(11) DEFAULT NULL,
  `iduser` int(11) DEFAULT NULL,
  `nom` varchar(20) NOT NULL,
  `description` varchar(100) NOT NULL,
  `imgSrc` varchar(255) NOT NULL,
  `calorie` int(11) NOT NULL,
  `protein` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `tag`
--

CREATE TABLE `tag` (
  `tag_id` int(11) NOT NULL,
  `tag_value` varchar(30) NOT NULL,
  `tag_nb_usage` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `tag`
--

INSERT INTO `tag` (`tag_id`, `tag_value`, `tag_nb_usage`) VALUES
(1, 'healthy', 0),
(2, 'diet', 0),
(3, 'spicy', 0),
(4, 'cooked', 0),
(5, 'grilled', 0),
(6, 'tasty', 0),
(7, 'sweet', 0),
(8, 'sour', 0),
(9, 'desert', 0),
(10, 'italian food', 0);

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE `user` (
  `idU` int(11) NOT NULL,
  `nom` varchar(20) NOT NULL,
  `prenom` varchar(20) NOT NULL,
  `email` varchar(50) NOT NULL,
  `mdp` varchar(150) NOT NULL,
  `tel` int(11) NOT NULL,
  `role` varchar(20) NOT NULL,
  `Image` varchar(100) NOT NULL,
  `idA` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`idU`, `nom`, `prenom`, `email`, `mdp`, `tel`, `role`, `Image`, `idA`) VALUES
(1, 'samir', 'samir', '', '', 0, '', '', NULL),
(29, 'temimi', 'yasmine', 'yasmine@gmail.com', 'Yas123??123', 98741258, 'client', 'C:\\Users\\ammou\\Desktop\\urgence 27-02\\src\\main\\resources\\com\\example\\yumlink\\images\\back.png', 14),
(39, 'emna', 'aaaaaaaaaa', 'jyhed@gmail.com', '$2a$10$FtOCxwsUC9nRSAOXGaT8H.dr.FevG4lAucZIstapUkFvaFInzGer2', 93855741, 'chef', 'C:/Users/ammou/OneDrive/Images/gestion-documentaire.jpg', 26),
(40, 'jiji', 'jiji', 't@gmail.com', '$2a$10$4JseoCuJTGp8ZCvJlDVhjOo2Y4e/MIW8jvgedqcn6MVQIV86FOfqe', 93588870, 'client', 'file:/C:/Users/ammou/OneDrive/Images/gestion-documentaire.jpg', 27),
(41, 'emna', 'lakhal', 'ammoun2011@gmail.com', '$2a$10$6LO/r8EiLiqhdkLqLo4youlloFNCQg4RNvJVJyL2.k0Bz65yTsVrC', 98646891, 'chef', 'C:/Users/ammou/Desktop/urgence%2027-02/src/main/resources/com/example/yumlink/images/man_2922510.png', 28),
(42, 'yassine', 'aaaaa', 'yassine@gmail.com', '$2a$10$ByInhfIdVOkDiKfgUtjE8.ilwu2j95AL1R/hXiE6XdQObmsJj0EN6', 98646891, 'client', 'C:/Users/ammou/Desktop/dc.png', 29),
(43, 'jyhed', 'horchani', 'jihedhorchani1234@gmail.com', '$2a$10$mUGdHZWa2yZBRO1Iky9xBOrVnrkICk37BvNVkO8PQh1O1jNGWviDW', 50711106, 'client', 'C:/Users/ammou/OneDrive/Images/292795107_5374385372643453_426383244334072911_n.jpg', 30);

-- --------------------------------------------------------

--
-- Structure de la table `user_nutrition`
--

CREATE TABLE `user_nutrition` (
  `id_nut` int(11) NOT NULL,
  `id` int(11) NOT NULL,
  `age` int(11) NOT NULL,
  `weight` double NOT NULL,
  `height` double NOT NULL,
  `activity_level` varchar(11) NOT NULL,
  `gender` varchar(10) NOT NULL,
  `calorie` float NOT NULL,
  `protein` float NOT NULL,
  `carbs` float NOT NULL,
  `fat` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `user_nutrition`
--

INSERT INTO `user_nutrition` (`id_nut`, `id`, `age`, `weight`, `height`, `activity_level`, `gender`, `calorie`, `protein`, `carbs`, `fat`) VALUES
(16, 39, 87, 150, 170, 'Lazy', 'Homme', 2559, 191.925, 255.9, 85.3);

-- --------------------------------------------------------

--
-- Structure de la table `vote`
--

CREATE TABLE `vote` (
  `id_vote` int(50) NOT NULL,
  `idU` int(50) NOT NULL,
  `idpart` int(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`emailA`);

--
-- Index pour la table `adresse`
--
ALTER TABLE `adresse`
  ADD PRIMARY KEY (`idA`);

--
-- Index pour la table `article`
--
ALTER TABLE `article`
  ADD PRIMARY KEY (`id_article`),
  ADD KEY `fk_user_id` (`idU`);

--
-- Index pour la table `commande`
--
ALTER TABLE `commande`
  ADD PRIMARY KEY (`id_com`);

--
-- Index pour la table `commentaire`
--
ALTER TABLE `commentaire`
  ADD PRIMARY KEY (`comment_id`,`id_article`),
  ADD KEY `fk_article_id` (`id_article`),
  ADD KEY `fk_com_user_id` (`idU`);

--
-- Index pour la table `defis`
--
ALTER TABLE `defis`
  ADD PRIMARY KEY (`id_d`),
  ADD KEY `fk_id_u` (`idU`);

--
-- Index pour la table `doctrine_migration_versions`
--
ALTER TABLE `doctrine_migration_versions`
  ADD PRIMARY KEY (`version`);

--
-- Index pour la table `favorite_recipes`
--
ALTER TABLE `favorite_recipes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `recipe_id` (`recipe_id`);

--
-- Index pour la table `ingredient`
--
ALTER TABLE `ingredient`
  ADD PRIMARY KEY (`id_ing`);

--
-- Index pour la table `nutrition_recommandation`
--
ALTER TABLE `nutrition_recommandation`
  ADD PRIMARY KEY (`id_meals`),
  ADD KEY `pk_id3` (`user_id`);

--
-- Index pour la table `panier`
--
ALTER TABLE `panier`
  ADD PRIMARY KEY (`idp`),
  ADD KEY `fk_idproduit` (`id_produit`);

--
-- Index pour la table `participant`
--
ALTER TABLE `participant`
  ADD PRIMARY KEY (`idpart`),
  ADD KEY `fk_id_user` (`idU`),
  ADD KEY `fk_id_d` (`id_d`);

--
-- Index pour la table `personne`
--
ALTER TABLE `personne`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `produit`
--
ALTER TABLE `produit`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `recette`
--
ALTER TABLE `recette`
  ADD PRIMARY KEY (`id_r`),
  ADD KEY `fk_recette_users` (`iduser`),
  ADD KEY `fk_recette_user` (`idu`);

--
-- Index pour la table `tag`
--
ALTER TABLE `tag`
  ADD PRIMARY KEY (`tag_id`),
  ADD UNIQUE KEY `tag_value_uniq` (`tag_value`);

--
-- Index pour la table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`idU`),
  ADD UNIQUE KEY `unique_email` (`email`),
  ADD KEY `fk_idA` (`idA`);

--
-- Index pour la table `user_nutrition`
--
ALTER TABLE `user_nutrition`
  ADD PRIMARY KEY (`id_nut`);

--
-- Index pour la table `vote`
--
ALTER TABLE `vote`
  ADD PRIMARY KEY (`id_vote`),
  ADD KEY `fk_part` (`idpart`),
  ADD KEY `fk_userr` (`idU`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `adresse`
--
ALTER TABLE `adresse`
  MODIFY `idA` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT pour la table `article`
--
ALTER TABLE `article`
  MODIFY `id_article` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `commande`
--
ALTER TABLE `commande`
  MODIFY `id_com` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `commentaire`
--
ALTER TABLE `commentaire`
  MODIFY `comment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT pour la table `defis`
--
ALTER TABLE `defis`
  MODIFY `id_d` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=83;

--
-- AUTO_INCREMENT pour la table `favorite_recipes`
--
ALTER TABLE `favorite_recipes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT pour la table `ingredient`
--
ALTER TABLE `ingredient`
  MODIFY `id_ing` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT pour la table `nutrition_recommandation`
--
ALTER TABLE `nutrition_recommandation`
  MODIFY `id_meals` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `panier`
--
ALTER TABLE `panier`
  MODIFY `idp` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT pour la table `participant`
--
ALTER TABLE `participant`
  MODIFY `idpart` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT pour la table `personne`
--
ALTER TABLE `personne`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT pour la table `produit`
--
ALTER TABLE `produit`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT pour la table `recette`
--
ALTER TABLE `recette`
  MODIFY `id_r` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `tag`
--
ALTER TABLE `tag`
  MODIFY `tag_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `idU` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT pour la table `vote`
--
ALTER TABLE `vote`
  MODIFY `id_vote` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `article`
--
ALTER TABLE `article`
  ADD CONSTRAINT `fk_user_id` FOREIGN KEY (`idU`) REFERENCES `user` (`idU`);

--
-- Contraintes pour la table `defis`
--
ALTER TABLE `defis`
  ADD CONSTRAINT `fk_id_u` FOREIGN KEY (`idU`) REFERENCES `user` (`idU`);

--
-- Contraintes pour la table `nutrition_recommandation`
--
ALTER TABLE `nutrition_recommandation`
  ADD CONSTRAINT `pk_id3` FOREIGN KEY (`user_id`) REFERENCES `user` (`idU`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `panier`
--
ALTER TABLE `panier`
  ADD CONSTRAINT `fk_idproduit` FOREIGN KEY (`id_produit`) REFERENCES `produit` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `participant`
--
ALTER TABLE `participant`
  ADD CONSTRAINT `fk_id_d` FOREIGN KEY (`id_d`) REFERENCES `defis` (`id_d`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_id_user` FOREIGN KEY (`idU`) REFERENCES `user` (`idU`);

--
-- Contraintes pour la table `recette`
--
ALTER TABLE `recette`
  ADD CONSTRAINT `fk_recette_user` FOREIGN KEY (`idu`) REFERENCES `user` (`idU`),
  ADD CONSTRAINT `fk_recette_users` FOREIGN KEY (`iduser`) REFERENCES `user` (`idU`);

--
-- Contraintes pour la table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `fk_idA` FOREIGN KEY (`idA`) REFERENCES `adresse` (`idA`);

--
-- Contraintes pour la table `vote`
--
ALTER TABLE `vote`
  ADD CONSTRAINT `fk_part` FOREIGN KEY (`idpart`) REFERENCES `participant` (`idpart`),
  ADD CONSTRAINT `fk_userr` FOREIGN KEY (`idU`) REFERENCES `user` (`idU`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
