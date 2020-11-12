<?php

require_once '../../config.php';

$id = intval( $_REQUEST['id'] );

$order = $db->getRow( 'select o.*, UNIX_TIMESTAMP(o.creation_date) as creation_date, s.name as shipping_name, s.price as shipping_price from '. ORDERS_TABLE ." o left join ". SHIPPING_TABLE." s on o.shipping_method=s.id where o.id='$id'" );

if ( !$order )
	$system->generalError( 'Sorry. Such order does not exists in our database' );

$contents = $db->getAll( 'select * from '. ORDERCONTENTS_TABLE." where order_id='$id'" );
foreach ( $contents as $idx=>$item ) {
	$contents[$idx]['category'] = $db->getOne( 'select cat_id from '.IMAGECATS_TABLE." where img_id='$item[item_id]' and site_key='$site'" );
}

$t->assign( 'order', $order );
$t->assign( 'contents', $contents );

$session->updateLocation( 'order_contents', 'Order Contents', array( 'id' ) );

$t->assign( 'bodyTemplate', 'modules/gallery/manage/ecommerce/orderContents.tpl' );
	

include_once( FULL_PATH . 'init_bottom.php' );

$t->display( $templateName );

?>