<?php

require_once '../config.php';

$settingName = 'checkoutFields';

if ( $isFormSubmitted ) {
	$checkout = serialize( $_POST['fields'] );
	$exists = $db->getOne( 'select id from '.MODULESETTINGS_TABLE." where name='$settingName' and site_key='$site' and module_key='$moduleKey'" );
	if ( $exists ) {
		$db->query( 'update '. MODULESETTINGS_TABLE." set value='$checkout'  where name='$settingName' and site_key='$site' and module_key='$moduleKey'" );
	}
	else {
		$db->query( 'insert into '. MODULESETTINGS_TABLE." (name,value,site_key,module_key) values ('$settingName','$checkout','$site','$moduleKey')" );
	}
}

$fields = array(
	'first_name' => array( 'title'=>'First Name' ),
	'last_name' => array( 'title'=>'Last Name' ),
	'email' => array( 'title'=>'E-Mail' ),
	'phone' => array( 'title'=>'Phone' ),
	'payment_method' => array( 'title'=>'Payment Method' ),
	
	'require_shipping' => array( 'title'=>'Require Shipping Input' ),
	
	'address_1' => array( 'title'=>'Address, Line 1' ),
	'address_2' => array( 'title'=>'Address, Line 2' ),
	'city' => array( 'title'=>'City' ),
	'states' => array( 'title'=>'State' ),
	'countries' => array( 'title'=>'Country' ),
	'zip' => array( 'title'=>'Zip' ),
	'shipping_method' => array( 'title'=>'Shipping Method' ),
);

$fs = $db->getOne( 'select value from '.MODULESETTINGS_TABLE." where name='$settingName' and site_key='$site' and module_key='$moduleKey'" );
$fs = unserialize( $fs );
if ( $fs )
foreach( $fs as $key=>$f ) {
	$fields[$key] = array_merge( $fields[$key], $f );
}

$t->assign( 'fields', $fields );

$boolean = array( '1'=>'Yes', '0'=>'No' );
$t->assign( 'boolean', $boolean );

$paymentOptions = array( 'allow_choose'=>'Allow Choose', 'paypal'=>'Use PayPal', 'twocheckout'=>'Use 2CheckOut' );
$t->assign( 'paymentOptions', $paymentOptions );

$shipValues = array( 'allow_choose' );
$shipTitles = array( 'Allow Choose' );
$opts = $db->getAll( 'select id, name from '. SHIPPING_TABLE." where site_key='$site' order by name" );
if ( $opts )
foreach ( $opts as $idx=>$opt ) {
	$shipValues[] = $opt['id'];
	$shipTitles[] = 'Use '. $opt['name'];
}
$t->assign( 'shipValues', $shipValues );
$t->assign( 'shipTitles', $shipTitles );

$session->updateLocation( 'checkout_settings', 'Checkout Settings' );
include_once( FULL_PATH . 'init_bottom.php' );

$t->assign( 'bodyTemplate', 'modules/gallery/manage/editCheckout.tpl' );
$t->display( $templateName );

?>