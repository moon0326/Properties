# Dump of table properties_aggregate
# ------------------------------------------------------------

CREATE TABLE `properties_aggregate` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `pk` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `pk_value` int(11) NOT NULL,
  `cached_properties` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `properties_aggregate_pk_index` (`pk`),
  KEY `properties_aggregate_pk_value_index` (`pk_value`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Dump of table properties_decimal
# ------------------------------------------------------------

CREATE TABLE `properties_decimal` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `index_id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `value` decimal(14,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `properties_decimal_index_id_index` (`index_id`),
  KEY `properties_decimal_name_index` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Dump of table properties_integer
# ------------------------------------------------------------

CREATE TABLE `properties_integer` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `index_id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `value` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `properties_integer_index_id_index` (`index_id`),
  KEY `properties_integer_name_index` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Dump of table properties_text
# ------------------------------------------------------------

CREATE TABLE `properties_text` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `index_id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `value` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `properties_text_index_id_index` (`index_id`),
  KEY `properties_text_key_index` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Dump of table properties_varchar
# ------------------------------------------------------------

DROP TABLE IF EXISTS `properties_varchar`;

CREATE TABLE `properties_varchar` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `index_id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `value` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `properties_varchar_index_id_index` (`index_id`),
  KEY `properties_varchar_name_index` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
