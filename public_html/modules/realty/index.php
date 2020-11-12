<?php

$manage = false;

require 'config.php';

$catid = intval( $catid );

if ( $search || $saved ) {

	// now get the actual record data to display

	$filter = array();

	// check each of the possible search criteria

	// only show the listings that have not exceeded the post days expiration

	$filter [] = "( to_days( now() ) - to_days( listing_date ) <= post_days )";

	// only get listings for this site

	$filter [] = "site_key = '$site'";

	if ( $saved ) {
		// get the list of saved properties, and add the IDs to the filter

		$savedIds = explode( ',', $_COOKIE['realtySavedProperties'] );

		$idFilter = array();

		foreach( $savedIds as $savedId ) {
			$idFilter [] = "id = '". intval( $savedId ) . "'";
		}

		$filter [] = implode( ' or ', $idFilter );

		$t->assign( 'savedProperties', 1 );
	}
	else {

		if ( $catid > 0 ) {

			// get the ancestral categories of this category as an array

			$children = $category->getDescendents( $catid );

			$children [] = $catid;
			$catFilter = array();

			// add ALL descendents to our category filter

			foreach( $children as $child ) {
				$catFilter [] = "cat_id = '$child'";
			}

			$filter [] = '( ' . implode( ' or ', $catFilter ) . ' )';
		}

		if ( $num_stories )
			$filter [] = "num_stories = '$num_stories'";

		if ( $price_min )
			$filter [] = "list_price >= '$price_min'";

		if ( $price_max )
			$filter [] = "list_price <= '$price_max'";

		if ( $bedroom_min )
			$filter [] = "bedrooms >= '$bedroom_min'";

		if ( $bedroom_max )
			$filter [] = "bedrooms <= '$bedroom_max'";

		if ( $days_old )
			$filter [] = "( to_days( now() ) - to_days( listing_date ) <= '$days_old' )";

		if ( $state )
			$filter [] = "state like '$state'";

		if ( $city )
			$filter [] = "city like '$city'";

		if ( $district )
			$filter [] = "district like '$district'";

		if ( $county )
			$filter [] = "county like '$county'";

		if ( $country )
			$filter [] = "country like '$country'";

		if ( $fireplace )
			$filter [] = "fireplace = '1'";

		if ( $garage )
			$filter [] = "garage = '1'";

		if ( $near_school )
			$filter [] = "near_school = '1'";

		if ( $near_transit )
			$filter [] = "near_transit = '1'";

		if ( $ocean_view )
			$filter [] = "ocean_view = '1'";

		if ( $mountain_view )
			$filter [] = "mountain_view = '1'";

		if ( $ocean_front )
			$filter [] = "ocean_front = '1'";

		if ( $river_front )
			$filter [] = "river_front = '1'";

		if ( $lake_front )
			$filter [] = "lake_front = '1'";

		if ( $lake_view )
			$filter [] = "lake_view = '1'";

		if ( $balcony )
			$filter [] = "balcony = '1'";

		if ( $laundry )
			$filter [] = "laundry = '1'";

		if ( $fitness_center )
			$filter [] = "fitness_center = '1'";

		if ( $pool )
			$filter [] = "pool = '1'";

		if ( $jacuzzi )
			$filter [] = "jacuzzi = '1'";

		if ( $guest_house )
			$filter [] = "guest_house = '1'";
	}

	$where = '';

	if ( sizeof( $filter ) > 0 ) {
		$where = ' where ' . implode( ' and ', $filter ) . ' ';
	}


	// get the total count for navigation
	$count = $db->getOne( 'select count(id) from ' . REALTYITEMS_TABLE . $where );
	
	if ( $order && preg_match( '/^[a-zA-Z_0-9]+$/', $order ) )
		$order = 'order by ' . $order;

	$limit = '';// add this later

	$n = new Navigation();

	// total number of items
	$n->_total = $count;

	// number of items to display per page
	$n->_itemsPerPage = getSetting( 'searchResultCount', 3 );

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

	$limit = ' limit ' . $n->_start . ',' . $n->_itemsPerPage;

	// get the actual data, using $n->_start and $n->_itemsPerPage
	$listingData  = $db->getAll( 'select * from ' . REALTYITEMS_TABLE . $where . $order . $limit );

	//print_r( $filter );

	// determine the number of 'extra' images that are present for each listing

	foreach( $listingData as $index => $row ) {

		$numAddedImages = $db->getOne( 'select count(id) from ' . MODULEOBJECTS_TABLE . " where site_key = '$site' and item_id = '$row[id]' and module_key = 'realty'" );

		$row['numAddedImages'] = $numAddedImages;

		// get the first image and add this to the data

		if ( $numAddedImages > 0 ) {
			$imgDataId = $db->getOne( 'select id from ' . MODULEOBJECTS_TABLE . " where site_key = '$site' and item_id = '$row[id]' and module_key = 'realty' order by _order asc limit 1" );

			// the first object id (image)
			$row['objid'] = $imgDataId;
		}
		$listingData[$index] = $row;
	}

	foreach( $listingData as $index => $row ) {

		$options1 = array();// extra amenities
		$options2 = array();// proximities
		$options3 = array();// views

		if ( $row['fireplace'] && $realtySettings['fireplace'] )
			$options1 [] = 'Fireplace';

		if ( $row['garage'] && $realtySettings['garage'] )
			$options1 [] = 'Garage';

		if ( $row['near_school'] && $realtySettings['near_school'] )
			$options2 [] = 'School(s)';

		if ( $row['near_transit'] && $realtySettings['near_transit'] )
			$options2 [] = 'Transit';

		if ( $row['ocean_view'] && $realtySettings['ocean_view'] )
			$options3 [] = 'Ocean View';

		if ( $row['lake_view'] && $realtySettings['lake_view'] )
			$options3 [] = 'Lake View';

		if ( $row['mountain_view'] && $realtySettings['mountain_view'] )
			$options3 [] = 'Mountain View';

		if ( $row['ocean_view'] && $realtySettings['ocean_front'] )
			$options3 [] = 'Ocean Front';

		if ( $row['lake_front'] && $realtySettings['lake_front'] )
			$options3 [] = 'Lake Front';

		if ( $row['river_front'] && $realtySettings['river_front'] )
			$options3 [] = 'River Front';

		if ( $row['balcony'] && $realtySettings['balcony'] )
			$options1 [] = 'Balcony';

		if ( $row['fitness_center'] && $realtySettings['fitness_center'] )
			$options1 [] = 'Fitness Center';

		if ( $row['pool'] && $realtySettings['pool'] )
			$options1 [] = 'Pool';

		if ( $row['guest_house'] && $realtySettings['guest_house'] )
			$options1 [] = 'Guest House';

		if ( $row['jacuzzi'] && $realtySettings['jacuzzi'] )
			$options1 [] = 'Jacuzzi/Spa';

		$row['options1'] = implode( ', ', $options1 );
		$row['options2'] = implode( ', ', $options2 );
		$row['options3'] = implode( ', ', $options3 );

		$listingData[$index] = $row;
	}

	$t->assign( 'listingData', $listingData );
	$t->assign( 'navigation', $n->output() );
	$t->assign( 'isNavigable', 1 );
}
else {
	$category->_indent = '&nbsp; > ';
	$categoryList = $category->getCategoryArray();
	$t->assign( 'categories', $categoryList );
}

$sortOptions = array(
	'list_price asc' 	=> 'Price (lowest to highest)',
	'list_price desc' 	=> 'Price (highest to lowest)',
	'bedrooms' 			=> '# Bedrooms',
	'bathrooms' 		=> '# Bathrooms',
	'listing_date desc'	=> 'Listing Date (most recent first)'
);

$t->assign( 'sortOptions', $sortOptions );

$priceFormat = getSetting( 'priceFormat', '0,.' );

$t->assign( 'format_dec', $priceFormat[0] );
$t->assign( 'format_point', $priceFormat[2] );
$t->assign( 'format_th', $priceFormat[1] );

// insert the body content
$t->assign( 'bodyTemplate', 'modules/realty/index.tpl' );

include_once( FULL_PATH . 'init_bottom.php' );

$t->display( $templateName );

?>