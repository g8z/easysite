<?php

if ( !defined( 'SMARTY_DIR' ) )
	include_once( 'init.php' );
	

function getLevel( $itemId ) {
	
	global $db;
	
	$level = 0;
	while ( $parent = $db->getOne( 'select parent from '.MENUITEMS_TABLE." where id='$itemId'" ) ) {
		$level++;
		$itemId = $parent;
	}
	
	return $level;
	
}
	
$menuItems = $db->getAll( 'select * from '. MENUITEMS_TABLE );	

foreach( $menuItems as $num=>$menuItem ) {
	
	$itemId = $menuItem[id];
	$level = getLevel( $itemId );
	
	$db->query( 'update '.MENUITEMS_TABLE." set level='$level' where id='$itemId'" );
	//echo 'update '.MENUITEMS_TABLE." set level='$level' where id='$itemId'<br>\r\n";
}

?>