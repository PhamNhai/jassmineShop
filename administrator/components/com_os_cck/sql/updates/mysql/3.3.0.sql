CREATE TABLE IF NOT EXISTS `#__os_cck_version` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `current_vers` varchar(255) NOT NULL DEFAULT '',
  `last_vers` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;