drop table if exists [prefix]_files ;
drop table if exists [prefix]_form_groups ;
drop table if exists [prefix]_form_sections ;
drop table if exists [prefix]_forms ;
drop table if exists [prefix]_groups ;
drop table if exists [prefix]_layers ;
drop table if exists [prefix]_menu_items ;
drop table if exists [prefix]_menus ;
drop table if exists [prefix]_modules ;
drop table if exists [prefix]_page_sections ;
drop table if exists [prefix]_pages ;
drop table if exists [prefix]_permissions ;
drop table if exists [prefix]_sessions ;
drop table if exists [prefix]_settings ;
drop table if exists [prefix]_sites ;
drop table if exists [prefix]_skins ;
drop table if exists [prefix]_styles ;
drop table if exists [prefix]_users ;
drop table if exists [prefix]_lists;
drop table if exists [prefix]_list_items;
drop table if exists [prefix]_form_redirects;
drop table if exists [prefix]_form_conditions;
drop table if exists [prefix]_reports;
drop table if exists [prefix]_report_conditions;
drop table if exists [prefix]_report_fields;
DROP TABLE IF EXISTS [prefix]_shares;
DROP TABLE IF EXISTS [prefix]_temp_sites;
DROP TABLE IF EXISTS [prefix]_embedded_reports;
DROP TABLE IF EXISTS [prefix]_mailing_lists;
DROP TABLE IF EXISTS [prefix]_mailing_to;
DROP TABLE IF EXISTS [prefix]_site_configurations;
DROP TABLE IF EXISTS [prefix]_auto_backups;
DROP TABLE IF EXISTS [prefix]_backups;


CREATE TABLE `[prefix]_mailing_lists` (
  `id` int(8) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL default '',
  `site_key` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `site_key` (`site_key`)
) TYPE=MyISAM;


CREATE TABLE `[prefix]_mailing_to` (
  `id` int(8) NOT NULL auto_increment,
  `mailing_id` int(8) NOT NULL default '0',
  `user_id` int(8) NOT NULL default '0',
  `group_id` int(8) NOT NULL default '0',
  `status` varchar(50) NOT NULL default '',
  `site_key` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `site_key` (`site_key`)
) TYPE=MyISAM AUTO_INCREMENT=16 ;


CREATE TABLE `[prefix]_site_configurations` (
  `id` int(8) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL default '',
  `user_id` int(8) NOT NULL default '0',
  `parent_data` text NOT NULL default '',
  `skin_id` int(8) NOT NULL default '0',
  `permissions` text NOT NULL default '',
  `site_key` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `site_key` (`site_key`)
) TYPE=MyISAM;


CREATE TABLE `[prefix]_temp_sites` (
  `id` int(8) NOT NULL auto_increment,
  `user_site_key` varchar(50) NOT NULL default '',
  `owner` int(8) NOT NULL default '0',
  `parent_data` text NOT NULL default '',
  `site_key` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `site_key` (`site_key`),
  KEY `owner` (`owner`)
) TYPE=MyISAM;

# --------------------------------------------------------
#
# Table structure for table `[prefix]_files`
#
CREATE TABLE `[prefix]_files` (
  `id` int(8) NOT NULL auto_increment,
  `site_key` varchar(50) NOT NULL default '',
  `download_name` varchar(255) NOT NULL default '',
  `file_data` mediumblob,
  `file_data_path` varchar(255) NOT NULL default '',
  `counter` int(8) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `site_key` (`site_key`)
) TYPE=MyISAM;
# --------------------------------------------------------
#
# Table structure for table `[prefix]_filter_overrides`
#
CREATE TABLE `[prefix]_filter_overrides` (
  `id` int(8) NOT NULL auto_increment,
  `form_id` int(8) NOT NULL default '0',
  `section_id` int(8) NOT NULL default '0',
  `report_field_id` varchar(50) NOT NULL default '0',
  `condition` varchar(15) NOT NULL default '',
  `case_sen` int(1) NOT NULL default '0',
  `skip_empty` tinyint(1) NOT NULL default '1',
  `allow_case` tinyint(1) NOT NULL default '0',
  `site_key` varchar(50) NOT NULL default '',
  KEY `id` (`id`,`section_id`,`report_field_id`,`site_key`),
  KEY `form_id` (`form_id`)
) TYPE=MyISAM;
# --------------------------------------------------------
#
# Table structure for table `[prefix]_form_groups`
#
CREATE TABLE `[prefix]_form_groups` (
  `id` int(8) NOT NULL auto_increment,
  `site_key` varchar(50) NOT NULL default '',
  `_group` int(8) NOT NULL default '0',
  `_order` int(5) NOT NULL default '0',
  `label` varchar(255) NOT NULL default '',
  `value` varchar(255) NOT NULL default '',
  `orientation` varchar(10) NOT NULL default '',
  `selected` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `site_key` (`site_key`),
  KEY `_group` (`_group`),
  KEY `_order` (`_order`)
) TYPE=MyISAM;
# --------------------------------------------------------
#
# Table structure for table `[prefix]_form_redirects`
#
CREATE TABLE `[prefix]_form_redirects` (
  `id` int(8) NOT NULL auto_increment,
  `site_key` varchar(50) NOT NULL default '',
  `form_id` int(8) NOT NULL default '0',
  `_order` int(8) NOT NULL default '0',
  `section_id` int(8) NOT NULL default '0',
  `condition` varchar(15) NOT NULL default '',
  `case_sen` int(1) NOT NULL default '0',
  `value` varchar(255) NOT NULL default '',
  `redirect_type` varchar(20) NOT NULL default '',
  `redirect_id` varchar(255) NOT NULL default '0',
  KEY `site_key` (`site_key`),
  KEY `_order` (`_order`),
  KEY `id` (`id`)
) TYPE=MyISAM;
# --------------------------------------------------------
#
# Table structure for table `[prefix]_form_sections`
#
CREATE TABLE `[prefix]_form_sections` (
  `id` int(8) NOT NULL auto_increment,
  `site_key` varchar(50) NOT NULL default '',
  `form_id` int(8) NOT NULL default '0',
  `_order` int(5) NOT NULL default '0',
  `field_type` varchar(50) NOT NULL default '',
  `field_name` varchar(255) NOT NULL default '',
  `field_size` varchar(20) NOT NULL default '0',
  `required` tinyint(1) NOT NULL default '0',
  `validator` varchar(20) NOT NULL default '',
  `err_msg` varchar(255) NOT NULL default '',
  `label` varchar(255) NOT NULL default '',
  `list_data` varchar(255) NOT NULL default '',
  `page_section` int(8) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `_order` (`_order`),
  KEY `site_key` (`site_key`)
) TYPE=MyISAM;
# --------------------------------------------------------
#
# Table structure for table `[prefix]_form_submissions`
#
CREATE TABLE `[prefix]_form_submissions` (
  `id` int(8) NOT NULL auto_increment,
  `submission_id` int(8) NOT NULL default '0',
  `form_id` int(8) NOT NULL default '0',
  `redirect_id` varchar(255) NOT NULL default '0',
  `field_id` int(8) NOT NULL default '0',
  `user_id` int(8) NOT NULL default '0',
  `value` text NOT NULL default '',
  `blob_value` mediumblob NOT NULL default '',
  `file_data_path` varchar(255) NOT NULL default '',
  `site_key` varchar(50) NOT NULL default '',
  KEY `site_key` (`site_key`),
  KEY `id` (`id`),
  KEY `user_id` (`user_id`)
) TYPE=MyISAM;
# --------------------------------------------------------
#
# Table structure for table `[prefix]_forms`
#
CREATE TABLE `[prefix]_forms` (
  `id` int(8) NOT NULL auto_increment,
  `site_key` varchar(50) NOT NULL default '',
  `redirect_type` varchar(20) NOT NULL default '0',
  `redirect_id` varchar(255) NOT NULL default '0',
  `other_redirect_type` varchar(20) NOT NULL default '',
  `other_redirect_id` varchar(255) NOT NULL default '0',
  `is_search_form` tinyint(1) NOT NULL default '0',
  `search_report_id` int(8) NOT NULL default '0',
  `counter` int(8) NOT NULL default '0',
  `counter_submit` int(8) NOT NULL default '0',
  `sef_title` varchar(255),
  PRIMARY KEY  (`id`),
  KEY `site_key` (`site_key`)
) TYPE=MyISAM;
# --------------------------------------------------------
#
# Table structure for table `[prefix]_groups`
#
CREATE TABLE `[prefix]_groups` (
  `id` int(8) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL default '',
  `description` text NOT NULL default '',
  `site_key` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `site_key` (`site_key`),
  KEY `name` (`name`)
) TYPE=MyISAM;
# --------------------------------------------------------
#
# Table structure for table `[prefix]_layers`
#
CREATE TABLE `[prefix]_layers` (
  `id` int(8) NOT NULL auto_increment,
  `_left` varchar(10) NOT NULL default '',
  `top` varchar(10) NOT NULL default '',
  `width` varchar(10) NOT NULL default '',
  `height` varchar(10) NOT NULL default '',
  `align` varchar(20) NOT NULL default '',
  `valign` varchar(20) NOT NULL default '',
  `zorder` int(5) NOT NULL default '0',
  `bgcolor` varchar(10) NOT NULL default '',
  `padding` int(5) NOT NULL default '0',
  `style` varchar(255) NOT NULL default '',
  `format` varchar(50) NOT NULL default '',
  `content` text NOT NULL default '',
  `nl2br` int(1) NOT NULL default '0',
  `restrict_to` text NOT NULL default '',
  `title` varchar(50) NOT NULL default '',
  `site_key` varchar(50) NOT NULL default '',
  `anchor` varchar(50) NOT NULL default '',
  `settings_override` int(8) NOT NULL default '0',
  `img_thumb` mediumblob NOT NULL default '',
  `img_thumb_path` varchar(255) NOT NULL default '',
  `img_large` mediumblob NOT NULL default '',
  `img_large_path` varchar(255) NOT NULL default '',
  `img_anchor` varchar(10) NOT NULL default '',
  `img_link` varchar(255) NOT NULL default '',
  `link_target` varchar(20) NOT NULL default '',
  `img_alt` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `site_key` (`site_key`)
) TYPE=MyISAM;
# --------------------------------------------------------
#
# Table structure for table `[prefix]_list_items`
#
CREATE TABLE `[prefix]_list_items` (
  `list_key` varchar(50) NOT NULL default '0',
  `_order` int(8) NOT NULL default '0',
  `data` varchar(255) NOT NULL default '',
  `label` varchar(255) NOT NULL default '',
  `site_key` varchar(50) NOT NULL default '',
  KEY `site_key` (`site_key`),
  KEY `list_key` (`list_key`)
) TYPE=MyISAM;
# --------------------------------------------------------
#
# Table structure for table `[prefix]_lists`
#
CREATE TABLE `[prefix]_lists` (
  `id` int(8) NOT NULL auto_increment,
  `list_key` varchar(50) NOT NULL default '',
  `title` varchar(255) NOT NULL default '',
  `site_key` varchar(50) NOT NULL default '',
  `_order` int(8) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `list_key` (`list_key`),
  KEY `site_key` (`site_key`),
  KEY `_order` (`_order`)
) TYPE=MyISAM;
# --------------------------------------------------------
#
# Table structure for table `[prefix]_menu_items`
#
CREATE TABLE `[prefix]_menu_items` (
  `id` int(8) NOT NULL auto_increment,
  `site_key` varchar(50) NOT NULL default '',
  `menu_id` int(8) NOT NULL default '0',
  `parent` int(8) NOT NULL default '0',
  `_order` int(8) NOT NULL default '0',
  `level` int(3) NOT NULL default '0',
  `hidden` char(1) NOT NULL default '',
  `resource_type` varchar(20) NOT NULL default '',
  `resource_id` varchar(255) NOT NULL default '',
  `title` varchar(255) default NULL,
  `sef_title` varchar(255),
  `out_style` varchar(255) NOT NULL default '',
  `over_style` varchar(255) NOT NULL default '',
  `image_out` blob,
  `image_over` blob,
  `item_width` int(5) NOT NULL default '0',
  `item_height` int(5) NOT NULL default '0',
  `borders` varchar(20) NOT NULL default '',
  `x_offset` int(5) NOT NULL default '0',
  `y_offset` int(5) NOT NULL default '0',
  `out_color` varchar(10) NOT NULL default '',
  `over_color` varchar(10) NOT NULL default '',
  `image_width` int(5) NOT NULL default '0',
  `image_height` int(5) NOT NULL default '0',
  `sticky_rollover` int(1) NOT NULL default '0',
  `target` varchar(10) NOT NULL default '',
  `in_template` char(3) NOT NULL default '',
  `restrict_to` text,
  `last_change` timestamp(14) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `parent` (`parent`),
  KEY `_order` (`_order`),
  KEY `hidden` (`hidden`),
  KEY `resource_type` (`resource_type`),
  KEY `resource_id` (`resource_id`),
  KEY `menu_id` (`menu_id`),
  KEY `level` (`level`),
  KEY `site_key` (`site_key`)
) TYPE=MyISAM;
# --------------------------------------------------------
#
# Table structure for table `[prefix]_menus`
#
CREATE TABLE `[prefix]_menus` (
  `id` int(8) NOT NULL auto_increment,
  `site_key` varchar(50) NOT NULL default '',
  `title` varchar(255) NOT NULL default '',
  `restrict_to` text,
  PRIMARY KEY  (`id`),
  KEY `title` (`title`),
  KEY `site_key` (`site_key`)
) TYPE=MyISAM;
# --------------------------------------------------------
#
# Table structure for table `[prefix]_module_categories`
#
CREATE TABLE `[prefix]_module_categories` (
  `id` int(8) NOT NULL auto_increment,
  `title` varchar(255) NOT NULL default '',
  `sef_title` varchar(255),
  `description` text NOT NULL default '',
  `parent` int(8) NOT NULL default '0',
  `_order` int(8) NOT NULL default '0',
  `site_key` varchar(50) NOT NULL default '',
  `level` int(3) NOT NULL default '0',
  `module_key` varchar(50) NOT NULL default '',
  `item_id` int(8) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `site_key` (`site_key`),
  KEY `module_key` (`module_key`),
  KEY `parent` (`parent`),
  KEY `_order` (`_order`),
  KEY `level` (`level`)
) TYPE=MyISAM;
# --------------------------------------------------------
#
# Table structure for table `[prefix]_module_objects`
#
CREATE TABLE `[prefix]_module_objects` (
  `id` int(8) NOT NULL auto_increment,
  `item_id` int(8) NOT NULL default '0',
  `site_key` varchar(50) NOT NULL default '',
  `module_key` varchar(50) NOT NULL default '',
  `title` varchar(255) NOT NULL default '',
  `description` text NOT NULL default '',
  `_order` smallint(6) NOT NULL default '0',
  `data` mediumblob NOT NULL default '',
  `type` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `_order` (`_order`),
  KEY `item_id` (`item_id`),
  KEY `site_key` (`site_key`),
  KEY `module_key` (`module_key`)
) TYPE=MyISAM;
# --------------------------------------------------------
#
# Table structure for table `[prefix]_module_settings`
#
CREATE TABLE `[prefix]_module_settings` (
  `id` int(8) NOT NULL auto_increment,
  `name` varchar(50) NOT NULL default '',
  `value` mediumtext NOT NULL default '',
  `cat_id` int(8) NOT NULL default '0',
  `site_key` varchar(50) NOT NULL default '',
  `module_key` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `site_key` (`site_key`),
  KEY `name` (`name`),
  KEY `module_key` (`module_key`),
  KEY `cat_id` (`cat_id`)
) TYPE=MyISAM;
# --------------------------------------------------------
#
# Table structure for table `[prefix]_modules`
#
CREATE TABLE `[prefix]_modules` (
  `id` int(8) NOT NULL auto_increment,
  `module_key` varchar(50) NOT NULL default '',
  `title` varchar(255) NOT NULL default '',
  `created` datetime NOT NULL default '0000-00-00 00:00:00',
  `author` varchar(255) NOT NULL default '',
  `version` varchar(10) NOT NULL default '',
  `skin_id` int(8) NOT NULL default '0',
  `site_key` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `site_key` (`site_key`)
) TYPE=MyISAM;
# --------------------------------------------------------
#
# Table structure for table `[prefix]_page_sections`
#
CREATE TABLE `[prefix]_page_sections` (
  `id` int(8) NOT NULL auto_increment,
  `site_key` varchar(50) NOT NULL default '',
  `page_id` int(8) NOT NULL default '0',
  `title` varchar(255) NOT NULL default '',
  `_order` int(5) NOT NULL default '0',
  `style` varchar(255) NOT NULL default '',
  `format` varchar(50) NOT NULL default '',
  `content` text NOT NULL default '',
  `nl2br` int(1) NOT NULL default '0',
  `img_thumb` mediumblob,
  `img_thumb_path` varchar(255) NOT NULL default '',
  `img_large` mediumblob,
  `img_large_path` varchar(255) NOT NULL default '',
  `img_anchor` varchar(10) NOT NULL default '',
  `img_link` varchar(255) NOT NULL default '',
  `link_target` varchar(20) NOT NULL default '',
  `img_alt` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `_order` (`_order`),
  KEY `site_key` (`site_key`),
  KEY `page_id` (`page_id`)
) TYPE=MyISAM;
# --------------------------------------------------------
#
# Table structure for table `[prefix]_pages`
#
CREATE TABLE `[prefix]_pages` (
  `id` int(8) NOT NULL auto_increment,
  `site_key` varchar(50) NOT NULL default '',
  `title` varchar(255) NOT NULL default '',
  `meta_keywords` varchar(255) NOT NULL default '',
  `meta_desc` TEXT default '',
  `page_key` varchar(50) NOT NULL default '',
  `counter` int(8) NOT NULL default '0',
  `sef_title` varchar(255),
  `skin_id` int(8) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `title` (`title`),
  KEY `page_key` (`page_key`),
  KEY `site_key` (`site_key`)
) TYPE=MyISAM;
# --------------------------------------------------------
#
# Table structure for table `[prefix]_permissions`
#
CREATE TABLE `[prefix]_permissions` (
  `resource_id` int(8) NOT NULL default '0',
  `resource_type` varchar(50) NOT NULL default '',
  `group_id` int(8) NOT NULL default '0',
  `user_id` int(8) NOT NULL default '0',
  `site_key` varchar(50) NOT NULL default '',
  KEY `resource_id` (`resource_id`),
  KEY `resource_type` (`resource_type`),
  KEY `site_key` (`site_key`),
  KEY `group_id` (`group_id`),
  KEY `user_id` (`user_id`)
) TYPE=MyISAM;
# --------------------------------------------------------
#
# Table structure for table `[prefix]_report_conditions`
#
CREATE TABLE `[prefix]_report_conditions` (
  `id` int(8) NOT NULL auto_increment,
  `resource` varchar(50) NOT NULL default '0',
  `report_id` int(8) NOT NULL default '0',
  `condition` varchar(15) NOT NULL default '',
  `case_sen` int(1) NOT NULL default '0',
  `value` varchar(255) NOT NULL default '',
  `site_key` varchar(50) NOT NULL default '',
  `section_id` varchar(50) NOT NULL default '0',
  KEY `site_key` (`site_key`),
  KEY `form_id` (`resource`),
  KEY `report_id` (`report_id`),
  KEY `id` (`id`)
) TYPE=MyISAM;
# --------------------------------------------------------
#
# Table structure for table `[prefix]_report_groups`
#
CREATE TABLE `[prefix]_report_groups` (
  `id` int(8) NOT NULL auto_increment,
  `report_id` int(8) NOT NULL default '0',
  `_order` int(8) NOT NULL default '0',
  `field_id` varchar(50) NOT NULL default '',
  `do_group` int(1) NOT NULL default '0',
  `sort_type` int(1) NOT NULL default '0',
  `position` int(1) NOT NULL default '0',
  `style` varchar(50) NOT NULL default '',
  `indent` int(5) NOT NULL default '0',
  `sum_field_id` varchar(50) NOT NULL default '',
  `layout` text NOT NULL default '',
  `site_key` varchar(50) NOT NULL default '',
  KEY `id` (`id`,`report_id`,`field_id`,`site_key`)
) TYPE=MyISAM;
# --------------------------------------------------------
#
# Table structure for table `[prefix]_reports`
#
CREATE TABLE `[prefix]_reports` (
  `id` int(8) NOT NULL auto_increment,
  `name` varchar(50) NOT NULL default '',
  `body` text NOT NULL default '',
  `advanced_layout` int(1) NOT NULL default '0',
  `layout_template` text NOT NULL default '',
  `resource` varchar(50) NOT NULL default '0',
  `header` text NOT NULL default '',
  `footer` text NOT NULL default '',
  `sef_title` varchar(255),
  `site_key` varchar(50) NOT NULL default '',
  KEY `site_key` (`site_key`),
  KEY `id` (`id`)
) TYPE=MyISAM;
# --------------------------------------------------------
#
# Table structure for table `[prefix]_sessions`
#
CREATE TABLE `[prefix]_sessions` (
  `id` int(8) NOT NULL auto_increment,
  `session_id` varchar(255) NOT NULL default '',
  `session_data` text,
  `expire_date` datetime NOT NULL default '0000-00-00 00:00:00',
  `site_key` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `session_id` (`session_id`),
  KEY `expire_date` (`expire_date`),
  KEY `site_key` (`site_key`)
) TYPE=MyISAM;
# --------------------------------------------------------
#
# Table structure for table `[prefix]_settings`
#
CREATE TABLE `[prefix]_settings` (
  `id` int(8) NOT NULL auto_increment,
  `active` int(1) NOT NULL default '0', 
  `resource_type` varchar(50) NOT NULL default '',
  `resource_id` varchar(50) NOT NULL default '0',
  `site_key` varchar(50) NOT NULL default '',
  `property` varchar(255) NOT NULL default '',
  `param` varchar(50) NOT NULL default '',
  `value` mediumtext NOT NULL default '',
  `skin_id` int(8) NOT NULL default '0', 
  `last_change` timestamp(14) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `site_key` (`site_key`),
  KEY `resource_type` (`resource_type`),
  KEY `resource_id` (`resource_id`)
) TYPE=MyISAM;
# --------------------------------------------------------
#
# Table structure for table `[prefix]_sites`
#
CREATE TABLE `[prefix]_sites` (
  `id` int(8) NOT NULL auto_increment,
  `site_key` varchar(50) NOT NULL default '',
  `title` varchar(255) NOT NULL default '',
  `is_default` char(1) NOT NULL default '',
  `counter` int(8) NOT NULL default '0',
  `last_updated` datetime NOT NULL default '0000-00-00 00:00:00',
  `admin_id` int(8) NOT NULL default '0',
  `restrict_skin` tinyint(1) NOT NULL default '0',
  `skin_id` int(8) NOT NULL default '0',
  `default_resource_type` varchar(20) NOT NULL default '',
  `default_resource_id` int(8) NOT NULL default '0',
  `login_form_id` int(8) NOT NULL default '0',
  `logout_page_id` int(8) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `title` (`title`),
  KEY `is_default` (`is_default`),
  KEY `site_key` (`site_key`),
  KEY `admin_id` (`admin_id`)
) TYPE=MyISAM;
# --------------------------------------------------------
#
# Table structure for table `[prefix]_skins`
#
CREATE TABLE `[prefix]_skins` (
  `id` int(8) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL default '',
  `description` text NOT NULL default '',
  `owner` int(8) NOT NULL default '0',
  `site_key` varchar(50) NOT NULL default '',
  `sections` text NOT NULL default '',
  `shared` tinyint(1) NOT NULL default '0',
  `share_groups` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `owner` (`owner`),
  KEY `shared` (`shared`),
  KEY `site_key` (`site_key`)
) TYPE=MyISAM;
# --------------------------------------------------------
#
# Table structure for table `[prefix]_styles`
#
CREATE TABLE `[prefix]_styles` (
  `id` int(8) NOT NULL auto_increment,
  `site_key` varchar(50) NOT NULL default '',
  `name` varchar(255) NOT NULL default '',
  `bold` tinyint(1) NOT NULL default '0',
  `underline` tinyint(1) NOT NULL default '0',
  `italic` tinyint(1) NOT NULL default '0',
  `font` varchar(50) NOT NULL default '',
  `size` int(5) NOT NULL default '0',
  `color` varchar(10) NOT NULL default '',
  `bg_color` varchar(10) NOT NULL default '',
  `user_defined` tinyint(1) NOT NULL default '0',
  `skin_id` int(8) NOT NULL default '0',
  `last_change` timestamp(14) NOT NULL,
  `active` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `site_key` (`site_key`),
  KEY `user_defined` (`user_defined`),
  KEY `skin_id` (`skin_id`)
) TYPE=MyISAM;
# --------------------------------------------------------
#
# Table structure for table `[prefix]_users`
#
CREATE TABLE `[prefix]_users` (
  `id` int(8) NOT NULL auto_increment,
  `login_id` varchar(40) NOT NULL default '',
  `login_pass` varchar(40) NOT NULL default '',
  `first_name` varchar(100) NOT NULL default '',
  `last_name` varchar(100) NOT NULL default '',
  `email` varchar(255) NOT NULL default '',
  `url` varchar(255) NOT NULL default '',
  `company` varchar(255) NOT NULL default '',
  `phone` varchar(50) NOT NULL default '',
  `fax` varchar(50) NOT NULL default '',
  `address_1` varchar(255) NOT NULL default '',
  `address_2` varchar(255) NOT NULL default '',
  `city` varchar(255) NOT NULL default '',
  `state` varchar(255) NOT NULL default '',
  `zip` varchar(20) NOT NULL default '',
  `country` varchar(255) NOT NULL default '',
  `group_id` int(8) NOT NULL default '0',
  `member_id` varchar(255) NOT NULL default '',
  `comments` text NOT NULL default '',
  `date_created` date NOT NULL default '0000-00-00',
  `last_login` date NOT NULL default '0000-00-00',
  `date_expires` date NOT NULL default '0000-00-00',
  `use_expiration` tinyint(1) NOT NULL default '0',
  `site_key` varchar(50) NOT NULL default '',
  `user_site_key` varchar(50) NOT NULL default '',
  `session_id` varchar(255) NOT NULL default '',
  `status` varchar(20) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `group_id` (`group_id`),
  KEY `login_id` (`login_id`),
  KEY `login_pass` (`login_pass`),
  KEY `site_key` (`site_key`),
  KEY `date_expires` (`date_expires`),
  KEY `session_id` (`session_id`),
  KEY `user_site_key` (`user_site_key`),
  KEY `status` (`status`),
  KEY `company` (`company`)
) TYPE=MyISAM;



CREATE TABLE `[prefix]_report_fields` (
  `id` int(8) NOT NULL auto_increment,
  `report_id` int(8) NOT NULL default '0',
  `field_id` varchar(50) NOT NULL default '',
  `title` varchar(50) NOT NULL default '',
  `display_title` varchar(50) NOT NULL default '',
  `target` varchar(15) NOT NULL default '',
  `use_link` int(1) NOT NULL default '0',
  `link` varchar(255) NOT NULL default '',
  `visible` mediumint(1) NOT NULL default '1',
  `content_template` text NOT NULL default '',
  `site_key` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `site_key` (`site_key`),
  KEY `report_id` (`report_id`),
  KEY `field_id` (`field_id`)
) TYPE=MyISAM;



CREATE TABLE `[prefix]_shares` (
  `id` int(8) NOT NULL auto_increment,
  `resource_id` varchar(50) NOT NULL default '',
  `resource_type` varchar(50) NOT NULL default '',
  `user_id` int(8) NOT NULL default '0',
  `group_id` int(8) NOT NULL default '0',
  `override` tinyint(1) NOT NULL default '0',
  `view` tinyint(1) NOT NULL default '0',
  `edit` tinyint(1) NOT NULL default '0',
  `site_key` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `resource_id` (`resource_id`),
  KEY `user_id` (`user_id`),
  KEY `group_id` (`group_id`),
  KEY `site_key` (`site_key`)
) TYPE=MyISAM;




CREATE TABLE `[prefix]_embedded_reports` (
  `id` int(8) NOT NULL auto_increment,
  `into_id` int(8) NOT NULL default '0',
  `source_id` int(8) NOT NULL default '0',
  `site_key` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `into_id` (`into_id`),
  KEY `source_id` (`source_id`),
  KEY `site_key` (`site_key`)
) TYPE=MyISAM;



CREATE TABLE `[prefix]_auto_backups` (
  `id` int(8) NOT NULL auto_increment,
  `backup_id` int(8) NOT NULL default '0',
  `email` varchar(255) NOT NULL default '',
  `subject` tinytext NOT NULL default '',
  `message` text NOT NULL default '',
  `name` varchar(255) NOT NULL default '',
  `secret_id` varchar(32) NOT NULL default '',
  `site_key` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `backup_id` (`backup_id`),
  KEY `secret_id` (`secret_id`),
  KEY `site_key` (`site_key`)
) TYPE=MyISAM AUTO_INCREMENT=10 ;



CREATE TABLE `[prefix]_backups` (
  `id` int(8) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL default '',
  `compression` varchar(10) NOT NULL default '',
  `structure` int(1) NOT NULL default '0',
  `resources` text,
  `site_key` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `site_key` (`site_key`)
) TYPE=MyISAM;
