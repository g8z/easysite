# realty module

drop table if exists [prefix]_realty_items ;

CREATE TABLE `[prefix]_realty_items` (
  `id` mediumint(8) NOT NULL auto_increment,
  `title` varchar(255) NOT NULL default '',
  `brief_description` text NOT NULL,
  `long_description` text NOT NULL,
  `cat_id` mediumint(8) NOT NULL default '0',
  `listing_date` date NOT NULL default '0000-00-00',
  `closing_date` date NOT NULL default '0000-00-00',
  `post_days` int(5) NOT NULL default '0',
  `site_key` varchar(50) NOT NULL default '',
  `user_id` int(11) NOT NULL default '0',
  `num_visits` int(11) NOT NULL default '0',
  `address_1` varchar(255) NOT NULL default '',
  `address_2` varchar(255) NOT NULL default '',
  `city` varchar(150) NOT NULL default '',
  `zip` varchar(20) NOT NULL default '',
  `district` varchar(150) NOT NULL default '',
  `county` varchar(150) NOT NULL default '',
  `state` varchar(100) NOT NULL default '',
  `country` varchar(100) NOT NULL default '',
  `list_price` int(11) NOT NULL default '0',
  `sell_price` int(11) NOT NULL default '0',
  `property_type` varchar(50) NOT NULL default '0',
  `bathrooms` int(2) NOT NULL default '0',
  `bedrooms` int(2) NOT NULL default '0',
  `fireplace` tinyint(1) NOT NULL default '0',
  `garage` tinyint(1) NOT NULL default '0',
  `floorsize` float(15,2) NOT NULL default '0.00',
  `lotsize` float(15,2) NOT NULL default '0.00',
  `home_age` char(3) NOT NULL default '',
  `near_school` tinyint(1) NOT NULL default '0',
  `near_transit` tinyint(1) NOT NULL default '0',
  `ocean_view` tinyint(1) NOT NULL default '0',
  `lake_view` tinyint(1) NOT NULL default '0',
  `mountain_view` tinyint(1) NOT NULL default '0',
  `ocean_front` tinyint(1) NOT NULL default '0',
  `lake_front` tinyint(1) NOT NULL default '0',
  `river_front` tinyint(1) NOT NULL default '0',
  `balcony` tinyint(1) NOT NULL default '0',
  `laundry` tinyint(1) NOT NULL default '0',
  `fitness_center` tinyint(1) NOT NULL default '0',
  `pool` tinyint(1) NOT NULL default '0',
  `num_stories` tinyint(1) NOT NULL default '0',
  `guest_house` tinyint(1) NOT NULL default '0',
  `jacuzzi` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `site_key` (`site_key`),
  KEY `cat_id` (`cat_id`)
) TYPE=MyISAM AUTO_INCREMENT=2342 ;

# add custom lists to the real estate module:
# 1) Countries
# 2) US States
# 3) Price Min
# 4) Price Max
# 5) Bedrooms Min
# 6) Bedrooms Max
# 7) Bathrooms Min
# 8) Bathrooms Max


INSERT INTO [prefix]_modules ( id, module_key, title, created, author, version, skin_id, site_key ) VALUES (5, 'realty', 'Real Estate', '2004-07-02 00:00:00', 'Revlyn Williams and Darren Gates', '1.0', 0, 'default');


# insert list data

INSERT INTO `[prefix]_lists` ( id, list_key, title, site_key, _order ) VALUES ('', 'bathroom_min', 'Real Estate - Bathrooms Minimum', 'default', 4);
INSERT INTO `[prefix]_lists` ( id, list_key, title, site_key, _order ) VALUES ('', 'price_min', 'Real Estate - Price Minimum', 'default', 3);
INSERT INTO `[prefix]_lists` ( id, list_key, title, site_key, _order ) VALUES ('', 'price_max', 'Real Estate - Price Maximum', 'default', 2);
INSERT INTO `[prefix]_lists` ( id, list_key, title, site_key, _order ) VALUES ('', 'bathroom_max', 'Real Estate - Bathrooms Maximum', 'default', 5);
INSERT INTO `[prefix]_lists` ( id, list_key, title, site_key, _order ) VALUES ('', 'bedroom_min', 'Real Estate - Bedrooms Minimum', 'default', 7);
INSERT INTO `[prefix]_lists` ( id, list_key, title, site_key, _order ) VALUES ('', 'bedroom_max', 'Real Estate - Bedrooms Maximum', 'default', 6);
INSERT INTO `[prefix]_lists` ( id, list_key, title, site_key, _order ) VALUES ('', 'days_old', 'Real Estate - Listing Days Old', 'default', 8);
INSERT INTO `[prefix]_lists` ( id, list_key, title, site_key, _order ) VALUES ('', 'num_bedrooms', 'Real Estate - Number of Bedrooms', 'default', 9);
INSERT INTO `[prefix]_lists` ( id, list_key, title, site_key, _order ) VALUES ('', 'num_bathrooms', 'Real Estate - Number of Bathrooms', 'default', 10);
INSERT INTO `[prefix]_lists` ( id, list_key, title, site_key, _order ) VALUES ('', 'num_stories', 'Real Estate - Number of Stories', 'default', 11);
INSERT INTO `[prefix]_lists` ( id, list_key, title, site_key, _order ) VALUES ('', 'home_age', 'Real Estate - Home Age', 'default', 12);
INSERT INTO `[prefix]_lists` ( id, list_key, title, site_key, _order ) VALUES ('', 'post_days', 'Real Estate - Post Days', 'default', 13);
INSERT INTO `[prefix]_lists` ( id, list_key, title, site_key, _order ) VALUES ('', 'cities', 'Real Estate - Cities', 'default', 14);
INSERT INTO `[prefix]_lists` ( id, list_key, title, site_key, _order ) VALUES ('', 'AreaCA01', 'Real Estate - 01CA - Cities', 'default', 15);

INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('price_min', 41, '5000000', '$3,000,000', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('price_min', 40, '3000000', '$2,000,000', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('price_max', 1, '75000', '$50,000', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('bathroom_min', 5, '5', '5 Bathrooms', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('bathroom_min', 4, '4', '4 Bathrooms', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('bathroom_max', 3, '3', '3', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('bathroom_max', 2, '2', '2', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('bathroom_max', 0, '0', '- Select Max Bathrooms -', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('bathroom_max', 1, '1', '1', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('bathroom_min', 3, '3', '3 Bathrooms', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('bedroom_min', 2, '2', '2', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('bedroom_min', 1, '1', '1', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('bedroom_max', 10, '8', '8 Bedrooms', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('bedroom_max', 9, '7', '7 Bedrooms', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('bedroom_min', 0, '0', '- Select Min Bedrooms -', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('days_old', 7, '30', '30', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('days_old', 6, '10', '10', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('days_old', 5, '5', '5', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('days_old', 4, '4', '4', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('days_old', 3, '3', '3', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('days_old', 1, '1', '1', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('days_old', 2, '2', '2', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('price_max', 0, '50000', '- Select Max Price -', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('bedrooms', 7, '7', '7', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('bedrooms', 6, '6', '6', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('bedrooms', 5, '5', '5', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('bedrooms', 4, '4', '4', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('bedrooms', 3, '3', '3', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('bedrooms', 2, '2', '2', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('bedrooms', 1, '1', '1', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('bedrooms', 0, '0', '- Num Bedrooms -', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('bathrooms', 9, '9', '9', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('bathrooms', 8, '8', '8', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('bathrooms', 7, '7', '7', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('bathrooms', 6, '6', '6', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('bathrooms', 5, '5', '5', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('num_stories', 5, '5', '5', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('num_stories', 4, '4', '4', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('num_stories', 3, '3', '3', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('num_stories', 2, '2', '2', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('num_stories', 1, '1', '1', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('num_stories', 0, '', '- Num Stories -', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('home_age', 29, '29', '29', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('home_age', 28, '28', '28', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('home_age', 27, '27', '27', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('home_age', 26, '26', '26', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('home_age', 25, '25', '25', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('home_age', 24, '24', '24', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('home_age', 23, '23', '23', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('home_age', 22, '22', '22', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('home_age', 20, '20', '20', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('home_age', 21, '21', '21', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('post_days', 10, '100', '100', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('post_days', 9, '90', '90', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('post_days', 8, '80', '80', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('post_days', 7, '70', '70', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('post_days', 6, '60', '60', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('post_days', 5, '50', '50', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('post_days', 4, '40', '40', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('post_days', 3, '30', '30', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('post_days', 2, '20', '20', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('post_days', 1, '10', '10', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('post_days', 0, '', '- Num Post Days -', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('home_age', 19, '19', '19', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('home_age', 18, '18', '18', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('home_age', 17, '17', '17', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('home_age', 16, '16', '16', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('home_age', 15, '15', '15', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('home_age', 14, '14', '14', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('home_age', 13, '13', '13', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('home_age', 12, '12', '12', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('home_age', 11, '11', '11', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('home_age', 10, '10', '10', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('home_age', 9, '9', '9', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('home_age', 8, '8', '8', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('home_age', 7, '7', '7', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('home_age', 6, '6', '6', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('home_age', 5, '5', '5', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('home_age', 4, '4', '4', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('home_age', 3, '3', '3', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('home_age', 2, '2', '2', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('home_age', 1, '1', '1', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('home_age', 0, '', '- Home Age in Years -', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('bedrooms', 8, '8', '8', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('bedrooms', 9, '9', '9', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('bedrooms', 10, '10', '10', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('bedrooms', 11, '11', '11', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('bedrooms', 12, '12', '12', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('bedrooms', 13, '13', '13', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('bedrooms', 14, '14', '14', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('bedrooms', 15, '15', '15', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('bathrooms', 4, '4', '4', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('bathrooms', 3, '3', '3', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('bathrooms', 2, '2', '2', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('bathrooms', 1, '1', '1', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('bathrooms', 0, '0', '- Num Bathrooms -', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('bathroom_min', 2, '2', '2 Bathrooms', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('bathroom_min', 1, '1', '1 Bathroom', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('bathroom_min', 0, '0', '- Select Min Bathrooms -', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('bathroom_max', 4, '4', '4', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('bathroom_max', 5, '5', '5', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('bathroom_max', 6, '6', '6', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('bathroom_max', 7, '7', '7', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('bathroom_max', 8, '8', '8', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('bathroom_max', 9, '9', '9', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('bathroom_max', 10, '10', '10', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('days_old', 0, '', '- Select Days -', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('home_age', 30, '30', '30', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('bedroom_max', 8, '6', '6 Bedrooms', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('bedroom_max', 7, '5', '5 Bedrooms', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('bedroom_max', 6, '4', '4 Bedrooms', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('bedroom_max', 5, '3', '3 Bedrooms', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('bedroom_max', 4, '2', '2 Bedrooms', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('bedroom_max', 3, '1', '1 Bedroom', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('bedroom_max', 2, 'Loft', 'Loft', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('bedroom_max', 1, 'Single', 'Single', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('bedroom_max', 0, '0', '- Select Max Bedrooms -', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('bedroom_min', 3, '3', '3', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('bedroom_min', 4, '4', '4', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('bedroom_min', 5, '5', '5', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('bedroom_min', 6, '6', '6', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('bedroom_min', 7, '7', '7', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('bedroom_min', 8, '8', '8', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('bedroom_min', 9, '9', '9', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('bedroom_min', 10, '10', '10', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('cities', 3, 'Third City', 'Third City', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('cities', 2, 'Second City', 'Second City', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('cities', 1, 'First City', 'First City', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('cities', 0, '', '- Select City or Region -', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('cities', 4, 'Fourth City', 'Fourth City', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 231, 'Woodland Hills', 'Winnetka', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 230, 'Wrigley', 'Windsor Hills', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 229, 'Winnetka', 'Wilmington', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 228, 'Windsor Hills', 'Willowbrook', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 227, 'Wilmington', 'Whittier', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 226, 'Willowbrook', 'Westwood', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 224, 'Westwood', 'Westlake village', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 225, 'Whittier', 'West Los Angeles', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 223, 'West Los Angeles', 'Westlake', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 221, 'Westlake', 'West Hills', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 222, 'Westlake village', 'West Hollywood', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 220, 'West Hollywood', 'West Covina', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 219, 'West Hills', 'Westchester', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 218, 'West Covina', 'Watts', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 216, 'Watts', 'Walteria', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 217, 'Westchester', 'Warner Center', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 215, 'Warner Center', 'Walnut Park', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 214, 'Walteria', 'Walnut', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 213, 'Walnut Park', 'view Park', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 212, 'Walnut', 'vernon', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 211, 'view Park', 'verdugo City', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 210, 'vernon', 'venice', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 208, 'venice', 'valley village', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 209, 'verdugo City', 'van nuys', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 207, 'van nuys', 'valinda', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 206, 'valley village', 'Universal City', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 205, 'valinda', 'Tujunga', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 204, 'Universal City', 'Trousdale Estates', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 203, 'Tujunga', 'Torrance', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 202, 'Trousdale Estates', 'Topanga', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 201, 'Torrance', 'Toluca Lake', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 200, 'Topanga', 'Terminal Island', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 199, 'Toluca Lake', 'Temple City', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 198, 'Terminal Island', 'Tarzana', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 197, 'Temple City', 'Sylia Park', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 196, 'Tarzana', 'Sylmar', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 195, 'Sylia Park', 'Sun valley', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 194, 'Sylmar', 'Sunland', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 193, 'Sun valley', 'Studio City', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 192, 'Sunland', 'Stevenson Ranch', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 191, 'Studio City', 'South San Gabriel', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 190, 'Stevenson Ranch', 'South Pasadena', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 189, 'South San Gabriel', 'South Gate', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 188, 'South Pasadena', 'South El Monte', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 187, 'South Gate', 'Silver Lake', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 186, 'South El Monte', 'Signal Hill', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 185, 'Silver Lake', 'Sierra Madre', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 184, 'Signal Hill', 'Sherman Oaks', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 183, 'Sierra Madre', 'Shadow Hills', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 182, 'Sherman Oaks', 'Sawtelle', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 181, 'Shadow Hills', 'Santa Monica', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 180, 'Sawtelle', 'Santa Fe Springs', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 179, 'Santa Monica', 'Santa Clarita', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 178, 'Santa Fe Springs', 'San Marino', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 177, 'Santa Clarita', 'San Gabriel', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 176, 'San Marino', 'San Fernando', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 175, 'San Gabriel', 'San Dimas', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 174, 'San Fernando', 'Rowland Heights', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 173, 'San Dimas', 'Rosemead', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 172, 'Rowland Heights', 'Rolling Hlls Estates', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 171, 'Rosemead', 'Rolling Hills', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 170, 'Rolling Hlls Estates', 'Reseda', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 168, 'Reseda', 'Red Hill', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 169, 'Rolling Hills', 'Redondo Beach', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 167, 'Redondo Beach', 'Rancho Park', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 166, 'Red Hill', 'Rancho Palos verdes', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 165, 'Rancho Park', 'Rancho Dominguez', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 164, 'Rancho Palos verdes', 'Portuguese Bend', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 163, 'Rancho Dominguez', 'Porter Ranch', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 162, 'Portuguese Bend', 'Pomona', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 161, 'Porter Ranch', 'Point Dume', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 160, 'Pomona', 'Playa Del Rey', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 159, 'Point Dume', 'Pico Riera', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 158, 'Playa Del Rey', 'Pico', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 157, 'Pico Riera', 'Phillips Ranch', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 156, 'Pico', 'Playa Del Rey', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 155, 'Phillips Ranch', 'Pico', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 154, 'Playa Del Rey', 'Philllips Ranch', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 153, 'Pico', 'Pasadena', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 152, 'Philllips Ranch', 'Park La Brea', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 151, 'Pasadena', 'Paramount', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 149, 'Paramount', 'Palos verdes Estates', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 150, 'Park La Brea', 'Panorama City', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 148, 'Panorama City', 'Palms', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 147, 'Palos verdes Estates', 'Palisades Highlands', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 146, 'Palms', 'Pacoima', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 145, 'Palisades Highlands', 'Pacific Palisades', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 144, 'Pacoima', 'Olive view', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 143, 'Pacific Palisades', 'Old Canyon', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 142, 'Olive view', 'Ocean Park', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 141, 'Old Canyon', 'Norwood illage', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 140, 'Ocean Park', 'Norwalk', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 139, 'Norwood illage', 'Northridge', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 138, 'Norwalk', 'North Long Beach', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 137, 'Northridge', 'North Hollywood', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 136, 'North Long Beach', 'North Hills', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 134, 'North Hills', 'Mount Washington', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 135, 'North Hollywood', 'Naples', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 132, 'Mount Washington', 'Morningside Park', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 133, 'Naples', 'Mount Olympus', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 131, 'Mount Olympus', 'Montrose', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 130, 'Morningside Park', 'Monterey Park', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 129, 'Montrose', 'Monterey Hills', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('price_max', 2, '100000', '$75,000', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('price_max', 3, '125000', '$100,000', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('price_max', 4, '150000', '$125,000', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('price_max', 5, '175000', '$150,000', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('price_max', 6, '200000', '$175,000', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('price_max', 7, '225000', '$200,000', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('price_max', 8, '250000', '$225,000', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('price_max', 9, '275000', '$250,000', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('price_max', 10, '300000', '$275,000', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('price_max', 11, '325000', '$300,000', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('price_max', 12, '350000', '$325,000', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('price_max', 13, '375000', '$350,000', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('price_max', 14, '400000', '$375,000', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('price_max', 15, '425000', '$400,000', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('price_max', 16, '450000', '$425,000', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('price_max', 17, '475000', '$450,000', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('price_max', 18, '500000', '$475,000', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('price_max', 19, '525000', '$500,000', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('price_max', 20, '550000', '$525,000', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('price_max', 21, '575000', '$550,000', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('price_max', 22, '600000', '$575,000', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('price_max', 23, '625000', '$600,000', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('price_max', 24, '650000', '$625,000', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('price_max', 25, '675000', '$650,000', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('price_max', 26, '700000', '$675,000', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('price_max', 27, '725000', '$700,000', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('price_max', 28, '750000', '$725,000', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('price_max', 29, '775000', '$750,000', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('price_max', 30, '800000', '$775,000', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('price_max', 31, '825000', '$800,000', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('price_max', 32, '850000', '$825,000', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('price_max', 33, '875000', '$850,000', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('price_max', 34, '900000', '$875,000', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('price_max', 35, '925000', '$900,000', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('price_max', 36, '950000', '$925,000', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('price_max', 37, '975000', '$950,000', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('price_max', 38, '1000000', '$975,000', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('price_max', 39, '2000000', '$1,000,000', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('price_max', 40, '3000000', '$2,000,000', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('price_max', 41, '5000000', '$3,000,000', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('price_max', 42, '10000000', '$5,000,000', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('price_max', 43, '', '$10,000,000', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('price_max', 44, '', '', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('price_min', 42, '10000000', '$5,000,000', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('price_min', 39, '2000000', '$1,000,000', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('price_min', 38, '1000000', '$975,000', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('price_min', 37, '975000', '$950,000', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('price_min', 36, '950000', '$925,000', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('price_min', 35, '925000', '$900,000', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('price_min', 34, '900000', '$875,000', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('price_min', 33, '875000', '$850,000', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('price_min', 32, '850000', '$825,000', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('price_min', 31, '825000', '$800,000', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('price_min', 30, '800000', '$775,000', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('price_min', 29, '775000', '$750,000', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('price_min', 28, '750000', '$725,000', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('price_min', 27, '725000', '$700,000', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('price_min', 26, '700000', '$675,000', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('price_min', 25, '675000', '$650,000', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('price_min', 24, '650000', '$625,000', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('price_min', 23, '625000', '$600,000', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('price_min', 22, '600000', '$575,000', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('price_min', 21, '575000', '$550,000', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('price_min', 20, '550000', '$525,000', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('price_min', 19, '525000', '$500,000', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('price_min', 18, '500000', '$475,000', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('price_min', 17, '475000', '$450,000', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('price_min', 16, '450000', '$425,000', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('price_min', 15, '425000', '$400,000', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('price_min', 14, '400000', '$375,000', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('price_min', 13, '375000', '$350,000', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('price_min', 12, '350000', '$325,000', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('price_min', 11, '325000', '$300,000', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('price_min', 10, '300000', '$275,000', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('price_min', 9, '275000', '$250,000', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('price_min', 8, '250000', '$225,000', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('price_min', 7, '225000', '$200,000', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('price_min', 6, '200000', '$175,000', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('price_min', 5, '175000', '$150,000', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('price_min', 4, '150000', '$125,000', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('price_min', 3, '125000', '$100,000', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('price_min', 2, '100000', '$75,000', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('price_min', 1, '75000', '$50,000', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('price_min', 0, '50000', '- Select Min Price -', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('price_min', 43, '', '$10,000,000', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('num_bathrooms', 5, '6', '5 Bathrooms', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('num_bathrooms', 4, '5', '4 Bathrooms', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('num_bathrooms', 3, '4', '3 Bathrooms', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('num_bathrooms', 2, '3', '2 Bathrooms', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('num_bathrooms', 1, '2', '1 Bathroom', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('num_bathrooms', 0, '1', '- Number of Bathrooms -', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('num_bathrooms', 6, '7', '6 Bathrooms', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('num_bathrooms', 7, '8', '7 Bathrooms', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('num_bathrooms', 8, '9', '8 Bathrooms', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('num_bathrooms', 9, '', '9 Bathrooms', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('num_bathrooms', 10, '', '', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('bathroom_min', 6, '6', '6 Bathrooms', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('bathroom_min', 7, '7', '7 Bathrooms', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('bathroom_min', 8, '8', '8 Bathrooms', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('bathroom_min', 9, '9', '9 Bathrooms', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('bathroom_min', 10, '', '', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 128, 'Monterey Park', 'Monte Nido', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 127, 'Monterey Hills', 'Montecito Heights', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 126, 'Monte Nido', 'Monrovia', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 125, 'Montecito Heights', 'Moneta', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 124, 'Monrovia', 'Mission Hills', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 122, 'Mission Hills', 'Mid-City', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 123, 'Moneta', 'Miraleste', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 120, 'Mid-City', 'Mayfair', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 121, 'Miraleste', 'Maywood', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 119, 'Maywood', 'Mar vista', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 118, 'Mayfair', 'Marina Del Rey', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 117, 'Mar vista', 'Manhattan Beach', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 116, 'Marina Del Rey', 'Malibu Bowl', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 115, 'Manhattan Beach', 'Malibu', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 114, 'Malibu Bowl', 'Lynwood', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 113, 'Malibu', 'Los Nietos', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 112, 'Lynwood', 'Los Feliz', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 111, 'Los Nietos', 'Los Angeles', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 110, 'Los Feliz', 'Los Altos', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 109, 'Los Angeles', 'Long Beach', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 108, 'Los Altos', 'Lomita', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 107, 'Long Beach', 'Lennox', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 106, 'Lomita', 'Leimert Park', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 105, 'Lennox', 'Lawndale', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 103, 'Lawndale', 'La Puente', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 104, 'Leimert Park', 'La verne', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 102, 'La verne', 'La Mirada', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 101, 'La Puente', 'Lakewood', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 100, 'La Mirada', 'Lake view Terrace', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 96, 'Ladera Heights', 'LaCanada Flintridge', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 99, 'Lakewood', 'La Habra Heights', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 98, 'Lake view Terrace', 'Ladera Heights', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 97, 'La Habra Heights', 'La Crescenta', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 95, 'La Crescenta', 'Koreatown', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 94, 'LaCanada Flintridge', 'Kagel Canyon', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 93, 'Koreatown', 'Jefferson Park', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 90, 'Irwindale', 'Hyde Park', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 92, 'Kagel Canyon', 'Irwindale', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 91, 'Jefferson Park', 'Inglewood', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 89, 'Inglewood', 'Highland Park', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 88, 'Hyde Park', 'Hidden Hills', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 87, 'Highland Park', 'Hermosa Beach', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 86, 'Hidden Hills', 'Hawthorne', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 85, 'Hermosa Beach', 'Hawaiian Gardens', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 84, 'Hawthorne', 'Harbor Gateway', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 82, 'Harbor Gateway', 'Hancock Park', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 83, 'Hawaiian Gardens', 'Harbor City', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 81, 'Harbor City', 'Hacienda Heights', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 80, 'Hancock Park', 'Granada Hills', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 79, 'Hacienda Heights', 'Glenview', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 78, 'Granada Hills', 'Glendora', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 77, 'Glenview', 'Glendale', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 76, 'Glendora', 'Glassell Park', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 75, 'Glendale', 'Garden Grove', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 74, 'Glassell Park', 'Gardena', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 73, 'Garden Grove', 'Fullerton', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 72, 'Gardena', 'Fox Hills', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 71, 'Fullerton', 'Fountain valley', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 70, 'Fox Hills', 'Foothill Ranch', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 69, 'Fountain valley', 'Florence', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 68, 'Foothill Ranch', 'Fernwood', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 67, 'Florence', 'Encino', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 66, 'Fernwood', 'El Sereno', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 65, 'Encino', 'El Segundo', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 64, 'El Sereno', 'El Porto', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 63, 'El Segundo', 'El Nido', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 62, 'El Porto', 'El Monte', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 61, 'El Nido', 'El Camino village', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 60, 'El Monte', 'Echo Park', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 59, 'El Camino village', 'East Los Angeles', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 58, 'Echo Park', 'Eagle Rock', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 57, 'East Los Angeles', 'Duarte', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 55, 'Duarte', 'Dominguez', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 56, 'Eagle Rock', 'Downey', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 54, 'Downey', 'Diamond Bar', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 53, 'Dominguez', 'Cypress Park', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 52, 'Diamond Bar', 'Culer City', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 51, 'Cypress Park', 'Cudahy', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 50, 'Culer City', 'Crenshaw District', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 49, 'Cudahy', 'Covina', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 48, 'Crenshaw District', 'Country Club Park', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 47, 'Covina', 'Cornell', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 46, 'Country Club Park', 'Compton', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 45, 'Cornell', 'Claremont', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 44, 'Compton', 'City Terrace', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 43, 'Claremont', 'City of Industry', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 42, 'City Terrace', 'City of Commerce', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 41, 'City of Industry', 'Chinatown', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 40, 'City of Commerce', 'Cheviot Hills', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 39, 'Chinatown', 'Chatsworth', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 38, 'Cheviot Hills', 'Charter Oak', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 37, 'Chatsworth', 'Cerritos', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 36, 'Charter Oak', 'Century City', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 35, 'Cerritos', 'Castellammare', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 34, 'Century City', 'Carson', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 33, 'Castellammare', 'Canoga Park', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 32, 'Carson', 'Calabasas Park', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 31, 'Canoga Park', 'Calabasas Highlands', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 30, 'Calabasas Park', 'Calabasas', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 29, 'Calabasas Highlands', 'Burbank', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 28, 'Calabasas', 'Brentwood', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 27, 'Burbank', 'Bradbury', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 26, 'Brentwood', 'Boyle Heights', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 25, 'Bradbury', 'Bixby Knolls', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 24, 'Boyle Heights', 'Big Rock', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 23, 'Bixby Knolls', 'Beverly Hills', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 22, 'Big Rock', 'Beverly Glen', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 21, 'Beverly Hills', 'Belmont Shore', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 20, 'Beverly Glen', 'Bell Gardens', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 19, 'Belmont Shore', 'Bellflower', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 18, 'Bell Gardens', 'Bell', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 17, 'Bellflower', 'Bel Air Estates', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 16, 'Bell', 'Bassett', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 15, 'Bel Air Estates', 'Baldwin Park', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 14, 'Bassett', 'Baldwin Hills', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 13, 'Baldwin Park', 'Azusa', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 12, 'Baldwin Hills', 'Avocado Heights', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 11, 'Azusa', 'Avalon', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 10, 'Avocado Heights', 'Atwater village', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 9, 'Avalon', 'Athens', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 8, 'Atwater village', 'Artesia', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 7, 'Athens', 'Arleta', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 6, 'Artesia', 'Arcadia', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 5, 'Arleta', 'Altadena', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 4, 'Arcadia', 'Alhambra', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 3, 'Altadena', 'Agoura Hills', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 2, 'Alhambra', 'Agoura', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 1, 'Agoura Hills', '-Los Angeles County-', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 0, 'Agoura', '- Select County, City / Community -', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 232, 'Woodside village', 'Wrigley', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 233, '', 'Woodland Hills', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 234, '- Orange County -', 'Woodside village', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 235, 'Aliso viejo', '', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 236, 'Anaheim', '-Orange County -', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 237, 'Anaheim Hills', 'Aliso viejo', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 238, 'Atwood', 'Anaheim', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 239, 'Balboa', 'Anaheim Hills', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 240, 'Balboa Island', 'Atwood', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 241, 'Brea', 'Balboa', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 242, 'Buena Park', 'Balboa Island', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 243, 'Capistrano Beach', 'Brea', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 244, 'Corona Del Mar', 'Buena Park', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 245, 'Costa Mesa', 'Capistrano Beach', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 246, 'Coto De Caza', 'Corona Del Mar', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 247, 'Cowan Heights', 'Costa Mesa', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 248, 'Cypress', 'Coto De Caza', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 249, 'Dana Point', 'Cowan Heights', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 250, 'Dove Canyon', 'Cypress', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 251, '', 'Dana Point', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 252, '', 'Dove Canyon', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 253, '- Riverside -', '', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 254, '', '', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 255, '- San Bernardino -', '- Riverside -', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 256, 'Chino', '', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 257, '', '- San Bernardino -', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 258, '- San Diego -', 'Chino', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 259, '', '', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 260, '- Ventura County -', '- San Diego -', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 261, 'Bell Canyon', '', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 262, '', '- ventura County -', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 263, '', 'Bell Canyon', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 264, '', '', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('AreaCA01', 265, '', '', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('bedroom_max', 11, '9+', '9+ Bedrooms', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('bedroom_max', 12, '', '', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('CA02-Cities', 0, '', '', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('num_bedrooms', 5, '6', '5 Bedrooms', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('num_bedrooms', 4, '5', '4 Bedrooms', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('num_bedrooms', 3, '4', '3 Bedrooms', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('num_bedrooms', 2, '3', '2 Bedrooms', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('num_bedrooms', 1, '2', '1 Bathroom', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('num_bedrooms', 0, '1', '- Number of Bedrooms -', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('num_bedrooms', 6, '7', '6 Bedrooms', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('num_bedrooms', 7, '8', '7 Bedrooms', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('num_bedrooms', 8, '9', '8 Bedrooms', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('num_bedrooms', 9, '', '9 Bedrooms', 'default');
INSERT INTO [prefix]_list_items ( list_key, _order, data, label, site_key ) VALUES ('num_bedrooms', 10, '', '', 'default');

