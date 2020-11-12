<?php

if ( !defined( 'SMARTY_DIR' ) )
	include_once( 'init.php' );

// check to see if we should display a popup poll

// get a list of all active polls
$form_id = intval( $_GET['form_id'] );

displayPolls();

// if no page or form is specified, then get the default page or form

if ( !$page_id && !$form_id ) {

	// update the site counter

	$siteCounter = 1 + $siteData[counter];

	$db->query( 'update ' . SITES_TABLE . " set counter = '$siteCounter' where site_key = '$site' limit 1" );

	if ( $siteData[default_resource_type] == 'page' ) {
		$page_id = $siteData[default_resource_id];
	}
	else if ( $siteData[default_resource_type] == 'form' ) {
		header( 'Location:getForm.php?' . 'form_id=' . $siteData[default_resource_id] );
		exit;
	}
	else if ( $siteData[default_resource_type] == 'module' ) {

		// get the name of the desired module
		$moduleName = $db->getOne( 'select module_key from ' . MODULES_TABLE . " where id = '$siteData[default_resource_id]'" );

		// presumes that the directory that the module is installed in = the module name
		header( 'Location:' . MODULES_DIR . '/' . $moduleName . '/index.php' );
		exit;
	}
	else {
		$page_id = $db->getOne( 'select min(id) from ' . PAGES_TABLE . " where site_key = '$site'" );
	}
}
if ( !$user->hasAccess( 'page', $page_id ) )
	loginError( ACCESS_DENIED );

// get the page name for the <title> tag

$shared = getSQLShares( 'page' );
$pageInfo = $db->getRow( 'select title, meta_keywords, meta_desc, counter from ' . PAGES_TABLE . " where id = '$page_id' and (site_key = '$site' or id in ($shared))" );

$t->assign( 'metaKeywords', $pageInfo['meta_keywords'] );
$t->assign( 'metaDescription', $pageInfo['meta_desc'] );
$t->assign( 'title', $pageInfo[title] );

$data = $db->getAll( "select * from " . SECTIONS_TABLE . " where page_id = '$page_id' and (site_key = '$site' or page_id in ($shared)) order by _order" );

// massage the data a bit to account for bulleted lists, etc.

$data = $system->applyFormat( $data );

// update the counter for this page

$newCount = $pageInfo[counter] + 1;

$db->query( 'update ' . PAGES_TABLE . " set counter = '$newCount' where id = '$page_id' and (site_key = '$site' or id in ($shared))" );

// fetch all data for this page
$t->assign( 'data', $data );

// if this page is the logout page, then also terminate the session
// HOWEVER: do not logout if we are simply previewing a skin!!

if ( $page_id == $siteData[logout_page_id] && $siteData[logout_page_id] ) {
	$session->end( $_REQUEST['PHPSESSID'] );
}

include_once( 'init_bottom.php' );

if ( $poll ) {
	$t->display( 'popupHeader.tpl' );
	$t->display( 'pages/index.tpl' );
	$t->display( 'popupFooter.tpl' );
}
else {
    
	$t->assign( 'bodyTemplate', 'pages/index.tpl' );
    $t->display( $templateName );
        
}



?>