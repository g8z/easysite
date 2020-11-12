<?php


require '../config.php';
require INCLUDE_DIR . 'internal/class.image.php';


function insertDefaults( $id ) {
	global $db, $site, $gallerySettings;

	if ( $gallerySettings['useEcommerce'] == 'yes' ) {

		// store user-defined attributes if present

		$attributes = $db->getAll( 'select id, name, type, _default from '. ATTRIBUTES_TABLE." where site_key='$site' and visible=1" );

		foreach ( $attributes as $idx=>$attr ) {
			$db->query( 'insert into '. ATTRVALUES_TABLE." (product_id,attr_id,value,site_key) values ('$id', '$attr[id]', '$attr[_default]', '$site')" );
		}
	}
}

/**
 * Resize images and store them in the DB
 */
function resizeAndStoreImage( $userName, $title, $cat_id, $imageName ) {

    global $db, $site, $gallerySettings;

    // Allowed image extensions

    $ext = array( 'gif', 'jpg', 'jpeg', 'png', 'swf' );

    $thumbWidth = getSetting( 'thumbnailWidth', 100 );
    $thumbHeight = getSetting( 'thumbnailHeight', 100 );
    $fullWidth = getSetting( 'imageWidth', 800 );
    $fullHeight = getSetting( 'imageHeight', 600 );
    $createThumb = gdInstalled() ? getSetting( 'createThumb', 'yes' ) : 'no';

    if ( $createThumb == 'yes' )
        $image = new Image( $imageName );

    // use temp folder for both thumbnails and full images

    $imageType = strtolower( end( explode( '.', $userName ) ) );

    if ( $title == '' )
        $title = $userName;

    if ( in_array( $imageType, $ext ) || $userName == '' ) {
        $did = $db->getOne( 'select max(id) from ' . IMAGES_TABLE );
        $did++;

        // Find next order

        $order = $db->getOne( 'select max(_order) from ' . IMAGECATS_TABLE . " where cat_id='$cat_id' and site_key='$site'" );

        if ( $order != '' )
        	$order++;
        else
        	$order = 0;


        if ( $createThumb == 'yes' ) {

            if ( $userName ) {
	        	$thumbPath = FULL_PATH . TEMP_DIR . '/' . 'thumb' . '.' . $imageType;
	            $fullPath = FULL_PATH . TEMP_DIR . '/' . 'full' . '.' . $imageType;

	            // --------------------------
	            // Create and store thumbnail
	            // --------------------------

	            $image->resize( $thumbWidth, $thumbHeight );
	            $image->saveAs( $thumbPath );
	            $smallImageData = addslashes( fread( fopen( $thumbPath, 'rb' ), filesize( $thumbPath ) ) );

	            // ---------------------------------------------
	            // Create and store resized full image if needed
	            // ---------------------------------------------

	            $image->resize( $fullWidth, $fullHeight );
	            $image->saveAs( $fullPath );
	            $largeImageData = addslashes( fread( fopen( $fullPath, 'rb' ), filesize( $fullPath ) ) );
            }
            else {
            	// set empty values
            	// default images will be dusplayed instead
            	$smallImageData = '';
            	$largeImageData = '';
            	$userName = $title;
            }

            // save to database...

            if ( ($error = $image->getError()) == '' ) {

                $db->query( 'insert into ' . IMAGES_TABLE . " (id, title, description, created, site_key, img_thumb, img_large) values( '$did', '$title', '', NOW(), '$site', '$smallImageData', '$largeImageData' )" );
                $db->query( 'insert into '. IMAGECATS_TABLE ." (img_id, cat_id, _order, site_key) values ('$did', '$cat_id', '$order', '$site')" );
                insertDefaults( $did );

                $result = array( 'Image '. stripslashes( $userName ) .' was successfully added.', 1 );
            }
            else
                $result = array( $error, 0 );
        }
        else {

        	if ( $userName )
            	$i = addslashes( fread( fopen( $imageName, 'rb' ), filesize( $imageName ) ) );
            else {
            	$i = '';
            	$userName = $title;
            }

            // ------------------------------------------------------------------
            // we are not creating a thumbnail, so insert the large image as both
            // thumbnail and also full-size image
            // ------------------------------------------------------------------

            $db->query( 'insert into ' . IMAGES_TABLE . " (id, title, description, created, site_key, img_thumb, img_large) values( '$did', '$title', '', NOW(), '$site', '$i', '$i')" );
            $db->query( 'insert into '. IMAGECATS_TABLE ." (img_id, cat_id, _order, site_key) values ('$did', '$cat_id', '$order', '$site')" );
            insertDefaults( $did );


            $result = array( 'Image '. stripslashes( $userName ) .' was successfully added.', 1 );
        }
    }
    else {
        $result = array( 'Image '. stripslashes( $userName ) .' is not a valid image. It was not added to the gallery.', 0 );
    }

    return $result;
}



if ( $upload ) {

    // -------------------------
    // Upload images
    // -------------------------

    $cat_id = $_POST[category];
    @set_time_limit( 0 );   // set_time_limit does not work in safe mode

    $oldId = $db->getOne( 'select max(id) from '. IMAGES_TABLE." where site_key='$site'" );

    foreach ( $_FILES as $key => $value ) {
        list( $type, $id ) = explode( '_', $key );
        if ( $value[name] != '' || $_POST["title_$id"] ) {
        	if ( !$value['error'] ) {
	            $imageName = $value['tmp_name'];
	            move_uploaded_file( $imageName, FULL_PATH . TEMP_DIR . '/uploaded.file' );
	            $userName = $value['name'];
	            $title = $_POST["title_$id"];
	            $messages[] = resizeAndStoreImage( $userName, $title, $cat_id, FULL_PATH . TEMP_DIR . '/uploaded.file' );
	            @unlink( $imageName );
        	}
        	else {
        		$messages[] = array( "An error occurred when trying to upload $value[name]. Please ensure that file uploads are allowed on your server and the size of the file you are uploading is not too big.", 0 );
        	}
        }
    }

    $newId = $db->getOne( 'select max(id) from '. IMAGES_TABLE." where site_key='$site'" );

    $t->assign( 'searchId', $oldId+1 );
    $t->assign( 'messages', $messages );
    $t->assign( 'count', count( $messages ) );
    $t->assign( 'bodyTemplate', 'modules/gallery/manage/afterUpload.tpl' );

}


if ( !$upload || ($newId == $oldId) ) {

    // -------------------
    // Display upload form
    // -------------------

    // Create list for choosing upload count

    for ($i=1; $i<21; $i++) {
        $uploadCounts[$i] = $i;
    }

    // ----------------------------------
    // This is for the upload from folder
    // ----------------------------------
    if ( $_SERVER['DOCUMENT_ROOT'][strlen($_SERVER['DOCUMENT_ROOT'])-1] != '/' )
        $fullPath = $_SERVER['DOCUMENT_ROOT'] . DOC_ROOT;
    else
        $fullPath = $_SERVER['DOCUMENT_ROOT'] . substr( DOC_ROOT, 1, strlen( DOC_ROOT )-1 );

    // Default ammount to upload images

    $count = ( $_POST['upload_count'] ? $_POST['upload_count'] : 5);

    $t->assign( 'cat_ids', $cat_ids );
    $t->assign( 'cat_names', $cat_names );
    $t->assign( 'uploadCounts', $uploadCounts );
    $t->assign( 'count', $count );
    $t->assign( 'fullPath', $fullPath );
    $t->assign( 'gdInstalled', gdInstalled() );
    $t->assign( 'bodyTemplate', 'modules/gallery/manage/uploadForm.tpl' );
}

$session->updateLocation( 'gallery_add_item', 'Add Items' );
include_once( FULL_PATH . 'init_bottom.php' );

$t->display( $templateName );

?>