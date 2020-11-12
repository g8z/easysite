<?php

include( 'config.php' );

$realtySavedProperties = explode( ',', $_COOKIE['realtySavedProperties'] );

if ( $set ) {

	if ( !in_array( $set, $realtySavedProperties ) )
		$realtySavedProperties [] = $set;

	$type = 'Saving';
}
else if ( $unset ) {

	$arr = array();

	if ( $unset != 'all' ) {

		foreach( $realtySavedProperties as $index => $value ) {
			if ( $value != $unset && trim( $value ) != '' )
				$arr [] = $value;
		}
	}

	$realtySavedProperties = $arr;

	$type = 'Removal';
}

$t->assign( 'title', "Property $type" );
$t->assign( 'type', $type );

$success = setcookie( 'realtySavedProperties', implode( ',', $realtySavedProperties ), time() + 9999999 );

$t->display( 'popupHeader.tpl' );

if ( $success ) {
	$t->assign( 'success', true );
}
else {
	$t->assign( 'success', false );
}

include_once( FULL_PATH . 'init_bottom.php' );

$t->display( 'modules/realty/saveProperty.tpl' );
$t->display( 'popupFooter.tpl' );

?>