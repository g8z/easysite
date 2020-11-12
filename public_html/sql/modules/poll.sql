# realty module

drop table if exists [prefix]_polls ;
drop table if exists [prefix]_poll_results ;

# list of the active polls

CREATE TABLE `[prefix]_polls` (
  `id` mediumint(8) NOT NULL auto_increment,
  `title` varchar(255) NOT NULL default '',
  `form_id` mediumint(8) NOT NULL default '0',
  `popup` smallint(1) NOT NULL default '0',
  `width` mediumint(8) NOT NULL default '0',
  `height` mediumint(8) NOT NULL default '0',
  `group_id` varchar(10) NOT NULL default '0',
  `user_id` mediumint(8) NOT NULL default '0',
  `frequency` tinyint(1) NOT NULL default '0',
  `site_key` varchar(50) NOT NULL default '',
  `added_on` datetime NOT NULL default '0000-00-00 00:00:00',
  `active` char(1) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `site_key` (`site_key`),
  KEY `group_id` (`group_id`),
  KEY `user_id` (`user_id`),
  KEY `added_on` (`added_on`),
  KEY `active` (`active`)
) TYPE=MyISAM AUTO_INCREMENT=23 ;


# table to hold the poll results

CREATE TABLE `[prefix]_poll_results` (
  `id` mediumint(8) NOT NULL auto_increment,
  `poll_id` int(8) NOT NULL default '0',
  `user_ip` varchar(20) NOT NULL default '',
  `user_id` mediumint(8) NOT NULL default '0',
  `label` varchar(255) NOT NULL default '0',
  `data` text NOT NULL,
  `site_key` varchar(50) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `site_key` (`site_key`),
  KEY `poll_id` (`poll_id`),
  KEY `user_ip` (`user_ip`),
  KEY `user_id` (`user_id`)
) TYPE=MyISAM AUTO_INCREMENT=22 ;


INSERT INTO [prefix]_modules ( id, module_key, title, created, author, version, skin_id, site_key ) VALUES (6, 'poll', 'Poll', '2004-07-02 00:00:00', 'Revlyn Williams and Darren Gates', '2.0', 0, 'default');

