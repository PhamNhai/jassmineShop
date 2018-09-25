ALTER TABLE `#__os_cck_entity_instance` ADD `instance_price` INT(11) NULL DEFAULT NULL;
ALTER TABLE `#__os_cck_entity_instance` ADD `instance_currency` VARCHAR(255) NULL DEFAULT NULL;
ALTER TABLE `#__os_cck_entity_instance` ADD `notreaded` int(1) NOT NULL DEFAULT '1';
ALTER TABLE `#__os_cck_entity_instance` ADD `featured_clicks` int(100) NOT NULL DEFAULT '-1';
ALTER TABLE `#__os_cck_entity_instance` ADD `featured_shows` int(100) NOT NULL DEFAULT '-1';
ALTER TABLE `#__os_cck_entity_field` ADD `params` LONGBLOB DEFAULT NULL;
ALTER TABLE `#__os_cck_entity` DROP `module`, DROP `description`, DROP `help`, DROP `teid`;
ALTER TABLE `#__os_cck_entity_field` DROP `global_settings`, DROP `required`, DROP `multiple`, DROP `db_storage`, DROP `module`, DROP `db_columns`, DROP `ordering`;
ALTER TABLE `#__os_cck_entity_field` ADD `show_in_instance_menu` BOOLEAN NOT NULL DEFAULT '0';
ALTER TABLE `#__os_cck_entity_field` ADD `db_field_name` varchar(255) NOT NULL DEFAULT '';
ALTER TABLE `#__os_cck_entity_field` CHANGE `field_name` `field_name` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT 'The machine-readable name of this field.';
--
-- Table structure for table `#__os_cck_orders`
--
CREATE TABLE IF NOT EXISTS `#__os_cck_orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fk_user_id` int(11) DEFAULT NULL,
  `fk_instance_id` int(11) DEFAULT NULL,
  `fk_request_id` int(11) DEFAULT NULL,
  `instance_type` varchar(255) NOT NULL DEFAULT '',
  `instance_title` varchar(255) NOT NULL DEFAULT '',
  `user_email` varchar(255) NOT NULL DEFAULT '',
  `user_name` varchar(255) NOT NULL DEFAULT '',
  `status` varchar(255) NOT NULL DEFAULT '',
  `order_date` DATETIME,
  `txn_type` varchar(255) NOT NULL DEFAULT '',
  `txn_id` varchar(255) NOT NULL DEFAULT '',
  `payer_id` varchar(255) NOT NULL DEFAULT '',
  `payer_status` varchar(255) NOT NULL DEFAULT '',
  `order_price` varchar(255) NOT NULL DEFAULT '',
  `order_currency` varchar(255) NOT NULL DEFAULT '',
  `paid_price` varchar(255) NOT NULL DEFAULT '',
  `paid_currency` varchar(255) NOT NULL DEFAULT '',
  `notreaded` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
    
--
-- Table structure for table `#__os_cck_orders_details`
--
CREATE TABLE IF NOT EXISTS `#__os_cck_orders_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fk_order_id` int(11) DEFAULT NULL,
  `fk_user_id` int(11) DEFAULT NULL,
  `fk_instance_id` int(11) DEFAULT NULL,
  `instance_title` varchar(255) NOT NULL DEFAULT '',
  `user_email` varchar(255) NOT NULL DEFAULT '',
  `user_name` varchar(255) NOT NULL DEFAULT '',
  `status` varchar(255) NOT NULL DEFAULT '',
  `order_date` DATETIME,
  `txn_type` varchar(255) NOT NULL DEFAULT '',
  `txn_id` varchar(255) NOT NULL DEFAULT '',
  `payer_id` varchar(255) NOT NULL DEFAULT '',
  `payer_status` varchar(255) NOT NULL DEFAULT '',
  `order_price` int(11) DEFAULT NULL,
  `order_currency` varchar(255) NOT NULL DEFAULT '',
  `payment_details` text NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;