<?php

include_once( INCLUDE_DIR . 'internal/db_items/class.Ordered_DB_Item.php' );
$odbi = new Ordered_DB_Item(0, IMAGES_TABLE);
$odbi->_orderField = 'cat_id';

/**
 * @return void
 * @param id $id
 * @desc Album delete handler. Will execute before deleting the album.
 *	   Deletes all images from the album
*/
function onAlbumDelete( $id ) {
	global $db, $site, $c;

	$c->_table = IMAGES_TABLE;

	//$db->query( 'delete from ' . IMAGECATS_TABLE . " where cat_id='$id'" );
	$images = $db->getAll( 'select id from ' . IMAGECATS_TABLE . " where cat_id='$id' and site_key='$site'" );

	foreach ( $images as $image ){
		deleteImage( $image['id'] );

		// delete from the cache

		$c->_id = $image['id'];
		$c->_field = 'img_thumb';
		$c->remove();
		$c->_field = 'img_large';
		$c->remove();
	}
}

function reorderImages() {
	global $db, $site;
	
	$odbi = new Ordered_DB_Item( 0, IMAGECATS_TABLE );
	$odbi->_orderField = 'cat_id';
	$odbi->reorder();
	
	unset( $odbi );
}


function getItemCategories( $id ) {
	
	global $db, $site;
	
	// determine which categories this item belongs to
	
	$cat_ids = array();
	$cats = $db->getAll( 'select cat_id from '. IMAGECATS_TABLE." where img_id='$id' and site_key='$site'" );
	foreach( $cats as $idx=>$cat ) {
		$cat_ids[] = $cat['cat_id'];
	}
	
	return $cat_ids;
}


function editImage( $id = -1, $url ) {
	
	global $db, $t, $site, $_GET, $_POST, $gallerySettings, $moduleKey;

	$createThumb = gdInstalled() ? getSetting( 'createThumb', 'yes' ) : 'no';

	$image = $db->getRow( 'select * from '. IMAGES_TABLE . " where id='$id'" );
	$image['title'] = stripslashes( $image['title'] );
	$image['description'] = stripslashes( $image['description'] );
	$image['_order'];
	

	$image['cat_ids'] = getItemCategories( $id );

/*	$cat_id = $image['cat_id'];

	$catImages = $db->getAll( 'select id, title, _order from ' . IMAGES_TABLE . " where site_key='$site' and cat_id='$cat_id' order by _order" );

	$order = array();

	foreach ( $catImages as $im ) {
		$o = $im['_order'];
		if ( $im['id'] != $image['id'] ) {
			$order[] = ($o+1).': '.$im['title'];
		} else {
			$order[] = ($o+1).': (current)';
		}
	}*/
	
	// Check if we are in ecommerce mode
	
	if ( $gallerySettings['useEcommerce'] == 'yes' ) {
		
		$sql = 
			'SELECT '.
				'a.id as id, a.name, a.measurement, a.type, v.use_default, v.value '.
			'FROM '. ATTRIBUTES_TABLE." a ".
			'LEFT JOIN '. ATTRVALUES_TABLE." v on (a.id=v.attr_id and v.product_id='$id')".
			"WHERE a.site_key='$site' and a.visible=1";
			
		$attributes = $db->getAll( $sql );
		//print_r( $attributes );
		
		foreach ( $attributes as $idx=>$attr ) {
			if ( preg_match( '/^list_(.*)$/', $attr[type], $matches ) )
			$attributes[$idx]['listName'] = $matches[1];
		}
		$t->assign( 'attributes', $attributes );
	}
	
	$add_fields = array( 'site_key', 'module_key' );
	$add_values = array( $site, $moduleKey );
	
	$category = new Category( $db, MODULECATEGORIES_TABLE, $add_fields, $add_values );

	$categories = $category->getCategoryArrayHelper();
	$t->assign( 'categories', $categories );
	
	unset( $categories );

	// Store post data for return to the previous page

	$prevPost = getArray( $_POST );
	$prevPost = array_append( $prevPost, getArray( $_GET ) );

	$t->assign( 'prevPost', $prevPost );
	$t->assign( 'width', getSetting( 'thumbnailWidth', 100 ) );
	$t->assign( 'createThumb', $createThumb );
	$t->assign( 'url', $url );
	$t->assign( 'image', $image );
	$t->assign( 'order', $order );
	$t->assign( 'bodyTemplate', 'modules/gallery/manage/imageForm.tpl' );
}


function deleteImage( $id ) {
	global $db;
	
	// remove images from cache

	global $c;  // Cacher object established in init.php

	$c->_table = IMAGES_TABLE;
	$c->_id = $id;
	$c->_field = 'img_thumb';
	$c->remove();
	$c->_field = 'img_large';
	$c->remove();
	
	$db->query( 'delete from ' . IMAGES_TABLE . " where id='$id'" );
	$db->query( 'delete from ' . IMAGECATS_TABLE . " where img_id='$id'" );
	$db->query( 'delete from ' . ATTRVALUES_TABLE . " where product_id='$id'" );

	reorderImages();

}


function deleteSelected() {
	
	global $db, $site, $moduleKey, $session;
	foreach( $_POST as $key => $value ) {
		
		if ( ereg( 'ch_image', $key ) ) {
			$id = end( explode( '_', $key ) );
			deleteImage( $id );
		}
		
		if ( ereg( 'ch_album', $key ) ) {
			$id = end( explode( '_', $key ) );
			
			$add_fields = array( 'site_key', 'module_key' );
			$add_values = array( $site, $moduleKey );
			$category = new Category( $db, MODULECATEGORIES_TABLE, $add_fields, $add_values );
		
			// Assign delete handler
			$category->onDelete( 'onAlbumDelete' );
		
			$category->delete( $id );
		}
	}
	
	header( 'Location: ' . $session->prevLocation['url'] );
	exit;
}


function changeImageOrder( $id, $step, $cat_id ) {
	
	global $db, $site;
	
	$icid = $db->getOne( 'select id from '. IMAGECATS_TABLE." where img_id='$id' and cat_id='$cat_id' and site_key='$site'" );
	$odbi = new Ordered_DB_Item( 0, IMAGECATS_TABLE );
	$odbi->_orderField = 'cat_id';
	
	$odbi->changeOrder( $icid, $step );
	$odbi->reorder();
	
	unset( $odbi );

}

function saveImage( $id = -1 ) {
	
	global $db, $t, $site, $gallerySettings, $c;

	$createThumb = gdInstalled() ? getSetting( 'createThumb', 'yes' ) : 'no';
	
	//extract( $_REQUEST );

	if ( $id != -1 ) {

		$image = $db->getRow( 'select * from ' . IMAGES_TABLE . " where id='$id'" );
		
		// --------------------------
		// Update general information
		// --------------------------
		
		$db->query( 'update ' . IMAGES_TABLE . " set 
		title='$_REQUEST[ititle]', 
		description='$_REQUEST[idescription]', 
		price='$_REQUEST[iprice]', 
		use_cat_price='$_REQUEST[useCategoryPrice]',
		quantity='$_REQUEST[iquantity]', 
		man_id='$_REQUEST[iman_id]' 
		where id='$id'" );
		
		
		// ----------------------
		// Update item categories
		// ----------------------
		
		$odbi = new Ordered_DB_Item( 0, IMAGECATS_TABLE );
		
		$oldItemCats = getItemCategories( $id );
		$newItemCats = $_REQUEST['icat_ids'];
		
		$int = @array_intersect( $oldItemCats, $newItemCats );
		
		$catsToRemove = @array_diff( $oldItemCats, $int );
		$catsToAdd = @array_diff( $newItemCats, $int );
		
		// remove item from cats
		if ( $catsToRemove )
		foreach( $catsToRemove as $idx=>$cat ) {
			$db->query( 'delete from '. IMAGECATS_TABLE." where cat_id='$cat' and img_id='$id' and site_key='$site'" );
			$odbi->reorder( "cat_id='$cat'" );
		}
		
		// add item to cats
		if ( $catsToAdd )
		foreach( $catsToAdd as $idx=>$cat ) {
			$order = $db->getOne( 'select max(_order) from '. IMAGECATS_TABLE." where cat_id='$cat' and site_key='$site'" );
			$order++;
			$db->query( 'insert into '. IMAGECATS_TABLE." (img_id, cat_id, _order, site_key) values ('$id', '$cat', '$order', '$site')" );
		}
		
		// ------------------------------------
		// Check if we should change full image
		// ------------------------------------
		
		if ( $_FILES['iimg_large']['name'] ) {
		
			$imageName = FULL_PATH . TEMP_DIR . '/uploaded.file';
			move_uploaded_file( $_FILES['iimg_large']['tmp_name'], $imageName );
			
			if ( $createThumb ) {
				
				// resize large image
				
				require_once INCLUDE_DIR . 'internal/class.image.php';
				
				$fullWidth = getSetting( 'imageWidth', 800 );
				$fullHeight = getSetting( 'imageHeight', 600 );
				$fullPath = FULL_PATH . TEMP_DIR . '/' . 'full' . '.' . $imageType;
				$image = new Image( $imageName );
				$image->resize( $fullWidth, $fullHeight );
				$image->saveAs( $fullPath );
			}
			else {
				$fullPath = $imageName;
			}
			
			$largeImageData = addslashes( fread( fopen( $fullPath, 'rb' ), filesize( $fullPath ) ) );
			
			$c->_table = IMAGES_TABLE;
			$c->_id = $id;
			$c->_field = 'img_large';
			$c->remove();
			
			$db->query( 'update '. IMAGES_TABLE." set img_large='$largeImageData' where id='$id'" );
			
			unset( $image );

		}
		
		
		// ----------------------------------
		// check if we should change thumbnal
		// ----------------------------------
		
		if ( $_REQUEST['overrideThumb'] && $imageName ) {
			$thumbName = $imageName;
		}

		if ( $_FILES['iimg_thumb']['name'] && ( !$thumbName ) ) {
			$thumbName = FULL_PATH . TEMP_DIR . '/uploaded.file';
			move_uploaded_file( $_FILES['iimg_thumb']['tmp_name'], $thumbName );
		}
		
		if ( $thumbName ) {
			
			if ( $createThumb ) {
				$thumbWidth = getSetting( 'thumbnailWidth', 100 );
				$thumbHeight = getSetting( 'thumbnailHeight', 100 );
				
				$thumbPath = FULL_PATH . TEMP_DIR . '/' . 'thumb' . '.' . $imageType;
				
				require_once INCLUDE_DIR . 'internal/class.image.php';
				$image = new Image( $thumbName );
				$image->resize( $thumbWidth, $thumbHeight );
				$image->saveAs( $thumbPath );
				unset( $image );
			}
			else {
				$thumbPath = $thumbName;
			}
			
			$smallImageData = addslashes( fread( fopen( $thumbPath, 'rb' ), filesize( $thumbPath ) ) );
			
			$c->_table = IMAGES_TABLE;
			$c->_id = $id;
			$c->_field = 'img_thumb';
			$c->remove();

			$db->query( 'update '. IMAGES_TABLE." set img_thumb='$smallImageData' where id='$id'" );
		}
		
		// -------------------------------------
		// update e-commerce attribues if needed
		// -------------------------------------
			
		if ( $gallerySettings['useEcommerce'] == 'yes' ) {
			
			// store user-defined attributes if present
			
			$attributes = $db->getAll( 'select id, name, type from '. ATTRIBUTES_TABLE." where site_key='$site' and visible=1" );
			
			foreach ( $attributes as $idx=>$attr ) {

				$value = getAttrValue( $attr['type'], 'attr_'.$attr[id] );
				$aid = $db->getOne( 'select id from '.ATTRVALUES_TABLE." where product_id='$id' and attr_id='$attr[id]'" );
				if ( $aid ) {
					$db->query( 'update '. ATTRVALUES_TABLE." set value='$value', use_default='{$_POST['attr_'.$attr[id].'_default']}' where id='$aid'" );
				}
				else {
					$db->query( 'insert into '. ATTRVALUES_TABLE." (product_id,attr_id,value,use_default,site_key) values ('$id', '$attr[id]', '$value', '{$_POST['attr_'.$attr[id].'_default']}', '$site')" );
				}
			}
		}
	}

}

?>