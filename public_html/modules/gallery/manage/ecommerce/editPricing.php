<?php

include_once( '../../config.php' );

$item_id = intval( $_REQUEST['item_id'] );

$attributes = $db->getAll( 'select * from '. ATTRIBUTES_TABLE. " where type!='multi-text' and site_key='$site'" );
foreach ( $attributes as $idx=>$attr ) {
	if ( preg_match( '/^list_(.*)/', $attr['type'], $m ) )
		$attributes[$idx]['listName'] = $m[1];
}

$t->assign( 'attributes', $attributes );

$prices = $db->getAll( 'select * from '.ATTRPRICING_TABLE." where product_id='$item_id' and site_key='$site'" );

$updatePrices = array_append( $prices, array( 0=>array( 'id'=>0 ) ) );

foreach ( $updatePrices as $pidx=>$price ) {

	$price_id = $price['id'];
	
	if ( $_POST[$price_id . '_attributes'] ) {
		
		$fixed_price = $_POST[$price_id.'_fixed_price'];
		$price_type  = $_POST[$price_id.'_price_type'];
		$delta_type  = $_POST[$price_id.'_delta_type'];
		$delta_price = $_POST[$price_id.'_delta_price'];
		$delta_item  = $_POST[$price_id.'_delta_item'];
		$quantity    = $_POST[$price_id.'_quantity'];
		
		if ( !$price_id ) {
			$db->query( 'insert into '. ATTRPRICING_TABLE." (product_id, fixed_price, type, delta_type, delta_price, delta_item, quantity, site_key) values ('$item_id','$fixed_price','$price_type','$delta_type','$delta_price','$delta_item', '$quantity', '$site')" );
			$pid = $db->getOne( 'select max(id) from '.ATTRPRICING_TABLE." where site_key='$site'" );
		}
		else {
			$db->query( 'update '. ATTRPRICING_TABLE." set fixed_price='$fixed_price', type='$price_type', delta_type='$delta_type', delta_price='$delta_price', delta_item='$delta_item', quantity='$quantity' where id='$price_id'" );
			$db->query( 'delete from '. ATTRPRICEVALUES_TABLE." where price_id='$price_id' and site_key='$site'" );
			$pid = $price_id;
		}
		
		// insert attribute values
		
		foreach ( $attributes as $idx=>$item ) {
			
			if ( !in_array( $item['id'], $_POST[$price_id . '_attributes'] ) )
				continue;
			
			if ( strpos( 'list', $item['type'] ) == 0 )
				$type='';
			else
				$type = $item['type'];
				
			if ( isset( $_POST[$price_id.'_attr_'.$item['id'].'_start'] ) ) {
				$value1 = getAttrValue( $type, $price_id.'_attr_'.$item['id'] );
				//$value2 = getAttrValue( $type, $price_id.'_attr_'.$item['id'].'_end' );
			}
			else {
				$value1 = $value2 = getAttrValue( $type, $price_id.'_attr_'.$item['id'] );
			}
			
			$db->query( 'insert into '.ATTRPRICEVALUES_TABLE." (price_id,attr_id,value1,value2,site_key) values ($pid,'$item[id]','$value1','$value2','$site')" );
		}
	}
}

if ( $_POST['deleteId'] ) {
	$id = intval( $_POST['deleteId'] );
	$db->query( 'delete from '.ATTRPRICEVALUES_TABLE." where price_id='$id'" );
	$db->query( 'delete from '.ATTRPRICING_TABLE ." where id='$id'" );
}

$prices = $db->getAll( 'select * from '.ATTRPRICING_TABLE." where product_id='$item_id' and site_key='$site'" );
foreach ( $prices as $idx=>$price ) {
	
	$pa = $attributes;
	
	foreach ( $attributes as $aidx=>$attr ) {
		$row = $db->getRow( 'select id, value1, value2 from '. ATTRPRICEVALUES_TABLE ." where price_id='$price[id]' and attr_id='$attr[id]'" );
		$pa[$aidx]['checked'] = $row['id'];
		$pa[$aidx]['value1'] = $row['value1'];
		$pa[$aidx]['value2'] = $row['value2'];
	}
	$prices[$idx]['attributes'] = $pa;
}

$t->assign( 'prices', $prices );


$t->assign( 'deltaValues', array( 'increase', 'decrease' ) );
$t->assign( 'deltaTitles', array( 'Increase', 'Decrease' ) );

$t->assign( 'deltaItemOptions', array( $gallerySettings['currency'], '%' ) );

$t->assign( 'item_id', $item_id );

$session->updateLocation( 'edit_pricing', 'Edit Pricing', array('item_id'));
include_once( FULL_PATH . 'init_bottom.php' );

$t->assign( 'bodyTemplate', 'modules/gallery/manage/ecommerce/editPricing.tpl' );
$t->display( $templateName );

?>