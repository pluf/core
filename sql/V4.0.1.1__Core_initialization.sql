
CREATE TABLE `pluf_queue` (
  `id` mediumint(9) unsigned NOT NULL AUTO_INCREMENT,
  `version` int(11) NOT NULL DEFAULT 0,
  `model_class` varchar(150) NOT NULL DEFAULT '',
  `model_id` int(11) NOT NULL DEFAULT 0,
  `action` varchar(150) NOT NULL DEFAULT '',
  `lock` int(11) NOT NULL DEFAULT 0,
  `creation_dtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modif_dtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `tenant` mediumint(9) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `lock_idx` (`tenant`,`lock`),
  KEY `tenant_foreignkey_idx` (`tenant`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `pluf_search_occs` (
  `id` mediumint(9) unsigned NOT NULL AUTO_INCREMENT,
  `word` mediumint(9) unsigned NOT NULL DEFAULT 0,
  `model_class` varchar(150) NOT NULL DEFAULT '',
  `model_id` int(11) NOT NULL DEFAULT 0,
  `occ` int(11) NOT NULL DEFAULT 0,
  `pondocc` decimal(32,8) NOT NULL DEFAULT 0.00000000,
  `tenant` mediumint(9) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `model_class_id_combo_word_idx` (`tenant`,`model_class`,`model_id`,`word`),
  KEY `word_foreignkey_idx` (`word`),
  KEY `tenant_foreignkey_idx` (`tenant`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `pluf_search_stats` (
  `id` mediumint(9) unsigned NOT NULL AUTO_INCREMENT,
  `model_class` varchar(150) NOT NULL DEFAULT '',
  `model_id` int(11) NOT NULL DEFAULT 0,
  `indexations` int(11) NOT NULL DEFAULT 0,
  `creation_dtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modif_dtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `tenant` mediumint(9) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `model_class_id_combo_idx` (`tenant`,`model_class`,`model_id`),
  KEY `tenant_foreignkey_idx` (`tenant`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `pluf_search_words` (
  `id` mediumint(9) unsigned NOT NULL AUTO_INCREMENT,
  `word` varchar(150) NOT NULL DEFAULT '',
  `tenant` mediumint(9) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `word_unique_idx` (`tenant`,`word`),
  KEY `tenant_foreignkey_idx` (`tenant`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `schema_info` (
  `id` mediumint(9) unsigned NOT NULL AUTO_INCREMENT,
  `application` varchar(150) NOT NULL DEFAULT '',
  `version` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `application_unique_idx` (`application`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;





CREATE TABLE `sessions` (
  `id` mediumint(9) unsigned NOT NULL AUTO_INCREMENT,
  `version` int(11) NOT NULL DEFAULT 0,
  `session_key` varchar(100) NOT NULL DEFAULT '',
  `session_data` longtext NOT NULL,
  `expire` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `tenant` mediumint(9) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `session_key_idx` (`tenant`,`session_key`),
  KEY `tenant_foreignkey_idx` (`tenant`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



CREATE TABLE `tenants` (
  `id` mediumint(9) unsigned NOT NULL AUTO_INCREMENT,
  `level` int(11) NOT NULL DEFAULT 0,
  `title` varchar(100) NOT NULL DEFAULT '',
  `description` varchar(1024) DEFAULT '',
  `domain` varchar(63) DEFAULT '',
  `subdomain` varchar(63) NOT NULL DEFAULT '',
  `validate` tinyint(1) NOT NULL DEFAULT 0,
  `email` varchar(150) NOT NULL DEFAULT '',
  `phone` varchar(150) NOT NULL DEFAULT '',
  `creation_dtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modif_dtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `subdomain_unique_idx` (`subdomain`),
  UNIQUE KEY `domain_unique_idx` (`domain`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
