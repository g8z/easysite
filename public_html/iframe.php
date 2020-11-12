<?php
if ( !defined( 'SMARTY_DIR' ) )
	include_once( 'init.php' );

$embeddedURL = $_GET['url'];

$t->assign( 'href', $embeddedURL );

// embedded urls have no body, since the iframe is substituted for the body
// and the url content is inputted directly into the iframe

$t->assign( 'bodyTemplate', 'pages/iframe.tpl' );

include_once( 'init_bottom.php' );
$t->display( $templateName );

$db->disconnect();
?>