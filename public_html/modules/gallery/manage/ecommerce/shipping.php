<?php

$manage = true;
include_once( '../../config.php' );

if ( $_POST['formIsSubmitted'] ) {
	
	if ( $_POST['deleteId'] ) {
		
		// deleting option
		$db->query( 'delete from '. SHIPPING_TABLE ." where id='$_POST[deleteId]'" );
	}
	
	// update existing options
	
	$options = $db->getAll( 'select * from '. SHIPPING_TABLE. " where site_key='$site'" );
	foreach ( $options as $idx=>$ship ) {
		
		$name = $_POST['ship_name_'.$ship[id]];
		$price = $_POST['ship_price_'.$ship[id]];
		$period = $_POST['ship_period_'.$ship[id]];
		$p_item = $_POST['ship_p_item_'.$ship[id]];
		$visible = $_POST['ship_visible_'.$ship[id]];
		
		$db->query( 'update '. SHIPPING_TABLE." set
			name='$name',
			price = '$price',
			period = '$period',
			p_item = '$p_item',
			visible = '$visible'
			where id='$ship[id]'" );
	}
	
	// check if we need to add new option
	if ( $_POST['ship_name'] != '' ) {
		
		$db->query( 'insert into '. SHIPPING_TABLE. " (name, price, period, p_item, site_key) values ('$_POST[ship_name]', '$_POST[ship_price]', '$_POST[ship_period]', '$_POST[ship_p_item]', '$site')" ); 
	}
}

$options = $db->getAll( 'select * from '. SHIPPING_TABLE. " where site_key='$site'" );
$t->assign( 'options', $options );

$t->assign( 'shipValues', array( 'day', 'week' ) );
$t->assign( 'shipTitles', array( 'Day(s)', 'Week(s)' ) );

$session->updateLocation( 'shipping_options', 'Shipping Options' );
include_once( FULL_PATH . 'init_bottom.php' );

$t->assign( 'bodyTemplate', 'modules/gallery/manage/ecommerce/shipping.tpl' );
$t->display( $templateName );

?>