# image gallery module

drop table if exists [prefix]_gallery_items ;
drop table if exists [prefix]_gallery_display_options ;
drop table if exists [prefix]_gallery_manufacturers ;
drop table if exists [prefix]_gallery_product_attributes ;
drop table if exists [prefix]_gallery_product_values ;
drop table if exists [prefix]_gallery_shipping_options ;
drop table if exists [prefix]_gallery_orders;
drop table if exists [prefix]_gallery_order_contents;
drop table if exists [prefix]_es_gallery_item_cat;
drop table if exists [prefix]_gallery_att_price_values;
drop table if exists [prefix]_gallery_att_pricing;


CREATE TABLE `[prefix]_gallery_att_price_values` (
  `id` int(8) NOT NULL auto_increment,
  `attr_id` int(8) NOT NULL default '0',
  `price_id` int(8) NOT NULL default '0',
  `value1` text NOT NULL,
  `value2` text NOT NULL,
  `site_key` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `attr_id` (`attr_id`),
  KEY `price_id` (`price_id`),
  KEY `site_key` (`site_key`)
) TYPE=MyISAM AUTO_INCREMENT=8 ;



CREATE TABLE `[prefix]_gallery_att_pricing` (
  `id` int(8) NOT NULL auto_increment,
  `product_id` int(8) NOT NULL default '0',
  `fixed_price` float NOT NULL default '0',
  `delta_price` varchar(10) NOT NULL default '',
  `delta_type` varchar(10) NOT NULL default '',
  `delta_item` varchar(10) NOT NULL default '',
  `quantity` int(8) NOT NULL default '0',
  `type` varchar(10) NOT NULL default '0',
  `site_key` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `product_id` (`product_id`),
  KEY `site_key` (`site_key`)
) TYPE=MyISAM AUTO_INCREMENT=7 ;


CREATE TABLE `[prefix]_gallery_display_options` (
  `id` int(8) NOT NULL auto_increment,
  `field_id` varchar(50) NOT NULL default '',
  `section` varchar(10) NOT NULL default 'top',
  `align` varchar(10) NOT NULL default '',
  `style` varchar(50) NOT NULL default '',
  `layout` text NOT NULL,
  `visible` int(1) NOT NULL default '1',
  `type` varchar(10) NOT NULL default '',
  `row_position` int(2) NOT NULL default '0',
  `row` int(2) NOT NULL default '0',
  `site_key` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `field_id` (`field_id`),
  KEY `site_key` (`site_key`)
);

-- --------------------------------------------------------

-- 
-- Table structure for table `[prefix]_gallery_item_cat`
-- 

CREATE TABLE `[prefix]_gallery_item_cat` (
  `id` int(8) NOT NULL auto_increment,
  `img_id` int(8) NOT NULL default '0',
  `cat_id` int(8) NOT NULL default '0',
  `_order` int(8) NOT NULL default '0',
  `site_key` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `img_id` (`img_id`),
  KEY `cat_id` (`cat_id`),
  KEY `site_key` (`site_key`)
);

CREATE TABLE `[prefix]_gallery_items` (
  `id` mediumint(8) NOT NULL auto_increment,
  `title` varchar(255) NOT NULL default '',
  `description` text NOT NULL,
  `created` date NOT NULL default '0000-00-00',
  `site_key` varchar(50) NOT NULL default '',
  `img_thumb` mediumblob NOT NULL,
  `img_large` mediumblob NOT NULL,
  `price` float NOT NULL default '0',
  `quantity` int(7) NOT NULL default '0',
  `use_cat_price` int(1) NOT NULL default '1',
  `man_id` int(8) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `site_key` (`site_key`),
  KEY `man_id` (`man_id`)
);

CREATE TABLE `[prefix]_gallery_manufacturers` (
  `id` int(8) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL default '',
  `url` varchar(255) NOT NULL default '',
  `logo` mediumtext NOT NULL,
  `site_key` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `site_key` (`site_key`)
);


CREATE TABLE `[prefix]_gallery_order_contents` (
  `id` int(8) NOT NULL auto_increment,
  `order_id` int(8) NOT NULL default '0',
  `item_id` int(8) NOT NULL default '0',
  `item_title` varchar(255) NOT NULL default '',
  `item_count` int(3) NOT NULL default '0',
  `item_price` float NOT NULL default '0',
  `site_key` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `order_id` (`order_id`),
  KEY `site_key` (`site_key`),
  KEY `item_id` (`item_id`)
);

CREATE TABLE `[prefix]_gallery_orders` (
  `id` int(8) NOT NULL auto_increment,
  `txn_id` int(8) NOT NULL default '0',
  `first_name` varchar(100) NOT NULL default '',
  `last_name` varchar(100) NOT NULL default '',
  `email` varchar(100) NOT NULL default '',
  `phone` varchar(20) NOT NULL default '',
  `country` varchar(100) NOT NULL default '',
  `state` varchar(100) NOT NULL default '',
  `city` varchar(100) NOT NULL default '',
  `address_1` varchar(100) NOT NULL default '',
  `address_2` varchar(100) NOT NULL default '',
  `zip` varchar(10) NOT NULL default '',
  `total_amount` float NOT NULL default '0',
  `tax` float NOT NULL default '0',
  `discount` float NOT NULL default '0',
  `creation_date` date NOT NULL default '0000-00-00',
  `shipping_method` int(8) NOT NULL default '0',
  `payment_method` varchar(50) NOT NULL default '',
  `status` varchar(50) NOT NULL default '',
  `site_key` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `site_key` (`site_key`),
  KEY `txn_id` (`txn_id`)
);

CREATE TABLE `[prefix]_gallery_product_attributes` (
  `id` int(8) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL default '',
  `measurement` varchar(50) NOT NULL default '',
  `type` varchar(50) NOT NULL default '',
  `_default` text NOT NULL,
  `visible` int(1) NOT NULL default '1',
  `site_key` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `site_key` (`site_key`)
);

CREATE TABLE `[prefix]_gallery_product_values` (
  `id` int(8) NOT NULL auto_increment,
  `product_id` int(8) NOT NULL default '0',
  `attr_id` int(8) NOT NULL default '0',
  `value` text NOT NULL,
  `use_default` int(1) NOT NULL default '1',
  `site_key` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `product_id` (`product_id`),
  KEY `attr_id` (`attr_id`),
  KEY `site_key` (`site_key`)
);

CREATE TABLE `[prefix]_gallery_shipping_options` (
  `id` int(8) NOT NULL auto_increment,
  `name` varchar(100) NOT NULL default '',
  `price` float NOT NULL default '0',
  `period` int(3) NOT NULL default '0',
  `p_item` varchar(10) NOT NULL default '',
  `visible` int(1) NOT NULL default '1',
  `site_key` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `site_key` (`site_key`)
);



INSERT INTO [prefix]_modules ( id, module_key, title, created, author, version, skin_id, site_key ) VALUES (4, 'gallery', 'Product Gallery', '2004-07-02 00:00:00', 'Alexander Pereverzev and Darren Gates', '2.0', 0, 'default');





INSERT INTO `[prefix]_gallery_display_options` (`id`, `field_id`, `section`, `align`, `style`, `layout`, `visible`, `type`, `row_position`, `row`, `site_key`) VALUES (1, 'title', 'top', 'center', 'subtitle', '{$value}', 1, 'full', 1, 1, 'default');
INSERT INTO `[prefix]_gallery_display_options` (`id`, `field_id`, `section`, `align`, `style`, `layout`, `visible`, `type`, `row_position`, `row`, `site_key`) VALUES (2, 'description', 'bottom', 'left', 'normal', '{$value}', 1, 'full', 1, 4, 'default');
INSERT INTO `[prefix]_gallery_display_options` (`id`, `field_id`, `section`, `align`, `style`, `layout`, `visible`, `type`, `row_position`, `row`, `site_key`) VALUES (3, 'items_count', 'bottom', 'center', 'normal', '{$value} products', 1, 'cat_thumb', 1, 1, 'default');
INSERT INTO `[prefix]_gallery_display_options` (`id`, `field_id`, `section`, `align`, `style`, `layout`, `visible`, `type`, `row_position`, `row`, `site_key`) VALUES (4, 'category_title', 'top', 'center', 'normal', '{$value}', 1, 'cat_thumb', 1, 1, 'default');
INSERT INTO `[prefix]_gallery_display_options` (`id`, `field_id`, `section`, `align`, `style`, `layout`, `visible`, `type`, `row_position`, `row`, `site_key`) VALUES (36, '9', '', '', '', '', 0, 'cat_thumb', 0, 0, 'default');
INSERT INTO `[prefix]_gallery_display_options` (`id`, `field_id`, `section`, `align`, `style`, `layout`, `visible`, `type`, `row_position`, `row`, `site_key`) VALUES (35, '11', '', '', '', '', 0, 'cat_thumb', 0, 0, 'default');
INSERT INTO `[prefix]_gallery_display_options` (`id`, `field_id`, `section`, `align`, `style`, `layout`, `visible`, `type`, `row_position`, `row`, `site_key`) VALUES (15, '11', 'right', 'left', 'normal', '{$value}', 0, 'pr_thumb', 1, 101, 'default');
INSERT INTO `[prefix]_gallery_display_options` (`id`, `field_id`, `section`, `align`, `style`, `layout`, `visible`, `type`, `row_position`, `row`, `site_key`) VALUES (16, '9', 'left', 'left', 'normal', '{$value}', 0, 'pr_thumb', 1, 101, 'default');
INSERT INTO `[prefix]_gallery_display_options` (`id`, `field_id`, `section`, `align`, `style`, `layout`, `visible`, `type`, `row_position`, `row`, `site_key`) VALUES (47, 'manufacturer', 'bottom', 'left', 'normal', '{$name}: {$value}', 0, 'full', 0, 2700, 'default');
INSERT INTO `[prefix]_gallery_display_options` (`id`, `field_id`, `section`, `align`, `style`, `layout`, `visible`, `type`, `row_position`, `row`, `site_key`) VALUES (22, 'title', 'top', 'center', 'subtitle', '{$value}', 1, 'pr_thumb', 1, 1, 'default');
INSERT INTO `[prefix]_gallery_display_options` (`id`, `field_id`, `section`, `align`, `style`, `layout`, `visible`, `type`, `row_position`, `row`, `site_key`) VALUES (23, 'description', 'right', 'left', 'normal', '', 0, 'pr_thumb', 1, 301, 'default');
INSERT INTO `[prefix]_gallery_display_options` (`id`, `field_id`, `section`, `align`, `style`, `layout`, `visible`, `type`, `row_position`, `row`, `site_key`) VALUES (24, 'price', 'bottom', 'left', 'normal', '<b>{$value}</b>', 1, 'pr_thumb', 1, 1, 'default');
INSERT INTO `[prefix]_gallery_display_options` (`id`, `field_id`, `section`, `align`, `style`, `layout`, `visible`, `type`, `row_position`, `row`, `site_key`) VALUES (25, 'quantity', 'bottom', 'left', 'normal', '', 0, 'pr_thumb', 3, 101, 'default');
INSERT INTO `[prefix]_gallery_display_options` (`id`, `field_id`, `section`, `align`, `style`, `layout`, `visible`, `type`, `row_position`, `row`, `site_key`) VALUES (26, 'manufacturer', 'top', 'left', '', '', 0, 'pr_thumb', 0, 400, 'default');
INSERT INTO `[prefix]_gallery_display_options` (`id`, `field_id`, `section`, `align`, `style`, `layout`, `visible`, `type`, `row_position`, `row`, `site_key`) VALUES (31, '9', 'bottom', 'left', 'normal', '<b>{$name}:</b> {$value}', 1, 'full', 1, 1, 'default');
INSERT INTO `[prefix]_gallery_display_options` (`id`, `field_id`, `section`, `align`, `style`, `layout`, `visible`, `type`, `row_position`, `row`, `site_key`) VALUES (30, '11', 'bottom', 'left', 'normal', '<b>{$name}:</b> {$value}', 1, 'full', 1, 2, 'default');
INSERT INTO `[prefix]_gallery_display_options` (`id`, `field_id`, `section`, `align`, `style`, `layout`, `visible`, `type`, `row_position`, `row`, `site_key`) VALUES (45, 'price', 'top', 'left', 'subtitle', '{$value}', 1, 'full', 1, 2, 'default');
INSERT INTO `[prefix]_gallery_display_options` (`id`, `field_id`, `section`, `align`, `style`, `layout`, `visible`, `type`, `row_position`, `row`, `site_key`) VALUES (46, 'quantity', 'bottom', 'left', 'normal', '<b>{$name}:</b> {$value}', 1, 'full', 1, 3, 'default');
INSERT INTO `[prefix]_gallery_display_options` (`id`, `field_id`, `section`, `align`, `style`, `layout`, `visible`, `type`, `row_position`, `row`, `site_key`) VALUES (48, 'add_to_cart', 'top', 'right', 'normal', 'Add To Cart', 1, 'full', 2, 2, 'default');
INSERT INTO `[prefix]_gallery_display_options` (`id`, `field_id`, `section`, `align`, `style`, `layout`, `visible`, `type`, `row_position`, `row`, `site_key`) VALUES (49, 'add_to_cart', 'bottom', 'right', 'normal', 'Add To Cart', 1, 'pr_thumb', 2, 1, 'default');


INSERT INTO `[prefix]_gallery_manufacturers` (`id`, `name`, `url`, `logo`, `site_key`) VALUES (4, 'test', 'test.com', '', 'default');


INSERT INTO `[prefix]_gallery_product_attributes` (`id`, `name`, `measurement`, `type`, `visible`, `site_key`) VALUES (11, 'Height', 'm', 'number', 1, 'default');
INSERT INTO `[prefix]_gallery_product_attributes` (`id`, `name`, `measurement`, `type`, `visible`, `site_key`) VALUES (9, 'Width', 'm', 'number', 1, 'default');


INSERT INTO `[prefix]_gallery_shipping_options` (`id`, `name`, `price`, `period`, `p_item`, `visible`, `site_key`) VALUES (3, 'Standard Post', 3.95, 4, 'day', 1, 'default');
INSERT INTO `[prefix]_gallery_shipping_options` (`id`, `name`, `price`, `period`, `p_item`, `visible`, `site_key`) VALUES (2, 'Next-Day Air', 10.95, 1, 'day', 1, 'default');
INSERT INTO `[prefix]_gallery_shipping_options` (`id`, `name`, `price`, `period`, `p_item`, `visible`, `site_key`) VALUES (4, 'Free Shipping', 0, 0, 'day', 1, 'default');

INSERT INTO `[prefix]_module_settings` (`id`, `name`, `value`, `cat_id`, `site_key`, `module_key`) VALUES (1, 'search_title', 'Image Gallery', 0, 'default', 'gallery');
INSERT INTO `[prefix]_module_settings` (`id`, `name`, `value`, `cat_id`, `site_key`, `module_key`) VALUES (2, 'search_desc', '', 0, 'default', 'gallery');
INSERT INTO `[prefix]_module_settings` (`id`, `name`, `value`, `cat_id`, `site_key`, `module_key`) VALUES (3, 'useEcommerce', 'yes', 0, 'default', 'gallery');
INSERT INTO `[prefix]_module_settings` (`id`, `name`, `value`, `cat_id`, `site_key`, `module_key`) VALUES (4, 'createThumb', 'yes', 0, 'default', 'gallery');
INSERT INTO `[prefix]_module_settings` (`id`, `name`, `value`, `cat_id`, `site_key`, `module_key`) VALUES (5, 'imageWidth', '800', 0, 'default', 'gallery');
INSERT INTO `[prefix]_module_settings` (`id`, `name`, `value`, `cat_id`, `site_key`, `module_key`) VALUES (6, 'imageHeight', '600', 0, 'default', 'gallery');
INSERT INTO `[prefix]_module_settings` (`id`, `name`, `value`, `cat_id`, `site_key`, `module_key`) VALUES (7, 'thumbnailWidth', '100', 0, 'default', 'gallery');
INSERT INTO `[prefix]_module_settings` (`id`, `name`, `value`, `cat_id`, `site_key`, `module_key`) VALUES (8, 'thumbnailHeight', '100', 0, 'default', 'gallery');
INSERT INTO `[prefix]_module_settings` (`id`, `name`, `value`, `cat_id`, `site_key`, `module_key`) VALUES (9, 'gridWidth', '5', 0, 'default', 'gallery');
INSERT INTO `[prefix]_module_settings` (`id`, `name`, `value`, `cat_id`, `site_key`, `module_key`) VALUES (10, 'gridHeight', '5', 0, 'default', 'gallery');
INSERT INTO `[prefix]_module_settings` (`id`, `name`, `value`, `cat_id`, `site_key`, `module_key`) VALUES (11, 'galleryName', 'Image Gallery', 0, 'default', 'gallery');
INSERT INTO `[prefix]_module_settings` (`id`, `name`, `value`, `cat_id`, `site_key`, `module_key`) VALUES (12, 'align', 'center', 0, 'default', 'gallery');
INSERT INTO `[prefix]_module_settings` (`id`, `name`, `value`, `cat_id`, `site_key`, `module_key`) VALUES (13, 'showNav', 'yes', 0, 'default', 'gallery');
INSERT INTO `[prefix]_module_settings` (`id`, `name`, `value`, `cat_id`, `site_key`, `module_key`) VALUES (14, 'showName', 'yes', 0, 'default', 'gallery');
INSERT INTO `[prefix]_module_settings` (`id`, `name`, `value`, `cat_id`, `site_key`, `module_key`) VALUES (15, 'showDesc', 'yes', 0, 'default', 'gallery');
INSERT INTO `[prefix]_module_settings` (`id`, `name`, `value`, `cat_id`, `site_key`, `module_key`) VALUES (16, 'showTitle', 'yes', 0, 'default', 'gallery');
INSERT INTO `[prefix]_module_settings` (`id`, `name`, `value`, `cat_id`, `site_key`, `module_key`) VALUES (17, 'titlePosition', 'bottom', 0, 'default', 'gallery');
INSERT INTO `[prefix]_module_settings` (`id`, `name`, `value`, `cat_id`, `site_key`, `module_key`) VALUES (18, 'imgBorderSize', '1', 0, 'default', 'gallery');
INSERT INTO `[prefix]_module_settings` (`id`, `name`, `value`, `cat_id`, `site_key`, `module_key`) VALUES (19, 'borderColor', '#000000', 0, 'default', 'gallery');
INSERT INTO `[prefix]_module_settings` (`id`, `name`, `value`, `cat_id`, `site_key`, `module_key`) VALUES (20, 'catImageWidth', '', 0, 'default', 'gallery');
INSERT INTO `[prefix]_module_settings` (`id`, `name`, `value`, `cat_id`, `site_key`, `module_key`) VALUES (21, 'catImageHeight', '', 0, 'default', 'gallery');
INSERT INTO `[prefix]_module_settings` (`id`, `name`, `value`, `cat_id`, `site_key`, `module_key`) VALUES (28, 'fImgBorderSize', '0', 0, 'default', 'gallery');
INSERT INTO `[prefix]_module_settings` (`id`, `name`, `value`, `cat_id`, `site_key`, `module_key`) VALUES (27, 'tImgBorderColor', '#000000', 0, 'default', 'gallery');
INSERT INTO `[prefix]_module_settings` (`id`, `name`, `value`, `cat_id`, `site_key`, `module_key`) VALUES (26, 'tImgBorderSize', '0', 0, 'default', 'gallery');
INSERT INTO `[prefix]_module_settings` (`id`, `name`, `value`, `cat_id`, `site_key`, `module_key`) VALUES (29, 'fImgBorderColor', '#000000', 0, 'default', 'gallery');
INSERT INTO `[prefix]_module_settings` (`id`, `name`, `value`, `cat_id`, `site_key`, `module_key`) VALUES (31, 'paymentGateway', 'PayPal', 0, 'default', 'gallery');
INSERT INTO `[prefix]_module_settings` (`id`, `name`, `value`, `cat_id`, `site_key`, `module_key`) VALUES (32, 'emailReceipt', 'yes', 0, 'default', 'gallery');
INSERT INTO `[prefix]_module_settings` (`id`, `name`, `value`, `cat_id`, `site_key`, `module_key`) VALUES (33, 'processingScript', '', 0, 'default', 'gallery');
INSERT INTO `[prefix]_module_settings` (`id`, `name`, `value`, `cat_id`, `site_key`, `module_key`) VALUES (34, 'LaddToCart', 'Add To Cart', 0, 'default', 'gallery');
INSERT INTO `[prefix]_module_settings` (`id`, `name`, `value`, `cat_id`, `site_key`, `module_key`) VALUES (35, 'Lcheckout', 'Checkout', 0, 'default', 'gallery');
INSERT INTO `[prefix]_module_settings` (`id`, `name`, `value`, `cat_id`, `site_key`, `module_key`) VALUES (36, 'LitemsNumber', 'View Cart: ({$count} items)', 0, 'default', 'gallery');
INSERT INTO `[prefix]_module_settings` (`id`, `name`, `value`, `cat_id`, `site_key`, `module_key`) VALUES (37, 'currency', 'USD', 0, 'default', 'gallery');
INSERT INTO `[prefix]_module_settings` (`id`, `name`, `value`, `cat_id`, `site_key`, `module_key`) VALUES (38, 'tax', '5', 0, 'default', 'gallery');
INSERT INTO `[prefix]_module_settings` (`id`, `name`, `value`, `cat_id`, `site_key`, `module_key`) VALUES (39, 'discountPrc1', '5', 0, 'default', 'gallery');
INSERT INTO `[prefix]_module_settings` (`id`, `name`, `value`, `cat_id`, `site_key`, `module_key`) VALUES (40, 'discountPrc2', '10', 0, 'default', 'gallery');
INSERT INTO `[prefix]_module_settings` (`id`, `name`, `value`, `cat_id`, `site_key`, `module_key`) VALUES (41, 'discountPrc3', '15', 0, 'default', 'gallery');
INSERT INTO `[prefix]_module_settings` (`id`, `name`, `value`, `cat_id`, `site_key`, `module_key`) VALUES (42, 'discountThr1', '1000', 0, 'default', 'gallery');
INSERT INTO `[prefix]_module_settings` (`id`, `name`, `value`, `cat_id`, `site_key`, `module_key`) VALUES (43, 'discountThr2', '2000', 0, 'default', 'gallery');
INSERT INTO `[prefix]_module_settings` (`id`, `name`, `value`, `cat_id`, `site_key`, `module_key`) VALUES (44, 'discountThr3', '3000', 0, 'default', 'gallery');
INSERT INTO `[prefix]_module_settings` (`id`, `name`, `value`, `cat_id`, `site_key`, `module_key`) VALUES (45, 'shipping', '2', 0, 'default', 'gallery');
INSERT INTO `[prefix]_module_settings` (`id`, `name`, `value`, `cat_id`, `site_key`, `module_key`) VALUES (46, 'priceFormat', '2.,', 0, 'default', 'gallery');
INSERT INTO `[prefix]_module_settings` (`id`, `name`, `value`, `cat_id`, `site_key`, `module_key`) VALUES (47, 'currentGateway', 'PayPal', 0, 'default', 'gallery');
INSERT INTO `[prefix]_module_settings` (`id`, `name`, `value`, `cat_id`, `site_key`, `module_key`) VALUES (48, 'paypalAccount', 'you@domain.com', 0, 'default', 'gallery');
INSERT INTO `[prefix]_module_settings` (`id`, `name`, `value`, `cat_id`, `site_key`, `module_key`) VALUES (49, 'returnPage', '5', 0, 'default', 'gallery');
INSERT INTO `[prefix]_module_settings` (`id`, `name`, `value`, `cat_id`, `site_key`, `module_key`) VALUES (50, 'cancelPage', '1', 0, 'default', 'gallery');
INSERT INTO `[prefix]_module_settings` (`id`, `name`, `value`, `cat_id`, `site_key`, `module_key`) VALUES (51, 'allowUserNotes', 'no', 0, 'default', 'gallery');
INSERT INTO `[prefix]_module_settings` (`id`, `name`, `value`, `cat_id`, `site_key`, `module_key`) VALUES (52, 'itemName', 'Your {$site} purchase', 0, 'default', 'gallery');
INSERT INTO `[prefix]_module_settings` (`id`, `name`, `value`, `cat_id`, `site_key`, `module_key`) VALUES (53, 'useOverImage', 'no', 1, 'default', 'gallery');
INSERT INTO `[prefix]_module_settings` (`id`, `name`, `value`, `cat_id`, `site_key`, `module_key`) VALUES (54, 'catName', 'Category: {$name}', 1, 'default', 'gallery');
INSERT INTO `[prefix]_module_settings` (`id`, `name`, `value`, `cat_id`, `site_key`, `module_key`) VALUES (55, 'mail_cc', '', 0, 'default', 'gallery');
INSERT INTO `[prefix]_module_settings` (`id`, `name`, `value`, `cat_id`, `site_key`, `module_key`) VALUES (56, 'mail_subject', 'Thanks for your order from {$site}', 0, 'default', 'gallery');
INSERT INTO `[prefix]_module_settings` (`id`, `name`, `value`, `cat_id`, `site_key`, `module_key`) VALUES (57, 'mail_body', 'Dear {$first_name},\r\n \r\nThank you for your order of {$order_amt} from {$site}!\r\n \r\nYour order number is {$order_num}, and it should arrive via {$ship_method} after about {$ship_period}. If you have not \r\nreceived the order after this period, please contact our customer support at 800-123-1234, or e-mail us at support@domain.com.\r\n \r\nWe value your business and hope that you will visit us again!\r\n \r\nWarmest regards,\r\n{$site}', 0, 'default', 'gallery');
INSERT INTO `[prefix]_module_settings` (`id`, `name`, `value`, `cat_id`, `site_key`, `module_key`) VALUES (58, 'tImgAlign', 'center', 0, 'default', 'gallery');
INSERT INTO `[prefix]_module_settings` (`id`, `name`, `value`, `cat_id`, `site_key`, `module_key`) VALUES (59, 'cImgBorderSize', '0', 0, 'default', 'gallery');
INSERT INTO `[prefix]_module_settings` (`id`, `name`, `value`, `cat_id`, `site_key`, `module_key`) VALUES (60, 'cImgBorderColor', '#000000', 0, 'default', 'gallery');
INSERT INTO `[prefix]_module_settings` (`id`, `name`, `value`, `cat_id`, `site_key`, `module_key`) VALUES (61, 'cImgAlign', 'center', 0, 'default', 'gallery');
INSERT INTO `[prefix]_module_settings` (`id`, `name`, `value`, `cat_id`, `site_key`, `module_key`) VALUES (62, 'fImgAlign', 'center', 0, 'default', 'gallery');
INSERT INTO `[prefix]_module_settings` (`id`, `name`, `value`, `cat_id`, `site_key`, `module_key`) VALUES (63, 'tableBorderSize', '0', 0, 'default', 'gallery');
INSERT INTO `[prefix]_module_settings` (`id`, `name`, `value`, `cat_id`, `site_key`, `module_key`) VALUES (64, 'tableBorderColor', '#FFFFFF', 0, 'default', 'gallery');
INSERT INTO `[prefix]_module_settings` (`id`, `name`, `value`, `cat_id`, `site_key`, `module_key`) VALUES (65, 'tableBGColor', '#FFFFFF', 0, 'default', 'gallery');
INSERT INTO `[prefix]_module_settings` (`id`, `name`, `value`, `cat_id`, `site_key`, `module_key`) VALUES (66, 'tableBetweenSize', '0', 0, 'default', 'gallery');
INSERT INTO `[prefix]_module_settings` (`id`, `name`, `value`, `cat_id`, `site_key`, `module_key`) VALUES (67, 'tableBetweenColor', '#FFFFFF', 0, 'default', 'gallery');
INSERT INTO `[prefix]_module_settings` (`id`, `name`, `value`, `cat_id`, `site_key`, `module_key`) VALUES (68, 'emptyCategoryMessage', 'There are currently no items or categories in the store.', 0, 'default', 'gallery');
INSERT INTO `[prefix]_module_settings` (`id`, `name`, `value`, `cat_id`, `site_key`, `module_key`) VALUES (69, 'noImageThumbPath', 'no-image-small.gif', 0, 'default', 'gallery');
INSERT INTO `[prefix]_module_settings` (`id`, `name`, `value`, `cat_id`, `site_key`, `module_key`) VALUES (70, 'noImageThumb', 'GIF89ad\0K\0÷\0\0\0\0\0ÿÿÿµµµJIJ~}~popşışüûüúùúöõöòñòìëìëêëêéêèçèäãäŞİŞÜÛÜØ×ØÖÕÖÒÑÒÑĞÑĞÏĞÌËÌÊÉÊÈÇÈÅÄÅÀ¿À¾½¾¼»¼º¹º¸·¸¶µ¶²±²©¨©¨§¨Š‰Šsstccdıışûûüùùú÷÷øõõöóóôññòééêççèááâÛÛÜ××ØÕÕÖÑÑÒËËÌÉÉÊÇÇÈÃÃÄ¿¿À½½¾»»¼¹¹º··¸µµ¶±±²‰‰Š€QRReffışşûüüùúú÷øøõööëììéêêçèèãääáââÛÜÜÕÖÖÑÒÒÏĞĞËÌÌÉÊÊÇÈÈ½¾¾»¼¼¹ºº·¸¸µ¶¶³´´±²²§¨¨“””‰ŠŠûüûùúù÷ø÷õöõóôóñòñéêéçèçãäãáâáÛÜÛ×Ø×ÑÒÑÏĞÏÉÊÉÃÄÃÂÃÂÁÂÁ¿À¿½¾½»¼»¹º¹µ¶µ§¨§şşıüüûúúùøø÷ööõõõôììëêêéèèçääãââáŞŞİØØ×ĞĞÏÌÌËÊÊÉÈÈÇÂÂÁÀÀ¿¾¾½¼¼»ºº¹¸¸·¶¶µ³³²¨¨§””“€€şııüûûúùùø÷÷öõõôóóòññğïïêééèççâááÜÛÛÖÕÕÒÑÑĞÏÏÊÉÉÈÇÇÆÅÅÂÁÁÁÀÀÀ¿¿¾½½¼»»º¹¹¸··¶µµ¦¥¥şşşıııúúúùùùøøøöööóóóğğğîîîíííéééæææåååãããâââàààßßßİİİÜÜÜÚÚÚÙÙÙØØØ×××ÖÖÖÕÕÕÔÔÔÓÓÓÒÒÒÏÏÏÎÎÎÍÍÍËËËÉÉÉÇÇÇÆÆÆ¿¿¿½½½ººº¸¸¸···¶¶¶´´´±±±¯¯¯­­­ªªª¦¦¦   œœœ”””“““‘‘‘ŒŒŒ‹‹‹‰‰‰‡‡‡‚‚‚€€€~~~}}}xxxwwwtttsssooommmjjjiiieeeccc___\\\\\\[[[TTTRRRQQQJJJIII???ÿÿÿ!ù\0\0ÿ\0,\0\0\0\0d\0K\0\0ÿ\0H° Áƒ*\\È°¡Ã‡#Jœ¨Ğ‘£:u¬i¬ÃŠUkYêø\0ákAø¨CÒ‘\0k4ZóáÈÈ˜ÖN‚`3£#“YLÂôø1$È“1³(ıó#OœXÔH²NÒ¨''ejİÊµ«×¯`ÃŠ;¶šY™g5šM«­µjoÏÂ•ËumZ¸[éÎ}Ûõ.Ş½j×¢5»j–µ=>Tû°ªÇªµ«a9l–Z,–íÚ¥\\­G5Ã˜''?Æò¸ÇåËX<>Ü8ñbÏ«NİùófÎ„7¢Æ›÷îŞÀ©ıîı!¸ñà˜©%¥øñãÎŸûV.ùtØyPãA‡;ŞÚ³‹ÿ×Î{üøíÙ»s//½öïìÕ¿ßş»{jğİ›û4UªL3xŒ¨ŠØÉà4t0Bª0‚„İ]‚ÙYèj …š‡!ŒHø!„xp…*<¨Ò€,ª7Ò(à8æ¨ã<öèã@æ8G8î`¤‘C©ä’L6éäÓ(ås09¥’U>Ie–Yjé¤`¦’Š”‰ä"‹t°ˆFv\0æ";¤2Ç©¨y¤šVĞÙœpÎÑÁvR¹çVÔù''š;¤‰fz¶ù''¡©ÀÉæŸ\\¦''šd¶™Š4œvêé§ v*¨Zñ©¨¤*\rª«¦êª§¬rÿÊª©¨pÀ4¨T\n§¶êP…¨øÊ¸Úªk®¾ê°«":È¡ƒŠÈÁÁ³ÎÚªH¶N«Ã´¨ÈQ¶`l¶UHc,ŠpªÈ°Š´kn¶ë2»k¹èJSE4áÚ\Z\r¾Ùr°o¶üú.¹ıæ›/¾Ñ€Ëï¾ã«ğ·''q¿“û­¿\r¬oÄ9ìG4‰ìÛq4§|Ìp4&ï[r)opòÈÑtÜñ)§¨\\óÉ8£2Î.ß,òÏ9M²Ë(Ÿ’ÈH''­ôÒL''mJÓP/ıtÔT3=uÕX#\rÇÖ\\wíõ×\\—öØd—möÙ^‹möl·íöÛpÇ-÷Üt×Í6"vç\r7|÷ÿí÷ß€÷íFà„nøáˆ''Ãà‹kàøãG.ùä”Wnùå˜g®ùæœGNJç ‡94¤—n:4Ïœ®úê¬·îúë°Ç¾ú3Ïdí´SAEíÏÜ°{ï´2Ê!£<S?â<sH9&ø®üî7àşL9şì~{ñ¿?CÅ\r·ë^;÷Ñ÷ş»ğÛ?S¼öT\\O¾ïĞû~Hú¶;“3ôÛ>üò×ÿüô;cB?û˜óœ‘>*è/~Ô3`şˆ?ú0}ÄŸùW?Ú¯ük`ÿœ8Ã ÄÀD8…6„P„†è !œaƒš ıG˜g”£şh‡):(Bêa ’h‡?ÿğÑ\n!ø#Ğ>üÑr¤ğ†ı@‡	Ú€t0ôkC	Û`p\n_œ\rFQ8£\rZl†\Z×ÈÆ6ºq& 	ôÑ˜à‡ş Á7úÑ6R¯@ğ:Âá}|ãğB?Z4#9Ü£#½pDB†C\\#0©ÉMr’ÂFIÊBò5‚\n!ÊS’$ÀA?ÀAz\\€øå9üqS^€¼æ(óøKˆ’s&/‹yK}ÈQ¶tå(}9MiRóÌÈ¦6·ÉÍnn“ä`9ôAz0—ÙÜå6Éáq¶“ş'';Ï‰r°£ód†:éAÿNrŞ¨@™±Œ‚\Zô MèAÁ¹\rôšË\0G<½Ñv”ËÀè2â™Ql#¸‡G±ávPÔ£ì‡7àá\r…ºô¥ËP†L•ÁeXÀÊ ÄM	!SœFa¦l`C,0„qX ãğ=p:²\Z*Rı¡Œ¤FÕFMêTû‘TX ©ıi(”qÒã=µéMqÊ†£Ê´¦n\rE(ØÀSTà®v­@^ïÊ×¾Şô®Qj_ı:ØÂòu¯z­@`«v\\#\nèG;ØÉNÖ°…µëe£Œd¬!ølg×\0Šd@´M-NKÔzv\r­Eí\Z`»(ÿĞ ¥U-j_;ÚÎ´"õV)ğ[Ó¶V´ ¨@jAAƒ\n„6·­j?‹ŒêZ÷ºØÍ®v·ËİîrW\0Úˆ†wÇÛİc˜÷¼èM¯z×ËŞöº÷½ğo{A_cL Ox‚1ô[ßı>aş¥o~?A`\0û—Àı5†ù`Ïà3Ğï''è;úÎ Â–p…ûû‰bĞ·Àı°9<acãÄÅH1ŠOŒâbCÅ,vñ‹YLã«XÆ0v1Œ[Lã×xÆ1–ñŠ…üâ™ÅÃÀ0–,,ùÉP^²’£¼äw ojx²“©ÜdqøcÌF“—<ˆ&#oÊÃÄ•ÇÜd5bÿSsš—¬	(ÍPv²0öÌç>ûùÏ~Ö€?ì‘@ZØIıgäùÙÑ†´¤ıŒJ[úÒ˜Î4¦ÃÑøcÁàÆ<*½ƒyìàùğG>°ŒoŒzÑ°²?ÖQi¼#Õæ¨u8ZÃwX@ÓÀ¶¥=ƒÄ€Ø1pB\Z€Alc§aÙğ„”†hG êpB>Êá	høƒ(G>b0y`#ó\0!=ñ`ığ!·\0&€\0şAh”ÃùXÇ³í+;ÉFx€Qm`$ÛØÀø…Ä''ş‹ˆW¼â§xÆ!\rŒàåà‡ÄK _ä#ÀÿHÆÊ1€yü‚¿x„?ÁñpÌcæõ‡Äø‹JVòæ·øÆ)Nt`HOº ’Îô¦;zÂ®  ~ˆÀÎ@?Ü	{Ì]ğ‡ ®ák€ƒ]è5 ào¤û#l‡Dåv§ÛıîN÷…Ş÷Î÷¾ûï[¿†àóá½çƒğ¾ ;8¶ÁyøB¢‰/»<òqt”İşàÇ6,\rÌƒ#\Zy¬<8şNúÒï½¨O½êWÏúÔoÃÛH½DÑ‰Æ¾îP5	øñ	‰öB\0ş?ò˜pôÂéà?Œ|ãw#Õù¸}ë§Oı^À€€öyÁ4ğ¢	¼€ÿ\ZşN¢	ä?øa@şN€?ühğ>÷á?ş&ÀÀıÚ¿~Ìÿıë—ÿşêg\0~Ø÷} ~Ø×	á—}\nX€ş××·8È}Èx»€˜øØ 8‚(‚h ˆ‚»ğ\0,Ø‚.ø‚0ƒ€à‚g ƒ.8ƒ1È,ˆƒ8ƒ>è‚:øƒ0¨DX„Fx„H˜„J¸„LØ„Nø„K˜R8…TX…Vx…X˜…Z¸…\\Ø…YøKà\0œğ/ğb8†ah†/P†œ°K`ğKÀ	°/à\0°†œ@‡œğth}†ahf‡€è\0‡8‡{ø„ø‡~ÿhwH†kX‡†g‡’Hˆsè‡u†€ Š¸ğ‰ †–(Š¤Š©Šx¸Š¸à†¬ÈŠ©ø‰¤8‹¢8ŠªH‹x˜‹´x‹¨‹\rĞ\0~ŒÄ¨ÄxŒÃˆÇØ\0Ê8ŒÁX.Ğ\0eğŒÈXJ°	Ò(ŒÄèÎ(Æ¸ŒÁøá¨Æ8ŒJ`äøäˆŒÁèØ8ŒJôXöxôHö8Àøˆ\rpÿXù\n¹\n™ù¹\0}‘ÙI‘y‘¹‘	©‘\ny ’"9’$Y’&y’(™’*¹’''i.ù’0“29“4Y“6y“8ÿ™“5©	<Ù“>ù“@”B9”DY”Fy”CYJ¹”LÙ”Nù”P•R9•TY•QÙ- \0-@™°•Xù•`‰•]–d)–XI_9–e¹–lé•mù•h)–qy–a©\0v©\0´`—yy—|‰—{¹—xÙ—‚	˜‚Ù—€I„Y˜Š¹˜ŒÉ—ˆù˜ˆ9ù˜,0™–y™˜™™“9’i™˜ ™ Y™”ù™“É¦yš¨™šª¹š¬Ùš®É|ğš²›¦I›©¹ªÉ+°›¼Ù›½)bà›Â9œÄYœ½y	Æ™œÊ™œ³ĞœÎùœĞÒ9Í¹Ò™\0Í‰Ï©Ô¹Ö©H@âÿ)*PæYaç¹³°î©îyğ‰îÙñ)Ÿñ9Ÿ÷¹Ÿë)åéŸş©a`	* G ² G ²Ğ êŸzªªz`	è²° ú 	jGàŸap¡G`z ª''úŸ\r\Z!š \Z¢(J * ±²p£9z£<º£<ú£=ê£@:¤DZ¤@*¤DŠ¤Gj¤8ú£`P	°`\0•\0F`y`•UjS\n7š7\nF\0T\n)UZ	•€\0y¥j\n`ğ¦FX\n¥lZ§WÚ¦W\ZdŠ¦°\07\n¨°€\0± ¥y¨ÿP¥‡\Z¥…\Z‹Š¨Wj¦–z©˜Š©(©œÚ©ú©fº© :ª¤Š¦zª¨šªªºª¬Êª_pª¯Úªª\Z«²Z«¶Z¸z\0x°«p\0Ep\0”€Àº«E€E€(€”Ğ«¼Ú«€ÎZ_@	¿ú¬ÖÚ«¿J	Ëê«Øú¸º«Ë*¬¾š¬ÎÊ­Âº«±Z­ÀÚ«Úú»jªå\Z¯¾ú\nÜZ®¯p¯õ\Z¯¸Š¯êZ­E@¯öú¯\0°€¯½ú\nêZ°¹\Z­ë¬{°°¿z¯[±{±›±\Z»±Û±Ë±`\0®0²#k\0wà\n"K²®p²#{!K+«²2;³4K²0K,«²“@²''P³*›²-ë³2³\0;', 0, 'default', 'gallery');
INSERT INTO `[prefix]_module_settings` (`id`, `name`, `value`, `cat_id`, `site_key`, `module_key`) VALUES (71, 'noImageFullPath', 'no-image.gif', 0, 'default', 'gallery');
INSERT INTO `[prefix]_module_settings` (`id`, `name`, `value`, `cat_id`, `site_key`, `module_key`) VALUES (72, 'noImageFull', 'GIF89a,á\0÷\0\0\0\0\0ÿÿÿµµµ???şışüûüúùúø÷øöõöôóôòñòğïğîíîìëìêéêèçèæåæäãäâáâàßàÜÛÜØ×ØÖÕÖÔÓÔÒÑÒĞÏĞÌËÌÈÇÈÆÅÆÄÃÄÂÁÂÀ¿À¾½¾¼»¼º¹º¸·¸¶µ¶zz{ıışûûüùùú÷÷øõõöóóôññòïïğííîëëìééêççèååæããäááâßßàÛÛÜ××ØÕÕÖÓÓÔÑÑÒÏÏĞÇÇÈÅÅÆÃÃÄÁÁÂ¿¿À½½¾»»¼¹¹º··¸µµ¶ışşûüüùúú÷øøõööóôôòóóñòòïğğíîîéêêçèèåææãääáââßààÛÜÜÕÖÖÓÔÔÑÒÒÏĞĞËÌÌÇÈÈÅÆÆÃÄÄÁÂÂ¿ÀÀ½¾¾»¼¼¹ºº·¸¸µ¶¶ışıûüûùúù÷ø÷õöõóôóñòñïğïíîíëìëêëêéêéçèçåæåãäãáâáßàßÛÜÛ×Ø×ÕÖÕÓÔÓÑÒÑÏĞÏËÌËÇÈÇÅÆÅÃÄÃÁÂÁ¿À¿½¾½»¼»¹º¹·¸·µ¶µ´µ´³´³şşıüüûúúùøø÷ööõôôóòòñğğïîîíììëêêéèèçææåääãââáààßÜÜÛØØ×ÖÖÕÔÔÓÒÒÑĞĞÏÈÈÇÆÆÅÄÄÃÂÂÁÀÀ¿¾¾½¼¼»ºº¹¸¸·¶¶µşııüûûúùùø÷÷öõõôóóòññğïïîííìëëêééèççæååäããâááàßßØ××ÖÕÕÔÓÓÒÑÑĞÏÏÌËËÈÇÇÆÅÅÄÃÃÂÁÁÀ¿¿¾½½¼»»º¹¹¸··¶µµ´³³şşşıııûûûùùù÷÷÷õõõğğğîîîíííìììèèèçççåååâââáááŞŞŞİİİÛÛÛÚÚÚÙÙÙØØØÖÖÖÕÕÕÓÓÓÑÑÑÏÏÏÍÍÍÌÌÌËËËÊÊÊÈÈÈÇÇÇÄÄÄÂÂÂÀÀÀ¿¿¿½½½»»»¹¹¹¶¶¶´´´³³³±±±°°°¯¯¯¬¬¬¥¥¥”””ŠŠŠfffWWWIIIÿÿÿ!ù\0\0ÿ\0,\0\0\0\0,á\0\0ÿ\0H° Áƒ*\\È°¡Ã‡#JœH±¢Å‹3jÜÈ±£Ç CŠI²¤É“(Sª\\É²¥Ë—0cÊœI³¦M“EŠ0<¤3a2ƒ?fº³DD\rö\\¸4)B§“•\nñĞB«J,Åj+H¨É[Z&Y*¬=Ëù©“„ÔœZPt,‰²I¬¥ª¶HªTt‰ OÜ"XÒ›ÌíÙ€IĞj´ˆ<«Eú–Ùl­Î­ˆyJ>dujN¸exêÜ,@qj³ò&§2Zf¶ÕÉÉIUKpo2‡şŞ•w6ño¹¢”qKº,Qà¤­¦"Ñ¼ğÚ"$bË½éÀàËÄÿNõs²V©wáj¥Şš4á³9sgN-qìŸ©ÈN5Ö0éşÂ-WXaFÅF qf-&àrÂí§—nü‰—<(–x&™xö7 <f‰''€dgÅv×fû%£Ö!j¸Öpî÷aòGŒ$ä''WŒûçáXüA¸Y2ÄµØâe–ÛfGæx…eh#zk)©a”¦§Ü‡ÙM—¡€1v‰á__µñ·cŒ6÷W4èäÁ6álOú‡İ„°ı5gm’eö&we‡e›6¾é¡¡–Xc£biáœÓMh)¡''bXåh.¦–¤-ŞEäe“¢iicº…¢£•I¤—\ZÿHØª¶á„Şš™›µı8§d…±h(»Î¶Y¢­Ai`£±²(#vf\n‹§$j8ê›n8ê‡ÜZÆ¬&r+nâÂ8î¹è~xd¬é‹b›çNÈíºãÒk·ò¦o†õhf»á˜ïèÒê/ÀóŠ»-ÂÉ.ÃG,ñÄWlñÅg¬ñÆwìñ‡D|hˆ!‡<®É£<1Éò˜¬òÇ0·;rÊ¿3Èé²Ì­ËDôŒ2#‡¬³!>]´<:Øsa=İ2ÒF?=`ÒJŸÜ2Ï!û,tÖL¿,4ÒMû,.×UïÜµÍO''ı³¸,³¼t×WK\rõÔp[Í3Èoç­wÕw«ÿìwËC63ÊNşöÜW{-µÖ`m´ÑY»|¶Ö{;.vâ@‹\rtã‰»}yÓN7wÑ™3~yÜm3ùç‘SŞºåO»N¹Û«ƒÍ4äNôî”N:à¾´æa7n:É¥›ùÏZûä‘ƒúÒË›uõ®¯.»åÛwï½ìÚ‡İ<ö¼«ş½ëØŸ¯şùéËûú¤»¿´øô{ßşö÷Ã¯ÿ÷ùïïÿÿ\0 \0HÀ\Zğ€, *|¶À°!cx=C†Ë''AdZ³ ş¶gA2@t#ƒ58;2Xh$Ô 2F ;’Ğh\rì*^ˆÂ>Nx¨	õ4\rŠ°‡èKßûÿ<è:úL‚L"FÀD	bpvDÃ`úVè3îğˆ ‹Ÿå†—>#­‚TD…!F`Â%…##G`C²q#c£!È F"ÃƒtDÅg46qŒÔ£u¸Ä5êcd!˜Ã%"‘.\\¡!ôGCPq’–|à\n½hI&.3Ä`u˜C&Æ±”fœd =ÉJ8‚Òƒs%''ÉÄ¦­PŒc\\¢	ñITàQ„~å‰àË?.‘’¬T#Ğš(FB\n“Œ½4e!7iÅÊRrì™›6KB­”¥dáKiG6ÑšØÌ¡\Z±YHq’Ñ¦¥''}¹Àwzpœï„g=áùÿÆy¦šLÜf-ÃùF:T„ù$ã$›IÎ|\Z´–"Ì!5áˆÍ9”Ÿk\\''F7:PŒq…n¼e"\r	Ê5ªÓ…''6Y˜GZ6r“|ã&‹éÁVr¤md@‘YQ~R²gıl§!WŠQl5¡MªI•ÊÔ¡Âs¥m*GU*U§VõªXÍjRŠÕ¨zµŸZ-ª>Á\ZĞ°šõ¬hM«ZÕÊÕµºõ­p=k<æ\Z2ÌU„ØŒGFG@W~Î5£DÕ«]ïªÆ¿Ö•‰uí+bùzT½ò•ŒtÕ+aùYØÃô¯O]ç`‘ºFÉ³ŒE,hùêXÀ®ô®˜ìQ7[Z¼\ZÖ±F5jikXCÿ¦öµ¤µílMËUº"´¬—Mì^7ÚZİŠ6²ÈMnríJZåö¹Íu®r+»\\é&ÖºÉ.v·‹İèj×¹Íı®g¥+^ò–w»ƒ}-xÉû\\îº×¹é}¯|çKßù²¶¾øÍ¯~÷ëŞóò÷¿\0°uqøÀN°‚\\ˆxA¹ŞnƒL×1¢Àc8E<ÜàcLø«F,âxˆ ÂÇ)\n,‚?x&0†[YÓµ\Z>±‰/\\`ÏµÀCxğŠçºâ oXÄ-öp‡Pˆ&;¸Á\Z6òbW9ÂDğà*ŸÂÉN¶ğ\\›Œb#ÆVnr‡#;e,k˜ÿ®FÆrÍ,f+sÙÍa¦+¹L`7?¸ÏL1ˆ¿<3oxÍzÁ…±Üä1ºÍcó„GáS|¹Á#±¶¼åB–>†£MÌåS8z™nq¦yìéO˜ÄÇx5¥|\nAŸºÖ²µ¢A]ë×úÂ–Vô¯M`TcúË .Ä,ìãMoùÁ›Şó³¡eBóÚÕ¯®u;İëXg¸Åß5ªµëq¿º¨^¶®kÍn`/ÛÔ²Vv<|­h,¿Zİ‚ñ²=¬ìNošËØFõ¿)ë-GyÏ¸nò«c}èû[ÑNµ…Yâˆãúß,ÎµÀÿ\rï\\‡[Ñï6µÁso’ç›Ë$ÿµÉ¹ÌiPËzß?ù1Dê‡ËzÅ&¿øÅWëçZå''×¸ËMnó—ç;ÃE¿y¸}.h¦s\\èúæ¹³OŞñ•Úæ[¹Ç[ŞsW'']ê`—ú×ÃNö²‡İéfO{ÏÕÎö ·}åh»Üç.÷NÃãßê¾»ÑE w¾Ãcïûß_\r»÷}Ä‡çûÀ;½l½;^ñv<áıNøÀß»ğúÖ;à''õ{+^ñ…¿û–ßw»#ñ‰G=â#ïqÃ=ğ¡÷÷ã—½ù\\''~ğ‡=æÿyÈgŞğ¡Ÿ<àsOrÌ¿^õ—ç<éƒïìĞ;ÿù°‡¾ôw?ıê[ø›¿>ê«ï÷ë[¿ûŞçûÿğ½?}ğ“øÏ}ù©ï}õıÒÿù/økŸıÎï¾ùÍ?ÿşûÿÿëÇ\08€H~èX€\nø~Ğ—€ôw}õG~B°€ğ`h\nHÓ§ØÏ7…‚…‡X‚óÇğ€‚Ş''‚Ö§‚è‚Òg!ğ !@\Zh„`B`\Zh\n8H! ğ@ƒh=(8B\0„Axƒ=hb !àƒ6xƒL(JHS¦`\nUH„<è…4HZx„\\è…;h„E‚48ƒaX…fzJ˜‚3¨„Æ€„,è…¦ ƒyØ„(…MXƒ68y˜‚b`\nLXx((…Šÿè‡…¨ƒ)H@˜‚)Ø‡‰xˆ!„_øƒ¦ …K‚EÈƒŠ8Šg8„2Nh††8ƒYˆƒ3¨›8ƒb¨‡8H„EØ„U˜‚T„¸†ø…v¸‰˜…e8…y‡}¨Š_Ø‰pøŒ´X…pX„ÏÈ…p(„3ø…Ìx…p(b°ƒW„S¨‹ÚøŒ©ÈpXˆb‡ÉĞ†Êè_(Üx…<H‰ø‰ôÙ8„`Xö„`Ø„¦ÀÆÀĞhƒüÈØ„CØ9\n¹‰ù(Ü†áØîˆªˆ‰ùXù‰é8‹´ø‰©¨ŠÉXƒÏH…ùŒ©Œıè4Y“ÑH“ÿ‰2©Œ1i“>ù“@ùŒ:ÙC”Fù"é.éY“9™;é”G9•T•4)…FY”/”õï •@y…ÜøUY–õ–`Y–j¹•kY“ôØ–n™oÉ•i–%	—d™—ï°—|Ù—|é•~˜€¹—^	˜ƒ˜„)z™—‡‰˜_I˜}©˜Š¹˜~Y˜ù˜9™ŠÙ˜™é˜•9™ˆÉ™œé— Ù—ƒ9ƒ{9™¢š™š¦ù—ôè™”ùš•Ùš¥)›_	š‡)™‘	‡®9›¸y›¸)˜ÃœÅ™Ç™œ9šÊÙœÄéœÉÉœĞ9ˆ)œÔiÔ™ÙYÚÙŞùß9àÿ9äYA°—ÅÅP\nïpïƒ0 pA à—â9Ÿíé|ï òYŸşŸíùŸƒP\0zŸ|YA0 ÿY\néÉ—\rº—â)¡î)ôù¥\0 ƒîYŸóI êÅ\0 0õ9ìÙúaÀçŸ)j¢ë)ŸÜy ëYŸïI¡{	+Š Z¢:Ÿ¥pŸñ>Z\n:Š¡Üùóya\0¡º£ÿ	Ü© ùi¤''šŸëÉMŠ ö9¢Bº—AĞ¤&úŸÜ¹ş©¤\0Ê\r\Z£A ú¢ÅàŸ,º—¥P¤ú‰ /Z\nHj¢&ª§€jŸg\n¨÷I£ ğ§€ª¤õÿ©§ú†ê£j¢Q*£€ú§Šú§*¨HJ¨~Z¤Šš¨''\Z¥…*Ÿ†Ú †\ZHªª”š©OŠ¤‚j¢¦*Ÿ~\Z©”z©„Êª“\n¨*Š¨ºº¨‹Z¤yz«Vj¨±*©‰:¬—º«Eª¦ÊŠ¬&jŸÊª§a`ªAÀ©¢j¨%Š¬¨šÑª¥ˆº«¬Z­ÄŠª¶z®ËŠ®»ª®ìÚ®çz¬‘\Zª©j«ğê®öj«˜ê®¿z¯ëª®ÏÊ¯\0°íJ¨û¯Ğšªõ\n¨î®ò*°»¯ñê° à+±Ä\Z©¯\rk±Ë±úº±›¯Ë± °îp²(›²*»²,«²aĞ²0«²û².{²4ÿ³‹²›¨1³7Û²HŠ³=;´9+³8[´)û³«³KK³7»³,‹´=+µ''k¢)[´Jë²Y;´\\Ë²[Ûµ`¶b;¶d[¶f{¶h›¶j»¶l³‚²Ä@¶@p²qëÄ@\npK·qû¶t;·(+‚°·t{²~;·q‹·1[·îà·„›²ŒË²€»²Ä ¸zK¹‹k¶€Ë·«¹î\0†³‚›·ƒ;´œ;¶œ“k·£[ºîÀº—«²–;·Œ›ºª[»0K\níğí Ûî@\nÄ\0í@\nxÃ‹»ÂK\n@ »@ ¼€K¼v›»¸ÛÔëÔKíĞ»Ùû¼Ã+¤À»Äÿ Ãk¼¨K½Ş»»Îk¼‚ğ€›»Ùë¹Ä\0¸û`¼¹‹½`°¾ù›½Ø›»ô;·ósîğ¤ğÂkÀâK¼¾¼¼K¼ñ¼ì»¸Ü¹Âû»À¾í·ıK¿Äğ{+¼Ë“Àk¼,Àñ»Á»¸Ûk½Íû½Ä¸ÂÊû»w;¹@¸;Œ·™k¿ôÛ¹í`¿è¼	L½ò{²õ;ÂL¿ \\ÃükÂô$ŒÂÍË»|‹½ËK½Ô;½ÚÛÅÓËÂîËÅ¹k¿Âë¿cÌÅaLÆ]lÂİKÆºËÆjl¾r<½º«»nL½ôËÅòK\ngÌÅTÆïëÇşûÁ(üÇîÁyÿ<¼CŒÀæ»ÆqÌÈ~|Àl<¿ œ½ÙËÄclÆCüÁbÈÈ•lÇÆÛ¿î»¿zÉ˜Ì¼ˆ,ÂHL½G\\É]ÌÆDŒÉzLÅÉÈr¼Ë¼Ç½üËÀÌÂ<ÌÁŒÈÄ|ÌÈœÌÊÌÈ¿üËüÌĞÍÁüÎÜËÕ\\Íí€ÍídLÍÎÌÍÜLÍÔ‹ÍâÌÅ×LÆÜ<Îæ\\ÎrüéÀÎl|ÎêlÎèÌËÚÏíìÍÙÜÍöÜÏÛĞñLÏlœÎM½ğœÍòLÍ½ÍÕÜĞóÌÏMĞßÌÎòÜÏü\\ÎéüÍ\n\rÑåŒÍ}ÍÚ¬Ğ$½ÎŞœÑ(}Òî¬ĞüÏ''=Ğ*Ó+íÍ.ÿÍĞúœÍ0\rÓ''ÍÒıÑ2Ï5ÍÒÍÒ>ÍĞ3]ÑG-Î0İÑ@íÎ?½ÓA-Ó)½ÓâüÒÎÜÒ-ÑR­ÒK­ÔCíÔ\\}Ó[ÕSÖÓ:=Îc\rÖkİÖnİÖÃğÖrıqıÖ:=×x½Õu×F½ÖwmÕa×Í×*={ıÔ|}×†MÍÃà?ğ_àÍ£@×ğ\\×ÃÀ_\0ÔÍ“Ù_Àì0\n_àì\0Ô_0\nÃÚt½×MÍ0Ûq}Úñ<\n£€Ù¥íØîÙğ<Úı¾=Û»ıØœMÜ­Ú`Ø¡ı¹ÙŒı˜İÛ¹MİİğÌÚ¯ÛŒÿmØ\rİ¤\rÓİÜÛ£ÍÙªİÚÍ=\níÛî=Ù.Û_°ØÛ=Ü¦\rÙ—Ùì0¸MÛ5Û-İÔœÙÄ×íß˜í=ÙÓÏ—ÍÄÛª=Ü«íà­Û¤-Ú¦×ıÚŞÚ³-ÚÄ-Ú˜İ&.Ú¡mâ¤Úè­âœ\rã&İ+Ù¢Ş"nÚ&>â6¾á5®ã%¾Ü¢ÍÛ7ã)~ä~ä¸mâ5^ä)>ÛKä"~ÚC>\n<nåşâ§Mİœ­ã)Îå;ŞÚ3ÚdÜ¦íå7áL^ßgîä*nåİ.ŞÚ`åZnãdÙÁ½à\nŞå¾ââj>â“\rä;.åHÿèŠ¾èŒŞèşèé’>é‹~ç”~é˜~éh¾èMéâ€\0ê&îGî¤.Ú¡~ä©Î¡ê>°ê£~ê§ë´©>ë«nê²Î¦.é¹>ë¢Mêºâ¸®êÄ.ì´^êÁê£ÎëÂì¼®è¤në¬ëÅäÀ®ìÚ¾ìŠîêÙíÄ.ê¹Îè°^ê€píÍí¶®ëÏ>ììşîçÎëŞşìÎnêç.ì÷í¯^ïëşîõşîşÎîànïûîî¯ŞëñŞîÎşïùï½ğ\nñÏïğ·Îî¶ñßî¿îÏğº~ïğ\0¿ğ/ñõò"ïÿïÏñó^ñ2ÿ?ó4Oğ5ó8Ÿó:¿ó<ßó4ò>ôBÿóCoêëĞ^ \n¯.ë\0ë Lô \nÂàIïë`õI/°¢pîO/\nOôÂ ö\0^õ^à^`ëdïôë°>@õ¢P÷bõn/ö_/÷q?õz/\n]oï¢`õğêJ÷Š¯÷sÏõı÷¶÷^pöpø>€ôkÏõ•¯ô¦¾ö¯~öK?÷Yø“/ö‚øHïõ›÷Âàô> ùMÿô‡û€ĞúŸöPúX/÷hÏöqïù‘Oú®ùTÿú¦^ùÏô¦_öŠŸöroï­ÿô?ùq_÷¬_ı®Ï÷^¿îHÿÏ÷S/ø^_ø¯ø]_ıä¯øèşTş»oşGŸşğÿ÷åÿûñÿÖÿI/öè?ÿÎë¼ˆZWĞ °*dØàBˆ×uHXĞ‹Â‹-6Ìh0aE7Ô(±£Æ‡ëDTHPTJ‘Â`:,éc A˜%¾THgNA…%ZÔèQ¤I•í±ÔéS¨QöøS°©Ä¦T«2ìqµkÕªWÿ„›õªÁ®ëšE{ÖkÚ®f­İJí:¶nã\Zü“ïY…}Óª5ëu®XÁ„ÕşuÛwñZÂz·şu¬P¯Z¶VÃBÆœu³\\¼[ë~[präÀqÖ<×4à´e#—İWuÿíÈ·kÖ»·jŞ·ßVüûëqâ¹«&œX÷ç¯¨—7^8uªÆ±ó¦®|5säÙÁWîûxİÅ—Ûş7˜õòÖåÏ§ï¾~oªßßï»Ÿ>vÿî¿®œïTpÁùŞ%ª‚á ”.8xï.ºèªÂ4%¿òÓğJäpC	ƒÁ0®üR‘Ş8èBÁş€Â!”ğA-D°Ã	iH{&”¾ú¢ğÈ±d|ÑF#‡´±B[D²D{øÂã$±¯;ìC\rC\\ñCCqPB*ı[1˜2Çº2Ë	C<òA&”°Dª*¤²I±Ô\nÉ.jDÎßÛÿÓBEyìN}³B<RËIÌñE©ü0C,MÄRSM±œqÎ=ÑÌÍEK\r5LQ©ÌqÖPµL•V,U\rÒBS?UÔü&|Xb…%öEC\rM 1ÜµÄ•\rqFWGÕ•*e³ÕvÑaY-ÖÔ\\µ…6ZMäMUÕìÊWAÅRqáUVYK”WÜríÍ¶‹{Cí7Û~Aõ³^y~ÕO}••õ_póÅWÔ~ï­7^zÔ†ÿUç^†÷Íğ_Y1¶ø[‡ùwbíİß•–qc˜9€yfzc¦ùf˜?Îyf•q†×g _¾™_ ‡&ºèŸåMúh™qnšçŸ~zgiîÿ™ê£‘®:k™+´zc¦_6yæ¬µ®ze›&ºl³ÛşÚm¸ãÆY€|êÎG\0 ñ±;¹áÖÛnú|pÂ7üğ¢AÙ˜AQÜ?€áañ\rx˜|rPŸÙ™''WÇË%—r.hV|fÅIgôÈ?WçôÏyØg\0Úè„Ëa–œŸÚàrÈ]WGsİ7€™suJ§Ùruxà½ö|Ò™Î«ç\\óÈ«7~øæßx`ˆ¾õ™§¾yİaGfËÏ~ıáŸüzuŒ?ùÒ—|øó¹×]sñ‚‘àç@§ºõ!ït• ã‚·é5ÏLG:@±\rL°‚«ã0˜ÿ.lğƒ™Û *hBPğ\0ä ˜\r€„é\0	Óa¹VPu”am¦cv½Û€ÑÁVğy´ ãç$òÀq3œáZ¨éAPr!´`:–8€èÅ¼!ƒøB#‚Š9Ü`;XÁÌÉğ…T„bÛB(îPŠ"ô!7èÁ¢Ğƒn|£Û˜Â†0†”ãêºHÃAúA€¯s¤\n%IÃ#Vğ…Æc!YèF\ZÊğ‚‹lc)MyH8òsTå#5ÉÊVªrˆ½ÃÇ)¿Cn2–TT¥¿È7SÂr—±œâ0KéÇ \ZS•~Tf3[™LÎ±˜»d&/SéLeÿ¢›ÛÜå,kÇ[ö®™ÚÄæ/³ÉÍ6’³‚êDç9Û9Lv¾sŒg<é©Mvâûäç>×ÙOrâsÿL‡>ùIĞƒ&TŸÿT§6½Y»}ôS‰½è=ZĞ†Ş“ Å/+ªĞ‹£è@*Q„2t M§?YêRj”"5¥KKjÒ‘Æt¡=©BGšÓU¨C%jQ\nĞ‡ÒÎí\0è‡J»õ¢j;êÆT€Z5D0êŞ°ZÔºqUªû„j>î1V´¦U­kek[İºÏ¤Òæè§SOúƒ}ô£wàG>ĞJUtğC°ü(:ˆğE~aŸ÷øb?ôT}èuÿ¯ıØÇ‚J„ô®~-Á`ùYÉ*µ©xkiM{ZÔ–6®´ó+?íÚO}øc¯½ã‡¤\nXÚŞC¶{õÇô1[Ú= ™.íúñƒ“ê¸úx-?ÚÙ\n7µÓ¥îXÏQÚë\Z5»píİn[WŠÂ¶¸šÆQq[»~xw²ã€!ú¹ZÚöóDPïzkĞç²w\0Ò­.?·[Ôÿ¦õºç0\r|`	fpƒœ]''xÁF°7cKDñÀ÷Øk	ô‘¨·fğ/\r<[~|ø‹JİG>.QÁ»şø0ˆ÷Š`â²ØÅĞ¥İ„ÑaÛîÃû˜1*&LaïóÁÿ>r’›¼d?YÁR†0”­|e,g™ÁŞÌÇCG@`\rg¸wíC9Ì¡\\èYÙÄŞë\\ÍQrğøşí7ËAà:Óî¬\nşâœ	Ìîúù`àñG Ys˜Ãµ±–ıhHŸ£}Ø5ğ\r\\ÚÒç¸ô/úp-PZ}øE©1mjPoÓ¤Ît©GıéI[Ú°Îô.­Y[Z’uvÍå>@—¡ó9ıÍDï\ZÔŞ±T`_kà—˜ÖÀ^ááéR™ä °9È@Û_HÚñpñ>&ıispYÒ\ZÈó\0J0ëQ££İØ´7ç<jçØ“6G¦\r¼ë]÷!Ù“vöÿ¤+\rêY‡Û×fõ«aU‡:Ó¬6Ç¬®ës„›á¾6µMëŠgºâùÈA^q—¼â[èwÉAr•¯üá1''9Í1=r˜sÙùØ«>ÌñÅ‘ë\\å>è.ËMNó_®¼»%ïƒ7÷¡rÚª¼äÀù"Û»|T¼AÎzËß¼W£ÿ¼vş9ËûĞn{0]å''5Ì!ö²·Üí47ùÉCN÷¸×ï{ç{ÉÍÜwÀ>èXŸuıáƒ/š™_$ÂÌG~è¦ïI¯xÔK.ô‘[ŞïöØG	xÌÚ‘=æ¢¯¸w7»·.^ğ«g}ëégØkıÍ°§½™k{ÛçşöZ}ïuÿ_ûÙÿÎ\\6³(Dœx8#ÿö¿şò	gÚÂyöÄ§}ôƒuöæÃöÈ—½ØkW}ıbXúã>íƒ?ûàç÷»''óŸşõã^şó7?ıíüÃúå˜·za¯üÚc>ûÀrˆ>ÚÛ?è³3Ø2ô"@\0ü¿Ş¿ğ£}È?¼@üK?Ü@¼@r€½¼?\\±œº/Z‡¤=èª‡û#@Ô?¬ƒ³|Ávó‡È‡u(‡ı;ÁÛƒ.	´}B!Âë@#<Bü#%œ:&üÀ\\Âœ:(lÂ%AtÂ*”B-|B%ìB+¬Â%ä².$‡1¯0Äº)üÀ½ª½''¼Âÿ_ZÂœ:âCÚ\nÁ/ª‡D“ÃTB1”ÁrX‡½zÂĞê)ÄBÌÂ+dB?,ÄB4Ä)¤BÚsB6„Â(|DITÄ1ÌDJÌÄFÃNÔDPEQ¤ÄO$1ÃÕêBc4O$‡vã‡E%|C%¬CTDC:ü¦.´Å.ä:Uì»ÅŞÃ ƒ‡Lì²º¹BYäÄedÆfäÄRÄBi|Fj¬Fk¤ÆSìÂø¼ÃÃm¤¥fü"|B^äCZjD^D´.õÂ‡%ÜõÒ‡.¬‡ú\ZÃU”«XìBèêküG€HH‚FÚÇc®1¬A}èrÈa{FrÜE]4È@HÿŠ”+~ÆØ9Ë‹ä«ÎSH(¶ ‡0ø¢(H–,Èà_ ‡É™C_È€˜ìÂØÉ—ÌIrø> ‡˜äƒ˜É¡Êø-ˆI_ğ…Ğ™ü„.lÊ™ÄI-ø„›ÊXLÊ˜ô&|Ø¢$‡ Ä\0ÜG%d@àò‡È\0> ÊˆÉšŒÉXü¥™äƒs4E4¬K»ÔE¢„¯½Ú¡$‡èn´èË.¤‡ğSË ÔÉ¤ÄJ™”I¨¬I±ô TÂÆÊ¶lÊÌÊ›4Ê ÄI%¼ÉÊšÌ\0³J-€JŸô¢,ÍOÈ\0-@M×|MÒ”M°DM¶TÊÒDÍÙ”MİNÿ-ÈÍ«ŒMİäÍÒüM¶<Î¬|ÍàôJÁÎH‡ú\nN>ø´Ô,8NÙDÎŞ,Mr,Í½ÔÇ§DM¯ÔÍÙ¤-İä€Âì,ô’Nuø¼~ğ¢ŞéMã|±´¤‡—”Í«”NÚäÏ¢DÍO0Î¢ìO\0õMœÎàPİ|KÿŒNñtÎŞÜIõO½PÍP\rİPUÎ8O}(Åô‡}H‡õP\rOÔTÏç¤%étÑÒT‡£1O\0Qİ‡¼2®}À€ø"¥t7àP#=R$MÒ‡qğM&•M&}ÒÕÔ‚&}RÔ´ÒhÒ,ÍR-åÒ½Ò(\rÎ0¥Ò-M2ÕÒ)R*­Rÿ3ÍÒÙÄR0‡è² Ä)İÒ-R=ıM+ÕR=ıS.õÓ''US*UÎ7‡AB|pÓ0-SôÜÈReÒ/úÓ(Íu Óã€0T&ıÍ5UÎ*Ó5mÔ8‡ÕìS''½R/EÕ<ES>µTO]ÓSõTYíÔ[ÅÕAÍÕ\\-UY-U_uU^mUK\rT^µÕcÕa5V=\rÔR5ÖyàEU=Í‚Ğâ‡_eÒOˆÒ—LVK%SfmÒ`İÕ[\rW]åÕ3•UğVYí=]×(…W&•×^p×q°×qØƒ=`R¸UHeR|ıSyÕÓ}eW^Íy-XKUØyÒ‚­×[Í‚,‡UWÿˆıÓ}İ|ÕWÅ‡Ó{õXF‹Ò€Õ‚Õ~}WKõOÈWŒ%X—½WwÕŠÅXOàØ\\ÕW…\rX’ÕSØWOÀ\0¡Å\0–Z£=Z¤MZ¡EÙ^PÚ=0Ú Å\0‰%Z£Í¥Ú¦ÅÚ¡Ú¡Ú®õ«Ú İƒ¨eY®[‰Í‚=°Z¦[¡\rÛ›\rÛ£õZ¢‡,@[¡}Ú­ÕÛŸ•Û­µZ¹õZ¸ÚqP/Å| 2ZºZÆ•Û¶•Ú­uÛ¤-[¿¥[È%Ú°­×®EZ”…Ú¬EZËe\\ÑMZ¿]Ó=]ÔM]ÕUİÒ]]×5Z¿ü¦×İÙ­]Û5ZqÚÜŞÕİŞı]ÿàİ]qÈŞŞâ•[‰Í]\0^âåİäUŞãmŞß]Şâõ]êŞê½Ş·ÕŞì^èÍİæ…Üäõ]çí]ê]^ò%ßİ•^ÜŞëŞ÷U_ôÍNÍBïıŞï%ŞåÕßâU^îí_õ­^ååßà-_âm^ıZá¥Şçİ^ã-àş½ß`\n®`–Ş~_ŞàæàãÕàÆ`ö``>Ş¦`.Çº‚~aîà\n.ááĞƒÎşÅa^(^=ÀaŞÍî_^Ğƒ.^!îaqĞa!Î!îß$nâ(nâîaşa=àÖb+&`^àbŞÕ,cŞ=âÿFã	b&fã!â)VbqèâÆb:,\0qÄE\\q, b)Nâøá.¦à€cNâ*®`<~cŞ½€2nâ4¦à;c\næ…À=ød,¸\0KîQ&e<îS.âQ.dR¾\0U~åNhåWödX¶cR^åW®åP&bRåOeRÎP¶ãP–åW.d[e`‡à…bŞåPe[Şe,¸f`şå\\Şæ\\Îf>~e"îaR®ãcFbbŞåg>ãˆåcVåc¾aS>b,ĞålåjÖft†epÎeSvå[6ãDe0FçBÎæNXgRæâNğd]¾ádşgt6ãæÿUvæğåQfæN°èSFç†şä|i‘şh‘.i“&è“élNé“^i–ékÖç|~ç—®i›¾i|~i—Îg’ÎçyÆé’¶åpjR&j¢¾€¡Nj¥êk¤6j¨Nê§ê©ê¥vêOÆj¦Æ‚¥Fj¯æê¢nj­æê®¾j©>k§6k£~ê•îj«¾j²&d³~ë¨®kne·¶j±&êVëSVêW^ê¸¦k¥ë¤&k¯Vk½®ë£ìª&lµlÉlÊ®lË¾lÌÎlÍŞlÎîlÏşlĞí<Èƒ¤æ„¡Ş…<ØÓØ…]À°€]°€ÒfmØ‡<¨m¸êÿÑí¤ÆÒ&í¡Îƒ+jN°mÚNjãmÒíÓfmŞ¾êÙmá.nâÆ+ÈnèŞîp@mãFnÒ6íŞŞîÔ¾n¥>î¥¶\0àîmİ‡ã~mónÚvoâÆíØÖí<Èí<€o÷†mØ†îŞn×Nê+Àäví+ ïÜön¥>oİ>í]\0‡ŞğÜpÖÖí+°mëîøæâ6îá.î''íŞvoİ.ğÖ&î\r‡ÑN¸p€qø¦qøæ„ŸqÇ‡p˜ñ§q\0àñ"''ò7o''rß…!ŸñÚñïñç¯í%‡ò!ŸpŸprppí%wòÇq‡qÿŸp§ò0ïqÕ¶ò!?pÙ¶rò(ws:—ó*_óØ¶\0:‡ñ?òş^ó%7î6óíó7m§m0q=Ÿqí¶ò+hqIWt:/òwq*·€+àòçqgt"÷s+OnOßn*?óMïïOm6uY¯ñY¯u[¿uYt Çu^ïu_ÿu6Wt`vb×u`_sb×òX¿ó]Oö[¿Àƒ\0‡h§òi§öh‡öhÇpàö‡ön÷vmÇvjÇvp7÷n¿viŸq<÷i?wm—vn?wnŸ÷j''÷pOwm?÷tŸ÷o¿ök/÷¯ömwkïqp÷wygó{/÷wçwwwøÿtïvr§÷€Ïö7÷m÷öçøvÇ÷u§r·ö€Gxqÿv”''yƒÿöçwv·÷}ù˜—ö™×ø‚—yœŸvšøx˜Ÿù}§ùr¸¢''zØvlïyœ·ù„—ùŸÇy(ú{(‡}ú¢/¥Ïùœ·z¢§úxúª—z¯‡ö¬×ú˜÷l7û_{¦''ûqwû³×w§w{§ø}ß](ø¼ßy]Àƒ¼ú¬—wm×Â‡û\nÈöÃ×öoÈù¿‡v¼GüÆÇû¿ÿ†yëhoü¯''û\nHü}Ï{]Xü˜''|Ìï|po·{wN0ı²ïü¾WúÔï{à|š_ûxıÚ‰ı\0}³÷ÿïû\nøûv?|ÎO{³ß„ÙWúÃ§ıË}™×…MØı\nøé—şMşèıéÿıéßşoÀƒo¸~îß„Í—~h''<ˆşÜGÿo¨şí~ñÏşÜ§şõg÷''Áàşé/ÿõç~õˆoV8ğ†Á„ºô\rh8àŞA†!\nD˜ğâ·M7á‘ØâÆ-VğøĞ Å‹)-¦DYå·2¼Qá™5f¤‰°àKuUx©ëÛÍox`<:ó%Æ¨R§R­juª‡Z÷µÄèí*Xƒ&A.Ô\n2ìÀ±WÕ¢mö«Û¸^\rÂõöÕî·»xõòµë—¯À¿‚û¥;xo_½ßLÿ:ì§˜ğß¼€''üØ¯>~šù‰ğËI-âÈ…%{6iï°ep¦Z\r8/éÁ±\rï-›2dÈ¨ëníû7ğàÂ‡WşİO«Ö|Ä—SŞ­[ho%µfş·#õá›L[ïîı;xïĞı÷]¾ûyèç[¯÷O«?­%Ìoï·½üä¼s·[¾[~ùùâ}ıy“ß:ZÆİs–­“ßâıeŸyíQÈ…êùg™zÏyØà‡!†H ˆ$‚hâ‡ş•ˆâˆ"ŠX‚V&ù“b7›iæâ>6Šà¡7ù‡\\?údÀãs™mÏ‡&ÕÓa7>"7@°¨¤06–ˆ‚Ø>?6äO		ºHÿ$“ãy&š’I#‹4¶ˆ&œqÊ9''!ÆçĞ8^ OˆV6„å‡hÕÏ‡~>	år£¤‡‹iµ¤‡†>ÙO¢ÏmãÈ•ğ¡£Aêa9üÚŸušz*ªqrÃ¢«ºúœ«¯Æº*¬±v3+®¬æŠë­²²Úk®ùÈ·¨|wÜzk=ÃººIûèÊØ¡%ÌÊ¨«JV;ª²´B;ª³×z\Z«8zšO®À«ë®é–«.­³ëk¯ï²;/½õÚk¯¡úp3rÌê¥?³\ZÊÁªãdêO>÷Ô#©7±ZëmCõ|pÂ#×0Ä•PO>¢jEğª¯ºÏ ùØ£ÏÁëÜ»2Ë-³kÿVØ 	6Ü±êPpÏwØÀÍÎ4½sĞh«\rVX¡óÍÜğl7?;­3Õw(mÏCÃ¬	Ì:M7ZÀ·MÎùÚLµI!ml¦ılµ°™â\rÕ†ŠQ3ĞJ\nM’?ÇİPŞX¡‰&†ª­³Œ‘ë´\r†vË÷£X[ÁÁ 5tcƒ\rïÈg´ÓÜhÒtÒ:Óì3æ6ÿÌ´Ï`¯ªs×`}3Ø9«N³Í´îªÔ\\ÿœ3×F³¾êÒT3ÑˆO³ğw óÖÁo<ÕËõó¾ş¼I% =(ñ:«³•èOÌÜx£=ùè#¼7&Ñ#¼û¾CîĞûĞ¡¯>ûñ‹nÿ¡Îc0hğô7¿†¸cx~òÇó4¡´êMïzò]õºæ¼à-zÄ\ZÓ¢''<é1Ûè Î>(<‚°„%$¡	u¦\r=‘Ogâ@:†ç1(ÕM+ãøZõzà$úÁO+ô ¡\0C(<êé}ÛsÈú¨öÁ\0:‹ß6¶1C\n¯IZyG\n³¨E%v…[a£èÁ0’QŒbãË¨Æ0\n‘fT#\Z×˜Æ4~Ï!ş #òUFñaq™*Ãø}”@\\\r1bİGFE–q…<ÔûÄÈÈF‘dôK¤Ã˜)~Ôƒô¤Iò±Æ7ÒŒ!,%áXÆ8¢ò”¦¤$*cIJYÒ²ÿ–±´dC‚¤]ffPd´B72µmˆï‡{4$rŒ‰ÉK†q’ÄDf&›ÍEFs’Ø:”>l©Ímr³–Úøæ\ZÁ¹\rmŒ“œá,#9Í‰Îrª“âŒ¢:Û9Jh\nã¼§Ÿø±\r\\º\rQ”TC4MršDş4É<Ì	Ğ”™Ëlˆ2ŞÉ,­$â¡Pæ5Ÿ¤‰’œ•ç7åyÏú!§H=ZÎp´¥.})Lc*Ó™Ò4¤óÈè“öÑRhE^ÚhH5!>0áCÄrHBË‰Ğ“.U¨ğ)AQÚ„†Ô$øpéRµ‘UmŠ—^õ*UkêÎš’µ¬f=+ZÓª\Z´”­eÿ]è5ıñM·jc†&AÇZCÊDØ¡ªZ	«VÿÊTÁÖU+ ,Rç\ZX$ºÔP~M,[gÈnäU­–½lLİJ×Ê¶µı¬gCÊÖÑ’V±š­hAKZÔ®•µª\rmiC»ÖL\r`_å%m¯úÙ‰~É!üx­V~KWCÍ´%íqkÜÓw´‹\rè64«¶Íu©l5^a;}Ì£¸®E­tU›ZÙŠ7¯¯5/g_«^ğ²w½î}okß+ßùÊ—˜•[­g3a¨¨ö…OÊpÚsĞÖ»MîÿêÙûz–À\nş¬âŒ+ŸÑ"­.@ß\r¯7¾óõ0‡İ›‰\Zä"#®ÿ& â* xÄvH1‰«0ÏÎØ³v@ñrQâ\Z¨¸™¨1‰=+cÏæ‚ÈŸÕ±2Qd“xIÎE_Çd;ùÆ¶£†s¡bmHÊ9ĞF‰¡¼BùhâÂ‰Ml\0Ù$ˆğ¬Jp\\˜9øøqr-}\\ ŒiG‰«àæ KQ>è¨(ö{â	°Ê Uq‘›i#Ó¸Æ–~t‘Lä"WáÄnqXŒg;äBÆP2ŠoŒc›xÅ*~5«üãW›ZË§fô©gİãWÏZÆ¦6u¬i	`¿:Å*>rÀpëbÿ8,¤2£¯¬1X¿z[Ø„öš¬â5ÿØÚ\Z-6ÿ°ÿµXgÛÖaÀ©?8áj\\÷˜ØÔ¦vŒ=ã`ÿúİ´æ5»İiHëÚŞş¾÷¿áğ›:?8µù7p\\ Ú€u\rô”\n{‡ƒ‰òÉçÃ·­6W{ã*®ø¨J€ñ;¤c‡â‡8È­•CP;´E?Úğ™ë›æ6¿9¬³apó¼ç>ÿ¹Î''\0t«˜çU:Ò^ğ×€‰ûè¹Ğƒ®ó@ ç\n>çg6¾sWAGØL"›¤ºØƒŞ‰¯ç#e''»VRqIíã>7I*¸şj ,´ê~õÖµu®KèI7|á‘.xŸ=êˆ7úĞipøÉGò–GúÃy^ÿ oşçEï¹ä/^ıó›w|6BÏs\\X^¤?»Îi€Ç³ôÙÀÆèõ‘\rL^õP>x‰p`#õ½ï''ßy¤''ßö—×yíŸécBöØ\0&b*`u AñÁ\\L¿˜ ‚äY_*L_Ô÷>&°A*dC:—@¾¿}\ZÄ?ÿì—\0l õaÃùáü]Ÿâ_6ÌŸHŞ÷µşU_„ß" ú¥_ña_ı‘ØùeßúÑ@\0Røa_÷É\rLàü]Öşáÿ	`6ì	’_àBşe_ı}`6lÿÉ_\0Ò\0ÿÙıuß÷ßbÿ \nÆ\0V`ıIŞ\0Æ_\0J@z ÷á	z	îàúİ_ıY!6´àºıáJabßÿ}ß~¡ÿ_NŸ® Ú!ûyûU`\Z`ü‘`ùı!!RŸûùaûå!û)"""!Æëı!ûıŸ!NßJ üÙ!ùi¢Ú¡ûÙ!øÅ_!Š¢#:¢("õ%â!fâõm"¦¢ü!à'' $â''¾_û‘b)²¢üußùÁ"øıŸû‰â''`bŸÂb''v¡''Şâ.>c!Ò!Î@!R#!Jãb#4n#7v£7~£#Z#8#9–c4šc9Šc5ÒÌ€;¶£;Î\0;¶ãôÅ£<Jÿ£4Êc<æ#5ÆãôÁc?¶#@b<Öc@Úã?Zã>Ò#6Ø£Cæ¡C¾cCò#AÊcCúã>*äCîãEväEÂ#?ö£>ºcEF¤Hn¤HdB*dCN$6’¤AÚ#@öcIR#=äLúcKn$E"äEš$Bš$;fäI$P¤P¥R&åR6¥S>%TeNeDN%RF%V>¥UfåV2¥Wf%W:ä\\B<FÀŒ¥;NA;FÀ-ÌÀ-´¥;¾åD\0°¥;²%;NA[F€Z¶%\\Î\0Y–å%¼%Ü\\ÂPÂ#_¶ã[úå_Æå[ªåYf[¦Y%_F@¦>Ş‚f¦åHÿ&`å_ŞÂ%L`2¦=¢&;š¥fÖå[~¦\\¢&`ªåĞ%cÚå`ºãiæ\\’%[&bšæ%l¦[jæeº&Qææ;J&bjæiŞæ-Ğæq¾fq*§[Â%e¦f6¦c†æe¾cZnå^¾å`ÒÁm"§z¥z¶§{¾§{Şæu"gzzætÂ§f¦''~ªgqêç~ºç%Ìç{‚çn&|hÒ''[¨~¦§€®g‚F(|ú''tò''€º¦„ZèŞ&…fæƒfè~^ƒfŠ(r^…^ƒˆ¦''æ''‹èm¦èh‚’hhÆ¨Œº§‰¶gzŞhÜ(Š¾(Îh‡–¨‚âèŠ®h‘6è€êÿ(ˆâè{)ƒòeƒú''…r(„fhŒB(”f)Šv©—~é—ö¨—(˜–)˜Úh—¢©‰z©šª©™ò¥™Æ)ŠŠiš~©Š)iæh™ºéœ–)r)šÆ¨›ú) ‚)™æ)™vé¢ÊéŸÊi–î©œ*ª£şè¤V*¦fª¦n*§vª§~*¨†ª¨*©–*§Ú‚%\\ƒ-Ø‚\\ÃÌŠZ‚@\0­Âj—Î*È€­ªêª¦ªü*¬Ê€%Ø‚ØŠ¾ê«%X‚­®ª«ÎAªª*\\C®J°+ëªÂ*«şªµªj«¢ê«Òê5´ª«ÊªLë¯š+«"k¶ÎÁ´¦*«—ÎÿAº®ª®Rë´B€ h¸B+µÊ\0±¢(½«-$«±¦««¢è¬ë5D+¾ÚÂ´ÎA¿²jªÒëªÒªÂ^ƒë¯>+ÃN,¬ìÂfì³\n¬®²j—®ªÃ¢(¿êj¿ºê¼¶ª¹>ì¯FëÂ+½Î*·Ú*³Rë±JÁ²Òª%ğ+-­&­k®&m¶ò¬Ó*mÑ*­Ñ­Õ\Zí²¦+ÑN-­.-¼:-×VíÒFmÕRlÒ¦+®’íÕš-¿mÚ&­ÄR,®†­Ñ«ÖN­ÛªíÖ­Æ.í¬r-³ÎëÕmÜêm×&­¬ê+×JìŞZ+³-ØFmÃ\ní²ZÚ’­Åª-à2nÔNí×\nÿmá\Z®èvíÛ®é.ê¦®ê®.Ù–îêº.ëÆ®ì¦.ìÎnëÎ.İ\Z®5ì.ïö®ïş®ïÒ*ğ¯5@\0ï\Z/ğ"/ñö®ò.oòş®ñJó6¯ó¯óRïõ>oõî.òJïîzoñnïöJ¯ôb¯ø6¯ùŠoønoúò.øª/üÆ¯üÎ/ıÖ¯ıŞ/şæ¯şî/ş>\0ï>ÀTB%XC°<@-Ô‚XC%ÔBXÃ°[ƒÿ&pïnÈA°[C-Ä€kp-ìn5,p€00p-T/ğ3°	£°<°‹ğœ°ÿz0ÇÀ	C°5Tƒc0ïÆ@	ğwÿp-<€Ç€Ã°WB5øï\03pŸpŒ°;ñË\0;0\0ûïğ1GW[\ZqïÊÁ+ğ\0''°Ã±kğ‹0?À\n§0cp/p?@540 ?°×°òË³±{±ïîûqï.sñp5t²''r!WCWCDOq(p(Oqğ*Ÿr!7°''Ï²\0ptrÈA''»p!²''ëò.Ãò*wr£²{2!»pğ2£2)—òûr)Ï²/{ñ2''s\rr-xò6/±''Ÿ0)ó)K3''ã²3r—s''ó6s8Ãr%Üò''{ÿó4''°ó12«ñ)çò.Ïr7ûò\0r»²›²3ó7‡³3*Ës#s''ô,Ot?S´E_ôEû2Fo4Gw4Ek´G‡´H4I—´IŸ4J§´JOtt2xòKÇA¼t5ÄôKß´K[4ÈôMÓ´''ótL·4L»ôN5NÃ\0R÷´PÏ2Mû´L3uM#õD''õP''µO÷4LÏôSKõ,ku''õLGµQûtM/uKï4R/uNó´X×tWOuSÏôU¿µ[ÃtO75WC5]_5Y‹u^uVWÃYŸµ`KuZ7uY[µbkµb7vcËµb×µUG5U¶cS¶c[5dkvf?ÿöYw6hg6e‡µXËuYõfËõf£õ]ã4fk¶i5Q‡6UGökwöjçµa‡udç6eßö]ß¶_³6ĞÂLÓ- urç6g;vr{¶c3÷cÓvR?·U[÷uƒ6vƒöq76%PwZKwcowfç6y/wg[7y5n7v³whÇ\\÷vc·¨vvW7q§wq''u8€@€x€¸€x€S%¸Ğ/ø€8ƒ#¸„#8‚C¸…/øS8‚;¸…;\0†c8€CA…[8ˆø‡—ø‡ß÷Šøkø€ÓÂ‚8€/øNK¸Š8Ã\0ˆ¯8%¨øw8Š‹8Ğ8†ÿ¸Œ8ûxOx@x88Šƒø‚9„+xx‹ù”ëx‹{ù—‡¹˜¯8Œ¹™Ÿ9š‹9”yš›9›£ù›·¹œÏ9×¹“ùš—yœ¯ù8Àô¹ä9›ÃÁÄyŸë9€—9 ë9Ÿú€ú ÿ9€ÿ9›Sº£¯ù€÷¹¤úçy¢ûù§3z¢z¡#8¦[¸oº¥s:¡;:¥#ú©£:§z¡3ú¦Cú¡_ºŸ{º…ßú›¿:§«º¥º¢³ú§{ª/z°Çº§cúªgz­z±cº§z¡×úµkº´ÿy¶Súµ“:·o»·—z¸c;¸s;º{º“;ºOúº³ûºŸÿû¹¿;ºçù»‹{½Óz¸+ú°«û½ïû¿“º¾ó9¶¼»G;½ó¹¼{û¶;{ÃK;©¼¹Ã{Ás»Ÿ§;¸Ï»­Ó;Çwüº¿Ç‡üŸÏ‚Èÿ9È—|¹×ûÈ“<Ê§¼È¿ÁÉ§{Ìw<Ì·¼ÍßüºÏËã<Ï÷¼È7\0Ğ³|Ğƒ<ËSÈOÉÏ<ËÏB¬;ĞOÂ¼5ü9Ò|Ğ7=ÉOÂÓïüŸSÃÒëüÇƒ|ÍG=ÌC=Ë—ıÔ7ı<5LÔSÃÔ¿Ôw=½7}Õ¿À$x=54€Ü=ŞOÂÉû½ÔÏÂÉÏ|ÕÇ=Ì¿AÓOıÚ;}@½Ü¿\0ßÏ}àÏàç½ÚÇ|Ó÷ÿ½×?şÚ—ıĞ‹ıß7@Ö?şŞwşÉ#=Ø¿=ã{=Ş}×+¾Éÿ9é¾ÕK>·O=ÜG=ïÃ}ì=Ğÿ¾é§½ïÃ}Ûëşé?İÿ¾ñç=Ü;¿ä3ÿîç½Ö»ıÄ=õ{~Ûçıõ_ÿñ·¾Éû¾ô{¿÷k=òK½ñ×sÿî{ç¿ô''¿ùë¾Ú?¿ûÿ,ø¾Û+¿îÄ¬ÔR3h°Á›I\r.|Ağá‹IÔ&´Hí…Bƒ#"|8)âCƒ³D"¤øBdEß\\Hqä¤Š\r&¾(ˆ¦M\r¾ùxqšÁ @y-zT(R£§\rUš”šÓ§L¡N=TªU­[™ÍÊuÿéU°]­~İêTêW´W›¶uûn\\·Q±Ö•Û”®İ»{ùöõK7¯ß»B÷îkøoÜÀˆVÜ.`Ä„ÛF]sfÍ›9wÖëthÑ£IfÀ`š7Ó\\¸x²\Zõ4Id­n*‹5nY’¦íİZš$Û’tãvúÉ[ÜM]Èr½Zµ$M“K{âœ4Ú¬“ËÂıûíî­Y»°İ›õiÕOˆ·Û­´òÍ…ßî\r_¼§g­Ú\rŞ¸cî67®[®-ÕzSM–''¦³µŞ’k®)7Z{P¶Ô^›æ´[;/57Â3À\nİó®©íìsAšéj{5§sP>ôÌƒ0¹Õ¾›Nÿ»ì¬“æGŸxÈ''Hë$!®Ç!ƒó1II~Êöô‹²I#¥¤HY’LÊ×¾PÉ/¥qã4*‰»ÒÉ''ÙãRšİZd @çÚTÓË%Ó¤2Í}DÓÀ$Ñd€È)¥³L UûrIí¤ÌoÊŞ¤493­Óo6BÉ´ÎIÉÜ²E)AıqÉÚ€DRÍà¶ÜÒÇê,T:8…ÓÒZmeôÖ\\u5³Ğ]}ÅrÊ[¡üõ×Qs–X[ÕÎÒe“¥U×7ŸvWdumA\ZlÄVÛ6¶ı²…Úğ–ÛlµÍÖÌpÁMòÜs¥iƒ[wÑe÷Ko´×L|¿ı_oım7IxÍ•W]_6×ÿV{µ=xÛ‚åİ7ß[~8W}ËM·â„wµ—Ü…®UİpEYÜpÉıVäqãÙ\\xI~9ŞÅXà—]^yf“U†™å‘–¹çkV÷æ”KhŸKnÙäwaŞvÜ›¥Î¹Û oN:é‘ÑİÙf«•Æ8æyv˜e®ÍÖÚçXx&Yíµ•6¹dqÛvÛå¹c™Ûí¼õîúe»[h{ê½ï{ğ¼ïŞ{¿×ŞÆ÷.:Üè†¼ğ¸EÆÛğÄÕn#šhÂe¼Xˆ$ÜhHoÁ‰œp"Ó£iCõHdWv''à$–Ôc‰„õÎ=o!\ZÛ#½óá9¦õà£Y€óÔKç=ÜÔ‰w¢ÿ…Äå^}yq•÷wÓO\\ò6œ@xñ=W}|àÇ‡~|É#	zÑEW½úø@ùûÕvâıéÍ_Şt÷“]ã\\Æ;ÉuxŞãÜî<×†÷/»_çX÷>èy{ãZéğ:ÔIğ\n`QG>ñ%.ä ï’÷:®.z|é|÷@ßQ¯sÇ{ßúZ7Á\Zúˆ@ä_m(Ä"1ˆÊëàö€¸ÄíM°ƒÃóİ“èÄ$NQˆIb}¸Áâ)‘‚a"¯XÆ%ZÑ†¼ËañÈØ9+²±x>\\¢\Zƒ(GÓıOˆë+£ùXFÆnä 	YHC‘‰T$"Ù°ÈA6Ò‘‘ÿì$%YÉ$²ÑÈ¤&Y°ÉNfò“Ñh$(GùI”²sL%'')™ÊM²a”«,¥*5ÙÊS‚R“¢â,o	Ë\\ÒÒ•¾%$…	ÊLb’¦Üä.k‰ÉPŠò˜Éì¤0W‰KN^S•Æ\\å59ùÌPúò›Ë¤¥)uII`zS—İL¥3=	Ëq\ZÓ“Ê|''ÉÎ[Ş²›ä´§<ù™Í{~rŸĞìç4M‰IuÂ3Ÿüt&/ÚPN3¡¡”§;*O*“å§F5ÚÏˆt µ¨2\ZÑ²sŸû©BWZĞ–/•éLiÊXÔ” 8ÕéNiªRşT7•),„ÚÒ¢Î\n`ÿ˜Ú¦>µ	N}*, I¥* ©O…*\n‰¬ÂBª\n ªV“\ZÕ®jMejZ±\nU¦fUªlğ*WµúÖ&,U©a­j[àTª6Á«p}*W!AT¶ö«\\*¢šU²V«,¸kWÇzX§Æ¯}u*Wo\ZÙ«Z5©t­ë''ßŠXÄ:±RM*jßº×´fÕ«f…ªWµzÙ²Öõµmf×ÚTÀºU³u\rk]‰KÜá¹ÉUîr™Û\\Ó:W­Êu,k¡[]©¹Ø%.u…kÜêbu¸ºıîx—ËóÖõ¼\nX´Ê^¦º×½ã…ï{éİøwäUÀ}ŸÊ^øÎ7¹LÈo{ÿ¼_â¦·¸N¯yã[`âøÀÊ½/\r\\ß÷—¾ü=/‚ëß''˜Áæ]Áˆ×Ë`ïw½#0ƒÕ»â“XÀ*.±ŒóËâ»8Æ%ñ‹m¬^‡˜Ä)®1{eÜßû8Äõ…ñƒ¼ä»xÀ/±yä·ø½6q‰\r,b/CYÈ!61ŠsLä!3YÈ4–±˜©ÌÔ+ïwËW†q›M,`‡9È9Î³Õæ&çùÏ€nrœi\\f>‹xÎˆtš=èF7ºÎRô¢ë,h>SzĞ‡Ş³šiF+šÑ5§''-j??\ZÕ©şó+TİjW™Õ‹^Ã«_ëYÓšÄ¯¸u w½‚ÿY÷º×¸nu¬S½†`¯€ØÂnõr­ì&[×	ö#¤ì$`	ÆN@µƒ¼†<Â×ˆõ+^îmxÖæN\0«™Íìˆ;ÖÒNÀµg½„yë:ÜÒŞõ+ÖıëY›ØÕ–öˆ½‚SÙó^¼ùíGP›Ùæ÷»í½îT¼ÜçÎö¹¯=ñw¿"Ûöæw½ç=b}˜Ü÷õ®	¾nq_»ä¾şvÂï½{âîŞ6³ùmq¼Ü#¿ø¶×Mto£İ/_¹Ñnów—œÕß6¶¸½]ô|c[áıvxÕ‰Nío½âà®º½no¯{èçv8ÚÓNm¶ƒ}Û^ox»®ö´ÛÿÜáØ®ú#ÖÀ÷m=î_ïú¼Óşuf~çD‡yÅÏx¸wİñ0¿;ÛÑşpÉ·ìf_xÑ	?ø´<ñqçû#@otÄ¾á\rüÃWOí¾§ıâü}àÿyÇŸ÷¹×=îoz{Cî¥ßığ±N|ã''\0ø¾G~Å“úâ?ÿøÑÿ¼ñ›/}ëKŸñ½¿şç¡Ñ}à{ùİG~ø%x_üéÿøÉ~ó7ßıáG¿÷\rşm‹Ÿùëw÷A¯ùÏüšïüèèÌo\0—Oş˜ÏàOıäïşÌ/ï\0¿m¯ÿ’Oıêoş¾ÏÑü>0ÿîoùà!ğı*ÿÏş0ğûÿP0ù~O70üpùïsPw°yĞUĞoğ÷t=0pşlı”p	{	Ÿ\n£ÿ‚°¥°\n¥³U@»]¡ÕÀÇËğÅpÑĞAÙ\\ÁıŞ\ZT@¡ÁPU€Õ@\rT@\r\\A\r ûüP\rÑ»\rQáùP±ûT@	êı\\•€ß&1ëù°û>Q	À°\Z”@\r”à\rQèpÑ°Å¹‘ßQ±1K1Åp1Qñı¡a\r{s‘\r±ÿ#½ï¡Aãğ¹‘	‘ÑÑ0Y]á-±û\\AÑûb‘Û¹±¹Q\rÿ0İs1ñ!R  \r!]1Ñ 2 òsÑ•à#ÒÿÑ #s\ZrQ=’;òr#?Ò"•€!3’R#\rò#!2#Ò&o!M’² ‘%a²#1ò!ÿ"1''AÒ"×°7&òıPQR(q2$mò'' ''1!ƒÒ!QÒ‚2c‘!«R*3r%²)a’!!ÓÒ-İR%›-ò-R!ëÒ"=r&å².Ñr&S/ÿ=R*“02-Ñ"ã²0Õ’/³1s.İ22“-sñ × 13!9<S	Á=“.7s%7“3E“%!ò2›R4ù²5a’4I3/-s4e“1?7%²57;Ó"_''1“!‰“-…S-yó(i³6×24uS7‹ó(C“93Ó2™“:Q9-S61ó2¿<E³5Á“:Ã“<Í=ËS7¿s=Ï3<?3<Ës<Ñ“>ë<áÓ>ÙS<ó“?ùS>û3=Íó?ß@½Ó@´@mAİ3"Ï?B#TB''”B+ÔB/C3TC#t@Ñ³¾3\r$ôCó3\rB=M@S\0DÿAEÑ3FtCÃFûsFŸAEíSE!Eo´E¿³F/“GÍóFësHÑ3	,4D‹<S@ImTF{tEÍ3\rT4\rZá\0’ àE“ ¬ô\0áJÓàK Rà\0¬ôEÓ 	L@M_ô\0ÜTKÔDS \ZáC[AE[!\r\ZAMŸ¡J[aMY”IKTO“ÀMŸAOÅ4NCTG«ÔF±4QÏôMKLï”I%ÕM­ÔOãôCÔN§4	,õ²ÔNGÔTµD±ôC5N¯ôKS JËH™ÔLUôJ¹ôJµT5NkõUõO[!K­”XÏTP‰5	ÌôXµ”W×ôEõ”RµôLÿñôJñ´MuTãÔNMµDÍÔSyÕF=[Éô29UMıÔLGuTÅQ_5K­µ[ÉV_5N³Ô^Ï^=uMóU_­TRË4Nµõ^©õNÏ´_É´XßQã•[×ÔK¥u_Étb1uMñU_ë•Z_•QÉôbÖ^›uY¹a_µaöd5v`W–Q›ÕO;öUÁdácÁÕK''Ö^–b›•b­Õ_]¶K)6cûôd1õTáõ]e¶ŞÕ_A–i›j£Vj§–j«Ö_ßc­VeµöjŸ–k¿lµÖkÃ6_³–l­ÖaÏöJ!_móuls5a™6aM¶ißöUñ¶mM6nïvoÿùníõmŸ–aûvpÃÖm›vlõ6gû–j“ q¡Qwk£vn™6rí¶^×q–rU6ssÖmwtK×tOuMrM÷\0R÷t[wta×ug—vOwuk·vãÔvqwve7vyxy×wI7xƒWv‡7wáv·v—·xK×yŸ÷w¥—z«×zU÷z³W{·÷u¹7y³—xÜv|İ\r¼×Pà|Ñ—zÃ÷t×·tİWzáuéuå×zË—}×~Ÿ·û×mY\rÀØ\0!€ÓÆ÷|ÃXA€Ñ\0\r¸€ÑÀ\rÀ\0ĞÀ\0˜˜‚	6	˜0Ø\0@Ø\0ÿÊ·ƒÓ—…Ó	Ôw}˜‚˜,XA˜€Á}8€!8|q\rX‚Sxt‚7x†Ï·„K¸‚ixtYaÕŠ¥xƒG÷…%ø|5ØˆSÀ‚ØˆÉ—‹Õw†8ƒá÷|Ø|İƒ	f˜‡YÁ‹x„é˜ÇxˆÅ8ƒS‡é¸‡_X-ø†¡„køØ¸„O8‘GwŠSxG¸1¸„‘€hØ„1ù…MX}3x“C˜49‘MØKY“M¸€M˜‹›˜’7Ø\0B8ƒC˜–18„52Y‚où„/ù€9¹“Kù€iY˜1ù•1Y‡ƒyƒ7ù„Gx•gø‘ÿgx˜1ØoY“Á˜Àx„i˜‹Mš1˜‡İ—ƒ¹’y–Xƒ™7˜¿ø€í¸¯ù\r8Gœ…ÙSØŠ‹X„Ex•ÁÙ•­ù‘Gù’¯9|;Y¡SX¡yŸ}¹“oY•ıx¡+º¢I“\Z¢-Z¡ñ¹£9\Z¤‡™˜-Z¢+º ÑÙšCš–û¸šAz•ıy£Mz–ÑÙ™#z¡Û¸›º˜CxŸ™™£Y ?š£Q:Ÿ‰:¤\Z©“Z©—š©›Ú©Ÿ\Zª£Zª§šª«:¨o¹šÁ„³\Z	š¡«»Z«µ\Zƒ³z–ÃZ¬ÏÚ\0´\Z¬·Z¬Óš­É:­½:«Õz®å:®İZ¡½\ZœÁº®ÿÕš­Ë™¬õº«mZ¯Çº°¯\r{¬ãú«ÓÙ­²#±ÍÚ¬·z±gy­óº­çú²ãú¬Íz­Û°+{°áú®Ëú´9»®-»´5®å:¬»¯g›¶k»¶GØ¶9Û³ëš¯WÛ·y[µs»¶e[¸‹Û¸m{·…;¹);¸#û¸){¹Ÿû¹£Û³Ëzºg¬{[º·›»‹;º»Û¸¿[¹Á[ºÅ›¼Ï½Ó[½×»¶O`r{Ø;·Ï «O@¾é[¾ó;½ñû¼í[¿ÿ»¸ùÛ¸íû€¾éûNà\nÀ¾í{|Ş{üNÀÀÏ`ŞûŞ»*¼N \0Î@Äã[Â%¿ÿ5\\Äİ{®-üàÃ³z¼Ã]üÃİ;Ã›Á\r|D¼¾`À¿q<Â1ÜÇÜÇ_|Ç5üÅ}œÃe¼\0h\\È›ábœÇ\\Á]\\Ä1<Â›Ã_|Ãq<Èç\ZËŸÜÆ›Ãc\\Æñ›ÈÍ\\Á-üÅ;œ¾#|®¿|tÅ\rœÁ™\\Æ“üÃ5\\ÂaüÁ#¼Î]<ÌÏ¿\\Êy|®w|Ågû\n ¾}¼\0ò|Á''}Òã›ÈAÓ-]Á|ò<«/ıÒÄ\0Ç‰Í[|Ò@ÓãÄ}ÈqüÉıÑ?İÇÇëÜÇ=ÇMİÒ-Õñ<ÄW¿G½À|Ò''|ÕeİÅ%=ÆcœÕÿÒWÒ?İÂ''}ÁU½ÔÜ½W!Á±}Ê[¼Õ­Í/=×­}Õ''¼Â-},<¾¥¼ÚıÉ§ÛU]Õ±½Ò-ıØ‡½Ô<Ãç]ÈëÜÖ“=ŞËÁ•]Ë“ıà''=Á¥ü×K=Úyá¥]Úƒâ+Õm]â+~àÙØkØ;ÚEıŞ)~Ö7ây]Õu=ßgİäç]ÜüÕïıĞ]Òi]â7ş×u^Ú·}Ş_¾â]×^ç<Ú©ã''âK]ßÛ=Ú}~Á+]ä;ŞÓ_¾ã;şÛØµë»Şë¿ìÃşëy^ì)^äËíÓ^íË~è×ŞíÇ^åw~çã¾\0˜Aî''İî]èÿótø~ï_¾Å7ğÉ^ëóş×íşÑñ·^Úßî#ÿëÿ~Õy½ï~ğë¾ñßÒûñ/?è·şò¿ä1ï7ŸòïEŸóUŸèóŞòëşÉ#_òi]ñiôÿŞò[œòàïß÷S_ñ5ÿ÷‹Ÿø=ŸññqØ¿øùÿøWŸò«_ó‰Ÿøy_ø%ÿø™¿û™ù¯ÿç·ßø}õƒ_÷ŸúÕ_åy_ù±?üGıû¿÷qş5?ø¯ÿùâHf\Z<Èl`A…64x¤`D‚J„˜0£C‹+ZlÈğ ÂI’„èñãÇ“*YŠ|	òbI—Ìÿ¤T‰³$Bš9{úü™ó&Ğ¡D‹\Z=Š4©Rœ<—:}\nõ ª¥f¢Z½Š©	„[NEXÕDUfFL Ø• *&Úš©ªêkÁ·_álÛÕŒª¶l	*šk".³µ»f¶UîT½~ß\\†P]¹w¤›Ö bµqùfŞÊvªaÆE3S”w.AÇj«ª\n7ğT#g]÷l;÷íØÕÌ‹kä ™Ú¼yoİ\Z|pj´&‚7çŒø²l¹Í,[FÀˆ#ËâÈş–ûw¶f-+ÏöúÖ·•…\\ï`Úf±w‡Ì¬{YËÜyİœ^Šüµ_UüIÖymi‡*×·Æ¥İğ€e­eŞY&`''Y`lqgD…¨\rxŞpßõ6`\\İ½f„"{•‡ƒ¥×â.#3g½µa¨•Ö"fl[‡ŠÕ"‰q½¶mª„œ""Ù†rgİutDÛõ™(¤d’ÑÆÖoÃ!	Y!şE]ü-ÃÙ½y]šZšgŸ„u"ø&mıÁiŞ€ÛÕÙßj^g^pu~gY"Jèuß)‘\r>(é¢r—h¤ã€''‘ªWgˆ{\Zg£f˜é{$šY –*©ƒTªú v~&8ªš¢Šú¦”‘vêŞ¨¯6Š©„²\n©¨÷!+e˜^\0;', 0, 'default', 'gallery');
INSERT INTO `[prefix]_module_settings` (`id`, `name`, `value`, `cat_id`, `site_key`, `module_key`) VALUES (160, 'showSubtotal', 'yes', 0, 'default', 'gallery');
INSERT INTO `[prefix]_module_settings` (`id`, `name`, `value`, `cat_id`, `site_key`, `module_key`) VALUES (161, 'showDiscount', 'yes', 0, 'default', 'gallery');
INSERT INTO `[prefix]_module_settings` (`id`, `name`, `value`, `cat_id`, `site_key`, `module_key`) VALUES (162, 'showTax', 'yes', 0, 'default', 'gallery');
INSERT INTO `[prefix]_module_settings` (`id`, `name`, `value`, `cat_id`, `site_key`, `module_key`) VALUES (163, 'styleSubtotal', 'normal', 0, 'default', 'gallery');
INSERT INTO `[prefix]_module_settings` (`id`, `name`, `value`, `cat_id`, `site_key`, `module_key`) VALUES (164, 'styleDiscount', 'normal', 0, 'default', 'gallery');
INSERT INTO `[prefix]_module_settings` (`id`, `name`, `value`, `cat_id`, `site_key`, `module_key`) VALUES (165, 'styleTax', 'normal', 0, 'default', 'gallery');
INSERT INTO `[prefix]_module_settings` (`id`, `name`, `value`, `cat_id`, `site_key`, `module_key`) VALUES (166, 'styleGrandtotal', 'subtitle', 0, 'default', 'gallery');
INSERT INTO `[prefix]_module_settings` (`id`, `name`, `value`, `cat_id`, `site_key`, `module_key`) VALUES ('', 'checkoutFields', 'a:13:{s:10:"first_name";a:3:{s:5:"title";s:10:"First Name";s:7:"visible";s:1:"1";s:8:"required";s:1:"1";}s:9:"last_name";a:3:{s:5:"title";s:9:"Last Name";s:7:"visible";s:1:"1";s:8:"required";s:1:"1";}s:5:"email";a:3:{s:5:"title";s:6:"E-Mail";s:7:"visible";s:1:"1";s:8:"required";s:1:"1";}s:5:"phone";a:3:{s:5:"title";s:5:"Phone";s:7:"visible";s:1:"1";s:8:"required";s:1:"0";}s:14:"payment_method";a:2:{s:5:"title";s:14:"Payment Method";s:7:"payment";s:12:"allow_choose";}s:16:"require_shipping";a:1:{s:7:"require";s:1:"1";}s:9:"address_1";a:3:{s:5:"title";s:15:"Address, Line 1";s:7:"visible";s:1:"1";s:8:"required";s:1:"1";}s:9:"address_2";a:3:{s:5:"title";s:15:"Address, Line 2";s:7:"visible";s:1:"1";s:8:"required";s:1:"0";}s:4:"city";a:3:{s:5:"title";s:4:"City";s:7:"visible";s:1:"1";s:8:"required";s:1:"1";}s:6:"states";a:3:{s:5:"title";s:5:"State";s:7:"visible";s:1:"1";s:8:"required";s:1:"0";}s:9:"countries";a:3:{s:5:"title";s:7:"Country";s:7:"visible";s:1:"1";s:8:"required";s:1:"1";}s:3:"zip";a:3:{s:5:"title";s:3:"Zip";s:7:"visible";s:1:"1";s:8:"required";s:1:"1";}s:15:"shipping_method";a:3:{s:5:"title";s:15:"Shipping Method";s:8:"shipping";s:12:"allow_choose";s:13:"show_shipping";s:1:"1";}}', 0, 'default', 'gallery');
