<?php

if ( !defined( 'SMARTY_DIR' ) )
    include_once( '../init.php' );

// templateName is defined in the init.php

if ( !hasAdminAccess( 'cm_page' ) ) {
    $t->assign( 'bodyTemplate', 'manage/authError.tpl' );
	$t->display( $templateName );
    exit;
}

$hasAccess = true;

include_once( INCLUDE_DIR . 'internal/db_items/class.Page.php' );
include_once( INCLUDE_DIR . 'internal/class.File.php' );


$page_id = intval( $_REQUEST['page_id'] );

if ( !$user->hasAccess( 'page', $page_id ) )
	loginError( ACCESS_DENIED );

$bsite = $site;

$page  = new Page();

if ( $user->isShared( 'page', $page_id, 'edit' ) )
	$site = $user->getSharedSiteKey( 'page', $page_id );

$pSect = new Page_Section();

$site = $bsite;

/**
 * Uploads images & updates image paths
 * Function assumes that the image fields are named identically
 * between the pages table and the sections table
 */
function uploadFile( $fieldName, $id ) {

	if ( !$_FILES[$fieldName . '_' . $id][name] )
		return;

    global $c, $pSect;

    $file = new File( $fieldName.'_'.$id );

    if ( $file->isUploaded() ){

        $fields = array(
            $fieldName          => $file->getContent(),
            $fieldName.'_path'  => $file->userName
        );

        $pSect->updateId( $id, $fields );

        $c->_table = SECTIONS_TABLE;
        $c->_id = $id;
        $c->_field = $fieldName;
        $c->remove();

	$file->delete();

        // unset this FILES var so that we do not re-add this image
        unset( $_FILES[$fieldName.'_'.$id] );
     }

    unset( $file );
}


if ( $_POST['deletePage'] ) {

    if ( $hasAccess = hasAdminAccess( 'cm_page_delete' ) ) {

        $page->delete( $_POST['page_id'] );

        // redirect back to admin index page
        include_once( FULL_PATH . '/manage/index.php' );

        exit;
    }
}


// check for form submission
if ( $_POST['formIsSubmitted'] ) {

    if ( !$page_id ) {

        if ( $hasAccess = hasAdminAccess( 'cm_page_add' ) ) {

            // create new page

            $fields = array( 'site_key'=>$site, 'skin_id'=>intval($skin_id) );
            $page->create( $fields );
            $page_id = $page->fields[id];
        }

    } else {
        $hasAccess = hasAdminAccess( 'cm_page_edit' );
    }


    if ( $hasAccess ) {

    $seen = array();

    // save all changed page data first
    foreach( $_POST as $key => $var ) {
        $keyParts = explode( '_', $key );

        $id = $keyParts[ sizeof( $keyParts ) - 1 ];

        if ( in_array( $id, $seen ) || !stristr( $key, '_' ) )
            continue;
        else
            $seen [] = $id;

        if ( $id == 'NEW' ) {
            $updateData = true;
            continue;
        }

        if ( !$updateData )
            continue;



        $fields = array(
            'style'       =>  $_POST["style_$id"],
            'format'	  =>  $_POST["format_$id"],
            'content'     =>  $_POST["content_$id"],
            'nl2br'       =>  $_POST["nl2br_$id"],
            'title'       =>  $_POST["title_$id"],
            'img_anchor'  =>  $_POST["img_anchor_$id"],
            'img_link'    =>  $_POST["img_link_$id"],
            'link_target' =>  $_POST["link_target_$id"],
            'img_alt'     =>  $_POST["img_alt_$id"],
        );

       $pSect->updateId( $id, $fields );


        uploadFile( 'img_thumb', $id );
        uploadFile( 'img_large', $id );


    }

    // override site_key
    // set parent value if the resource is shared

    if ( $user->isShared( 'page', $page_id, 'edit' ) )
    	$insertSiteKey = $user->getSharedSiteKey( 'page', $page_id );
    else
    	$insertSiteKey = $site;

    // add new form data, if needed
    if ( $_POST['addNewItem'] ) {

        $fields = array(
            'style'      => $style_NEW,
            'format'     => $format_NEW,
            'site_key'   => $insertSiteKey,
            'content'    => $content_NEW,
            'nl2br'      => $nl2br_NEW,
            'page_id'    => $page_id,
            'title'      => $title_NEW,
            'img_anchor' => $img_anchor_NEW,
            'img_link'   => $img_link_NEW,
            'link_target'=> $link_target_NEW,
            'img_alt'    => $img_alt_NEW
        );

        $pSect->create( $fields );
        $id = $pSect->fields[id];

        $_FILES['img_thumb_'.$id] = $_FILES['img_thumb_NEW'];
        $_FILES['img_large_'.$id] = $_FILES['img_large_NEW'];

        uploadFile( 'img_thumb', $id );
        uploadFile( 'img_large', $id );

    }

    $fields = array(
        'title'    => $_POST['page_title'],
        'skin_id'  => intval($_POST['skin_id']),
        'meta_keywords' => $_POST['meta_keywords'],
        'meta_desc' => $_POST['meta_desc'],
        'sef_title' => getSefTitle($_POST['page_title'], PAGES_TABLE, 'sef_title', $page_id ),
    );


    $page->updateId( $page_id, $fields );

    // determine if we should delete or bump up a section
    if ( $_POST['deleteSectionVar'] ) {

        $pSect->delete( $_POST['deleteSectionVar'] );
    }
    else if ( $_POST['bumpUpSectionVar'] ) {

        $pSect->bumpUp( $_POST['bumpUpSectionVar'], $page_id );
    }

	// set the image caching vars
	$c->_table = SECTIONS_TABLE;
	$c->_id = $id;

    // determine if we should delete an image
    if ( $_POST['deleteThumbImg'] ) {

        $fields = array(
            'img_thumb'      =>'',
            'img_thumb_path' =>''
        );
        $pSect->updateId( $_POST['deleteThumbImg'], $fields );

		$c->_field = 'img_thumb';
		$c->remove();
    }
    else if ( $_POST['deleteLargeImg'] ) {

        $fields = array(
            'img_large'      =>'',
            'img_large_path' =>''
        );
        $pSect->updateId( $_POST['deleteLargeImg'], $fields );

		$c->_field = 'img_large';
		$c->remove();
    }

	$defaultId = $page_id;
	$defaultType = 'page';

    if ( $_POST['is_default'] ) {
    	// determine if the default page has been set yet

		$db->query( 'update ' . SITES_TABLE . " set
			default_resource_type 	= '$defaultType',
			default_resource_id 	= '$defaultId'
			where site_key 			= '$site'
			" );
    }
	else {
		// clear default if this page or form was previously the default
		//$defaults = $db->getRow( 'select * from ' . SITES_TABLE . " where site_key = '$site'" );

		if ( $siteData[default_resource_type] == $defaultType && $siteData[default_resource_id] == $defaultId ) {
			$db->query( 'update ' . SITES_TABLE . " set
				default_resource_type 	= '',
				default_resource_id 	= ''
				where site_key 			= '$site'
				" );
		}
	}

	// this is the page that the user should see when they logout
	if ( $is_logout_page ) {
		$db->query( 'update ' . SITES_TABLE . " set logout_page_id = '$page_id' where site_key = '$site'" );
	}
	else {
		// clear default if this page or form was previously the default
		// $siteData is an array set in init.php which contains this information about the site (defaults, etc)
		if ( $siteData[logout_page_id] == $page_id ) {
			$db->query( 'update ' . SITES_TABLE . " set logout_page_id = '' where site_key = '$site'" );
		}
	}


    } // hasAccess

}

$resourceType = 'page';

//$page->load( $page_id, array( 'page_key' ) );

//if ( $tempSiteSettings[use_page_key] == 'yes' && $page->fields[page_key] )
//    $resourceId = $page->fields[page_key];
//else
$resourceId = $page_id;

$menuData = $db->getAll( 'select * from ' . MENUITEMS_TABLE . " where resource_type = '$resourceType' and resource_id = '$resourceId' and resource_id !='' " );

$t->assign( 'menuLinkageDegree', sizeof( $menuData ) );
$t->assign( 'menuData', $menuData );

// get all page content for this page_id
$data = $pSect->loadByParentId( $page_id, array(), '', '_order' );

// list of pages for jump menu
$shared = getSQLShares( 'page', 'edit' );
$pages = $db->getAll( "select id, if(id in ($shared), concat(title, '[shared]'), title) as title, page_key from " . PAGES_TABLE . " where (site_key = '$site' or id in ($shared)) order by title" );

$pageIds = array();
$pageTitles = array();

foreach( $pages as $index => $row ) {
    $pageIds [] = $row[id];

    $pageTitles [] = $row[title] . ' (' . $row[id] . ')';
}

$t->assign( 'numPages', sizeof( $pageTitles ) );

$t->assign( 'page_ids', $pageIds );
$t->assign( 'page_titles', $pageTitles );

$basePath = 'http://' . $_SERVER['SERVER_NAME'] . dirname( $_SERVER['PHP_SELF'] );

// path to test pages
$pagePath = $basePath . '/index.php' . '?page_id=' . $page_id;
$pagePath = stri_replace( '/' . ADMIN_DIR . '/', '/', $pagePath );

$t->assign( 'pagePath', $pagePath );

// add one item to the beginning of $data for the "new" section
array_unshift( $data, array( 'id' => 'NEW', 'nl2br'=>0 ) );

$t->assign( 'data', $data );

// if we are in a "child" website, then assign the $skin->parentSite so that
// we can get any shared skins, shared to us by the parent site administrator

if ( $_SESSION['es_auth']['user_site_key'] == $site ) {

	// we are in a user-specific site
	// determine the parent site, so that we can copy over the settings

	$parentSite = $_SESSION['es_auth']['site_key'];

	$skin->setParentSite( $parentSite );
}

$allSkins = $skin->getAll();// $skin object is from init.php

$t->assign( 'skins', $allSkins );

require_once INCLUDE_DIR . 'BrowserInfo.php';

$userBrowser = new BrowserInfo($_SERVER['HTTP_USER_AGENT']);

$wysiwygCompatible = ( $userBrowser->Browser == 'Internet Explorer' && $userBrowser->Browser_Version >= 5.5 ) || ( $userBrowser->Browser == 'Firefox' && $userBrowser->Browser_Version >= 0.7 ) || ( $userBrowser->Browser == 'Mozilla' && $userBrowser->Browser_Version >= 1.3 );

// assign wysiwyg options

if ( $wysiwygCompatible ) {

	$t->assign( 'wysiwygCompatible', $wysiwygCompatible );

	$t->assign( 'wysiwygLink', "<a href=\"javascript:enableWYSIWYG('content_[textarea_id]',false);\"><img border=0 src=\"".DOC_ROOT."images/editor.gif\" ALT=\"Use editor for this section only\"></a><a href=\"javascript:enableWYSIWYG('content_[textarea_id]',true);\"><img border=0 src=\"".DOC_ROOT."images/multiedit.gif\" ALT=\"Use editor for all sections\"></a>" );
}

if ( $page_id && !$_POST['globalLayers'] ) {
    $t->assign( 'page_id', $page_id );

    $page->load( $page_id, array( 'page_key', 'title', 'skin_id', 'counter', 'meta_keywords', 'meta_desc' ) );

    $t->assign( 'pageTitle', $page->fields[title] );
    $t->assign( 'pageKey', $page->fields[page_key] );
    $t->assign( 'counter', $page->fields[counter] );
    $t->assign( 'skin_id', $page->fields[skin_id] );
    $t->assign( 'meta_keywords', $page->fields['meta_keywords'] );
    $t->assign( 'meta_desc', $page->fields['meta_desc'] );

	// determine if this page is the default

	$defaultID = $db->getOne( 'select default_resource_id from ' . SITES_TABLE . " where site_key = '$site' and default_resource_type = 'page' limit 1" );

	if ( $defaultID == $_POST['page_id'] )
		$t->assign( 'isDefault', true );

	$logoutPageID = $db->getOne( 'select logout_page_id from ' . SITES_TABLE . " where site_key = '$site'" );

	if ( $logoutPageID  == $_POST['page_id'] )
		$t->assign( 'isLogoutPage', true );
}


$styleList = $system->getStyleList();

// for {img} tag
$t->assign( 'table', SECTIONS_TABLE );


// create the 'format' lists

$formatList = array(
	''			=> '(none)',
	'bullet' 	=> 'Bullets',
	'mbullet'	=> 'Mini-bullets',
	'arrow'		=> 'Arrows',
	'circle'	=> 'Circles',
	'asterisk' 	=> 'Asterisks',
	'dash'		=> 'Dashes',
	'number_1' 	=> '1. 2. 3.',
	'number_2' 	=> '1) 2) 3)',
	'number_3' 	=> '1 2 3',
	);

$t->assign( 'formatList', $formatList );

$anchorValues = array( '', 'left', 'right' );
$anchorNames = array( '---', 'left', 'right' );

$t->assign( 'anchorValues', $anchorValues );
$t->assign( 'anchorNames', $anchorNames );
$t->assign( 'styleList', $styleList );
$t->assign( 'menuItemText', 'Link to Menu Item: ' );
$t->assign( 'specialOperationsBackground', '#DDDDDD' );
$t->assign( 'disabledElementColor', '#DDDDDD' );
$t->assign( 'newLineCombo', array( 'Use System Default', 'Convert to Line Breaks', 'Ignore New Lines' ) );
$t->assign( 'linkOptions', array( '_blank'=>'_blank', '_self'=>'_self', '_parent'=>'_parent' ));

// assign 'align' and 'valign' options
$t->assign( 'align', array( 'left', 'center', 'right' ) );
$t->assign( 'valign', array( 'top', 'middle', 'bottom' ) );

// 'no' should have 0 index
$t->assign( 'booleanCombo', array( 'no', 'yes' ) );

$permissions = array(
    'add'    => hasAdminAccess( 'cm_page_add' ),
    'edit'   => hasAdminAccess( 'cm_page_edit' ),
    'delete' => hasAdminAccess( 'cm_page_delete' )
);

$t->assign( 'permissions', $permissions );

if ( !$hasAccess ) {
	$t->assign( 'bodyTemplate', 'manage/authError.tpl' );
}
else {
	$t->assign( 'bodyTemplate', 'manage/editPages.tpl' );
}

if ( $page_id )
	$title = "Edit Page ({$page->fields[title]})";
else
	$title = 'Add Page';

$session->updateLocation( 'page_manager', $title, array( 'page_id' ) );
include_once( '../init_bottom.php' );
$t->display( $templateName );


?>