<?php

if ( !defined( 'SMARTY_DIR' ) )
	include_once( 'init.php' );

$t->assign( 'bodyTemplate', 'pages/login.tpl' );

include_once( 'init_bottom.php' );
$t->display( $templateName );

$db->disconnect();

?>