<?php

$manage = true;

require '../config.php';


$startid = 0;
$startLevel = 0;
$levelLimit = 0;

$add_fields = array( 'site_key' );
$add_values = array( $site );

$session->updateLocation( 'manage_realty_index', 'Realty Index', array() );

// insert the body content
$t->assign( 'bodyTemplate', 'modules/realty/manage/index.tpl' );


include_once( FULL_PATH . 'init_bottom.php' );

$t->display( $templateName );



?>