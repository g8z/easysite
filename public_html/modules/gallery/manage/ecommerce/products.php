<?php

$manage = true;
include_once( '../../config.php' );

$attributes = array();

if ( $_POST['formIsSubmitted'] ) {
	
	if ( $_POST['deleteId'] ) {
		
		// deleting attribute
		$db->query( 'delete from '. ATTRIBUTES_TABLE ." where id='$_POST[deleteId]'" );
		$db->query( 'delete from '. ATTRVALUES_TABLE ." where attr_id='$_POST[deleteId]'" );
		//$db->query( 'delete from '. ATTRPRICING_TABLE ." where attr_id='$_POST[deleteId]'" );
		$db->query( 'delete from '. DISPLAYOPTIONS_TABLE ." where field_id='$_POST[deleteId]'" );
	}
	
	// update existing attributes
	
	$attributes = $db->getAll( 'select * from '. ATTRIBUTES_TABLE. " where site_key='$site'" );
	foreach ( $attributes as $idx=>$attr ) {
		
		$name = $_POST['attr_name_'.$attr[id]];
		$measurement = $_POST['attr_measurement_'.$attr[id]];
		$type = $_POST['attr_type_'.$attr[id]];
		$default = getAttrValue( $type, 'attr_default_'.$attr[id] );
		$visible = $_POST['attr_visible_'.$attr[id]];
		
		$db->query( 'update '. ATTRIBUTES_TABLE." set
			name='$name',
			measurement = '$measurement',
			type = '$type',
			_default = '$default',
			visible = '$visible'
			where id='$attr[id]'" );
	}
	
	// check if we need to add new attribute
	if ( $_POST['attr_name'] != '' ) {
		
		$default = getAttrValue( $_POST[attr_type], 'attr_default' );
		
		$db->query( 'insert into '. ATTRIBUTES_TABLE. " (name, measurement, type, _default, site_key) values ('$_POST[attr_name]', '$_POST[attr_measurement]', '$_POST[attr_type]', '$default', '$site')" ); 
		$attid = $db->getOne( 'select max(id) from '.ATTRIBUTES_TABLE );
		
		// now set default value for all products
		
		$items = $db->getAll( 'select id from '.IMAGES_TABLE." where site_key='$site'" );
		foreach( $items as $idx=>$item ) {
			$db->query( 'insert into '. ATTRVALUES_TABLE." (product_id, attr_id, use_default, site_key) values ('$item[id]', '$attid', '1', '$site')" );
		}
	}
}

$attributes = $db->getAll( 'select * from '. ATTRIBUTES_TABLE. " where site_key='$site'" );
foreach ( $attributes as $idx=>$attr ) {
	if ( preg_match( '/^list_(.*)/', $attr['type'], $m ) )
		$attributes[$idx]['listName'] = $m[1];
}

$t->assign( 'attributes', $attributes );


$lists = $db->getAll( 'select list_key, title from '. LISTS_TABLE." where site_key='$site' order by title" );

$typeValues = array( 'number', 'single-text', 'multi-text', 'date' );
$typeTitles = array( 'Number', 'Single-line Text', 'Multi-line Text', 'Date' );

foreach ( $lists as $idx=>$list ) {
	$typeValues[] = 'list_' . $list[list_key];
	$typeTitles[] = 'List - ' . $list[title];
}

$t->assign( 'typeValues', $typeValues );
$t->assign( 'typeTitles', $typeTitles );

$session->updateLocation( 'product_attributes', 'Product Attributes' );
include_once( FULL_PATH . 'init_bottom.php' );

$t->assign( 'bodyTemplate', 'modules/gallery/manage/ecommerce/products.tpl' );
$t->display( $templateName );

?>