SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

CREATE TABLE IF NOT EXISTS `appointments` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `task_id` double DEFAULT NULL,
  `what` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `where` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `start_time` int(11) unsigned DEFAULT NULL,
  `end_time` int(11) unsigned DEFAULT NULL,
  `creation_date` int(11) unsigned DEFAULT NULL,
  `created_by` double DEFAULT NULL,
  `creator` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `update_date` int(11) unsigned DEFAULT NULL,
  `updated_by` double DEFAULT NULL,
  `editor` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `devices` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `category` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `brand` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `model` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `serial` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `code_number` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `registration_number` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `purchase_date` date DEFAULT NULL,
  `under_warranty` tinyint(3) unsigned DEFAULT NULL,
  `insurance` text COLLATE utf8_unicode_ci,
  `details` text COLLATE utf8_unicode_ci,
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `groups` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `description` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

INSERT INTO `groups` (`id`, `name`, `description`) VALUES
(1, 'tj_admin', 'Tooljar Administrator'),
(2, 'admin', 'Administrator'),
(3, 'members', 'User');

CREATE TABLE IF NOT EXISTS `login_attempts` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `ip_address` varbinary(16) NOT NULL,
  `login` varchar(100) NOT NULL,
  `time` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `mcb_activities` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `task_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `date` int(11) DEFAULT NULL,
  `description` text NOT NULL,
  `duration` float(8,2) NOT NULL DEFAULT '0.00',
  `mileage` int(11) NOT NULL DEFAULT '0',
  `tag` varchar(250) DEFAULT NULL,
  `task_weight` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `task_id` (`task_id`,`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `mcb_client_credits` (
  `client_credit_id` int(11) NOT NULL AUTO_INCREMENT,
  `client_credit_client_id` int(11) NOT NULL,
  `client_credit_date` varchar(14) NOT NULL DEFAULT '',
  `client_credit_amount` decimal(10,2) NOT NULL,
  `client_credit_note` longtext NOT NULL,
  PRIMARY KEY (`client_credit_id`),
  KEY `client_credit_client_id` (`client_credit_client_id`,`client_credit_date`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `mcb_client_data` (
  `mcb_client_data_id` int(11) NOT NULL AUTO_INCREMENT,
  `client_id` int(11) NOT NULL DEFAULT '0',
  `mcb_client_key` varchar(50) NOT NULL DEFAULT '',
  `mcb_client_value` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`mcb_client_data_id`),
  KEY `client_id` (`client_id`),
  KEY `mcb_client_key` (`mcb_client_key`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `mcb_data` (
  `mcb_data_id` int(11) NOT NULL AUTO_INCREMENT,
  `mcb_key` varchar(50) NOT NULL DEFAULT '',
  `mcb_value` varchar(100) DEFAULT '',
  PRIMARY KEY (`mcb_data_id`),
  UNIQUE KEY `mcb_data_key` (`mcb_key`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=74 ;

INSERT INTO `mcb_data` (`mcb_data_id`, `mcb_key`, `mcb_value`) VALUES
(1, 'default_tax_rate_id', '1'),
(2, 'default_item_tax_rate_id', '1'),
(3, 'currency_symbol', '€'),
(4, 'dashboard_show_open_invoices', 'TRUE'),
(5, 'dashboard_show_closed_invoices', 'TRUE'),
(6, 'default_date_format', 'Y-m-d'),
(7, 'default_date_format_mask', '9999-99-99'),
(8, 'default_date_format_picker', 'yy-mm-dd'),
(9, 'default_invoice_template', 'default'),
(10, 'currency_symbol_placement', 'after'),
(11, 'invoices_due_after', '30'),
(12, 'pdf_plugin', 'dompdf'),
(13, 'email_protocol', 'smtp'),
(14, 'dashboard_show_pending_invoices', 'TRUE'),
(15, 'default_open_status_id', '1'),
(16, 'default_closed_status_id', '3'),
(17, 'default_language', 'English'),
(18, 'include_logo_on_invoice', 'FALSE'),
(19, 'dashboard_show_overdue_invoices', 'TRUE'),
(20, 'decimal_taxes_num', '2'),
(21, 'default_receipt_template', 'default'),
(22, 'dashboard_override', ''),
(23, 'decimal_symbol', ','),
(24, 'thousands_separator', '.'),
(25, 'default_quote_template', 'default'),
(26, 'results_per_page', '15'),
(27, 'display_quantity_decimals', '0'),
(28, 'default_invoice_group_id', '1'),
(29, 'disable_invoice_audit_history', '0'),
(30, 'default_quote_group_id', '1'),
(32, 'application_title', 'MCB-SB'),
(33, 'cc_enable_client_tax_id', '0'),
(34, 'cc_enable_client_address', '0'),
(35, 'cc_enable_client_address_2', '0'),
(36, 'cc_enable_client_city', '0'),
(37, 'cc_enable_client_state', '0'),
(38, 'cc_enable_client_zip', '0'),
(39, 'cc_enable_client_country', '0'),
(40, 'cc_enable_client_phone_number', '0'),
(41, 'cc_enable_client_fax_number', '0'),
(42, 'cc_enable_client_mobile_number', '0'),
(43, 'cc_enable_client_email_address', '0'),
(44, 'cc_enable_client_web_address', '0'),
(45, 'cc_edit_enabled', '0'),
(46, 'cron_key', ''),
(47, 'dashboard_total_paid_cutoff_date', '0'),
(48, 'default_bcc', ''),
(49, 'default_cc', ''),
(50, 'default_email_body', '0'),
(51, 'default_tax_rate_option', '2'),
(52, 'email_body', ''),
(53, 'email_footer', ''),
(54, 'enable_profiler', '0'),
(55, 'sendmail_path', ''),
(56, 'smtp_host', 'mail.squadrainformatica.com'),
(57, 'smtp_pass', 'C4mmeLL0.C4mmeLLat0'),
(58, 'smtp_port', '25'),
(59, 'smtp_timeout', '100'),
(60, 'smtp_user', 'd.venturin@squadrainformatica.com'),
(61, 'dashboard_show_quotes', 'TRUE'),
(62, 'default_item_tax_option', '1'),
(63, 'default_apply_invoice_tax', '1'),
(64, 'default_invoice_email_template', ''),
(65, 'default_overdue_invoice_email_template', ''),
(66, 'dashboard_show_open_tasks', 'TRUE'),
(67, 'default_payment_method', '1'),
(68, 'merchant_enabled', '0'),
(69, 'merchant_driver', 'Paypal'),
(70, 'merchant_account_id', ''),
(71, 'merchant_currency_code', ''),
(73, 'smtp_security', 'tls');

CREATE TABLE IF NOT EXISTS `mcb_email_templates` (
  `email_template_id` int(11) NOT NULL AUTO_INCREMENT,
  `email_template_user_id` int(11) NOT NULL,
  `email_template_title` varchar(50) NOT NULL DEFAULT '',
  `email_template_body` longtext NOT NULL,
  `email_template_footer` longtext NOT NULL,
  PRIMARY KEY (`email_template_id`),
  KEY `email_template_user_id` (`email_template_user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `mcb_fields` (
  `field_id` int(11) NOT NULL AUTO_INCREMENT,
  `object_id` int(11) NOT NULL DEFAULT '0',
  `field_name` varchar(50) NOT NULL DEFAULT '',
  `field_index` int(11) NOT NULL DEFAULT '0',
  `column_name` varchar(25) NOT NULL DEFAULT '',
  PRIMARY KEY (`field_id`),
  KEY `object_id` (`object_id`),
  KEY `field_index` (`field_index`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `mcb_inventory` (
  `inventory_id` int(11) NOT NULL AUTO_INCREMENT,
  `inventory_type_id` int(11) NOT NULL DEFAULT '0',
  `inventory_tax_rate_id` int(11) NOT NULL DEFAULT '0',
  `inventory_name` varchar(255) NOT NULL DEFAULT '',
  `inventory_unit_price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `inventory_description` longtext,
  `inventory_track_stock` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`inventory_id`),
  KEY `inventory_type_id` (`inventory_type_id`),
  KEY `inventory_tax_rate_id` (`inventory_tax_rate_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `mcb_inventory_stock` (
  `inventory_stock_id` int(11) NOT NULL AUTO_INCREMENT,
  `inventory_id` int(11) NOT NULL DEFAULT '0',
  `invoice_item_id` int(11) NOT NULL DEFAULT '0',
  `inventory_stock_quantity` decimal(10,2) NOT NULL DEFAULT '0.00',
  `inventory_stock_date` varchar(14) NOT NULL DEFAULT '',
  `inventory_stock_notes` longtext,
  PRIMARY KEY (`inventory_stock_id`),
  KEY `inventory_id` (`inventory_id`),
  KEY `invoice_item_id` (`invoice_item_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `mcb_inventory_types` (
  `inventory_type_id` int(11) NOT NULL AUTO_INCREMENT,
  `inventory_type` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`inventory_type_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `mcb_invoices` (
  `invoice_id` int(11) NOT NULL AUTO_INCREMENT,
  `client_id_key` varchar(10) NOT NULL DEFAULT '',
  `client_id` int(11) NOT NULL DEFAULT '0',
  `user_id` int(11) NOT NULL DEFAULT '0',
  `invoice_status_id` int(11) NOT NULL DEFAULT '0',
  `invoice_date_entered` varchar(25) NOT NULL DEFAULT '',
  `invoice_number` varchar(50) NOT NULL DEFAULT '',
  `invoice_notes` longtext,
  `invoice_due_date` varchar(14) NOT NULL DEFAULT '',
  `invoice_is_quote` int(1) NOT NULL DEFAULT '0',
  `invoice_group_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`invoice_id`),
  KEY `client_id` (`client_id`),
  KEY `user_id` (`user_id`),
  KEY `invoice_status_id` (`invoice_status_id`),
  KEY `invoice_group_id` (`invoice_group_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `mcb_invoice_amounts` (
  `invoice_amount_id` int(11) NOT NULL AUTO_INCREMENT,
  `invoice_id` int(11) NOT NULL DEFAULT '0',
  `invoice_item_subtotal` decimal(10,2) NOT NULL DEFAULT '0.00',
  `invoice_item_taxable` decimal(10,2) NOT NULL DEFAULT '0.00',
  `invoice_item_tax` decimal(10,2) NOT NULL DEFAULT '0.00',
  `invoice_subtotal` decimal(10,2) NOT NULL DEFAULT '0.00',
  `invoice_tax` decimal(10,2) NOT NULL DEFAULT '0.00',
  `invoice_shipping` decimal(10,2) NOT NULL DEFAULT '0.00',
  `invoice_discount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `invoice_paid` decimal(10,2) NOT NULL DEFAULT '0.00',
  `invoice_total` decimal(10,2) NOT NULL DEFAULT '0.00',
  `invoice_balance` decimal(10,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`invoice_amount_id`),
  KEY `invoice_id` (`invoice_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `mcb_invoice_groups` (
  `invoice_group_id` int(11) NOT NULL AUTO_INCREMENT,
  `invoice_group_name` varchar(50) NOT NULL DEFAULT '',
  `invoice_group_prefix` varchar(10) NOT NULL DEFAULT '',
  `invoice_group_next_id` int(11) NOT NULL DEFAULT '0',
  `invoice_group_left_pad` int(2) NOT NULL DEFAULT '0',
  `invoice_group_prefix_year` int(1) NOT NULL DEFAULT '0',
  `invoice_group_prefix_month` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`invoice_group_id`),
  KEY `invoice_group_next_id` (`invoice_group_next_id`),
  KEY `invoice_group_left_pad` (`invoice_group_left_pad`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

INSERT INTO `mcb_invoice_groups` (`invoice_group_id`, `invoice_group_name`, `invoice_group_prefix`, `invoice_group_next_id`, `invoice_group_left_pad`, `invoice_group_prefix_year`, `invoice_group_prefix_month`) VALUES
(1, 'Simple Increment', '', 3, 0, 0, 0);

CREATE TABLE IF NOT EXISTS `mcb_invoice_history` (
  `invoice_history_id` int(11) NOT NULL AUTO_INCREMENT,
  `invoice_id` int(11) NOT NULL DEFAULT '0',
  `user_id` int(11) NOT NULL DEFAULT '0',
  `invoice_history_date` varchar(14) NOT NULL DEFAULT '',
  `invoice_history_data` longtext,
  PRIMARY KEY (`invoice_history_id`),
  KEY `user_id` (`user_id`),
  KEY `invoice_id` (`invoice_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `mcb_invoice_items` (
  `invoice_item_id` int(11) NOT NULL AUTO_INCREMENT,
  `invoice_id` int(11) NOT NULL DEFAULT '0',
  `inventory_id` int(11) NOT NULL DEFAULT '0',
  `item_name` longtext,
  `item_description` longtext,
  `item_date` varchar(14) NOT NULL DEFAULT '',
  `item_qty` decimal(10,2) NOT NULL DEFAULT '0.00',
  `item_price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `tax_rate_id` int(11) NOT NULL DEFAULT '0',
  `is_taxable` int(1) NOT NULL DEFAULT '0',
  `item_tax_option` int(1) NOT NULL DEFAULT '0',
  `item_order` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`invoice_item_id`),
  KEY `invoice_id` (`invoice_id`),
  KEY `tax_rate_id` (`tax_rate_id`),
  KEY `inventory_id` (`inventory_id`),
  KEY `item_order` (`item_order`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `mcb_invoice_item_amounts` (
  `invoice_item_amount_id` int(11) NOT NULL AUTO_INCREMENT,
  `invoice_item_id` int(11) NOT NULL DEFAULT '0',
  `item_subtotal` decimal(10,2) NOT NULL DEFAULT '0.00',
  `item_tax` decimal(10,2) NOT NULL DEFAULT '0.00',
  `item_total` decimal(10,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`invoice_item_amount_id`),
  KEY `invoice_item_id` (`invoice_item_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `mcb_invoice_statuses` (
  `invoice_status_id` int(11) NOT NULL AUTO_INCREMENT,
  `invoice_status` varchar(255) NOT NULL DEFAULT '',
  `invoice_status_type` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`invoice_status_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

INSERT INTO `mcb_invoice_statuses` (`invoice_status_id`, `invoice_status`, `invoice_status_type`) VALUES
(1, 'Open', 1),
(2, 'Pending', 2),
(3, 'Closed', 3);

CREATE TABLE IF NOT EXISTS `mcb_invoice_tags` (
  `invoice_tag_id` int(11) NOT NULL AUTO_INCREMENT,
  `invoice_id` int(11) NOT NULL DEFAULT '0',
  `tag_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`invoice_tag_id`),
  KEY `invoice_id` (`invoice_id`,`tag_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

CREATE TABLE IF NOT EXISTS `mcb_invoice_tax_rates` (
  `invoice_tax_rate_id` int(11) NOT NULL AUTO_INCREMENT,
  `invoice_id` int(11) NOT NULL DEFAULT '0',
  `tax_rate_id` int(11) NOT NULL DEFAULT '0',
  `tax_rate_option` int(1) NOT NULL DEFAULT '1',
  `tax_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`invoice_tax_rate_id`),
  KEY `invoice_id` (`invoice_id`,`tax_rate_id`),
  KEY `tax_rate_option` (`tax_rate_option`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `mcb_merchant_responses` (
  `merchant_response_id` int(11) NOT NULL AUTO_INCREMENT,
  `merchant_response_payment_id` int(11) NOT NULL,
  `merchant_response_client_id` int(11) NOT NULL,
  `merchant_response_invoice_id` int(11) NOT NULL,
  `merchant_response_amount` decimal(10,2) NOT NULL,
  `merchant_response_method` varchar(25) NOT NULL DEFAULT '',
  `merchant_response_status` varchar(25) NOT NULL DEFAULT '',
  `merchant_response_payment_status` varchar(25) NOT NULL DEFAULT '',
  `merchant_response_payment_processed` int(1) NOT NULL DEFAULT '0',
  `merchant_response_post` longtext NOT NULL,
  PRIMARY KEY (`merchant_response_id`),
  KEY `merchant_response_payment_id` (`merchant_response_payment_id`,`merchant_response_client_id`,`merchant_response_invoice_id`),
  KEY `merchant_response_payment_processed` (`merchant_response_payment_processed`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `mcb_modules` (
  `module_id` int(11) NOT NULL AUTO_INCREMENT,
  `module_path` varchar(50) NOT NULL DEFAULT '',
  `module_name` varchar(50) NOT NULL DEFAULT '',
  `module_description` varchar(255) DEFAULT '',
  `module_enabled` int(1) DEFAULT '0',
  `module_author` varchar(50) DEFAULT '',
  `module_homepage` varchar(255) DEFAULT '',
  `module_version` varchar(25) DEFAULT '',
  `module_available_version` varchar(25) DEFAULT '',
  `module_config` longtext,
  `module_change_status` int(1) DEFAULT '1',
  `module_order` int(2) NOT NULL DEFAULT '99',
  `module_top_menu` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`module_id`),
  KEY `module_order` (`module_order`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=15 ;

INSERT INTO `mcb_modules` (`module_id`, `module_path`, `module_name`, `module_description`, `module_enabled`, `module_author`, `module_homepage`, `module_version`, `module_available_version`, `module_config`, `module_change_status`, `module_order`, `module_top_menu`) VALUES
(1, 'tasks', 'Tasks', 'A simple task manager which allows task based invoice creation.', 1, 'Damiano Venturin', 'http://www.mcbsb.com', '0.13.0', NULL, 'a:1:{s:12:"contact_tabs";a:1:{s:5:"Tasks";s:11:"/tasks/task";}}', 0, 9, 1),
(2, 'invoice_search', 'Invoice Search', NULL, 0, NULL, NULL, NULL, NULL, 'a:0:{}', 0, 99, 0),
(4, 'payments', 'Payments', NULL, 0, NULL, NULL, NULL, NULL, 'a:2:{s:13:"settings_view";s:33:"payments/payment_settings/display";s:13:"settings_save";s:30:"payments/payment_settings/save";}', 0, 4, 0),
(5, 'activities', 'Activities', 'An supplementary module for the task manager.', 0, 'Damiano Venturin', 'http://www.mcbsb.com', '0.0.1', NULL, 'a:0:{}', 0, 99, 1),
(6, 'google', 'Google', NULL, 1, NULL, NULL, NULL, NULL, 'a:2:{s:13:"settings_view";s:23:"google/display_settings";s:13:"settings_save";s:20:"google/save_settings";}', 0, 2, 0),
(7, 'mailer', 'Mailer', NULL, 0, NULL, NULL, NULL, NULL, 'a:2:{s:13:"settings_view";s:23:"mailer/display_settings";s:13:"settings_save";s:20:"mailer/save_settings";}', 0, 5, 0),
(8, 'invoices', 'Invoices', NULL, 0, NULL, NULL, NULL, NULL, 'a:2:{s:13:"settings_view";s:33:"invoices/invoice_settings/display";s:13:"settings_save";s:30:"invoices/invoice_settings/save";}', 0, 3, 1),
(9, 'inventory', 'Inventory', NULL, 0, NULL, NULL, NULL, NULL, 'a:0:{}', 0, 4, 1),
(10, 'contact', 'Contact', NULL, 1, NULL, NULL, NULL, NULL, 'a:2:{s:13:"settings_view";a:3:{s:6:"Person";s:39:"contact/contact_settings/display_person";s:12:"Organization";s:45:"contact/contact_settings/display_organization";s:8:"Location";s:41:"contact/contact_settings/display_location";}s:13:"settings_save";s:29:"contact/contact_settings/save";}', 0, 3, 1),
(11, 'tooljar', 'Tooljar', 'API client for Tooljar', 1, 'Damiano Venturin', 'http://www.tooljar.biz', '0.0.1', NULL, 'a:0:{}', 0, 3, 0),
(12, 'dashboard', 'Dashboard', NULL, 0, NULL, NULL, NULL, NULL, 'a:2:{s:13:"settings_view";s:36:"dashboard/dashboard_settings/display";s:13:"settings_save";s:33:"dashboard/dashboard_settings/save";}', 0, 10, 1),
(13, 'products', 'Products', 'A products list manager.', 1, 'Damiano Venturin', 'http://tooljar.biz', '0.13.0', NULL, 'a:0:{}', 0, 9, 1),
(14, 'devices', 'Devices', 'A device list manager.', 1, 'Damiano Venturin', 'http://tooljar.biz', '0.13.0', NULL, 'a:1:{s:12:"contact_tabs";a:1:{s:7:"Devices";s:15:"/devices/device";}}', 0, 10, 1);

CREATE TABLE IF NOT EXISTS `mcb_payments` (
  `payment_id` int(11) NOT NULL AUTO_INCREMENT,
  `invoice_id` int(11) NOT NULL DEFAULT '0',
  `payment_method_id` int(11) NOT NULL DEFAULT '0',
  `payment_date` varchar(14) NOT NULL DEFAULT '',
  `payment_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `payment_note` longtext,
  PRIMARY KEY (`payment_id`),
  KEY `invoice_id` (`invoice_id`),
  KEY `payment_method_id` (`payment_method_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `mcb_payment_methods` (
  `payment_method_id` int(11) NOT NULL AUTO_INCREMENT,
  `payment_method` varchar(25) CHARACTER SET latin1 NOT NULL DEFAULT '',
  PRIMARY KEY (`payment_method_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

INSERT INTO `mcb_payment_methods` (`payment_method_id`, `payment_method`) VALUES
(1, 'Cash'),
(2, 'Check'),
(3, 'Credit');

CREATE TABLE IF NOT EXISTS `mcb_tags` (
  `tag_id` int(11) NOT NULL AUTO_INCREMENT,
  `tag` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`tag_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `mcb_tasks_invoices` (
  `task_invoice_id` int(11) NOT NULL AUTO_INCREMENT,
  `task_id` int(11) NOT NULL,
  `invoice_id` int(11) NOT NULL,
  PRIMARY KEY (`task_invoice_id`),
  KEY `task_id` (`task_id`,`invoice_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `mcb_tax_rates` (
  `tax_rate_id` int(11) NOT NULL AUTO_INCREMENT,
  `tax_rate_name` varchar(25) NOT NULL DEFAULT '',
  `tax_rate_percent` decimal(5,2) NOT NULL,
  PRIMARY KEY (`tax_rate_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

INSERT INTO `mcb_tax_rates` (`tax_rate_id`, `tax_rate_name`, `tax_rate_percent`) VALUES
(1, 'Standard', 0.00);

CREATE TABLE IF NOT EXISTS `mcb_userdata` (
  `mcb_userdata_id` int(11) NOT NULL AUTO_INCREMENT,
  `mcb_userdata_user_id` int(11) NOT NULL,
  `mcb_userdata_key` varchar(50) NOT NULL DEFAULT '',
  `mcb_userdata_value` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`mcb_userdata_id`),
  KEY `mcb_data_key` (`mcb_userdata_key`),
  KEY `mcb_userdata_user_id` (`mcb_userdata_user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=15 ;

INSERT INTO `mcb_userdata` (`mcb_userdata_id`, `mcb_userdata_user_id`, `mcb_userdata_key`, `mcb_userdata_value`) VALUES
(1, 1, 'default_tax_rate_id', '1'),
(2, 1, 'default_tax_rate_option', '0'),
(3, 1, 'default_item_tax_rate_id', '0'),
(4, 2, 'default_tax_rate_id', '0'),
(5, 2, 'default_tax_rate_option', '0'),
(6, 2, 'default_item_tax_rate_id', '0'),
(7, 3, 'default_tax_rate_id', '0'),
(8, 3, 'default_tax_rate_option', '0'),
(9, 3, 'default_item_tax_rate_id', '0'),
(10, 1, 'default_item_tax_option', ''),
(11, 4, 'default_tax_rate_id', '0'),
(12, 4, 'default_tax_rate_option', '0'),
(13, 4, 'default_item_tax_rate_id', '0'),
(14, 4, 'default_item_tax_option', '');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `products` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `product` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `details` text COLLATE utf8_unicode_ci,
  `code_number` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `brand` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `model` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `note` text COLLATE utf8_unicode_ci,
  `price` double DEFAULT NULL,
  `months_warranty` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `salable` tinyint(3) unsigned DEFAULT NULL,
  `creation_date` int(11) unsigned DEFAULT NULL,
  `created_by` double DEFAULT NULL,
  `creator` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `update_date` int(11) unsigned DEFAULT NULL,
  `updated_by` double DEFAULT NULL,
  `editor` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `tasks` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `task` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `details` text COLLATE utf8_unicode_ci,
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(20) unsigned NOT NULL DEFAULT '0',
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
  `preferred_language` varchar(50) DEFAULT 'english',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `users_groups` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(20) unsigned NOT NULL,
  `group_id` mediumint(8) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

