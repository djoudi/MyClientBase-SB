##
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
