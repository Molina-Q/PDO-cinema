-- --------------------------------------------------------
-- Hôte:                         127.0.0.1
-- Version du serveur:           8.0.30 - MySQL Community Server - GPL
-- SE du serveur:                Win64
-- HeidiSQL Version:             12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Listage de la structure de la base pour cinema
CREATE DATABASE IF NOT EXISTS `cinema` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `cinema`;

-- Listage de la structure de table cinema. acteur
CREATE TABLE IF NOT EXISTS `acteur` (
  `id_acteur` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) NOT NULL,
  `prenom` varchar(255) NOT NULL,
  `sexe` varchar(255) NOT NULL,
  `dateDeNaissance` datetime NOT NULL,
  PRIMARY KEY (`id_acteur`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Listage des données de la table cinema.acteur : ~17 rows (environ)
INSERT INTO `acteur` (`id_acteur`, `nom`, `prenom`, `sexe`, `dateDeNaissance`) VALUES
	(1, 'Hamill', 'Mark', 'Homme', '1951-09-25 00:00:00'),
	(2, 'Ford', 'Harrison', 'Homme', '1942-06-13 00:00:00'),
	(3, 'Fisher', 'Carrie', 'Femme', '1956-10-21 00:00:00'),
	(4, 'McGregor', 'Ewan', 'Homme', '1971-03-31 00:00:00'),
	(5, 'Chistensen', 'Hayden', 'Homme', '1981-04-19 00:00:00'),
	(6, 'Portman', 'Natalie', 'Femme', '1981-07-09 00:00:00'),
	(7, 'Bale', 'Christian', 'Homme', '1974-01-30 00:00:00'),
	(8, 'Caine', 'Michael', 'Homme', '1933-03-14 00:00:00'),
	(9, 'Oldman', 'Gary', 'Homme', '1958-03-21 00:00:00'),
	(10, 'Freeman', 'Morgan', 'Homme', '1937-07-01 00:00:00'),
	(11, 'Chalamet', 'Timothée', 'Homme', '1995-12-27 00:00:00'),
	(12, 'Hathaway', 'Anne', 'Femme', '1982-11-12 00:00:00'),
	(13, 'Foy', 'Mackenzie', 'Femme', '2000-11-10 00:00:00'),
	(14, 'Thurman', 'Uma', 'Femme', '1970-04-29 00:00:00'),
	(15, 'Liu', 'Lucy', 'Femme', '1968-12-02 00:00:00'),
	(16, 'Jackson', 'Samuel L.', 'Homme', '1948-12-21 00:00:00'),
	(17, 'Mikkelsen', 'Mads', 'Homme', '1965-11-22 00:00:00'),
	(18, 'Perlman', 'Ron', 'Homme', '1950-04-13 12:06:36'),
	(19, 'Clooney', 'George', 'Homme', '1961-05-06 17:30:05'),
	(20, 'Gough', 'Michael', 'Homme', '1916-11-23 17:31:56');

-- Listage de la structure de table cinema. casting
CREATE TABLE IF NOT EXISTS `casting` (
  `film_id` int NOT NULL,
  `acteur_id` int NOT NULL,
  `role_id` int NOT NULL,
  KEY `FK_casting_acteur` (`acteur_id`),
  KEY `FK_casting_film` (`film_id`),
  KEY `FK_casting_role` (`role_id`),
  CONSTRAINT `FK_casting_acteur` FOREIGN KEY (`acteur_id`) REFERENCES `acteur` (`id_acteur`),
  CONSTRAINT `FK_casting_film` FOREIGN KEY (`film_id`) REFERENCES `film` (`id_film`),
  CONSTRAINT `FK_casting_role` FOREIGN KEY (`role_id`) REFERENCES `role` (`id_role`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Listage des données de la table cinema.casting : ~25 rows (environ)
INSERT INTO `casting` (`film_id`, `acteur_id`, `role_id`) VALUES
	(1, 1, 1),
	(1, 2, 2),
	(1, 3, 3),
	(2, 4, 11),
	(2, 5, 12),
	(2, 6, 13),
	(2, 16, 4),
	(3, 7, 15),
	(3, 7, 14),
	(3, 9, 17),
	(3, 8, 16),
	(4, 12, 20),
	(4, 11, 10),
	(4, 13, 8),
	(5, 16, 7),
	(5, 14, 5),
	(5, 15, 6),
	(6, 2, 18),
	(6, 17, 19),
	(7, 18, 9),
	(3, 10, 21),
	(4, 8, 22),
	(8, 20, 16),
	(8, 19, 14),
	(8, 14, 23);

-- Listage de la structure de table cinema. film
CREATE TABLE IF NOT EXISTS `film` (
  `id_film` int NOT NULL AUTO_INCREMENT,
  `titre` varchar(255) NOT NULL,
  `dateDeSortie` datetime NOT NULL,
  `duree` time NOT NULL,
  `realisateur_id` int NOT NULL,
  `affiche` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT 'indisponible.jpg',
  PRIMARY KEY (`id_film`),
  KEY `FK__realisateur` (`realisateur_id`),
  CONSTRAINT `FK__realisateur` FOREIGN KEY (`realisateur_id`) REFERENCES `realisateur` (`id_realisateur`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Listage des données de la table cinema.film : ~8 rows (environ)
INSERT INTO `film` (`id_film`, `titre`, `dateDeSortie`, `duree`, `realisateur_id`, `affiche`) VALUES
	(1, 'Star wars : episode IV', '1997-10-12 11:56:20', '02:01:26', 1, 'Star_Wars_4'),
	(2, 'Star wars : episode III', '2005-05-18 14:07:40', '02:20:42', 1, 'Star_Wars_3'),
	(3, 'The Dark Knight', '2008-08-13 14:09:42', '02:32:42', 2, 'The_Dark_Knight'),
	(4, 'Interstellar', '2014-11-05 14:10:11', '02:49:12', 2, 'Interstellar'),
	(5, 'Kill Bill : Volume 1', '2003-10-10 00:00:00', '01:51:00', 4, 'Kill_Bill'),
	(6, 'Indiana Jones and the temple of doom', '1984-05-08 14:15:18', '01:58:18', 1, 'Indiana_Jones'),
	(7, 'Pacific Rim', '2013-06-17 12:00:28', '02:11:29', 3, 'Pacific_Rim'),
	(8, 'Batman & Robin', '1997-06-06 17:32:37', '02:05:56', 5, 'Batman_&_Robin');

-- Listage de la structure de table cinema. genre
CREATE TABLE IF NOT EXISTS `genre` (
  `id_genre` int NOT NULL AUTO_INCREMENT,
  `libelle` varchar(255) NOT NULL,
  PRIMARY KEY (`id_genre`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Listage des données de la table cinema.genre : ~8 rows (environ)
INSERT INTO `genre` (`id_genre`, `libelle`) VALUES
	(1, 'Super-héros'),
	(2, 'Science-fiction'),
	(3, 'Action'),
	(4, 'Horreur'),
	(5, 'Fantasy'),
	(6, 'Documentaire'),
	(7, 'Comédie'),
	(8, 'Drame');

-- Listage de la structure de table cinema. genre_film
CREATE TABLE IF NOT EXISTS `genre_film` (
  `film_id` int NOT NULL,
  `genre_id` int NOT NULL,
  KEY `FK__film_genre` (`film_id`),
  KEY `FK__genre` (`genre_id`),
  CONSTRAINT `FK__film_genre` FOREIGN KEY (`film_id`) REFERENCES `film` (`id_film`),
  CONSTRAINT `FK__genre` FOREIGN KEY (`genre_id`) REFERENCES `genre` (`id_genre`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Listage des données de la table cinema.genre_film : ~10 rows (environ)
INSERT INTO `genre_film` (`film_id`, `genre_id`) VALUES
	(6, 3),
	(2, 2),
	(2, 7),
	(1, 3),
	(1, 2),
	(3, 3),
	(3, 8),
	(5, 3),
	(4, 2),
	(3, 1),
	(7, 2),
	(8, 3),
	(8, 5);

-- Listage de la structure de table cinema. realisateur
CREATE TABLE IF NOT EXISTS `realisateur` (
  `id_realisateur` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) NOT NULL,
  `prenom` varchar(255) NOT NULL,
  `sexe` varchar(255) NOT NULL,
  `dateDeNaissance` date NOT NULL,
  PRIMARY KEY (`id_realisateur`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Listage des données de la table cinema.realisateur : ~4 rows (environ)
INSERT INTO `realisateur` (`id_realisateur`, `nom`, `prenom`, `sexe`, `dateDeNaissance`) VALUES
	(1, 'Lucas', 'George', 'Homme', '1944-05-14'),
	(2, 'Nolan', 'Christopher', 'Homme', '1970-06-30'),
	(3, 'del Toro', 'Guillermo', 'Homme', '1964-10-09'),
	(4, 'Tarantino', 'Quentin', 'Homme', '1963-03-27'),
	(5, 'Schumacher', 'Joel', 'Homme', '1939-08-29');

-- Listage de la structure de table cinema. role
CREATE TABLE IF NOT EXISTS `role` (
  `id_role` int NOT NULL AUTO_INCREMENT,
  `libelle` varchar(255) NOT NULL,
  PRIMARY KEY (`id_role`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Listage des données de la table cinema.role : ~23 rows (environ)
INSERT INTO `role` (`id_role`, `libelle`) VALUES
	(1, 'Luke Skywalker'),
	(2, 'Han Solo'),
	(3, 'Leia Organa'),
	(4, 'Mace Windu'),
	(5, 'The Bride'),
	(6, 'O-Ren Ishii'),
	(7, 'Rufus'),
	(8, 'Murph'),
	(9, 'Brand'),
	(10, 'Tom'),
	(11, 'Obiwan Kenobi'),
	(12, 'Anakin Skywalker'),
	(13, 'Padmé Amidala'),
	(14, 'Batman'),
	(15, 'Bruce Wayne'),
	(16, 'Alfred Pennyworth'),
	(17, 'James Gordon'),
	(18, 'Indiana Jones'),
	(19, 'Jurgen Voller'),
	(20, 'Hannibal Chau'),
	(21, 'Lucius Fox'),
	(22, 'Professor Brand'),
	(23, 'Poison Ivy');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
