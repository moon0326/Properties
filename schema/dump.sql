# Dump of table properties_aggregate
# ------------------------------------------------------------
DROP TABLE IF EXISTS `properties_aggregate`;

CREATE TABLE `properties_aggregate` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `pk` varchar(255) DEFAULT NULL,
  `pk_value` int(11) DEFAULT NULL,
  `cached_value` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

# Dump of table properties_decimal
# ------------------------------------------------------------
DROP TABLE IF EXISTS `properties_decimal`;

CREATE TABLE `properties_decimal` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `index_id` int(11) DEFAULT NULL,
  `key` varchar(60) DEFAULT NULL,
  `value` decimal(13,2) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

# Dump of table properties_integer
# ------------------------------------------------------------
DROP TABLE IF EXISTS `properties_integer`;

CREATE TABLE `properties_integer` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `index_id` int(11) DEFAULT NULL,
  `key` varchar(60) DEFAULT NULL,
  `value` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

# Dump of table properties_text
# ------------------------------------------------------------
DROP TABLE IF EXISTS `properties_text`;

CREATE TABLE `properties_text` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `index_id` int(11) DEFAULT NULL,
  `key` varchar(60) DEFAULT NULL,
  `value` text,
  `type` varchar(4) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

# Dump of table properties_varchar
# ------------------------------------------------------------
DROP TABLE IF EXISTS `properties_varchar`;

CREATE TABLE `properties_varchar` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `index_id` int(11) DEFAULT NULL,
  `key` varchar(60) DEFAULT NULL,
  `value` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


