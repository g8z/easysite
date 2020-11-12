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
INSERT INTO `[prefix]_module_settings` (`id`, `name`, `value`, `cat_id`, `site_key`, `module_key`) VALUES (70, 'noImageThumb', 'GIF89ad\0K\0�\0\0\0\0\0������JIJ~}~pop���������������������������������������������������������������������������������������������sstccd�����������������������������������������������������Ŀ������������������������QRReff��������������������������������������������������Ƚ���������������������������������������������������������������������������������������������������������������������������������������������������������������������������������������������������������������������������������������������������������������������������������������������������������������������������������������������������������������������������������ƿ�����������������������������������������������������������������������~~~}}}xxxwwwtttsssooommmjjjiiieeeccc___\\\\\\[[[TTTRRRQQQJJJIII???���!�\0\0�\0,\0\0\0\0d\0K\0\0�\0H����*\\Ȱ�Ç#J��Б�:u�i�ÊUkY��\0�kA��Cґ\0k�4Z���Ș�N�`3�#�YL���1$ȓ1�(��#O��X��H�NҨ''ej�ʵ�ׯ`Ê;��Y�g5�M���jo��umZ�[��}��.޽jע5�j��=>T���Ǫ���a9l�Z,��ڥ\\�G5Ø''?�����X<>�8�bϫN���f΄7�ƛ��������!�����%�����Ο�V.�t�yP�A�;��ڳ����{���ٻs//����տ���{j�ݛ��4U�L3�x������4t0B�0����]���Y�j����!�H�!�xp�*<���,�7��(��8��<���@�8G�8�`��C��L6���(�s09��U>Ie�Yj�`������"�t��Fv\0�";�2���y��V�ف�p���vR��V��''�;��fz��''�����\\�''�d���4�v�駠v*��Z񩨤�*\r���ꪧ�r�ʪ��p��4�T�\n���P���ʁ�ڪk��갫":ȡ�������ڪH�N�ô��Q�`l�UHc,�p�Ȱ��kn��2�k��JSE4��\Z\r��r�o���.���/�р����''q�����\r�o�9�G4���q4�|�p4&�[r)op���t��)��\\��8�2�.�,��9M��(���H''���L''mJ�P/�t�T3=u�X#\r��\\w���\\���d�m��^�m�l����p�-��t��6"v�\r7|����߀��F��n��''����k���G.��Wn��g���GNJ砇94��n:4Ϝ��ꬷ���Ǿ�3�d��SAE��ܰ{ﴏ2�!�<S�?�<sH9&����7��L9��~{�?C�\r��^;�������?S��T\\O����~H��;��3��>������;cB?���>*�/~�3`��?�0}ğ�W?گ�k`���8� ��D8�6�P���!�a�� �G�g���h�):(B�a �h�?���\n!�#�>�яr����@�	ڀt0�kC	�`�p\n_�\rFQ8�\rZl�\Z���6�q�& 	������ �7��6R�@�:��}|��B?Z4#9ܣ#�pDB�C�\\#0��Mr����FI�B��5��\n!�S��$�A?�Az\\���9�qS^���(��K��s&/�yK}�Q�t�(}9MiR��Ȧ6���nn��`9�Az0����6��q���'';ωr����d�:�A�Nr���@����\Z��M�A��\r���\0G<��v�����2�Ql#��G��vPԣ��7��\r�����P�L��eX�� �M	!S�Fa�l`C,0�qX ��=p:�\Z*R����F�FM�T��TX ��i(�q��=��Mqʆ�ʴ�n\rE(��ST�v�@^��׾���Qj_�:���u�z�@`�v\\#\n�G;��Nְ���e���d�!�lg�\0�d@��M-NK��zv\r�E�\Z`�(�� �U-j_;�Ξ�"�V)�[ӶV���@jAA�\n�6���j?���Z���ͮv����rW\0ڈ�w���c����M�z���������o{�A_cL�Ox�1�[��>a��o~?A`\0����5���`��3��''�;�� ��p����bз����9<ac���H1�O��bC�,v�YL��X�0v1�[L��x�1������Ð�0�,,��P^�����w ojx����dq�c��F��<�&#o��ĕ��d5b�Ss���	(�Pv�0���>���~ր?�@Z�I�g���ц�����J[�Ҙ�4��я�c���<*��y����G>��o�z���?�Qi�#��u8Z��wX@����=�Ā�1pB\Z�Alc�aٞ�����hG �pB>��	h���(G>b0�y`#�\0!=�`��!�\0&��\0�A�h���Xǳ�+;�Fx�Qm`$������''���W���x�!\r������K��_�#��H��1�y����x�?��p�c���ď��JV�����)Nt`HO� ����;�z��  ~���@?�	{�]�� ��k��]��5 �o��#l�D�v����N����������[�������� ;8��y�B��/�<�q�t�����6,\r̃#\Zy�<8�N����O��W���o��H�D���ƾ�P5	��	��B\0�?�p����?�|�w#���}�O�^������y�4�	���\Z�N�	�?�a@�N�?�h�>��?�&���ڿ~�������g\0~��}� ~��	�}\nX���׷8��}�x��������؁ 8�(�h� ����\0,؂.��0����g �.8�1�,��8�>�:��0�DX�Fx�H��J��L؄N��K�R8�TX�Vx�X��Z��\\؅Y�K�\0��/�b8�ah�/P���K`�K�	�/�\0���@���th}�ahf����\0�8�{����~�hwH�kX���g��H�s�u����������(�����x������Ȋ����8��8��H�x���x���\r�\0~�Ĩ�x�È��\0�8��X.�\0e���XJ�	�(����(�Ƹ������8�J`����䈌���8��J��X��x��H��8������\rp���X�����\n��\n����\0}��I�y���	��\ny �"9�$Y�&y�(��*��''i.��0�29�4Y�6y�8���5�	<ٓ>��@�B9�DY�Fy�CYJ��LٔN��P�R9�TY�Q�-�\0-@���X��`��]�d)�XI_9�e��l�m��h)�qy�a�\0v�\0�`�yy�|��{��xٗ�	��ٗ�I�Y�����ɗ����9���,0��y�����9�i�����Y�������y��������ٚ��|���I�����+���ٛ�)b���9��Y��y	ƙ�ʙ��М������9�͹ҙ\0͉�ϩ�Թ֩�H@���)�*P��Y�a繞����y�����ٞ�)��9�����)����a`	*�G���G���Р�z��z`	������	j�G��ap�G`�z��''��\r\Z!��\Z�(J�*���p�9z�<��<��=�@:�DZ�@*�D��Gj�8��`P	��`\0�\0F`y`�UjS\n7�7\nF\0T\n)�UZ	��\0y��j\n`�F�X\n�lZ�WڦW\Zd���\07\n���\0� �y��P��\Z��\Z���Wj��z����(���ک���f���:����z��������ʪ_p��ڪ�\Z��Z��Z�z\0x��p\0Ep\0�����E�E�(��Ы�ګ��Z_@	����ګ�J	��������*�����ʭº��Z��ګ���j��\Z���\n�Z��p��\Z�����Z�E@����\0�����\n�Z��\Z��{���z�[�{���\Z��۱˱`\0�0�#k\0w�\n"K��p�#{!K+��2;�4K�0K,���@�''P�*��-�2�\0;', 0, 'default', 'gallery');
INSERT INTO `[prefix]_module_settings` (`id`, `name`, `value`, `cat_id`, `site_key`, `module_key`) VALUES (71, 'noImageFullPath', 'no-image.gif', 0, 'default', 'gallery');
INSERT INTO `[prefix]_module_settings` (`id`, `name`, `value`, `cat_id`, `site_key`, `module_key`) VALUES (72, 'noImageFull', 'GIF89a,�\0�\0\0\0\0\0������???���������������������������������������������������������������������������������������������������zz{�����������������������������������������������������������������������������¿����������������������������������������������������������������������������������������������¿�����������������������������������������������������������������������������������������������������������������������������������������������������������������������������������������������������������������������������������������������������������������������������������������������������������������������������������������������������������������������������������������������������������������������������������������������������������������������������������fffWWWIII���!�\0\0�\0,\0\0\0\0,�\0\0�\0H����*\\Ȱ�Ç#J�H��ŋ3j�ȱ�Ǐ C�I��ɓ(S�\\ɲ�˗0cʜI��M�E�0<�3a2�?�f��D�D\r�\\�4)B���\n��B�J,�j�+H��[Z&Y*�=�����ԜZPt,��I����H�Tt�� O��"Xқ��ف�I��j��<�E���l�έ�yJ>dujN�ex��,@qj��&�2Zf����IUKpo2���ޕw6�o���qK�,Qभ�"Ѽ��"$b˝�������N�s�V�w�j�ޚ4�9sgN-q쟩�N5�0���-WXaF�F qf-&�r��n����<(�x&�x�7�<f�''�dg�v�f�%��!j��p��a�G�$�''W�����X�A�Y2ĵ��e��fG�x�eh#zk)�a���܇�M���1v��__���c�6�W�4����6�lO��݄��5gm�e�&we�e�6�顡��Xc�bi��Mh)�''bX�h.����-�E�e��iic�����I��\Z�Hت��ޚ����8�d��h(��ζY��Ai`���(#vf\n���$j8�n�8��Z��&r+n���8��~xd�鞋b��N����k��o��hf�������/��-���.�G,��Wl��g���w��D|h�!�<���<1����0�;r��3��̭�D�2#���!>]�<:�sa=�2�F?=`�J��2�!�,t�L�,4�M�,.�U�ܵ�O''���,��t�WK\r��p[�3�o�w�w���w�C63�N���W{-��`m��Y�|��{;.v�@�\rt㉻}y�N7wљ3~y�m3���S޺�O�N�۫��4䐏N��N:ྏ��a7n:ɥ���Z��䑃��˛�u���.���w��ڇ�<������؟����ˎ��������{����ï�������\0�\0H�\Z��, *|���!cx=C��''Ad�Z� ��gA2@t�#�58;2X�h$� 2F ;��h\r�*^��>Nx�	�4\r����K���<�:�L�L"F�D	bpvD�`�V�3������冗>#��TD�!F`�%��##G`C�q#c�!� F"ÃtD�g46q�ԣu��5ꐐcd!���%"�.\\�!�GCPq��|�\n�hI&.3�`u�C&Ʊ�f�d =�J8�҃s%''�Ħ�P�c\\�	�IT�Q�~����?.���T#К(FB\n���4e!7i��R�r��6KB���d�KiG6њ�̡\Z�YHq�ѐ��''}��wzp��g=����y��L�f-��F:T��$�$�I�|\Z��"�!5��9��k\\''F7:P��q�n�e"\r	�5�Ӑ�''6Y�GZ6r�|�&���Vr�md@�YQ~R�g�l�!W�Ql5�M�I��ԡ�s�m*GU*U�V��X�jR��ըz��Z-�>�\Zа���hM�Z��յ���p=k<�\Z2�U�،GFG@W~�5�Dի]�ƿ֕�u�+b�zT��t�+a�Y����O]�`��F���E,h��X�������Q7[Z�\ZֱF5jikXC�������lM�U�"���M�^7�Z݊6��Mnr�JZ����u�r+�\\�&ֺɍ.v����j׹���g�+^�w��}-x��\\�׹�}�|�K������ͯ~�������\0�u�q��N��\\�xA��n�L�1��c8E<��cL���F,�x� ���)\n,�?x&0�[Yӵ\Z>��/\\`ϵ�Cx���� oX�-�p��P�&;��\Z6��bW9�D��*���N��\\��b#�Vnr�#;e,k���F�r��,f+s��a�+��L`7?��L1��<3ox�z�����1��c�G�S|��#����B�>��M��S8z�nq�y��O����x5�|\nA��ֲ���A]���V��M`Tc�ˠ.�,��Mo����󳏡eB��կ�u�;��Xg���5���q���^��k�n`/�ԲVv<|�h,�Z݂�=��No���F��)��-Gyϸn�c}��[�N��Y����,ε��\r�\\�[��6��so���$���ɹ�iP�z�?�1D��z�&���W���Z�''׸�Mn��;�E�y�}.h�s\\��湳O����[���[�sW'']�`����N�����fO{������}�h���.�N���꾻�E�w��c���_\r���}ć���;�l�;^�v<��N��߻���;�''�{+^����w�#�G=�#�q�=���㗽�\\''~��=��y�g��<�sOr̿^���<����;������w?��[���>����[�������?}���}���}������/�k������?�������\08�H~�X�\n�~З��w}�G~B���`h�\nHӧ�؁�7������X��ǁ����''�֧���g!� !@\Zh��`B`\Zh\n8H! �@�h�=(8B\0�Ax�=hb !��6x�L(JHS�`\nUH�<�4HZx�\\�;h�E�48�aX�fzJ��3��ƀ�,腦 �y؄(�MX�68�y��b`\nLXx((���臅��)H@��)؇�x�!��_�����K�Eȃ�8�g8�2Nh��8�Y��3���8�b��8H�E؄U��T�����v�����e8�y�}��_؉p���X�pX��ȅp(�3���x�p(b��W�S������ȍpX�b��������_(�x�<H�������8�`X���`؄�������h��ȍ��C؏9�\n���(����؍������X����8��������X��H�������4Y��H����2��1i�>��@��:ٍC�F��"�.�Y�9��;�G9�T�4)�FY�/��@y���UY���`Y�j��kY��ؖn��oɕi�%	�d��ﰗ|ٗ|�~����^	����)z�����_I�}�����~Y����9��٘�阕9��ə�闠ٗ�9�{9����������虔���ٚ�)�_	��)��	��9��y��)�����Ǚ��9��ٜ���ɜ�9��)��i�ԙ��Y�ٝ����9��9��Y�A��Ő��P\n�p��0 p�A ���9���|� ��Y�������P\0z�|YA0��Y\n�ɗ\r���)��)�����\0���Y��I���\0 0�9�ٞ�a����)j��)��y��Y��I�{	+��Z�:��p��>Z\n:������y�a\0����	ܩ��i�''���ɞM���9�B��AФ&��ܹ����\0ʞ\r\Z�A ������,���P����/Z\nHj�&���j�g\n��I� 𧀪�������ꣁj�Q*�������*�HJ�~Z����''\Z��*��ڠ�\ZH�����O���j��*�~\Z��z��ʪ�\n�*������Z�yz�Vj��*��:����E��ʊ�&j�ʪ�a`�A���j�%�����Ѫ�����Z�Ċ��z�ˊ�����ڮ�z��\Z��j����j��ꮿz�몮�ʯ\0��J���К��\n��*����� �+��\Z��\rk�˱�����˱ ��p�(��*��,��aв0����.{�4������1�7۲H��=;�9+�8[�)����KK�7��,��=+�''k�)[�J�Y;�\\˲[۵`�b;�d[�f{�h��j��l�����@�@p�q��@\npK�q��t;�(+���t{�~;�q��1[��හ���˲���Ġ�zK��k��˷���\0������;��;���k��[��������;�����[�0K\n��� ���@\n�\0�@\nxË��K\n@��@ ��K�v�������K�л����+����� �k��K�޻��k���������\0��`����`�����؛��;��s�����k��K����K���컸ܹ��������K���{+����k�,������k����������w;�@�;���k��۹�`���	L��{��;�L� \\��k��$���˻|���K��;���������Źk���c��aL�]l��Kƺ��jl�r<����nL�����K\ng��T�������(����y�<�C����q��~|�l<� �����cl�C��bȞȕl��ۿzɘ̼�,�HL�G\\�]��D��zL���r�˼ǽ�����<�����|�Ȝ���ȿ������������\\����dL�����L�ԋ�����L��<��\\�r�����l|��l������������������L�l��M����L���������M��������\\����\n\r���}�ڬ�$��ޜ�(}�����''=�*�+��.������0\r�''����2�5����>��3]�G-�0��@��?��A-�)��������-��R��K��C��\\}�[��S��:=�c\r�k��n�����r�q��:=�x��u��F��wm�a����*={��|}׆M���?�_�ͣ@��\\���_\0����_��0\n_��\0�_0\n���t�אM�0�q}��<\n��ُ������<ڏ��=ۻ�؜Mܭ�ځ`ء��ٌ���۹M����گی�m؍\rݤ\rӍ�܏�ۣ�٪���=\n���=�.�_���=ܦ\rٗ��0�M�5�ۏ-�Ԝ��׎�ߘ��=��ϗ�ĝ۪=ܫ���ۤ-ڦמ���ڳ-��-ژ�&.ڡm����\r�&�+�٢��"n�&>�6��5��%�ܢ��7�)~�~�m�5^�)>�K��"~�C>\n<n���Mݜ��)��;��3�d�ܦ��7�L^�g��*n���.��`�Zn�d�����\n���␝�j>�\r�;.�H��芾������>�~�~�~�h��M�鞞�\0�&�G��.ڡ~����>��~���>�n���.�>�M꺞⸮��.�^������켮�n��Ŏ����ھ������.���^�p�����>�����������n��.���^�����������n����������������\n������������~��\0��/����"������^�2�?�4O�5�8��:��<��4�>�B��Co���^ \n�.�\0� L� \n��I��`�I/��p�O/\nO�� �\0^��^�^`�d���>@��P�b�n/�_/�q?�z/\n]o�`���J����s�������^p�p�>��k���������~�K?�Y��/����H�������>��M���������P�X/�h��q���O���T���^�����_����ro����?�q_��_����^��H���S/�^_���]_�����T���o�G������������I/��?��뼈ZWР�*d؁�B��uHXЋ-6�h0aE�7��(��Ƈ�D�THPTJ��`:,�c�A�%�THgN�A�%Z��Q�I����S�Q���S��ĦT�2�q�kժW�������뚎E{�kڮf���J�:�n�\Z���Y�}Ӫ5�u�X����u�w�Z�z��u�P�Z�V�BƜu�\\�[�~[pr��q�<�4�e#��Wu��ȷk���j޷�V���q⹁�&�X�篨�7^�8u�Ʊ�|5s���W��x�ŗ��7�����ϧ�~o���ﻟ>v�������Tp���%��� �.8x�.���4%����J�pC	��0��R��8�B�����!��A-D��	iH{&�����ȱd|�F#���B[D�D{���$��;�C\rC\\�CCqPB*�[1�2Ǻ2�	C<�A&���D�*��I��\n�.jD�����BEy�N}�B<R�I��E��0C,M�RSM��q�=���EK\r5LQ��q�P�L�V,U\r�BS?U��&|Xb�%�EC\rM 1ܵ��\rqFWGՕ*e��v�aY-��\\��6ZM�MU���WA��Rq�UVYK�W�r�Ͷ�{C�7�~A��^y~�O}���_p��W�~�7^zԆ�U�^����_Y1��[��wb���ߕ�qc�9�yfzc��f�?�yf�q��g�_��_��&���M�h�qn�瞟~zg�i���꣑�:k�+�zc�_6y欵�ze��&�l����m���Y�|��G\0��;����n�|p�7��A���AQ�?��a�\rx�|rP�ُ�''W��%�r.hV|fŁIg��?W���y�g\0����a�����r�]WGs�7��suJ��rux��|ҙΫ�\\�ȫ7~���x`�������y�aGf��~����zu�?���|���]s����@���!�t� シ�5�LG:@��\rL����0��.l���� *hBP�\0� ��\r���\0�	�a�VPu�am�cv�����V�y����$��q3��Z��APr!�`:�8��Ő�!��B#��9�`;X�����T�b�B(�P�"�!7���Ѓn|�ۘ��0����H�A�A��s�\n%I�#V���c!Y�F\Z����lc)MyH8��sT�#5��V�r����)�Cn2�TT���7S�r����0K�� \ZS�~Tf3[�Lα��d&/S�Le������,k�[������/���6����D�9�9Lv�s��g<�Mv����>��Or�s��L�>�IЃ&T��T�6�Y�}�S���=ZІޓ��/+�Ћ��@*Q�2t� M�?Y�Rj��"5�KKjґ�t�=�BG�ӞU�C%jQ�\nЇ���\0��J���j;��T�Z5D0�ްZԺqU���j>�1V��U�kek[ݺϤҎ��SO��}��w�G>�JUt�C��(:��E~�a���b?�T}�u������J���~-�`�Y�*��xkiM{ZԖ6���+?��O}�c����\nX��C�{���1[�=��.������x-?��\n7�ӥ�X�Q��\Z5�p��n�[W�¶���Qq[�~xw���!��Z���DP�zk��w\0ҭ.?�[������0�\r|`	fp��]''x�F�7cKD����k	�����f�/\r<[~|��J�G>.Q����0���`���Х݄�a������1*&La����>r���d?Y�R�0��|e,g�����CG@`\rg�w��C9̡\\�Y����\\�Q�r����7�A�:��\n��	̎���`��G�Ys�õ���hH��}��5�\r\\����/�p�-PZ}�E�1mjPoӤ�t�G��I[����.��Y[Z��uv��>@���9��D�\Z���T�`_k�����^���R�� �9�@�_H��p�>&�ispY�\Z��\0J0�Q���ش7�<j���6G�\r��]�!ٓv���+\r�Y���f��aU�:Ӭ6Ǭ��s���6�M��g����A^q����[�w�A�r����1''9�1=r�s��ث>��ő�\\�>�.�MN�_���%�7��rڪ����"ۻ|T�A�z�߼W���v�9���n{0]�''5�!�����47��CN����{�{���w�>�X�u���/��_$��G~覝�I�x�K.��[����G	x�ڑ=梯�w7��.^�g}��g�k�Ͱ���k{����Z�}�u�_����\\6�(D�x8#�����	g��y�ħ}�u����ȗ��kW}�bX��>�?�����''�����^��7?���Þ�嘷za���c>��r�>��?�3�2�"@\0�����}�?�@�K?�@�@r���?\\���/Z��=誇�#@�?���|�v�ȇu(��;�ۃ.	��}B!��@#<B�#%�:&��\\��:(l�%At�*�B-|B%�B+��%�.$�1�0ĺ)�����''���_Z��:�C�\n�/��D��TB1��rX��z���)�B��+dB?,�B4�)�B�sB6��(|DIT�1�DJ��F�N�DPEQ��O$1���B�c4O$�v�E%|C%�CTDC:��.��.�:U����� ��L첺�BY��ed�f��R�Bi|Fj�Fk��S������m��f�"|�B^�CZjD^D�.�%��҇.���\Z�U��X�B��k�G�H�H�Fځ�c�1�A}�r�a{Fr�E]4�@H���+~��9����SH(� �0��(H�,��_ ��əC_Ȁ����ɗ�Ir�> ����ɡ��-�I_������.lʙ�I-����XLʘ�&|؁�$���\0�G%d@���\0> ��ɚ��X����s4E4�K��E������$��n���.���Sˠ�ɤ�J��I��I���T��ʶl��ʛ4ʠ�I%���ʚ�\0�J-�J���,�O�\0-@M�|MҔM�DM�T��D�ٔM�N�-�ͫ�M�����M�<ά|���J��H��\nN>���,8N�D��,Mr,ͽ�ǧDM���٤-����,��Nu��~���M�|������ͫ�N��ϢD�O0΢�O\0�M���P�|K��N�t���I�O�P�P\r�PU�8O}(��}H��P\rO�T��%�t��T��1O\0Q���2�}���"�t7�P#=R$M��q�M&�M&}��Ԃ&}RԴ�h�,�R-����(\r�0��-�M2��)�R*�R�3����R0�� �)��-�R=�M+�R=�S.��''US*U�7�AB|p�0-S���Re�/��(�u���0T&��5U�*�5m�8���S''�R/E�<ES>�TO]�S�TY��[��A��\\-UY-U_uU^mUK\rT^��c�a5V=\r�R5�y�EU=͂��_e�O�җLVK%Sfm�`��[\rW]��3�U�VY�=]�(�W&��^p�q��q؃=`R�UHeR|�Sy��}eW^�y-XKU�y�҂��[͂,��UW����}�|�W�Ň��{�XF�ҀՁ�Ձ~}WK�O�W�%X��WwՁ��XO��\\�W�\rX��S�WO�\0��\0�Z�=Z�MZ�E�^P�=0ڠ�\0�%Z���ڦ�ڡ�ڡ�ڮ��ڠ݃�eY�[�͂=�Z�[�\rۛ\rۣ�Z��,@[�}ڭ�۟�ۭ�Z��Z��qP/�| 2Z�Zƕ۶�ڭuۤ-[��[�%ڰ�׮EZ��ڬEZ�e\\�MZ�]�=]�M]�U��]]�5Z�����٭]�5Zq�������]���]q����[��]\0^����U��m��]���]���޷���^�������]��]�]^�%�ݕ^�����U_��N�B����%�����U^��_��^����-_�m^�Z����^�-�����`\n�`��~_��������`�`�`>��`.Ǻ�~a��\n.��Ѓ΁��a^(^=�a�́�_^Ѓ.^!�aq�a!�!��$n�(n��a�a=��b+&�`^�b��,c�=��F�	�b&f�!�)Vbq���b:,\0q�E\\q, b)N���.���cN�*�`<~c޽�2n�4��;�c\n��=�d,�\0K�Q&e<�S.�Q.dR�\0U~�Nh�W�dX�cR^�W��P&bR�O�eR�P��P��W.d[�e`���b��Pe[�e,�f`��\\��\\�f>~e"�aR��cFbb��g>���cV�c�aS>b,��l�j�ft�ep�eSv�[6�De0F�B��NXgR��N�d]��d�gt6���Uv���Qf�N��SF���|i��h�.i�&��lN�^i��k��|~痮i��i|~i��g���y�钶�pjR&j����Nj��k�6j�N꧎꩞�v�O�j�Ƃ�Fj���nj��ꮾj�>k�6k�~��j��j�&d�~먮k�ne��j�&�V�SV�W^긦k���&k�Vk����&l��lɞlʮl˾l��l��l��l��l��<ȃ�愡ޅ<��؅]���]���fm��<�m�������&�΃+jN�m�Nj�mҞ��fm޾�ٞm�.n�Ɓ+�n���p@m�Fn�6����Ծn�>\0��m���~m�n�vo������<��<�o��m؆�ޞn�N�+���v�+ ���n�>o�>�]\0�ޞ��p���+�m�����6��.�''��vo�.��&�\r��N�p�q��q���qǁ�p���q\0��"''�7o''r߅!��ڞ������%��!�p�prpp�%w��q�q��p��0�qն�!?pٶr�(ws:��*_�ض\0:��?��^�%7�6���7m�m0q=�q��+hqIWt:/�wq*��+���qgt"�s+OnO�n*?�M��Om6�uY��Y�u[�uYt �u^�u_�u6Wt`vb�u`_sb��X��]O�[����\0�h��i��h��h�p����n�vm�vj�vp7�n�vi�q<�i?wm�vn?wn��j''�pOwm?�t��o��k/���mwk�qp�wyg�{/�w�www��t�vr������7�m����v��u�r���Gxq�v�''y�����wv��}����������y��v���x���}��r��''z�vl�y��������y(�{(�}���/�����z���x���z��������l7��_{�''�qw���w�w{���}�](���y]������wm��\n�����o����v�G�������y�ho��''�\nH�}�{]X��''|��|po�{�wN0�����W���{�|�_�x�ډ�\0}�������\n��v?|�O{�߄�W�ç��}�ׅM��\n���M����������o��o�~�߄͗~h''<���G�o���~���ܧ��g�''����/���~��oV8������\rh8��A�!\nD���M7�ؐ�ƍ-V��Рŋ)-�DY�2�Q���5f����K�uUx����ox`<:�%ƨR�R�ju��Z�����*X�&A.�\n2���Wբm��۸^\r����x��뗯�����;xo_��L�:짘�߼�''�د>~�����I-�ȅ%{6i�ep�Z\r8/���\r�-�2dȨ�n��7��W��O��|ėSޭ[ho%��f��#��L[���;x�����]��y��[��O�?�%�o﷞���s�[�[~���}�y��:Z��s������e�y�Q����g�z�y���!�H��$�h�����"�X�V&��b7�i��>6��7��\\?�d��s�mχ&��a7>"7@����06����؍>?6�O		�H�$��y&��I#�4��&�q�9''�!���8^�O�V6��h�χ~>	�r����i����>�O��m�ȕ�A�a9���u�z*�qrÍ�������ƺ*��v3+���뭲��k��ȷ�|w�zk=ú��I���ء%�ʨ�JV;���B;���z\Z�8z�O����閫.����k��;/���k���p3r��?�\Z����d�O>��#�7�Z�mC�|p�#�0��PO>�jE���Ϡ�أ���ܻ2�-�k�Vؠ	6ܱ�Pp�w����4�s�h�\rVX�����l7?;�3�w(m�Cì	�:M7Z��M���L�I!ml��l����\rՆ�Q3�J\nM��?��P�X��&�������\r�v���X[���5tc�\r��g���h�t�:��3�6�̴�`��s�`}3�9�N�ʹ��\\��3�F����T3шO��w ���o<������I%�=(�:�����O��x�=��#�7&�#���C���С�>��n���c0h��7���cx~���4���M�z�]����-z�\ZӢ''<�1����>(<���%$�	u�\r=�Og�@�:��1(�M+��Z�z�$��O+� �\0C(<��}�s�����\0:��6�1C\n�IZyG\n��E%v�[a���0�Q�b�˨�0\n��fT#\Zט�4~�!� #�UF�aq�*���}�@\\\r1b�GFE�q��<�����F�d�K�Ø)~ԃ���I��7�Ҍ!,%�X�8�򔦤$*cIJYҲ����dC���]ffPd�B72��m��{4$r���K�q��Df&��EFs��:�>l��mr�����\Z��\rm����,#9͉�r���⌢:�9Jh�\n㼧���\r\\�\r�Q�TC4Mr�D�4�<�	����l�2��,�$�P�5�������7�yώ��!�H=Z�p���.})Lc*ә�4������RhE^�hH5!>0�C�rHBˉГ.U��)AQ�ڐ���$�p�R��Um��^�*Uk�Κ���f=+ZӪ�\Z���e�]�5��M�jc�&A�ZC�Dء�Z	�V��T��U+ ,R�\ZX$��P~M,[g�n�U���lL�J�ʶ����gC��ђV���hAKZԮ���\rmiC��L\r`_�%m��ى~�!�x�V~KWC��%�qk��w��\r�64����u�l5^a;}̣��E�tU�Zي7��5/g_�^�w��}ok�+��ʗ��[�g3a����O�p�s�ֻ�M�����z��\n���+��"��.@�\r�7���0�ݛ�\Z�"#���&��*�x�vH1��0��سv@�rQ�\Z����1�=+c��ȟձ2Qd�xI�E_�d;�����s�bmH�9�F���B�h���Ml\0�$��Jp�\\�9��qr-}\\ �iG���� KQ>�(�{�	�ʠUq��i#ӸƖ~t��L�"W�ďnqX�g;�B�P2�o�c�x�*~5����W�Z˧f��g��W�ZƦ6u�i�	`�:�*>r�p�b�8,�2���1X�z[؄�����5���\Z-6����Xg��a��?8�j\\���Ԧv�=�`��ݴ�5��iH����������:?8��7p\\�ڀu\r��\n{������÷�6W{�*���J��;�c��8ȭ�CP;�E�?ځ���6�9��ap���>���''\0t����U:�^�׀���Ѓ��@ �\n>�g6��s�WAG�L"����؃މ��#e''�VRqI��>7I*��j ,��~�ֵu�K��I7|�.x�=�7��ip��G��G��y^��o��E��/^���w|6B�s\\X^�?��i�ǳ�������\rL^�P>x�p`#����''�y�''����yퟏ��cB�ؐ\0&b�*`u�A���\\L�����Y_*L_��>&�A*dC:�@��}\Z�?��\0l���a����]��_6̟H����U_��" ��_�a_����e���@\0R�a�_��\rL��]����	`6�	�_�B�e_�}`6l��_\0�\0���u����b� \n�\0V`�I�\0�_\0J@z ��	z	����_�Y!6�����Jab��}�~���_N����!�y�U`\Z`��`��!!R���a��!�)"""!���!���!N�J���!�i�ڡ��!��_!��#:�(�"�%�!f��m"���!�''��$�''�_��b)���u���"������''`b��b''v�''��.>c!�!�@!R#!J�b#4n#7v�7~�#Z#8�#9�c4�c9�c5�̀;��;�\0;���ţ<J��4�c<�#5����c?�#@b<�c@��?Z�>�#6أC�C�cC�#A�cC��>*�C��Ev�E�#?��>�cEF�Hn�HdB*dCN$6��A�#@�cIR#=��L�cKn$E"�E�$B�$;f�I$P��P�R&�R6�S>%TeNeDN%RF%V>�Uf�V2�Wf%W:�\\B<F���;NA;F�-��-��;��D\0��;�%;NA[F�Z�%\\�\0Y��%�%�\\�P�#_��[��_��[��Yf[�Y%_F@�>ނf��H�&`��_��%L`2�=�&;��f��[~�\\�&`���%c��`��i�\\�%[&b��%l�[j�e�&Q��;J&bj�i��-��q�fq*�[�%e�f6�c��e�cZn�^��`��m"�z��z��{��{��u"gzz�t§f�''~�gq��~��%��{��n&|h��''[�~����g�F(|�''t�''����Z��&�f�f�~^�f�(r^�^���''��''��m���h��hhƨ�����gz�h�(��(��h�����芮h�6���(���{)��e��''�r(�fh�B(�f)�v��~�����(��)��h����z�������)��i�~���)��i��h��霖)�r)�ƨ��)��)��)�v����i�*����V*�f��n*�v��~*�����*��*�ڂ%\\�-؂\\���Z�@\0��j��*Ȁ��ꪦ��*�ʀ%؂�����%X������A��*\\C�J��+��*�����j�����5���ʪL믚+�"k�����*����A����R�B��h�B+��\0��(��-$��������5D+��´�A��j���Ҫ�^��>+�N,���f�\n���j���â(��j��꼶��>�F��+��*��*�R�J��Ҫ%�+-�&�k�&m���*m�*����\Z�+�N-�.-�:-�V��Fm�RlҦ+���՚-�m�&��R,������N�۪�֞��.�r-����m��m�&���+�J��Z+��-�Fm�\n�Zڒ�Ū-�2n�N��\n�m�\Z��v�ێ��.ꦮ�.ٖ��.�Ʈ�.��n��.�\Z�5�.��������*��5@\0�\Z/�"/����.o����J�6����R��>o��.�J��zo�n��J��b��6���o�no��.��/�Ư��/�֯��/����/�>\0�>�TB%XC�<@-ԂXC%�BX��[��&p�n�A�[C-Ākp-�n5,p�00�p-T/�3�	��<������z0��	C�5T�c0��@	�w�p-<�ǀðWB5��\03p�p��;�ˁ\0;0\0���1G�W[\Zq���+�\0''�ñk��0?�\n�0cp/p?@540 ?�װ�ˁ��{����q�.s�p5t�''r!WCWCD�Oq(p(Oq�*�r!7�''ϲ\0ptr�A''�p!�''��.��*wr��{2!�p�2�2)���r)ϲ/{�2''s\rr-x�6/�''�0)�)K3''�3r�s''�6s8�r%��''{��4''��12��)��.�r7��\0r����3�7��3*�s#s''�,Ot?S�E_�E�2Fo4Gw4Ek�G��H�4I��I�4J��JOtt2x�K�A�t5��KߴK[4��MӴ''�tL�4L��N5N�\0R��P�2M��L3uM#�D''�P''�O�4L��SK�,ku''�LG�Q�tM/uK�4R/uN�X�tWOuS��U��[�tO75WC5]_5Y�u^uVW�Y��`KuZ7uY[�bk�b7vc˵b׵UG5U�cS�c[5dkvf?��Yw6hg6e��X�uY�f��f��]�4fk�i5Q�6UG�kw�j�a�ud�6e��]߶_�6��L�- ur�6g;vr{�c3�c�vR?�U[�u�6v��q76%PwZKwcowf�6y/wg[7y5n7v�wh�\\�vc��vvW7q�wq''u8�@�x���x�S%���/��8�#��#8�C��/�S8�;��;\0�c8�CA�[8������������k���8�/�NK���8��\0��8%��w8��8�8����8��x�Ox@x88����9�+x�x����x�{������8����9��9�y��9��������9�׹�����y���8����9����y��9��9��9������9��9�S�����������y����3z�z�#8�[��o��s:�;:�#���:�z�3��C��_��{������:���������{�/z�Ǻ�c��gz�z�c��z����k���y�S���:�o���z�c;�s;��{��;�O����������;�����{��z�+������������9���G;��{��;{�K;����{�s���;�ϻ��;�w����Ǉ��ς��9ȗ|���ȓ<ʧ�ȿ�ɧ{�w<̷��������<����7\0г|Ѓ<�S�O��<��B�;�O��5�9ҏ|�7=�O�����S����ǃ|�G=�C=˗��7��<5L�S�Կ�w=�7}տ�$x=54��=�O���������|��=̿A�O��;}@�ܿ\0��}������|�����?�ڗ�Ћ��7@�?��w��#=ؿ=�{=�}�+���9���K>�O=�G=��}�=���駽��}����?�����=�;��3���ֻ��=�{~����_������{��k=�K���s��{���''����?���,���+��Ĭ�R3h���I\r.|A��I�&�H�B�#"|8)�C��D"��BdE��\\Hq䤊\r&�(��M�\r��xq���@y-zT(R��\rU���ӧL�N=T�U�[���u��U�]�~��T�W�W��u�n\\�Q�֕۔�ݻ{���K7�߻B��k�o���V�.`Ą�F]sf͛9w��thѣIf�`�7�\\�x�\Z�4Id�n*�5nY����Z�$ےt�v���[�M]�r�Z�$M�K{✁4ڬ�������Y��ݛ�i�O��ۭ��ͅ��\r_��g��\r޸c�67�[�-�zSM�''����ޒk�)7Z{P��^��[;/57�3�\n����sA��j{5�sP>�̃0�վ�N��쬓�G�x�''H�$!��!��1II~���I#��HY�L�׾P�/�q�4*����''��R��Zd @��T��%Ӥ2�}D��$�d��)��L U�rI��o�ޤ493��o6Bɴ�I�ܲE)A�q�ڀDR������,T:8���Zme��\\u5��]}�r�[����Qs�X[����e��U�7��vWdumA\Zl��V�6�������l����p�M��s�i�[w�e�Ko���L|��_o�m7Ix͕W]_6��V{�=xۂ��7�[~8W}�M��w��܅�U�pEY�p��V�q��\\xI~9ށ�X��]^yf�U������kV��Kh�Kn��wa�vܛ�ι۠oN:����f���8�yv�e�����Xx&Y�6�dq�v��c������e�[h{��{���{�׎���.:�膼�E�����n#�h�e��X�$�hHo���p"ӣiC�HdWv''��$��c����=o!\Z�#���9����Y���K�=�ԉw�����^}yq��wӁO\\�6�@x�=W}|�Ǉ~|�#	�z�EW���@���v����_�t��]�\\�;�ux����<׆�/��_�X�>�y{�Z��:�I��\n`QG>�%.���:�.z|�|�@�Q�s�{��Z7�\Z��@�_m(�"1���������M��������$NQ�Ib}���)��a"�X�%Zц��a���9+��x>\\�\Z�(G��O��+��XF�n��� 	YHC��T$"ٰ�A6ґ���$%Y�$���Ȥ&Y��Nf��h$(G�I��s�L%'')��M�a��,�*5��S�R���,o	�\\�ҕ�%$�	�Lb����.k��P����0W�KN^S��\\�59��P��ˤ�)uII`zS��L�3=	�q\Zӓ�|''���[޲�䴧<���{~r����4M�Iu�3��t&/�PN3����;*O�*���F5�ψt���2\Zю�s���BWZЖ/��Li�XԔ�8��Ni�R��T�7�),��Ң�\n`�����>�	N}*, �I�* �O�*�\n���B�\n �V�\ZծjMejZ�\nU�fU�l�*W���&,U�a�j[�T�6��p}*W!AT���\\�*��U�V�,�kW�zX���}u*Wo\Z٫Z5�t��''ߊX�:�RM*jߺ״fիf��W�zٲ�����mf��T��U�u\rk]�K����U�r��\\�:W��u,k�[]���%.u�k��bu����x������\nX��^��׽��{���w�U�}��^��7�L�o{��_⦷�N�y�[`���ʽ/\r\\�����=/����''���]����`�w�#0�ջ��X�*.������8�%��m�^���)�1{e���8���񎃼��x�/���y����6q�\r,b/CY�!61�sL�!3Y�4������+�w�W�q�M,`�9�9γ���&��πnr�i\\f>�xΈt�=�F7��R���,h>SzЇ޳��iF+�с5�''-j??\Zթ��+T�jW�Ջ^ë_�YӚį�u�w���Y��׸nu�S��`����n�r��&[�	�#���$`	�N@����<�׏��+^�m�x��N\0�����;��N��g��y�:����+���Y��Ֆ����S��^����GP�������T�������=�w�"���w��=b}������	�nq_���v��{����6���mq��#����Mto��/_�яn�w����6���]�|c[��vxՉN�o��஺�no�{���v8��Nm��}�^ox��������خ�#���m�=�_�����uf~�D�y��x�w��0�;���pɷ�f_x�	?��<�q��#@ot���\r��WO����}��yǟ���=�oz{C����N|�''\0��G~œ��?������/}�K�����}�{��G~��%x_�����~�7���G��\r�m����w�A����������o\0�O����O�����/��\0��m���O��o���я�>0��o���!��*���0���P0�~O70�p��sPw�y�U�o��t=0�p�l��p	{	�\n������\n��U@�]���ǐ���p��A�\\���\ZT@��PU��@\rT@\r\\A\r���P\r��\rQ��P��T@	��\\����&1����>Q	��\Z�@\r��\rQ�pѰ����Q�1K1�p1Q����a\r{s�\r��#���A����	�я�0Y]�-��\\A��b�ې���Q\r�0�s1�!R  \r!]1� 2 �s���#��� #s\ZrQ=�;�r#?�"��!3�R#\r�#!2#�&o!M�� �%a�#1�!�"1''A�"װ7&��PQR(q2$m�'' ''1!��!Q��2c�!�R*3r%�)a�!!��-�R%�-��-�R!��"=r&�.�r&S/�=R*�0�2-�"�0Ւ/�1s.�22�-s�� � 13!9<S	��=�.7s%7�3E�%!�2�R4��5a�4I3/-s4e�1?7%�57;�"_''1�!��-�S-y�(i�6�24uS7��(C�93�2��:Q9-S61�2�<E�5��:Ó<�=�S7�s=�3<?3<�s<ѓ>�<��>�S<�?�S>�3=��?�@��@�@mA�3"�?B#TB''�B+�B/C3TC#t@ѳ�3\r$�C�3\rB=M@S\0D�AE�3FtC�F�sF�AE�SE!Eo�E��F/�G��F�sH�3	,4D�<S@ImTF{tE�3\rT4\rZ�\0���E����\0��J��K�R�\0��E� 	�L@M_�\0�TK��DS�\Z�C[AE[!\r\ZAM��J[aMY�IKTO��M�AO�4NCTG��F�4Q��MKL�I%�M��O��C��N�4	,���NG�T�D��C5N��KS�J�H��LU�J��J�T5Nk�U�O[!K��X�TP�5	��X��W��E��R��L���J�MuT��NM�D��Sy�F=[��29UM��LGuT�Q_5K��[�V_5N��^�^=uM�U_�TR�4N��^��Nϴ_ɴX�Q�[��K�u_�tb1uM�U_�Z_�Q��b�^�uY�a_�a�d5v`W�Q��O;�U�d�c��K''�^�b��b��_]�K)6c��d1�T��]e���_A�i�j�Vj��j��_�c�Ve��j��k�l��k�6_��l��a��J�!_��m�uls5a�6aM�i��U�mM6n�vo��n��m��a�vp��m�vl�6g��j� q�Qwk�vn�6r�^�q�rU6ss�mwtK�tOuMrM�\0R�t[wta�ug�vOwuk�v��vqwve7vyxy�wI7x�Wv�7w��v��v��xK�y��w��z��zU�z�W{��u�7y��x�v|�\r��P�|їz��t׷t�Wz�u�u��z˗}��~����mY\r���\0!����|�XA��\0\r�����\r�\0��\0����	6	�0�\0@�\0�ʷ�ӗ��	�w}���,X�A����}8�!8|q\rX�Sxt�7x�Ϸ�K��ixtYa����x�G��%�|5؈S���؈ɗ��w�8���|�|��	f��Y���x�阁�x��8�S�鸇_X�-�����k��ؐ��O8�Gw�Sx�G��1����h؄1��MX}3x�C�49�M؎KY�M��M�����7�\0B8�C��18�52Y�o��/��9��K��iY�1��1Y��y�7��Gx�g���gx�1؎oY����x�i��M�1�������y�X����7��������\r8�G��َS؊�X�Ex��ٕ���G���9|;Y�SX��y�}��oY��x�+��I�\Z�-Z��9\Z����-Z�+���ٚC�����Az��y�Mz��ٙ#z�۸���Cx�����Y�?��Q:��:��\Z��Z�����ک�\Z��Z�����:�o�����\Z	����Z��\Z��z��Z���\0�\Z��Z�Ӛ��:��:��z��:��Z��\Z�����՚�˙����mZ�Ǻ��\r{�����٭�#��ڬ�z�gy���������z�۰+{�������9��-��5��:���g��k��Gض9۳뚯W۷y[�s��e[��۸m{��;�);�#��){�����۳�z�g�{[�����;��۸�[��[�ś����[�׻�O`r{�;�� �O@��[��;�����[�����۸�������N�\n���{|�{�N���`��޻*�N�\0�@��[�%��5\\��{�-���óz��]���;Û��\r|D��`���q<�1����_|�5��}��e�\0h\\ț�b��\\�]\\�1<�_|�q<��\Z˟�ƛ��c\\����\\�-��;��#|��|t�\r���\\Ɠ��5\\�a��#��]<���\\�y|�w|�g�\n �}�\0�|�''}���A�-]�|�<�/���\0ǉ�[|ҏ@���}�q����?������=�M��-���<�W�G��|�''|�e��%=�c����W�?��''}�U��ܽW!��}�[�խ�/=׭}�''��-},<�����ɧ��U]ձ��-�؇��<��]���֓=�˝��]˓��''=����K=�y��]ڃ��+��m]�+~���k�;��E��)~�7��y]�u=�g���]܁�����]�i]�7��u^ڷ}�_��]�^�<ک�''��K]��=�}~�+]�;��_��;�ہص��������y^�)^����^��~�����^�w~��\0�A�''��]���t��~�_��7��^��������^���#���~�y��~��������/?�����1�7���E��U��������#_�i]�i����[������S_�5�����=���q؍�������W��_��y_�%������������}��_����_�y_��?�G�����q�5?�����Hf\Z<�l`A�64x�`D�J��0�C�+Zl��I�����Ǔ*Y�|	�bI�����T��$B�9{����&СD�\Z=�4�R�<�:}\n����f�Z���	�[NEX�DUfFL ؕ�*&ښ���k��_�l�Ռ��l	*�k".���f�U�T�~�\\�P]�w��֠b�q�f��v�a�E3S�w.A�j��\n7�T#g]�l;������k䠙ڼyo�\Z|pj�&�7���l���,[F��#������w�f-+���ַ���\\�`�f�w�̬{Y��yݜ^���_U�I֝ymi��*��ƥ���e�e�Y&`''Y`lqgD��\rx�p��6`\\ݽf�"{������.#3g��a���"fl[���"�q��m���""نrg�utDې��(�d����o�!	Y!�E]�-Ý�ٽy]�Z�g��u"�&m��iހ���ߍj^g^pu~g�Y"J�u߁)�\r>(�r�h���''���Wg�{�\Zg�f��{$��Y��*��T���v~&8��������v�ި�6�����\n���!+e��^\0;', 0, 'default', 'gallery');
INSERT INTO `[prefix]_module_settings` (`id`, `name`, `value`, `cat_id`, `site_key`, `module_key`) VALUES (160, 'showSubtotal', 'yes', 0, 'default', 'gallery');
INSERT INTO `[prefix]_module_settings` (`id`, `name`, `value`, `cat_id`, `site_key`, `module_key`) VALUES (161, 'showDiscount', 'yes', 0, 'default', 'gallery');
INSERT INTO `[prefix]_module_settings` (`id`, `name`, `value`, `cat_id`, `site_key`, `module_key`) VALUES (162, 'showTax', 'yes', 0, 'default', 'gallery');
INSERT INTO `[prefix]_module_settings` (`id`, `name`, `value`, `cat_id`, `site_key`, `module_key`) VALUES (163, 'styleSubtotal', 'normal', 0, 'default', 'gallery');
INSERT INTO `[prefix]_module_settings` (`id`, `name`, `value`, `cat_id`, `site_key`, `module_key`) VALUES (164, 'styleDiscount', 'normal', 0, 'default', 'gallery');
INSERT INTO `[prefix]_module_settings` (`id`, `name`, `value`, `cat_id`, `site_key`, `module_key`) VALUES (165, 'styleTax', 'normal', 0, 'default', 'gallery');
INSERT INTO `[prefix]_module_settings` (`id`, `name`, `value`, `cat_id`, `site_key`, `module_key`) VALUES (166, 'styleGrandtotal', 'subtitle', 0, 'default', 'gallery');
INSERT INTO `[prefix]_module_settings` (`id`, `name`, `value`, `cat_id`, `site_key`, `module_key`) VALUES ('', 'checkoutFields', 'a:13:{s:10:"first_name";a:3:{s:5:"title";s:10:"First Name";s:7:"visible";s:1:"1";s:8:"required";s:1:"1";}s:9:"last_name";a:3:{s:5:"title";s:9:"Last Name";s:7:"visible";s:1:"1";s:8:"required";s:1:"1";}s:5:"email";a:3:{s:5:"title";s:6:"E-Mail";s:7:"visible";s:1:"1";s:8:"required";s:1:"1";}s:5:"phone";a:3:{s:5:"title";s:5:"Phone";s:7:"visible";s:1:"1";s:8:"required";s:1:"0";}s:14:"payment_method";a:2:{s:5:"title";s:14:"Payment Method";s:7:"payment";s:12:"allow_choose";}s:16:"require_shipping";a:1:{s:7:"require";s:1:"1";}s:9:"address_1";a:3:{s:5:"title";s:15:"Address, Line 1";s:7:"visible";s:1:"1";s:8:"required";s:1:"1";}s:9:"address_2";a:3:{s:5:"title";s:15:"Address, Line 2";s:7:"visible";s:1:"1";s:8:"required";s:1:"0";}s:4:"city";a:3:{s:5:"title";s:4:"City";s:7:"visible";s:1:"1";s:8:"required";s:1:"1";}s:6:"states";a:3:{s:5:"title";s:5:"State";s:7:"visible";s:1:"1";s:8:"required";s:1:"0";}s:9:"countries";a:3:{s:5:"title";s:7:"Country";s:7:"visible";s:1:"1";s:8:"required";s:1:"1";}s:3:"zip";a:3:{s:5:"title";s:3:"Zip";s:7:"visible";s:1:"1";s:8:"required";s:1:"1";}s:15:"shipping_method";a:3:{s:5:"title";s:15:"Shipping Method";s:8:"shipping";s:12:"allow_choose";s:13:"show_shipping";s:1:"1";}}', 0, 'default', 'gallery');
