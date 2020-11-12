<?php

require_once dirname(__FILE__) .'/../../init.php';

require_once INCLUDE_DIR . 'internal/class.navigation.php';

include_once FULL_PATH . MODULES_DIR .'/gallery/include/functions.php';
include_once FULL_PATH . MODULES_DIR .'/include/functions.php';

$moduleKey = 'gallery';

define( 'IMAGES_TABLE', DB_PREFIX . '_gallery_items' );
define( 'IMAGECATS_TABLE', DB_PREFIX . '_gallery_item_cat' );
define( 'MANUFACTURERS_TABLE', DB_PREFIX . '_gallery_manufacturers' );
define( 'ATTRIBUTES_TABLE', DB_PREFIX . '_gallery_product_attributes' );
define( 'SHIPPING_TABLE', DB_PREFIX . '_gallery_shipping_options' );
define( 'ATTRVALUES_TABLE', DB_PREFIX . '_gallery_product_values' );
define( 'ATTRPRICING_TABLE', DB_PREFIX . '_gallery_att_pricing' );
define( 'ATTRPRICEVALUES_TABLE', DB_PREFIX . '_gallery_att_price_values' );
define( 'ORDERS_TABLE', DB_PREFIX . '_gallery_orders' );
define( 'ORDERCONTENTS_TABLE', DB_PREFIX . '_gallery_order_contents' );
define( 'DISPLAYOPTIONS_TABLE', DB_PREFIX . '_gallery_display_options' );

// get the category list (used in all forms, search + manage forms)

$add_fields = array( 'site_key', 'module_key' );
$add_values = array( $site, $moduleKey );

$category = new Category( $db, MODULECATEGORIES_TABLE, $add_fields, $add_values );

$categories = $category->getCategoryArray();

$t->assign( 'categories', $categories );

// array of propery names where images are stored in settings table
// skip them while loading settings
$skipImages = array( 'noImageThumb', 'noImageFull' );

$gallerySettings = getAllSettings( $skipImages );
$setImg = getSetImages( $skipImages );

$t->assign( 'metaKeywords', $gallerySettings['meta_keywords'] );
$t->assign( 'metaDescription', $gallerySettings['meta_desc'] );
$t->assign( 'gallery', $gallerySettings );
$t->assign( 'setImages', $setImg );

if ( $gallerySettings['useEcommerce'] == 'yes' ) {
	
	//get shopping cart info
	
	$cartItems = 0;
	if ( is_array( $_SESSION['cart']['items'] ) && count( $_SESSION['cart']['items'] ) ) {
		
		foreach( $_SESSION['cart']['items'] as $cart_id=>$cart_item ) {
			$cartItems += $cart_item['count'];
		}
	}
	
	if ( !$gallerySettings['LitemsNumber'] ) 
		$gallerySettings['LitemsNumber'] = '{$count} items are in your cart';
		
	$cartLabel = '<a href="cart.php">'.str_replace( '{$count}', $cartItems, $gallerySettings['LitemsNumber'] ).'</a>';
	$t->assign( 'cartLabel', $cartLabel );
}

?>