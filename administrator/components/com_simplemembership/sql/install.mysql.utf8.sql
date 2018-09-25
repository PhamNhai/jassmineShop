
--
-- Table structure for table `#__simplemembership_users`
--
CREATE TABLE IF NOT EXISTS `#__simplemembership_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fk_users_id` int(11) DEFAULT NULL,
  `name` varchar(255) NOT NULL DEFAULT '',
  `username` varchar(255) NOT NULL DEFAULT '',
  `email` varchar(100) NOT NULL DEFAULT '',
  `block` tinyint(4) NOT NULL DEFAULT '0',
  `last_approved` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `current_gid` int(11) NOT NULL,
  `current_gname` varchar(255) NOT NULL DEFAULT '',
  `want_gid` int(11) NOT NULL,
  `want_gname` varchar(255) NOT NULL,
  `approved` tinyint(4) NOT NULL DEFAULT '1',
  `expire_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8; 


--
-- Table structure for table `#__simplemembership_orders`
--
CREATE TABLE IF NOT EXISTS `#__simplemembership_orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fk_sm_users_id` int(11) DEFAULT NULL,
  `fk_sm_users_email` varchar(255) NOT NULL DEFAULT '',
  `fk_sm_users_name` varchar(255) NOT NULL DEFAULT '',
  `status` varchar(255) NOT NULL DEFAULT '',
  `order_date` DATETIME,
  `fk_group_id` int(11) DEFAULT NULL,
  `txn_type` varchar(255) NOT NULL DEFAULT '',
  `txn_id` varchar(255) NOT NULL DEFAULT '',
  `payer_id` varchar(255) NOT NULL DEFAULT '',
  `payer_status` varchar(255) NOT NULL DEFAULT '',
  `order_price` DOUBLE NOT NULL,
  `order_currency_code` varchar(5) NOT NULL DEFAULT '',
PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8; 



--
-- Table structure for table `#__simplemembership_orders_details`
--
CREATE TABLE IF NOT EXISTS `#__simplemembership_orders_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fk_sm_users_id` int(11) DEFAULT NULL,
  `fk_order_id` int(11) DEFAULT NULL,
  `fk_sm_users_email` varchar(255) NOT NULL DEFAULT '',
  `fk_sm_users_name` varchar(255) NOT NULL DEFAULT '',
  `status` varchar(255) NOT NULL DEFAULT '',
  `order_date` DATETIME,
  `fk_group_id` int(11) DEFAULT NULL,
  `txn_type` varchar(255) NOT NULL DEFAULT '',
  `txn_id` varchar(255) NOT NULL DEFAULT '',
  `payer_id` varchar(255) NOT NULL DEFAULT '',
  `payer_status` varchar(255) NOT NULL DEFAULT '',
  `order_price` DOUBLE NOT NULL,
  `order_currency_code` varchar(5) NOT NULL DEFAULT '',
  `payment_details` text NOT NULL DEFAULT '',
PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8; 


--
-- Table structure for table `#__simplemembership_groups`
--
CREATE TABLE IF NOT EXISTS `#__simplemembership_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `price` double NOT NULL,
  `currency_code` varchar(3) NOT NULL,
  `name` varchar(150) NOT NULL,
  `acl_group` varchar(150) NOT NULL,
  `acl_gid` int(11) NOT NULL,
  `expire_range` int(11) NOT NULL,
  `expire_units` varchar(3) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_name` varchar(250) NOT NULL,
  `auto_approve` tinyint(4) DEFAULT '0',
  `link` text NOT NULL,
  `notes` text NOT NULL,
  `published` tinyint(4) DEFAULT '1',
PRIMARY KEY (`id`),
UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8; 


--
-- Table structure for table `#__simplememberships`
--
CREATE TABLE IF NOT EXISTS `#__simplememberships` (
  `id` mediumint(9) NOT NULL AUTO_INCREMENT,
  `invitee_name` varchar(100) NOT NULL DEFAULT '',
  `invitee_email` varchar(50) NOT NULL DEFAULT '',
  `invited_by_name` varchar(100) NOT NULL DEFAULT '',
  `invited_by_email` varchar(50) NOT NULL DEFAULT '',
  `to_be_invited` smallint(6) NOT NULL DEFAULT '0',
  `last_sent` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `msg` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `checked_out` int(11) NOT NULL DEFAULT '0',
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
PRIMARY KEY (`id`),
UNIQUE KEY `invitee_email` (`invitee_email`,`invited_by_email`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


--
-- Table structure for table `#__simplemembership_config`
--
CREATE TABLE IF NOT EXISTS `#__simplemembership_config` (
  `id` mediumint(9) NOT NULL AUTO_INCREMENT,
  `msg` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `auto_invites` smallint(6) NOT NULL DEFAULT '0',
  `send_after` smallint(6) NOT NULL DEFAULT '30',
  `email_from_user` tinyint(4) NOT NULL DEFAULT '1',
  `bcc_admin` tinyint(4) NOT NULL DEFAULT '0',
  `custom_subject` varchar(150) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `use_custom_msg` tinyint(4) NOT NULL DEFAULT '1',
PRIMARY KEY (`id`)
)ENGINE=MyISAM  DEFAULT CHARSET=utf8;


--
-- Table structure for table `#__simplemembership_group_joomgroup`
--
CREATE TABLE IF NOT EXISTS `#__simplemembership_group_joomgroup` (
   `mgroup_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Foreign Key to #__simlemembership_groups.id',
   `jgroup_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Foreign Key to #__usergroups.id',
    PRIMARY KEY (`mgroup_id`,`jgroup_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


--
-- Table structure for table `#__simplemembership_check`
--
CREATE TABLE IF NOT EXISTS `#__simplemembership_check` (
  `id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `last_check` datetime NOT NULL,
UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


INSERT IGNORE INTO `#__simplemembership_check` (id, `last_check`) VALUES ('1','0000-00-00 00:00:00');
