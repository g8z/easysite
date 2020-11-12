<?php

if ( !defined( 'SMARTY_DIR' ) )
    include_once( 'init.php' );

// remove session & cookie (possibly move these to UserAuth class later)

$session->end( $_REQUEST['PHPSESSID'] );

// unsetting of es_auth cookie is not working...
// setcookie( "es_auth", "", time() - 3600 );

$t->assign( 'bodyTemplate', 'pages/logout.tpl' );

include_once( 'init_bottom.php' );
$t->display( $templateName );

$db->disconnect();

?>