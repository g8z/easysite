<?php
if ( !defined( 'SMARTY_DIR' ) )
	die( 'You can not access this file directly.' );

header("Content-Type: image/gif");
header("Content-Disposition: inline");

$table = $_GET['table'];
$field = $_GET['field'];
$id = intval( $_GET['id'] );

echo $db->getOne( "select $field from $table where id = '$id'" );
?>