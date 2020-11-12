<?php

require_once( 'config.php' );
require_once( 'include/functions.php' );
require_once( 'include/adminFunctions.php' );

$category = intval( $_REQUEST['category'] );
$id = intval( $_REQUEST['id'] );
$_SESSION['addToCart'] = '';

function adminContent() {
    global $t, $session;
	$session->updateLocation( 'gallery_edit_image', 'Edit Gallery Item', array( 'category', 'id' ) );
    $t->assign( 'bodyTemplate', 'modules/gallery/manage/imageForm.tpl' );
}

$action = $_POST['galleryAction'];
if ( $eid = intval( end( explode( '_', $action ) ) ) ) {
    $action = substr( $action, 0, strlen( $action )-strlen( $eid )-1 );
}

$images = $db->getAll( 'select img_id as id from '.IMAGECATS_TABLE." where site_key='$site' and cat_id='$category' order by _order" );

if ( !isset( $_REQUEST['start'] ) ) {
    $images2 = array();

    foreach( $images as $index => $row ) {
        $images2 [] = $row[id];
    }

    $current = array_search( $_REQUEST['id'], $images2 );
    $_REQUEST['start'] = $current;
}

$id = $images[$_REQUEST['start']]['id'];


switch( $action ) {
	
    case "save_image":
        saveImage( intval( $eid ) );
        displayImage( $eid, $category );
		$session->updateLocation( 'gallery_image', 'Gallery Image', array( 'category', 'id' ), '', 0 );
	    $t->assign( 'bodyTemplate', 'modules/gallery/displayImage.tpl' );
        break;

    case "edit_image":
        editImage( intval( $id ), $_SERVER['HTTP_REFERER'] );
        adminContent();
        break;
        
    case "delete_image":
        deleteImage( intval( $eid ) );
        //displayImage( $id, $category );
		//$session->updateLocation( 'gallery_image', 'Gallery Image', array( 'category', 'id', 'start', 'set' ) );
	    //$t->assign( 'bodyTemplate', 'modules/gallery/displayImage.tpl' );
	    header( 'Location: index.php?category='.$category );
	    exit;
        break;

    default:
        displayImage( $id, $category );
		$session->updateLocation( 'gallery_image', 'Gallery Image', array( 'category', 'id', 'start', 'set' ), '', 0 );
	    $t->assign( 'bodyTemplate', 'modules/gallery/displayImage.tpl' );
        break;

}


include_once( FULL_PATH . 'init_bottom.php' );

if ( $newWindow == 'yes' )
    // Display only the file
    $t->display( 'modules/gallery/displayImage.tpl' );
else {
    $t->display( $templateName );
}

$db->disconnect();

?>