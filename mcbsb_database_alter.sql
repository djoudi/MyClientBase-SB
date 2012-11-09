##
DROP TABLE IF EXISTS `groups`;

#
# Table structure for table 'groups'
#

CREATE TABLE `groups` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `description` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
);

#
# Dumping data for table 'groups'
#

INSERT INTO `groups` (`id`, `name`, `description`) VALUES
	(1,'admin','Administrator'),
	(2,'members','General User');



DROP TABLE IF EXISTS `users`;

#
# Table structure for table 'users'
#

CREATE TABLE `users` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `ip_address` varbinary(16) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(80) NOT NULL,
  `salt` varchar(40) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `activation_code` varchar(40) DEFAULT NULL,
  `forgotten_password_code` varchar(40) DEFAULT NULL,
  `forgotten_password_time` int(11) unsigned DEFAULT NULL,
  `remember_code` varchar(40) DEFAULT NULL,
  `created_on` int(11) unsigned NOT NULL,
  `last_login` int(11) unsigned DEFAULT NULL,
  `active` tinyint(1) unsigned DEFAULT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `company` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
);


#
# Dumping data for table 'users'
#

INSERT INTO `users` (`id`, `ip_address`, `username`, `password`, `salt`, `email`, `activation_code`, `forgotten_password_code`, `created_on`, `last_login`, `active`, `first_name`, `last_name`, `company`, `phone`) VALUES
	('1',0x7f000001,'administrator','59beecdf7fc966e2f17fd8f65a4a9aeb09d4a3d4','9462e8eee0','admin@admin.com','',NULL,'1268889823','1268889823','1', 'Admin','istrator','ADMIN','0');


DROP TABLE IF EXISTS `users_groups`;

#
# Table structure for table 'users_groups'
#

CREATE TABLE `users_groups` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` mediumint(8) unsigned NOT NULL,
  `group_id` mediumint(8) unsigned NOT NULL,
  PRIMARY KEY (`id`)
);

INSERT INTO `users_groups` (`id`, `user_id`, `group_id`) VALUES
	(1,1,1),
	(2,1,2);


DROP TABLE IF EXISTS `login_attempts`;

#
# Table structure for table 'login_attempts'
#

CREATE TABLE `login_attempts` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `ip_address` varbinary(16) NOT NULL,
  `login` varchar(100) NOT NULL,
  `time` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
);

ALTER TABLE  `users` CHANGE  `id`  `id` MEDIUMINT( 8 ) UNSIGNED NOT NULL;
ALTER TABLE  `users` CHANGE  `id`  `id` MEDIUMINT( 8 ) UNSIGNED NULL;
ALTER TABLE  `users` CHANGE  `id`  `id` INT( 20 ) UNSIGNED NOT NULL DEFAULT  '0';
ALTER TABLE  `users` ADD  `preferred_language` VARCHAR( 50 ) NULL DEFAULT  'english' AFTER  `phone`;
TRUNCATE TABLE `users`;
TRUNCATE TABLE `users_groups`;

DROP TABLE IF EXISTS `mcb_users`;

ALTER TABLE `mcb_modules` DROP `module_core`;

ALTER TABLE `mcb_modules` CHANGE `module_description` `module_description` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '',
CHANGE `module_author` `module_author` VARCHAR( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '',
CHANGE `module_homepage` `module_homepage` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '',
CHANGE `module_version` `module_version` VARCHAR( 25 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '',
CHANGE `module_available_version` `module_available_version` VARCHAR( 25 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '';

ALTER TABLE `mcb_modules` CHANGE `module_enabled` `module_enabled` INT( 1 ) NULL DEFAULT '0',
CHANGE `module_change_status` `module_change_status` INT( 1 ) NULL DEFAULT '1';

ALTER TABLE `mcb_modules` ADD `module_top_menu` INT( 1 ) NOT NULL DEFAULT '1' AFTER `module_order`;

TRUNCATE TABLE `mcb_modules`;

DROP TABLE `mcb_contacts`;

UPDATE `mcb_data` SET `mcb_value` = 'MCBSB' WHERE `mcb_data`.`mcb_key` = "application_title";
