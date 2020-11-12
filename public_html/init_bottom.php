<?php

// --------------------------------------------------
// this file should included at the end of every file
// --------------------------------------------------

// determine the 'back location' for this page

$t->assign( 'path', $session->getPath() );
$t->assign( 'prevLocation', $session->prevLocation['url'] );
$t->assign( 'prevZone', $session->prevLocation['zone'] );
$t->assign( 'pathway', $t->fetch( 'manage/adminPath.tpl' ) );

// fetch all of the layers for this page & for the background template

$layerData = $system->getAvailableLayers( $page_id, $form_id );
$t->assign( 'layerData', $layerData );

// used for javascript auto-center feature
assignCenterVariables( $system->settings[body_x], $system->settings[body_w], $layerData );


// save session data
$session->set( $_SESSION );

// footer text
$system->settings[footer][content] = $system->replaceInternalVariables( $system->settings[footer][content] );

$t->assign_by_ref( 'settings', $system->settings );
$t->assign( 'cssStyles', $system->styles );
$t->assign( 'site_key', $system->site );


// check if we have all nesessary files cached
// like styles menus as auto-center javascript

$lastChange = time(); //max( $system->settingsLastChange, $system->stylesLastChange );
$filesToCache = array(
  'system'=>'system',
	'areaStyles'=>'styles/areaStyles',
	'systemStyles'=>'styles/systemStyles',
);

foreach ( $filesToCache as $dest=>$source ) {
	checkCache( $lastChange, $dest, $source );
}

$t->assign( 'skin_id', $system->skin_id );
$t->assign( 'cssLastChange', $lastChange );

$menus = array();

// populate the menus array with all the menus available in this site

include( ROOT_DIR . "menu.php" );
$t->assign( 'menus', $menus );

$timeEnd = getmicrotime();
$overalTime = $timeEnd - $timeStart;
//echo $overalTime."_";
//echo @count( $system->queries );

// temporary 'logger'
/*
$url = addslashes('http://' . $_SERVER['SERVER_NAME'] . $_SERVER['PHP_SELF']);
$vars = array( 'get'=>$_GET, 'post'=>$_POST, 'cookie'=>$_COOKIE );
$vars = serialize( $vars );
$scount = $db->getOne( 'select count(*) from '.SETTINGS_TABLE );
$queries = addslashes(serialize( $system->queries ));
$db->query( 'insert into '.DB_PREFIX."_access (ip,user_agent,url,vars,settings_count, queries) values ('$_SERVER[REMOTE_ADDR]','".addslashes($_SERVER[HTTP_USER_AGENT])."', '$url', '$vars', '$scount', '$queries')" );
*/


?>