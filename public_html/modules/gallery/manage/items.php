<?php

require_once '../config.php';
include_once '../include/adminFunctions.php';

$startid = 0;
$startLevel = 0;
$levelLimit = 0;

$add_fields = array( 'site_key' );
$add_values = array( $site );


function getSubCategories( &$rez, $parent=0 ) {
	
	global $db, $site;
	
	$cats = $db->getAll( 'select id from '. MODULECATEGORIES_TABLE." where parent='$parent' and site_key='$site'" );
	
	if ( is_array( $cats ) && count( $cats ) ) {
		
		foreach( $cats as $idx=>$cat) {
			$rez[] = $cat['id'];
			getSubCategories( $rez, $cat['id'] );
		}
	}
}


function searchImages() {
    global $db, $site, $t, $categories, $session, $gallerySettings;

    extract( $_REQUEST );

    $startTime = mktime( 0, 0, 0, $Start_Month, $Start_Day, $Start_Year );
    $endTime = mktime( 23, 59, 59, $End_Month, $End_Day, $End_Year );

    $cond = array();
    
    if ( $endTime > 0 ) {
    	$cond[] = "UNIX_TIMESTAMP(i.created)>=$startTime";
    	$cond[] = "UNIX_TIMESTAMP(i.created)<=$endTime";
    }
    
    //if ( $name ) $cond[] = "i.name LIKE '%$title%'";
    if ( $title ) $cond[] = "i.title LIKE '%$title%'";
    if ( $description ) $cond[] = "i.description LIKE '%$description%'";
    
    if ( $searchId ) {
    	$cond[] = "i.id >= '$searchId'";
    }
    else {
    	
    	// display in categories
	    $cats = array( $cat_id );
	    
	    if ( $subCategories ) {
	    	getSubCategories( $cats, $cat_id );
	    }
	    
	    $sqlCats = implode( ', ', $cats );
    	$cond[] = "ic.cat_id in ($sqlCats)";
    }
    
    $sqlCond = implode( ' and ', $cond );
    
    if ( !$order ) $order = 'title';
    if ( !$direction ) $direction = 'ASC';
    
    $query = 
    	'SELECT '.
    		'i.*, c.title as c_title '.
		'FROM ' . IMAGECATS_TABLE . " ic ".
    	'LEFT JOIN ' . IMAGES_TABLE . ' i on i.id=ic.img_id '.
		'LEFT JOIN ' . MODULECATEGORIES_TABLE . " c on c.id=ic.cat_id ".
		"WHERE $sqlCond and ic.site_key='$site'";
		
    $images = array();
    
    $n = new Navigation();
    
    $n->_start = intval( $_REQUEST['start'] );
    
    // number of items to display per page
    $n->_itemsPerPage = intval( $perPage ? $perPage : 50 );

    // the number of links to show in the navigation bar (in case > 10)
    $n->_pagesPerScreen = 10;

    // the search vars that we should pass from screen to screen
    $n->_requestVars = $_REQUEST;

    $n->_separator = ' | ';

    // the current set that we are in
    $n->_set = $_REQUEST['set'];

    if ( !$n->_start )
      $n->_start = '0';

    $t->assign( 'minLevel', 0 );
    
    $limit = ' limit ' . $n->_start . ',' . $n->_itemsPerPage;
    if ( $order=='_order' )
    	$sqlOrder = 'ic._order';
    else
    	$sqlOrder = "i.$order";
    $images = $db->getAll( $query." group by i.id order by $sqlOrder $direction $limit" );
    
    // --------------------------------
    // now search by product attributes
    // if we are in ecommerce mode
    // --------------------------------
    
    if ( $gallerySettings['useEcommerce'] == 'yes' ) {
    	
    	$rezImages = array();
    	
    	// ----------------------------------
    	// find which attributes to search by
    	// ----------------------------------
    	
    	$attrIds = array();
    	$ecommerceVars = array();
    	
    	foreach ( $_REQUEST as $key=>$value ) {
    		if ( preg_match( '/^custom_([0-9]+)(.*)$/', $key, $matches) && strlen($value) ) {
    			$ecommerceVars[] = $key;
    			$attrIds[] = $matches[1];
    		}
    	}
    	
    	// -------------------------------------------------------
    	// contunue search if any of product attributes was filled
    	// -------------------------------------------------------
    	
    	if ( count( $attrIds ) ) {
    	
	    	$sqlIds = implode( ', ', $attrIds );
	    	
	    	$fields = $db->getAll( 'select id, name, type from '. ATTRIBUTES_TABLE." where id in ($sqlIds) and visible='1' and site_key='$site'" );
	    	
	    	// search
	    	
	    	foreach ( $images as $idx=>$image ) {
	    		
	    		$goodImage = true;
	    		
		    	foreach ( $fields as $idx=>$item ) {
		    		
		    		$cond = array( 'product_id='. $image['id'], 'attr_id='.$item['id'] );
	    	
		    		if ( preg_match( "/^list_(.*)$/", $item['type'], $matches ) ) {
		    			$fields[$idx]['type'] = 'list';
		    		}
		    		
		    		if ( $item['type'] == 'list' ) {
				    	$sqlCond = implode( ' and ', $cond );
				    	$v = $db->getRow( 'select id, value from '.ATTRVALUES_TABLE." where $sqlCond and site_key='$site'" );
	    				if ( @array_intersect( unserialize($v['value']), $_REQUEST['custom_'.$item['id']] ) ) {
	    					$row = $v['id'];
	    				}
		    		}
		    		else {
		    		
			    		switch( $item['type'] ) {
			    			case 'number':
			    				if ( strlen( $_REQUEST['custom_'.$item['id'].'_start'] ) )
			    					$cond[] = "value >= '".$_REQUEST['custom_'.$item['id'].'_start']."'";
			    					
			    				if ( strlen( $_REQUEST['custom_'.$item['id'].'_end'] ) )
				    				$cond[] = "value <= '".$_REQUEST['custom_'.$item['id'].'_end']."'";
			    				break;
			    				
			    			case 'date':
			    				$d = $_REQUEST['custom_'.$item['id'].'_Year'].'-';
			    				$d .= $_REQUEST['custom_'.$item['id'].'_Month'].'-';
			    				$d .= $_REQUEST['custom_'.$item['id'].'_Day'];
			    				$cond[] = "value = '$d'";
			    				break;
			    				
			    			case 'single-text':
			    			case 'multi-text':
			    			default:
			    				$cond[] = "value LIKE '%".$_REQUEST['custom_'.$item['id']]."%'";
			    				break;
			    		}
			    		
				    	$sqlCond = implode( ' and ', $cond );
				    	$row = $db->getOne( 'select id from '.ATTRVALUES_TABLE." where $sqlCond and site_key='$site'" );
		    		}
			    	
			    	if ( !$row )
			    		$goodImage = false;
			    	
		    	}
		    	
		    	if ( $goodImage )
		    		$rezImages[] = $image;
	    	}
	    	
	    	$images = $rezImages;
    	}
    }
    
    
    // query every image for category
    
    foreach ( $images as $idx=>$image ) {
    	$cats = $db->getAll( 'select c.id, c.title from '. MODULECATEGORIES_TABLE.' c left join '. IMAGECATS_TABLE." ic on c.id=ic.cat_id where ic.img_id='$image[id]' order by c.title" );
    	$images[$idx]['categories'] = $cats;
    }
    
    $count = count( $images );
    
    // total number of items
    $n->_total = $count;

    if ( $count ) {
        $t->assign( 'images', $images );
        $out = $t->fetch( 'modules/gallery/manage/imagesList.tpl' );
    }


    // Store post data for return to the previous page

    //$prevPost = getArray( $_POST );
    //$prevPost = array_append( $prevPost, getArray( $_GET ) );

    //$t->assign( 'prevPost', $prevPost );
    $t->assign( 'navigation', $n->output() );
    $t->assign( 'images_list', $out );
    
    // search depends on these vars
    $vars = array(
    	'galleryAction',
    	'Start_Month',
    	'Start_Day',
    	'Start_Year',
    	'End_Month',
    	'End_Day',
    	'End_Year',
    	'name',
    	'title',
    	'description',
    	'searchId',
    	'cat_id',
    	'subCategories',
    	'order',
    	'direction',
    );
    
    if ( $gallerySettings['useEcommerce'] == 'yes' ) {
    	$vars = array_merge( $vars, $ecommerceVars );
    }    
    
    $session->updateLocation( 'gallery_search_results', 'Search Items Results', $vars );

    $t->assign( 'bodyTemplate', 'modules/gallery/manage/searchResults.tpl' );

}


function searchImagesForm() {
    global $db, $site, $t, $categories, $session, $gallerySettings;

    // get the minimum image year

    $minDate = $db->getOne( 'select min( created ) from ' . IMAGES_TABLE . " where site_key = '$site'" );
    
    $t->assign( 'minDate', $minDate );

    $t->assign( 'perPageList', array( 5, 10, 20, 30, 40, 50, 75, 100, 200, 300, 500, 1000 ) );
    
    $c = $categories;
    $t->assign( 'searchCategories', $c );
    
    $orderValues = array( 'title', 'created', 'cat_id' );
    $orderTitles = array( 'Title', 'Date', 'Category' );
    
    if ( $gallerySettings['useEcommerce'] == 'yes' ) {
    	$orderValues = array_merge( $orderValues, array( 'price', 'quantity' ) ); 
    	$orderTitles = array_merge( $orderTitles, array( 'Price', 'Quantity' ) ); 
    }
    
    $t->assign( 'orderValues', $orderValues );
    $t->assign( 'orderTitles', $orderTitles );
    
    $direction = array( 'ASC', 'DESC' );
    $t->assign( 'direction', $direction );
    
    // -----------------------------
    // get custom product attributes 
    // is e-commerce mode is enabled
    // -----------------------------
    
    if ( $gallerySettings['useEcommerce'] == 'yes' ) {
    	$fields = $db->getAll( 'select id, name, type from '. ATTRIBUTES_TABLE." where visible='1' and site_key='$site'" );
    	
    	foreach ( $fields as $idx=>$item ) {
    		
    		if ( preg_match( "/^list_(.*)$/", $item['type'], $matches ) ) {
    			$fields[$idx]['type'] = 'list';
    			$fields[$idx]['listName'] = $matches[1];
    		}
    		
    	}
    	$t->assign( 'ecommerceFields', $fields );
    }
    
    
    $session->updateLocation( 'search_items', 'Search Items Form' );
    $t->assign( 'bodyTemplate', 'modules/gallery/manage/search.tpl' );

}


$galleryAction = $_REQUEST['galleryAction'];

if ( $id = intval( end( explode( '_', $galleryAction ) ) ) ) {
    $galleryAction = substr( $galleryAction, 0, strlen( $galleryAction )-strlen( $id )-1 );
}


switch( $galleryAction ) {
    case 'search_images':
        searchImages();
        break;

    case "up_image":
        changeImageOrder( $id, -1 );
        searchImages();
        break;

    case "down_image":
        changeImageOrder( $id, 1 );
        searchImages();
        break;

    case "edit_image":
        editImage( $id, $_SERVER['HTTP_REFERER'] );
        break;

    case "delete_image":
        if( hasAdminAccess( 'cm_module' ) && hasAdminAccess( 'cm_gallery' ) && hasAdminAccess( 'cm_gallery_delete_images' ) ) {
            deleteImage( $id );
            //searchImages();
            header( 'Location: ' . $session->prevLocation['url'] );
            exit;
        } else {
            noAccessMessage( 'modules/gallery/navigation.tpl' );
        }
        break;

    case "save_image":
        saveImage( $id );
        searchImages();
        break;

    case "delete_selected":
        deleteSelected();
        //searchImages();
        break;

    default:
        searchImagesForm();
        break;

}


include_once( FULL_PATH . 'init_bottom.php' );

$t->display( $templateName );


?>