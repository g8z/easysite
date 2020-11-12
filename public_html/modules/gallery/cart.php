<?php

/**
* Shopping Cart contains an array with the following structure
* $_SESSION['cart']['items'] = array( cart_id=>array( count, cart_item ), .... ),
* where cart_id = md5 (product_id+attributes)
*/

require_once 'config.php';

$action = $_REQUEST['action'];

function addToCart( $id, $category, $pid=0 ) {
	
	global $session, $db, $t;
	
	$item = $db->getRow( 'select id from '. IMAGES_TABLE." where id='$id'" );
	
	if ( $item ) {
		
		$item['cat_id'] = intval( $_REQUEST['category'] );
		$item['attributes'] = getAttributes( $item['id'] );
		
		if ( $attributes = $_SESSION['addToCart'][$item['id']] ) { 
			
			// oveeride some attributes if user selected non-standard
			
			foreach ( $attributes as $aid=>$attr ) {
				$item['attributes'][$aid]['value'] = $attr;
			}
		}
		else if ( $pid ) {
			$attributes = $db->getAll( 'select *, value1 as value from '.ATTRPRICEVALUES_TABLE." where price_id='$pid'" );
			foreach ( $attributes as $idx=>$attr ) {
				$item['attributes'][$attr['attr_id']]['value'] = $attr['value'];
			}
		}
		$item['attributes'] = serialize( $item['attributes'] );
		
		$cart_id = md5( $item['id'] . $item['attributes'] );
		
		$_SESSION['cart']['items'][$cart_id]['item'] = $item;
		$_SESSION['cart']['items'][$cart_id]['count']++;
	}
	
}

function updateCart() {
	
	global $db, $t;
	
	$itemsToDelete = $_POST['deleteItem'];
	
	foreach ( $_SESSION['cart']['items'] as $cart_id=>$cart_item ) {
		
		if ( @in_array( $cart_id, $itemsToDelete ) )
			unset( $_SESSION['cart']['items'][$cart_id] );
		else {
			$_SESSION['cart']['items'][$cart_id]['count'] = $_POST['count_'.$cart_id];
		}
	}
}

switch ( $action ) {
	
	case 'add':
		addToCart( intval( $_GET['id'] ), intval( $_GET['category'] ), intval( $_GET['pid'] ) );
		break;
		
	case 'Update':
		updateCart();
		break;

		
}


$cartContents = getCartContents();
$totalPrice = $_SESSION['cart']['totalPrice'];

$t->assign( 'subtotal', $totalPrice );

$discounts = array( 
	0 => array( $gallerySettings['discountThr1'], $gallerySettings['discountPrc1'] ),
	1 => array( $gallerySettings['discountThr2'], $gallerySettings['discountPrc2'] ),
	3 => array( $gallerySettings['discountThr3'], $gallerySettings['discountPrc3'] ),
);

list( $discountPrice, $discountPercent ) = calculateDiscount( $totalPrice, $discounts );
$totalPrice -= $discountPrice;

// add tax
$tax = $gallerySettings['tax']/100*$totalPrice;
$totalPrice += $tax;

$_SESSION['cart']['totalPrice'] = $totalPrice;
$_SESSION['cart']['tax'] = $tax;
$_SESSION['cart']['discount'] = $discountPrice;

//$session->set( $_SESSION );

$t->assign( 'cartContents', $cartContents );
$t->assign( 'totalPrice', $totalPrice );
$t->assign( 'discountPrice', $discountPrice );
$t->assign( 'discountPercent', $discountPercent );
$t->assign( 'tax', $tax );

$createThumb = gdInstalled() ? getSetting( 'createThumb', 'yes' ) : 'no';
$t->assign( 'createThumb', $createThumb );

$session->updateLocation( 'shopping_cart', $system->settings['mainTitle'] ? $system->settings['mainTitle'] : 'Your Shopping Cart' );

include_once( FULL_PATH . 'init_bottom.php' );


$t->assign( 'bodyTemplate', 'modules/gallery/cart.tpl' );
$t->display( $templateName );

?>