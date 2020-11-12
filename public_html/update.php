<?php

if ( !defined( 'SMARTY_DIR' ) )
	include_once( 'init.php' );

$db->query( "alter table ".SECTIONS_TABLE." add `img_alt` varchar(255) NOT NULL default ''");


?>