# ************************************************************
# Sequel Pro SQL dump
# Version 4096
#
# http://www.sequelpro.com/
# http://code.google.com/p/sequel-pro/
#
# Host: localhost (MySQL 5.5.34)
# Database: phpvideo
# Generation Time: 2014-07-04 10:36:34 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table values_aggregate
# ------------------------------------------------------------

CREATE TABLE `values_aggregate` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `pk` varchar(255) DEFAULT NULL,
  `pk_value` int(11) DEFAULT NULL,
  `cached_value` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table values_decimal
# ------------------------------------------------------------

CREATE TABLE `values_decimal` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `index_id` int(11) DEFAULT NULL,
  `key` varchar(60) DEFAULT NULL,
  `value` decimal(13,2) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table values_integer
# ------------------------------------------------------------

CREATE TABLE `values_integer` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `index_id` int(11) DEFAULT NULL,
  `key` varchar(60) DEFAULT NULL,
  `value` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table values_text
# ------------------------------------------------------------

CREATE TABLE `values_text` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `index_id` int(11) DEFAULT NULL,
  `key` varchar(60) DEFAULT NULL,
  `value` text,
  `type` varchar(4) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table values_varchar
# ------------------------------------------------------------

CREATE TABLE `values_varchar` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `index_id` int(11) DEFAULT NULL,
  `key` varchar(60) DEFAULT NULL,
  `value` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;




/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
