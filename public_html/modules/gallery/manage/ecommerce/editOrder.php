<?php

require_once '../../config.php';

$id = intval( $_REQUEST['id'] );

if ( $formIsSubmitted ) {
	
	// update order
	$fields = array(
		'first_name' => $first_name,
		'last_name' => $last_name,
		'email' => $email,
		'phone' => $phone,
		'country' => $countries,
		'state' => $states,
		'city' => $city,
		'address_1' => $address_1,
		'address_2' => $address_2,
		'zip' => $zip,
		'shipping_method' => $shipping_method,
		'payment_method' => $payment_method,
		'status' => $status,
	);
	
	$f = array();
	foreach( $fields as $name=>$value ) {
		$f[] = $name."='$value'";
	}
	
	$updSQL = implode( ', ', $f );
	
	$db->query( 'update '. ORDERS_TABLE." set $updSQL where id='$id'" );
	
	// redirect to the $backAction
	header( 'Location: '.$session->prevLocation['url'] );
	
}

$order = $db->getRow( 'select * from '. ORDERS_TABLE." where id='$id'" );

if ( !$order ) {
	$t->assign( 'backAction',  urldecode( $_GET['backAction'] ) );
	$system->generalError( 'Sorry, there is no such order in the database.' );
}

$t->assign( 'vars', $order );

$shipOptions = array();
$ships = $db->getAll( 'select id, name, price from '. SHIPPING_TABLE." where site_key='$site' order by name" );
foreach( $ships as $idx=>$ship ) {
	$shipOptions[$ship[id]] = $ship[name].' ('.$ship[price].')';
}
$t->assign( 'shipOptions', $shipOptions );
$t->assign( 'paymentOptions', array( 'paypal'=>'PayPal', 'twocheckout'=>'2CheckOut' ) );
$t->assign( 'statuses', array( 'Pending', 'Confirmed', 'Cancelled', 'Refunded' ));

assignOrderFields();

$t->assign( 'edit', 1 );

$t->assign( 'bodyTemplate', 'modules/gallery/manage/ecommerce/editOrder.tpl' );

include_once( FULL_PATH . 'init_bottom.php' );

$t->display( $templateName );

?>