<?php

define( 'HELPER', true );

require_once dirname(__FILE__) . '/../config.php';
    
require_once FULL_PATH . "libs/JSHttpRequest/JSHttpRequest.php";
$JSHttpRequest = new JSHttpRequest("windows-1251");

$cart_id = $_REQUEST['cart_id'];
$attr_id = $_REQUEST['attr_id'];
$value = $_REQUEST['value'];

$cart_item = $_SESSION['cart']['items'][$cart_id];

$attributes = @unserialize( $cart_item['item']['attributes'] );
$attributes[$attr_id]['value'] = $value;
$attributes = @serialize( $attributes );
$cart_item['item']['attributes'] = $attributes;

$new_cart_id = md5( $cart_item['item']['id'] . $attributes );

unset( $_SESSION['cart']['items'][$cart_id] );
$_SESSION['cart']['items'][$new_cart_id]['count'] += $cart_item['count'];
$_SESSION['cart']['items'][$new_cart_id]['item'] = $cart_item['item'];


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


$_RESULT = array(
  "output"   => $t->fetch( 'modules/gallery/cartContents.tpl' ),
);

?>