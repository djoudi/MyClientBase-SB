##
DROP TABLE IF EXISTS `ci_sessions`;
CREATE TABLE IF NOT EXISTS  `ci_sessions` (
	session_id varchar(40) DEFAULT '0' NOT NULL,
	ip_address varchar(45) DEFAULT '0' NOT NULL,
	user_agent varchar(120) NOT NULL,
	last_activity int(10) unsigned DEFAULT 0 NOT NULL,
	user_data text NOT NULL,
	PRIMARY KEY (session_id),
	KEY `last_activity_idx` (`last_activity`)
);

DROP TABLE IF EXISTS `devices`;

DROP TABLE IF EXISTS `devices_ori`;

DROP TABLE IF EXISTS `mcb_activities`;

DROP TABLE IF EXISTS `otrs`;
CREATE TABLE IF NOT EXISTS `otrs` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `object_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `object_id` int(11) unsigned DEFAULT NULL,
  `colleague_id` int(11) unsigned DEFAULT NULL,
  `colleague_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `creation_date` int(11) unsigned DEFAULT NULL,
  `created_by` double DEFAULT NULL,
  `creator` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;


DROP TABLE IF EXISTS `assets_tasks`;
CREATE TABLE IF NOT EXISTS `assets_tasks` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `assets_id` int(11) unsigned DEFAULT NULL,
  `tasks_id` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UQ_c6b2fba78717cd8ef63d70c853a00f06ad5eafdb` (`assets_id`,`tasks_id`),
  KEY `index_for_assets_tasks_assets_id` (`assets_id`),
  KEY `index_for_assets_tasks_tasks_id` (`tasks_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

  
DROP TABLE IF EXISTS `assets`;
CREATE TABLE IF NOT EXISTS `assets` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `brand` varchar(70) COLLATE utf8_unicode_ci NOT NULL,
  `model` varchar(70) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `details` text COLLATE utf8_unicode_ci,
  `category` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `purchase_date` date DEFAULT NULL,
  `price` float(11,2) DEFAULT NULL,
  `value` float(11,2) DEFAULT NULL,
  `serial` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `code_number` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `registration_number` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `network_device` tinyint(1) DEFAULT NULL,
  `network_name` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ip` varchar(39) COLLATE utf8_unicode_ci DEFAULT NULL,
  `mac_address` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `insurance` text COLLATE utf8_unicode_ci,
  `openvpn_certificate` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `operating_system` varchar(70) COLLATE utf8_unicode_ci DEFAULT NULL,
  `storage_space` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ram` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `contact_id_key` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `contact_id` double DEFAULT NULL,
  `contact_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `creation_date` int(11) unsigned DEFAULT NULL,
  `created_by` double DEFAULT NULL,
  `creator` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `update_date` int(11) unsigned DEFAULT NULL,
  `updated_by` double DEFAULT NULL,
  `editor` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;


DROP TABLE IF EXISTS `tasks`;
CREATE TABLE IF NOT EXISTS `tasks` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `task` text COLLATE utf8_unicode_ci,
  `details` text COLLATE utf8_unicode_ci,
  `where` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `city` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `budget` double(9,2) DEFAULT NULL,
  `hours_budget` double(9,2) DEFAULT NULL,
  `urgent` tinyint(3) unsigned DEFAULT NULL,
  `creation_date` int(11) unsigned DEFAULT NULL,
  `created_by` double DEFAULT NULL,
  `creator` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `update_date` int(11) unsigned DEFAULT NULL,
  `updated_by` double DEFAULT NULL,
  `editor` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `contact_id_key` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `contact_id` double DEFAULT NULL,
  `contact_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `complete_date` date DEFAULT NULL,
  `completed_by` double DEFAULT NULL,
  `completionist` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `endnote` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;


ALTER TABLE `assets_tasks`
  ADD CONSTRAINT `assets_tasks_ibfk_1` FOREIGN KEY (`assets_id`) REFERENCES `assets` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `assets_tasks_ibfk_2` FOREIGN KEY (`tasks_id`) REFERENCES `tasks` (`id`) ON DELETE CASCADE;
  
DROP TABLE IF EXISTS `appointments`;
CREATE TABLE IF NOT EXISTS `appointments` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `tasks_id` int(11) DEFAULT NULL,
  `what` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `where` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `start_time` int(11) unsigned DEFAULT NULL,
  `end_time` int(11) unsigned DEFAULT NULL,
  `creator_is_owner` tinyint(1) NOT NULL DEFAULT '0',
  `creation_date` int(11) unsigned DEFAULT NULL,
  `created_by` double DEFAULT NULL,
  `creator` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `update_date` int(11) unsigned DEFAULT NULL,
  `updated_by` double DEFAULT NULL,
  `editor` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
  
DROP TABLE IF EXISTS `activities`;
CREATE TABLE IF NOT EXISTS `activities` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `activity` text COLLATE utf8_unicode_ci,
  `note` text COLLATE utf8_unicode_ci,
  `action_date` date NOT NULL,
  `duration` float(5,2) DEFAULT NULL,
  `mileage` float(6,1) DEFAULT NULL,
  `billable` tinyint(1) NOT NULL DEFAULT '0',
  `weight` int(3) unsigned DEFAULT NULL,
  `tasks_id` int(11) unsigned DEFAULT NULL,
  `contact_id_key` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `contact_id` double DEFAULT NULL,
  `contact_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `creation_date` int(11) unsigned DEFAULT NULL,
  `created_by` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `creator` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `update_date` int(11) unsigned DEFAULT NULL,
  `updated_by` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `editor` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;


DROP TABLE IF EXISTS `gtokens`;
CREATE TABLE IF NOT EXISTS `gtokens` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_token` text COLLATE utf8_unicode_ci,
  `email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `access_token` text COLLATE utf8_unicode_ci,
  `token_type` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `expires_in` int(11) unsigned DEFAULT NULL,
  `refresh_token` text COLLATE utf8_unicode_ci,
  `contact_id_key` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `contact_id` double DEFAULT NULL,
  `contact_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `creation_date` int(11) unsigned DEFAULT NULL,
  `created_by` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `creator` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `update_date` int(11) unsigned DEFAULT NULL,
  `updated_by` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `editor` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;


DROP TABLE IF EXISTS `ogrs`;
CREATE TABLE IF NOT EXISTS `ogrs` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `google_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `google_name` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `google_mimeType` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `google_url` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `google_icon_url` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `google_resource_name` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `google_resource_id` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `object_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `object_id` int(11) unsigned DEFAULT NULL,
  `creation_date` int(11) unsigned DEFAULT NULL,
  `created_by` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `creator` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `update_date` int(11) unsigned DEFAULT NULL,
  `updated_by` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `editor` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;


DROP TABLE IF EXISTS `routes`;
CREATE TABLE IF NOT EXISTS `routes` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `city` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `route_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `contact_id_key` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `contact_id` double DEFAULT NULL,
  `contact_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `creation_date` int(11) unsigned DEFAULT NULL,
  `created_by` double DEFAULT NULL,
  `creator` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `update_date` int(11) unsigned DEFAULT NULL,
  `updated_by` double DEFAULT NULL,
  `editor` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;