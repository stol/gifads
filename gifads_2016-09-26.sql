# ************************************************************
# Sequel Pro SQL dump
# Version 4499
#
# http://www.sequelpro.com/
# https://github.com/sequelpro/sequelpro
#
# Hôte: localhost (MySQL 5.5.27)
# Base de données: gifads
# Temps de génération: 2016-09-26 08:36:24 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Affichage de la table ad
# ------------------------------------------------------------

DROP TABLE IF EXISTS `ad`;

CREATE TABLE `ad` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `originalName` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

LOCK TABLES `ad` WRITE;
/*!40000 ALTER TABLE `ad` DISABLE KEYS */;

INSERT INTO `ad` (`id`, `slug`, `originalName`)
VALUES
	(1,'loreal.gif','loreal.gif'),
	(2,'tefal.gif','tefal.gif'),
	(3,'burger-king.gif','burger-king.gif');

/*!40000 ALTER TABLE `ad` ENABLE KEYS */;
UNLOCK TABLES;


# Affichage de la table gif
# ------------------------------------------------------------

DROP TABLE IF EXISTS `gif`;

CREATE TABLE `gif` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `originalName` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

LOCK TABLES `gif` WRITE;
/*!40000 ALTER TABLE `gif` DISABLE KEYS */;

INSERT INTO `gif` (`id`, `originalName`)
VALUES
	(1,'giphy (1).gif'),
	(2,'giphy (2).gif'),
	(3,'giphy (3).gif'),
	(4,'giphy (4).gif'),
	(5,'giphy (5).gif'),
	(6,'giphy (6).gif'),
	(7,'giphy (7).gif'),
	(8,'giphy (8).gif');

/*!40000 ALTER TABLE `gif` ENABLE KEYS */;
UNLOCK TABLES;


# Affichage de la table version
# ------------------------------------------------------------

DROP TABLE IF EXISTS `version`;

CREATE TABLE `version` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `hash` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `gif_id` int(11) NOT NULL,
  `ad_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

LOCK TABLES `version` WRITE;
/*!40000 ALTER TABLE `version` DISABLE KEYS */;

INSERT INTO `version` (`id`, `hash`, `gif_id`, `ad_id`)
VALUES
	(1,'3088e88973db7d3cb77377c09c14dac0',1,1),
	(2,'b46d4e02a64655f2d76d59621f8b2d66',1,2),
	(3,'bb7c4bc261289754e58aa5d57e335269',1,3),
	(4,'',2,1),
	(5,'47dbc392f1c8d4ced6eae398ec00666a',2,2),
	(6,'c0609d38081df603aead4afeab538c4f',2,3),
	(7,'',3,1),
	(8,'795e287ea99e1e553d8558651cd87697',3,2),
	(9,'',3,3),
	(10,'',4,1),
	(11,'72dfff5af87b411f1e820c77750144d7',4,2),
	(12,'6a9530dfab80db2e38be618c1d444084',4,3),
	(13,'0d22640a6f3c25549736c6b2f7229b66',5,1),
	(14,'d4e877edd84bf3ebe15200f51b230f1e',5,2),
	(15,'8738f63269e44d2fdf62f860d6e3fcfd',5,3),
	(16,'f742f0a5a82b7cf965e026d476926667',6,1),
	(17,'',6,2),
	(18,'101510f235268430ff844ab79565f1fe',6,3),
	(19,'9b573053b9d03835c4349458e262f335',7,1),
	(20,'',7,2),
	(21,'4d37e0c41aa35dc5e01c2b32ad8e4cf5',7,3);

/*!40000 ALTER TABLE `version` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
