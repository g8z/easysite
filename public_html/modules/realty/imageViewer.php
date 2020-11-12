<?php

$manage = false;

require 'config.php';

$t->assign( 'title', 'Image Viewer' );

// note: $id is the listing id

// now get the actual record data to display

// make sure that we only retrieve images for this listing
// note: use _order > 1 because we want to view the secondary images only

$id = intval( $id );

$filter = array( "site_key like '$site'", "item_id like '$id'", "module_key like 'realty'", '_order > 0' );

$where = ' where ' . implode( ' and ', $filter ) . ' ';

// get the total count for navigation
$count = $db->getOne( 'select count(id) from ' . MODULEOBJECTS_TABLE . $where . ' order by _order' );

$n = new Navigation();

// total number of items
$n->_total = $count;

// number of items to display per page
$n->_itemsPerPage = 1;

// the number of page links to show in the navigation bar (in case > 10)
$n->_pagesPerScreen = 10;

// the search vars that we should pass from screen to screen
$n->_requestVars = $_REQUEST;

$n->_separator = ' | ';

// the current 'start' index
$n->_start = $_REQUEST['start'];

// the current set that we are in
$n->_set = $_REQUEST['set'];

if ( !$n->_start )
	$n->_start = '0';

$limit = ' limit ' . $n->_start . ',' . $n->_itemsPerPage;

// get the actual data, using $n->_start and $n->_itemsPerPage
$imageData  = $db->getRow( 'select * from ' . MODULEOBJECTS_TABLE . $where . " order by _order limit $n->_start, 1" );
'<br />';


$t->assign( 'data', $imageData );

$t->assign( 'navigation', $n->output() );

$t->assign( 'isNavigable', 1 );

//$vars = $t->get_template_vars( 'imageData' );

include_once( FULL_PATH . 'init_bottom.php' );

// insert the body content
$t->display( 'popupHeader.tpl' );
$t->display( 'modules/realty/imageViewer.tpl' );
$t->display( 'popupFooter.tpl' );

?>