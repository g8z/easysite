<?php

require_once 'config.php';

require_once 'include/functions.php';
require_once INCLUDE_DIR . 'internal/class.image.php';
require_once 'include/adminFunctions.php';

$_SESSION['addToCart'] = '';

// Display full image in new window ?
$newWindow = getSetting( 'newWindow', 'no' );

$gallerySettings = getAllSettings();

$t->assign( 'gallery', $gallerySettings );

/**
 * Displays thumbnails table
 */
function displayTable( $cat_id ) {
	global $db, $t, $site, $newWindow, $moduleKey, $gallerySettings, $category, $system;

	$cols = getSetting( 'gridWidth', 5 );
	$createThumb = gdInstalled() ? getSetting( 'createThumb', 'yes' ) : 'no';
	if ( $createThumb == 'no' ) $newWindow = 'no';
	$albumCols = $cols;

	$currentCategory = $cat_id;
	if ( $cat_id )
		$categorySettings = getCategorySettings( $cat_id );
	
	$albums = $db->getAll( 'select *, 0 as first, 0 as last from '.MODULECATEGORIES_TABLE." where parent='$cat_id' and site_key='$site' and module_key = '$moduleKey' order by _order" );

	$albumCount = count( $albums );

	if ( $albumCount ) {
		$albums[0][first] = 1;
		$albums[$albumCount-1][last] = 1;
	}

	if ( $albumCols == 0 ) {
		$albumCols = 3;
		$cols = 3;
	}
	
	$imageFields = getFields( 'pr_thumb' );
	$albumFields = getFields( 'cat_thumb' );
	
	$albumRows = ceil( $albumCount / $albumCols );

	$imCount = $db->getOne( 'select count(id) from '.IMAGECATS_TABLE." where cat_id='$cat_id' and site_key='$site'" );

	$rows = $imCount + ceil( $albumCount / $cols );
	$r = getSetting( 'gridHeight', 5 );


	if ( $rows > $r ) 
		$rows = $r;

	$nav = new Navigation();
	$nav->_total = $imCount + $albumCount;
	$nav->_itemsPerPage = $rows*$cols;
	if ( !$nav->_itemsPerPage )
		!$nav->_itemsPerPage = 1;
	$nav->_pagesPerScreen = 10;
	$nav->_requestVars = $_REQUEST;
	$nav->_separator = ' | ';
	$nav->_start = intval( $_REQUEST['start'] );
	$nav->_set = $_REQUEST['set'];
	if ( !$nav->_start )
	  $nav->_start = '0';

	$limit = ' limit ' . $nav->_start . ',' . $nav->_itemsPerPage;

	$start = $nav->_start;
	$end = $nav->_start + $nav->_itemsPerPage;
	if ( $albumCount < $start ) {
		$start -= $albumCount;
		$end -= $albumCoun;
		$albumCount = 0;
	} else {
		$start = 0;
		$end = ( $albumCount < $end ) ? $end-$albumCount : 0;
	}
	$count = $end - $start;

	$allImages = $db->getAll( 
		'SELECT '.
			'i.id as id, i.title, i.description, ic.cat_id, i.created, ic._order, i.site_key, if(i.use_cat_price,\''.$categorySettings['defaultPrice'].'\',i.price) as price, i.quantity, i.man_id, if( length(i.img_thumb) = 0, 1, 0) as is_empty, 0 as first, 0 as last, 0 as album '.
		'FROM '.IMAGECATS_TABLE." ic ".
		'LEFT JOIN '.IMAGES_TABLE." i on i.id=ic.img_id ".
		"WHERE ic.cat_id='$cat_id' and ic.site_key='$site' order by ic._order limit $start, $count" 
	);
		
	$n = count( $allImages );
	
	if ( $start == 0 ) 
		$allImages[0][first] = 1;
		
	if ( $start + $count >= $imCount ) 
		$allImages[$n-1][last] = 1;

	$t->assign( 'navigation', $nav->output() );

	for ($i=0; $i<$rows; $i++) {
		for ($j=0; $j<$cols; $j++) {

			// First add available subalbums in the current album
			if ( ( $index = $i*$cols+$j ) < $albumCount ) {
				
				$set = getCategorySettings( $albums[$index][id] );
				$albums[$index][settings] = $set;
				
				if ( $set['useOverImage'] == 'yes' ) {
					// get overloaded image id
					$image_id = $db->getOne( 'select id from '. MODULESETTINGS_TABLE." where site_key='$site' and module_key='$moduleKey' and cat_id='{$albums[$index][id]}' and name='catImage'" );
				}
				else {
					// get default image id
					$image_id = $db->getOne( 'select id from '. MODULESETTINGS_TABLE." where site_key='$site' and module_key='$moduleKey' and name='defImage'" );
				}
				$images[$i][$j] = $albums[$index];
				
				if ( $image_id ) {
					require_once $t->_get_plugin_filepath('function', 'imgsrc');
					$params = array( 'table'=>MODULESETTINGS_TABLE, 'field'=>'value', 'id'=>$image_id );
				
					$images[$i][$j]['path'] = smarty_function_imgsrc($params, $t);
				}
				else
					// for compatibility
					$images[$i][$j]['path'] = DOC_ROOT . 'modules/'.$moduleKey.'/images/album.gif';
				
				$images[$i][$j]['href'] = 'index.php?category='.$albums[$index]['id'];
				$images[$i][$j]['type'] = 'album';
				
				$fields = array();
				foreach ( $albumFields as $num=>$item ) {
					
					if ( !$item[visible] )
						continue;

					switch( $item[field_id] ) {
						case 'category_title':
					   		$value = $images[$i][$j]['title'];
					   		$item['layout'] = '<a href="index.php?category='.$albums[$index]['id'].'">'.$item['layout'].'</a>';
							break;
						case 'items_count':
							$categories = array( $albums[$index]['id'] );
							$categories = array_append( $categories, array_keys( $category->getCategoryArray( $albums[$index]['id'] ) ) );
							$categories = '\''. implode( '\', \'', $categories ) . '\'';
							$value = $db->getOne( 'select count(id) from '.IMAGECATS_TABLE." where cat_id in ($categories) and site_key='$site'" );
							break;
					}
					$item[title] = str_replace( array( '{$name}', '{$value}' ), array( $item['name'], $value ), $item['layout'] );

					$fields[$item[section]][$item[row]][] = $item;
					
					$fields[$item[section]][$item[row]][canDown] = 0;
					//$fields[$item[section]][$item[row]][canUp] = 0;
					
					if ( $item[row] == 1 && count( $fields[$item[section]][$item[row]] ) == 2 ) {
						$fields[$item[section]][$item[row]][canUp] = 0;
					}
					else {
						$fields[$item[section]][$item[row]][canUp] = 1;
						
						//if ( $item[row] != 1 )
							//$fields[$item[section]][$item[row]-1][canDown] = 1;
					}
					
					if ( count( $fields[$item[section]][$item[row]] ) > 3 ) {
						$fields[$item[section]][$item[row]][canDown] = 1;
						//$fields[$item[section]][$item[row]][canUp] = 1;
					}
			   }
			   	$images[$i][$j]['fields'] = $fields;
				
			} else if ( ( $imIndex = $index-$albumCount) < $n ) {
				
				$images[$i][$j] = $allImages[$imIndex];
				$images[$i][$j]['path'] = '../images/thumbnail/thumb_'.$allImages[$imIndex]['id'].'.jpg';
				if ( $allImages[$imIndex]['is_empty'] )
					$images[$i][$j]['type'] = 'empty';
				else
					$images[$i][$j]['type'] = 'image';
				$href = 'displayImage.php?category='.$allImages[$imIndex]['cat_id'].'&id='.$allImages[$imIndex]['id'];
				$images[$i][$j]['href'] = $href;
				
				$images[$i][$j]['attributes'] = getAttributes( $images[$i][$j]['id'] );
				$images[$i][$j]['price'] = calculatePrice( $images[$i][$j] );

				$fields = array();
				foreach ( $imageFields as $num=>$item ) {
					
					if ( !$item[visible] )
						continue;
						
					$item = fetchField( $item, $images[$i][$j] );
					$fields[$item[section]][$item[row]][] = $item;
					
					$fields[$item[section]][$item[row]][canDown] = 0;
					
					if ( $item[row] == 1 && count( $fields[$item[section]][$item[row]] ) == 2 ) {
						$fields[$item[section]][$item[row]][canUp] = 0;
					}
					else {
						$fields[$item[section]][$item[row]][canUp] = 1;
					}
					
					if ( count( $fields[$item[section]][$item[row]] ) > 3 ) {
						$fields[$item[section]][$item[row]][canDown] = 1;
					}
				}
				$images[$i][$j]['fields'] = $fields;
				
			}
		}
	}
	
	if ( $currentCategory ) {
		$path = getPath( $currentCategory );
		$categoryTitle = $db->getOne( 'select title from '.MODULECATEGORIES_TABLE." where site_key='$site' and id='$currentCategory' and module_key = 'gallery'" );
		$title .= $categoryTitle . ' - ';
	}
	
	$title .= $gallerySettings['galleryName']. ' - ' . $system->settings['title'];
	
	$itemsCount = $albumCount + $n;
	
	if ( $itemsCount < $cols && $itemsCount > 0 ) {
		$cols = $itemsCount;
	}
	
	$tempRows = ceil( $itemsCount / $cols );
	if ( $tempRows < $rows )
		$rows = $tempRows;
		
	$equalWidth = floor( 100 / $cols );
	$t->assign( 'equalWidth', $equalWidth );
	
	
	$pageHeader = $gallerySettings['galleryName'] ? $gallerySettings['galleryName'] : 'Image Gallery';
	
	if ( $currentCategory && !($categorySettings['useGalleryTitle']=='yes') ) {
		$pageHeader = $categorySettings['catPageHeader'] ? $categorySettings['catPageHeader'] : '{$title}';
		$pageHeader = str_replace( '{$title}', $categoryTitle, $pageHeader );
	}
	
	if ( $currentCategory ) {
		$metaKeywords = $categorySettings['cat_meta_keywords'] ? $categorySettings['cat_meta_keywords'] : $gallerySettings['meta_keywords'];
		$metaDescription = $categorySettings['cat_meta_desc'] ? $categorySettings['cat_meta_desc'] : $gallerySettings['meta_desc'];
		$t->assign( 'metaKeywords', $metaKeywords );
		$t->assign( 'metaDescription', $metaDescription );
	}
	
	$t->assign( 'pageHeader', $pageHeader );
	
	$t->assign( 'galleryPath', $path );
	$t->assign( 'rows', $rows );
	$t->assign( 'cols', $cols );
	$t->assign_by_ref( 'images', $images );
	$t->assign( 'empty', ($itemsCount-$albumCount == 0) && !$found && $currentCategory );
	$t->assign( 'title', $title );
	$t->assign( 'bodyTemplate', 'modules/gallery/index.tpl' );
}


function userContent() {
	global $t, $session, $db;

	if ( $_GET['category_sef_title'] ) {
	   	$id = $db->getOne( 'select id from ' . MODULECATEGORIES_TABLE . " where sef_title='{$_GET['category_sef_title']}'" );
	   	$_GET['category'] = $id;
	}
	$category = intval( $_GET['category'] );
	
	$id = intval( $_GET['id'] );

	$session->updateLocation( 'image_category', 'Image Category', array( 'category' ), '', 0 );
	
	$createThumb = gdInstalled() ? getSetting( 'createThumb', 'yes' ) : 'no';

	if ( $createThumb == 'no' )
		$newWindow = 'no';

	displayTable( $category );
	
	$t->assign( 'createThumb', $createThumb );

}


/**
 * @return void
 * @param int $masterid album to move
 * @param int $step step for reordering, can be 1 or -1
 * @desc Reorder album depending on step
*/
function moveAlbum( $masterid, $step ) {
	global $db, $site;

	$table = MODULECATEGORIES_TABLE;
	$add_fields = array( 'site_key', 'module_key' );
	$add_values = array( $site, 'gallery' );
	if ( $step > 0 ) {
		$current = $db->getRow( 'select _order, parent from '.$table." where id='$masterid' and module_key='gallery'" );
		$id = $db->getOne( 'select id from '.$table." where site_key='$site' and _order>'$current[_order]' and parent='$current[parent]' and module_key='gallery' order by _order limit 0,1" );
	} else {
		$id = $masterid;
	}
	$category = new Category( $db, $table, $add_fields, $add_values );

	$category->bump( $id );
}


function deleteAlbum( $id ) {
	global $db, $site, $moduleKey;

	$add_fields = array( 'site_key', 'module_key' );
	$add_values = array( $site, $moduleKey );
	$category = new Category( $db, MODULECATEGORIES_TABLE, $add_fields, $add_values );

	// Assign delete handler
	$category->onDelete( 'onAlbumDelete' );

	$category->delete( $id );
}


function adminContent() {
	global $t;
	$t->assign( 'bodyTemplate', 'modules/gallery/manage/imageForm.tpl' );
}



$action = $_POST['galleryAction'];

if ( $id = intval( end( explode( '_', $action ) ) ) ) {
	$action = substr( $action, 0, strlen( $action )-strlen( $id )-1 );
}

switch( $action ) {
	case "save_image":
		saveImage( intval( $id ) );
		userContent();
		break;

	case "edit_image":
		editImage( intval( $id ), $_SERVER['HTTP_REFERER'] );
		adminContent();
		break;

	case "delete_image":
		deleteImage( intval( $id ) );
		userContent();
		break;

	case "delete_album":
		deleteAlbum( intval( $id ) );
		userContent();
		break;

	case "delete_selected":
		deleteSelected();
		userContent();
		break;

	case "up_image":
		changeImageOrder( intval( $id ), -1, intval( $_REQUEST['category'] ) );
		userContent();
		break;

	case "down_image":
		changeImageOrder( intval( $id ), 1, intval( $_REQUEST['category'] ) );
		userContent();
		break;

	case "up_album":
		moveAlbum( intval($id), -1 );
		userContent();
		break;

	case "down_album":
		moveAlbum( intval($id), 1 );
		userContent();
		break;

	default:
		userContent();
		break;

}


include_once( FULL_PATH . 'init_bottom.php' );

$t->display( $templateName );

$db->disconnect();

?>