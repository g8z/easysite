<?php

define( 'HELPER', true );

require_once dirname(__FILE__) . '/../config.php';
    
require_once FULL_PATH . "libs/JSHttpRequest/JSHttpRequest.php";
$JSHttpRequest = new JSHttpRequest("windows-1251");

// clear attributes session data for all items
// except for the one we are editing

$attr = $_SESSION['addToCart'][$_REQUEST['id']];
$_SESSION['addToCart'] = '';
$attr[$_REQUEST['attr_id']] = $_REQUEST['value'];
$_SESSION['addToCart'][$_REQUEST['id']] = $attr;

// calculate item price depending on attributes
$categorySettings = getCategorySettings( $_REQUEST['category'] );

$item = $db->getRow( 
	'SELECT '.
		'i.id as id, i.title, i.description, ic.cat_id, i.created, ic._order, i.site_key, if(i.use_cat_price,\''.$categorySettings['defaultPrice'].'\',i.price) as price, i.quantity, i.man_id, if( length(i.img_thumb) = 0, 1, 0) as is_empty, 0 as first, 0 as last, 0 as album '.
	'FROM '.IMAGECATS_TABLE." ic ".
	'LEFT JOIN '.IMAGES_TABLE." i on i.id=ic.img_id ".
	"WHERE i.id='$_REQUEST[id]'" 
);
$item['attributes'] = getAttributes( $_REQUEST['id'] );
$item['attributes'][$_REQUEST['attr_id']] = array( 'type'=>'list_blah', 'value'=>$_REQUEST['value'] );
$price = calculatePrice( $item );

$_RESULT = array(
  "price"   => galleryPrice( $price ),
);
?>