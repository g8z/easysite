<?php

$manage = true;
include_once( '../../config.php' );


$t->assign( 'bodyTemplate', 'modules/gallery/manage/ecommerce/index.tpl' );

$session->updateLocation( 'ecomm_settings_index', 'E-Commerce Settings' );
include_once( FULL_PATH . 'init_bottom.php' );

$t->display( $templateName );

?>