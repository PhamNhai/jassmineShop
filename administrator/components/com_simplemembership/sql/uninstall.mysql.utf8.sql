
DELETE FROM `#__simplemembership_config`;

DELETE FROM `#__simplemembership_check`;

DELETE FROM `#__simplememberships`;

DELETE FROM `#__simplemembership_users`;

DELETE FROM `#__simplemembership_groups`;

DELETE FROM `#__simplemembership_group_joomgroup`;

DROP TABLE `#__simplemembership_config`;

DROP TABLE `#__simplemembership_check`;

DROP TABLE `#__simplememberships`;

DROP TABLE `#__simplemembership_users`;

DROP TABLE `#__simplemembership_groups`;

DROP TABLE `#__simplemembership_group_joomgroup`;

DROP TRIGGER IF EXISTS confirm_user