<?php

$manage = true;

require '../config.php';

include_once INCLUDE_DIR . 'internal/class.image.php';

$table = REALTYITEMS_TABLE;

$t->assign( 'realtyOptions', $realtyOptions );

// add / update / delete the listing data

$user = $_SESSION['es_auth']['id'];

$hasAccess = hasAdminAccess( 'cm_module' ) && hasAdminAccess( 'cm_'.$moduleKey ) &&
           ( hasAdminAccess( 'cm_'.$moduleKey.'_add_listings' ) || hasAdminAccess( 'cm_'.$moduleKey.'_edit_listings' ) );

if ( $hasAccess ) {

if ( $formIsSubmitted ) {

    $hasAccess = 0;

    if ( !$listing_id ) {

        if ( $hasAccess = hasAdminAccess( 'cm_'.$moduleKey.'_add_listings' ) ) {
    		$db->query( 'insert into ' . REALTYITEMS_TABLE . " ( site_key, user_id ) values ( '$site', '$user' )" );

    		// determine the listing_id
    		$listing_id = $db->getOne( 'select max(id) from ' . REALTYITEMS_TABLE . " where site_key = '$site' and user_id = '$user'" );
        }
	}

    if ( $listing_id && ( $hasAccess || hasAdminAccess( 'cm_'.$moduleKey.'_edit_listings' ) ) ) {

        $hasAccess = 1;

        // update this listing

    	$result = $db->query( "select * from " . REALTYITEMS_TABLE . " where id = '$listing_id'" );

    	$info = $result->tableInfo();

    	$updateFields = array();

    	$_POST['listing_date'] = "$listing_date_Year-$listing_date_Month-$listing_date_Day";
    	$_POST['closing_date'] = "$closing_date_Year-$closing_date_Month-$closing_date_Day";

    	foreach( $info as $index => $row ) {

    		// fields that we should skip
    		if ( $row[name] == 'site_key' || $row[name] == 'user_id' || $row[name] == 'id' || $row[name] == 'image' )
    			continue;

    		$updateFields [] = $row[name] . ' = \'' . $_POST[$row[name]] . '\'';
    	}

    	// add the listing date and closing date to the list of fields to update

    	$db->query( "update $table set " . implode( ',', $updateFields ) . " where id = '$listing_id' and site_key = '$site'" );

    	// check to see if we should add a new or updated image for this listing

    	foreach( $_POST as $name => $arr ) {

			list( $temp, $fileId ) = explode( '_', $name );

			// get the image title & description, if available

			if ( $fileId > 0 ) {
				$title = $_POST['imgTitle_' . $fileId];
				$description = $_POST['imgDescription_' . $fileId];

				$db->query( 'update ' . MODULEOBJECTS_TABLE . " set title = '$title', description = '$description' where id = '$fileId' and site_key = '$site' and module_key = 'realty' and item_id = '$listing_id'" );
			}
    	}

    	foreach( $_FILES as $name => $arr ) {

    		if ( $_FILES[$name]['tmp_name'] ) {

    			// get the raw data for this file

    			$resizeOption = getSetting( 'resizeImage', 'yes' );

                if ( ($resizeOption === 'yes' || $resizeOption === '1') && gdInstalled() ) {
                    $imWidth = getSetting( 'imageWidth', 350 );
                    $imHeight = getSetting( 'imageHeight', 350 );
                    $image = new Image( $_FILES[$name]['tmp_name'] );
                    $imageType = strtolower( end( explode( '.', $_FILES[$name][user_name] ) ) );
                    $fullPath = FULL_PATH . TEMP_DIR . '/' . 'full' . '.' . $imageType;

                    $image->resize( $imWidth, $imHeight );
                    $image->saveAs( $fullPath );
                    $fileData = getFileFromDiskData( $fullPath );
                } else
    			    $fileData = getFileData( $name );	// 'image' is the name of a 'file' field

    			// determine the fileId value from $name

    			list( $temp, $fileId ) = explode( '_', $name );

    			// get the image title & description, if available

				$title = $_POST['imgTitle_' . $fileId];
				$description = $_POST['imgDescription_' . $fileId];

    			// check to see if this is an image update or an image addition

    			if ( $fileId == 'NEW' ) {
    				// add a new entry for this image

    				// determine the order id for this new image

    				$newOrder = 1 + $db->getOne( 'select max(_order) from ' . MODULEOBJECTS_TABLE . " where site_key = '$site' and module_key = 'realty' and item_id = '$listing_id'" );

    				/*
    				echo 'insert into ' . MODULEOBJECTS_TABLE . " ( _order, item_id, site_key, module_key, title, description ) values ( '$newOrder', '$listing_id', '$site', 'realty', '$title', '$description' )";
					*/

    				$db->query( 'insert into ' . MODULEOBJECTS_TABLE . " ( _order, item_id, site_key, module_key, title, description ) values ( '$newOrder', '$listing_id', '$site', 'realty', '$title', '$description' )" );

    				$fileId = $db->getOne( 'select max(id) from ' . MODULEOBJECTS_TABLE . " where site_key = '$site' and module_key = 'realty' and item_id = '$listing_id' limit 1" );
    			}

				// NOTE: update of title & description is handled before this foreach loop

    			$db->query( 'update ' . MODULEOBJECTS_TABLE . " set data = '$fileData' where id = '$fileId' and site_key = '$site' and module_key = 'realty' and item_id = '$listing_id'" );
    		}
    	}

    	// check to see if the user has asked to remove the primary image for this listing
    	if ( $deleteImage && hasAdminAccess( 'cm_'.$moduleKey.'_edit_listings')) {

            $hasAccess = 1;

    		$db->query( 'delete from ' . MODULEOBJECTS_TABLE . " where id = '$deleteImage' and site_key = '$site' and module_key = 'realty'" );
    	}

    }
}

if ( $deleteId > 0 && hasAdminAccess( 'cm_'.$moduleKey.'_delete_listings') ) {

    $hasAccess = 1;

	$db->query( "delete from $table where id = '$listing_id' and site_key = '$site'" );

	// now delete the associated object

	$db->query( 'delete from ' . MODULEOBJECTS_TABLE . " where item_id = '$listing_id' and site_key = '$site' and module_key = 'realty'" );
}

if ( $bumpId ) {

	// adjust the _order

	// get the order # just before this one

	$current = $db->getRow( 'select _order, id from ' . MODULEOBJECTS_TABLE . " where site_key = '$site' and module_key = 'realty' and id = '$bumpId'" );

	$previous = $db->getRow( 'select _order, id from ' . MODULEOBJECTS_TABLE . " where _order < $current[_order] and site_key = '$site' and module_key = 'realty' order by _order desc limit 1" );

	// swap $prevOrder and $curOrder

	$db->query( 'update ' . MODULEOBJECTS_TABLE . " set _order = '$previous[_order]' where id = '$current[id]' and site_key = '$site' and module_key = 'realty'" );

	$db->query( 'update ' . MODULEOBJECTS_TABLE . " set _order = '$current[_order]' where id = '$previous[id]' and site_key = '$site' and module_key = 'realty'" );

	$reorder = true;// reorder the _order field values
}

if ( $listing_id > 0 )
	$t->assign( 'data', $db->getRow( "select * from $table where id = '$listing_id' and site_key = '$site'" ) );

// get a list of all listings from this user

$allListings = $db->getAll( "select * from $table where user_id = '$user' and site_key = '$site'" );
$listings = array();

foreach( $allListings as $index => $row ) {
	$listings[ "$row[id]" ] = $row[ title ];
}

$t->assign( 'listings', $listings );

// get the secondary images for this listing

$imageData = $db->getAll( 'select * from ' . MODULEOBJECTS_TABLE . " where item_id = '$listing_id' and site_key = '$site' and module_key = 'realty' order by _order" );

// re-number all _order fields

if ( $reorder ) {

	$order = 0;

	foreach( $imageData as $index => $row ) {

		$db->query( 'update ' . MODULEOBJECTS_TABLE . " set _order = '$order' where id = '$row[id]' and site_key = '$site' and item_id = '$row[item_id]' and module_key = 'realty'" );

		$order++;
	}
}

array_unshift( $imageData, array( 'id' => 'NEW' ) );

//$imageData [] = array( 'id' => 'NEW' );

$t->assign( 'imageData', $imageData );

$t->assign( 'numImages', sizeof( $imageData ) );

// how yes/no option buttons should be displayed
$yesno = array(
	'1' => 'Yes',
	'0' => 'No'
);

$t->assign( 'yesno', $yesno );

if ( $hasAccess )
    // insert the body content
    $t->assign( 'bodyTemplate', 'modules/realty/manage/listings.tpl' );

} // if has access

if ( !$hasAccess )
    noAccessMessage( 'modules/realty/navigation.tpl' );


$session->updateLocation( 'realty_listings', 'Realty Listings', array( 'listing_id' ) );

include_once( FULL_PATH . 'init_bottom.php' );

$t->display( $templateName );



?>