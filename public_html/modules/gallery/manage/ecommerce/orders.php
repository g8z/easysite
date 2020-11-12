<?php

require_once '../../config.php';

if ( $action == 'delete_order' ) {
	$id = intval( $_REQUEST['id'] );
	$db->query( 'delete from '. ORDERS_TABLE." where id='$id'" );
	$db->query( 'delete from '. ORDERCONTENTS_TABLE." where order_id='$id'" );
}

// -------------
// get sort info
// -------------

if ( !$sort )
	$sort = 'person';

$sqlDirection = $direction == 0 ? 'ASC' : 'DESC';

$sortSymbols = array( '&#9650;', '&#9660;' );

$headers = array(
	'person' => array( 'title'=>'Person', 'sort_fields'=> array( 'first_name', 'last_name' ) ),
	'amount' => array( 'title'=>'Amount', 'sort_fields'=> 'total_amount' ),
	'created' => array( 'title'=>'Created', 'sort_fields'=> 'creation_date' ),
	'status' => array( 'title'=>'Status', 'sort_fields'=> 'status' )
);

$currentLocation = $session->getLocation( 'start', 'set', 'perPage' );

foreach( $headers as $key=>$info ) {
	
	if ( $key == $sort ) {
		
		if ( is_array( $info['sort_fields'] ) && count( $info['sort_fields'] ) ) {
			$sqlOrder = 'concat(' . implode( ',', $info['sort_fields'] ) . ')';
		}
		else {
			$sqlOrder = $info['sort_fields'];
		}
		$headers[$key]['sort_symbol'] = $sortSymbols[$direction];
		
	}
	
	$vars = 'sort='.$key;
	$delim = ereg( '\?', $currentLocation ) ? '&' : '?';
	$headers[$key]['url'] = $currentLocation . $delim . $vars;
	
}

$t->assign( 'headers', $headers );

$orderCount = $db->getOne( 'select count(*) from '. ORDERS_TABLE." where site_key='$site'" );
$perPage = intval( $_REQUEST['perPage'] ) ? intval( $_REQUEST['perPage'] ) : 50;

include_once( INCLUDE_DIR . 'internal/class.navigation.php' );

$n = new Navigation();

// total number of items
$n->_total = $orderCount;

// number of items to display per page
$n->_itemsPerPage = $perPage;

// the number of links to show in the navigation bar (in case > 10)
$n->_pagesPerScreen = 10;

// the search vars that we should pass from screen to screen
$n->_requestVars = $_REQUEST;

$n->_separator = ' | ';

// the current 'start' index
$n->_start = intval( $_REQUEST['start'] );

// the current set that we are in
$n->_set = $_REQUEST['set'];

if ( !$n->_start )
	$n->_start = '0';

$t->assign( 'navigation', $n->output() );
            
$orders = $db->getAll( 'select *, UNIX_TIMESTAMP(creation_date) as created from '. ORDERS_TABLE ." where site_key='$site' order by $sqlOrder $sqlDirection limit {$n->_start}, {$n->_itemsPerPage}" );

$t->assign( 'perPageArray', array( '10', '20', '30', '40', '50', '60', '70', '80', '90', '100' ) );
$t->assign( 'perPage', $perPage );
$t->assign( 'orders', $orders );
$t->assign( 'statuses', array( 'Pending', 'Confirmed', 'Cancelled', 'Refunded' ));

$session->updateLocation( 'order_list', 'Order List', array( 'perPage', 'start', 'set', 'sort', 'direction' ) );

$t->assign( 'bodyTemplate', 'modules/gallery/manage/ecommerce/orders.tpl' );
	
include_once( FULL_PATH . 'init_bottom.php' );

$t->display( $templateName );

?>