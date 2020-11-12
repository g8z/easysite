<?php

/*************************************************************/
/* INITIALIZATION ROUTINE - DO NOT MODIFY THE CONTENTS BELOW */
/*************************************************************/

error_reporting( 0 );

// for generation time determining

function getmicrotime() {
    list($usec, $sec) = explode(" ",microtime());
    return ((float)$usec + (float)$sec);
}

$timeStart = getmicrotime();


require_once( dirname(__FILE__).'/config.php' );

@ini_set( 'magic_quotes_runtime', 'Off' );

if ( EASYSITE_INSTALLED != 1) {
	die ( '<font face=Arial size=2>EasySite is not installed, or a previous installation was not successfully completed.<br /><br />Please run <a href=install.php>install.php</a> to use EasySite. You will need your database login parameters, and the ability to set the permissions on various files and folders on your server.</font>' );
}

// include Pear classes & classes from phpclasses.org

require_once SMARTY_DIR . 'Config_File.class.php';
require_once SMARTY_DIR . 'Smarty.class.php';
require_once PEAR_DIR . 'DB.php';
//require_once PEAR_DIR . 'Compat.php';
require_once INCLUDE_DIR . 'internal/class.session.php';
require_once INCLUDE_DIR . 'internal/class.skin.php';
require_once INCLUDE_DIR . 'internal/Functions.php';
require_once INCLUDE_DIR . 'internal/class.userAuth.php';
require_once INCLUDE_DIR . 'internal/db_items/class.category.php';
require_once INCLUDE_DIR . 'internal/class.cacher.php';
require_once INCLUDE_DIR . 'internal/class.system.php';
require_once INCLUDE_DIR . 'internal/class.menu.php';
require_once INCLUDE_DIR . 'internal/class.error.php';

require_once INCLUDE_DIR . 'internal/db_items/class.DB_Item.php';
require_once INCLUDE_DIR . 'internal/db_items/class.Ordered_DB_Item.php';


// accomodate for magic quotes on or off (add slashes if not present)

if ( !get_magic_quotes_gpc() ) {
   function addslashes_deep($value) {
       return is_array($value) ? array_map('addslashes_deep', $value) : addslashes($value);
   }

   $_POST 	= array_map('addslashes_deep', $_POST);
   $_GET 	= array_map('addslashes_deep', $_GET);
   $_COOKIE	= array_map('addslashes_deep', $_COOKIE);
}


// This is necessary, because some PHP versions
// seem to read directly from input, but not from $_POST, $_GET, etc
// this fixes this issue

$pp = $_POST;
$gg = $_GET;

extract( $pp );
extract( $gg );

// create database connection & Smarty instance

$dsn = DB_TYPE . '://' . DB_USER . ':' . DB_PASS . '@' . DB_HOST . '/' . DB_NAME;

function errhndl ( $err ) {

	if ( $err->code == -18 ) {

		echo '<font face=Arial><h3>You may need to update your EasySite configuration</h3><font size=2>The system has attempted to access a non-existing database table. This could mean that one or more tables have been dropped, or the login credentials have changed for the database. To update your configuration, edit the config.php file on your server with the new credentials, or re-install EasySite using the install.php file which comes with the EasySite distribution.<br /><br />The error shown below was produced by your database server. <br /><hr></font></font>';
	}

	echo '<pre>' . $err->message;
	print_r( $err );
	die();
}



PEAR::setErrorHandling( PEAR_ERROR_CALLBACK, 'errhndl' );

$db = @DB::connect( $dsn );

$t = new Smarty;

$t->template_dir = TEMPLATE_DIR;
$t->compile_dir = TEMPLATE_C_DIR;
$t->cache_dir = CACHE_DIR;


$db->setFetchMode( DB_FETCHMODE_ASSOC );

// set commonly used template paths
$t->assign( 'docroot', DOC_ROOT );
$t->assign( 'admindir', ADMIN_DIR );
$t->assign( 'defaultSite', DEFAULT_SITE );

$t->caching = false;

// true only for development puproses
$t->force_compile = false;
$t->compile_check = true;

$adminPath = DOC_ROOT . ADMIN_DIR . '/index.php';

$system = new System( $db );

$skin = new ES_Skin( $db, 'default' );

$session = new ES_Session( $db, SESSIONS_TABLE );
$session->setId( $_REQUEST['PHPSESSID'] );
$sdata = $session->get();

// determine which site we are in, for multi-website systems
if ( $_GET['site'] ) {

	// logout
	$session->end( $_REQUEST['PHPSESSID'] );

}


// some special conditions apply to the demo server (for security purposes)
// you may ignore this section

if ( DEMO_MODE ) {
	@include_once( 'demo.php' );
}

$result = $system->getSiteData( $_GET['site'] );

$site = $system->site;

// check if we need update site counter
if ( !$_SESSION['counter_updated'] ) {
	$db->query( 'update '. SITES_TABLE ." set counter=counter+1 where site_key='$site'" );
	$system->siteData['counter']++;
	$_SESSION['counter_updated'] = 1;
}

// for image caching
$c = new Cacher( FULL_PATH . TEMP_DIR, DOC_ROOT . TEMP_DIR, $site );
$c->_db = $db;


if ( !isError( $result ) ) {

	$siteData = $result;

	$_SESSION['site'] = $site;

	if ( !$_SESSION['shares'] ) {

		$_SESSION['shares']['determined'] = 1;

		// ----------------------------------------
		// determine resources shared for this site
		// ----------------------------------------

		$adminGroup = $db->getOne( 'select group_id from '.USERS_TABLE." where id='{$siteData[admin_id]}'" );
		$sharedResources = $db->getAll( 'select * from '.SHARES_TABLE." where ( group_id=".ALL_TARGETS." or group_id='$adminGroup' or ( (user_id=".ALL_TARGETS." or user_id='$siteData[admin_id]') and override=1 ) ) AND ( view=1 ) AND ( site_key!='$site')" );

		/**
		 * Determines 'priprity' for the share settings
		 * Setting with higher priority overrides setting with lower priority
		 */
		function getPriority( $targetType, $targetId ) {

			if ( $targetType == 'group_id' && $targetId != ALL_TARGETS )
				$priority = 1;

			if ( $targetType == 'group_id' && $targetId == ALL_TARGETS )
				$priority = 2;

			if ( $targetType == 'user_id' && $targetId != ALL_TARGETS )
				$priority = 3;

			if ( $targetType == 'user_id' && $targetId == ALL_TARGETS )
				$priority = 4;

			return $priority;
		}

		foreach ( $sharedResources as $num=>$resource ) {

			if ( $resource['group_id'] )
				$targetType = 'group_id';
			else
				$targetType = 'user_id';

			$targetId = $resource[$targetType];

			$priority = getPriority( $targetType, $targetId );

			if ( $priority > $shares[$resource['resource_type']][$resource['resource_id']]['priority'] )
				$shares[$resource['resource_type']][$resource['resource_id']] = array( 'view'=> $resource[view], 'edit'=>$resource[edit], 'priority'=>$priority, 'site_key'=>$resource['site_key'] );
		}

		$_SESSION['shares'] = $shares;
	}

	// update the saved session data
	//$session->set( $_SESSION );
}
else {
	// display an error

	$result->messageDie();
}


// used by all files to call main template file

$templateName = $system->getTemplateName();


/**
 * Determines if the currently logged-in user (as defined by $_SESSION) has
 * administrative access to the specified admin tool (as identified by $tool)
 */
function hasAdminAccess( $tool ) {
	global $site;

	return $_SESSION['cm_auth'][$tool] == $site;
}

// let is stay for compatibility
$page_id = $system->getIdFromKey( 'page', $page_id );
$form_id = $system->getIdFromKey( 'form', $form_id );

if ( $_GET['page_sef_title'] ) {
	$page_id = $system->getIdFromSefTitle( 'page', $_GET['page_sef_title'] );
}

if ( $_GET['form_sef_title'] ) {
	$form_id = $system->getIdFromSefTitle( 'form', $_GET['form_sef_title'] );
}

if ( $_GET['report_sef_title'] ) {
	$report_id = $system->getIdFromSefTitle( 'report', $_GET['report_sef_title'] );
}

if ( !$page_id && !$form_id ) {
	if ( $system->siteData[default_resource_type] == 'page' ) {
		$page_id = $system->siteData['default_resource_id'];
	}
	else if ( $system->siteData[default_resource_type] == 'form' ) {
		$form_id = $system->siteData['default_resource_id'];
	}
}

$_GET['page_id'] = $page_id;
$_GET['form_id'] = $form_id;

$system->page_id = $page_id;
$system->form_id = $form_id;
$system->report_id = $report_id;
$location = $system->getLocation();

// get the skin_id that will be used in getSettings() and getStyles() functions
$system->getSkinId( $page_id, $form_id );

$page_id_loaded = $page_id;
$form_id_loaded = $form_id;

$cssStyles = $system->getStyles();

// used for deterining popup bg color
// in case of main & screen are bg colors are not set
$normalFontColor = $db->getOne( 'select color from '.STYLES_TABLE." where name='.normal'" );
$normalFontColor = substr( $normalFontColor, 1 );
$normalFontColor = base_convert( $normalFontColor, 16, 10 );
$t->assign( 'popupBG', '#' . base_convert( $normalFontColor ^ 0xFFFFFF, 10, 16 ) );


// get the site last access date and the site counter, for use
// with <!--numvisitors--> and <!--lastupdate-->

$system->getSettings();

$siteLastUpdate = date( $system->settings[php_date], strtotime( $siteData[last_updated] ) );
$siteCounter = $siteData[counter];

$tempSiteSettings = $system->settings;


// properties for the popup help window
$helpOptions = array(
	'width'		=> 500,
	'height'	=> 500,
	'options'	=> 'resizable,scrollbars',
	'url'		=> DOC_ROOT . ADMIN_DIR . '/help.php'
);

$t->assign( 'helpSymbol', '<img src=' . DOC_ROOT . 'images/help.gif alt="What\'s This?" border=0>' );
$t->assign_by_ref( 'help', $helpOptions );

// create a new skin object that will be accessible by all pages

$skin = new ES_Skin( $db, $site );

// fetch menu layer information


if ( $siteData[logout_page_id] == 0 )
	$logoutLink = DOC_ROOT . 'logout.php';
else
	$logoutLink = DOC_ROOT . 'index.php?page_id=' . $siteData[logout_page_id];


// only used by the content management templates - a return link to index
$t->assign( 'adminReturnLink', '[ <a href=' . DOC_ROOT . ADMIN_DIR . '/index.php>Return to the Content-Management Tools Index</a> ]' );
$t->assign( 'logoutLink', '[ <a href=\'' . $logoutLink . '\'>Logout</a> ]' );
$t->assign( 'userGuideLink', '[ <a target="_blank" href="http://www.tufat.com/docs/easysite/index.html">User Guide</a> ]' );

$OS = $system->getOS();
$crlf = $system->getCrlf();


// check for existence of install.php (security breach)

if ( file_exists( FULL_PATH . 'install.php' ) ) {
	$t->assign( 'installFileError', true );
}


$t->assign( 'lastupdate', $siteLastUpdate );
$t->assign( 'numvisitors', $siteCounter );
$t->assign( 'adminPath', $adminPath );// full path to content-management tools

/*** USER AUTHENTICATION ***/

$user = new UserAuth( $db );

if ( $_POST['es_login'] ) {
	// attempt to login the user

	if ( !$user->login( $_POST ) )
		$loginError = LOGIN_NOT_FOUND;

	if ( $user->isExpired() )
		$loginError = LOGIN_EXPIRED;

	if ( $loginError ) {
		$session->updateLocation( 'cm_index', 'Admin Index', array(), 'http://' . $_SERVER['SERVER_NAME'] . DOC_ROOT . 'manage/index.php' );
		$t->assign( 'login_error', $loginError );
		include( FULL_PATH . 'getForm.php' );
		exit;
	}
}

// check if we are in admin panel
if ( stristr( $_SERVER['PHP_SELF'], '/' . ADMIN_DIR . '/' ) ) {
	$access = $system->siteData['admin_id'] == $user->id;
	$access |= $db->getOne( 'select 1 from '. PERMISSIONS_TABLE ." where resource_type like 'cm%' and group_id='{$user->group_id}' and site_key='$site'" );

	if ( !$access ) {

		$session->updateLocation( 'cm_index', 'Admin Index', array(), 'http://' . $_SERVER['SERVER_NAME'] . DOC_ROOT . 'manage/index.php' );
		include( FULL_PATH . 'getForm.php' );
		exit;
	}
}

// filtering output

$compressOutput =
	$system->settings['compress_output'] == 'yes' &&
	strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip') &&
	function_exists( 'gzencode' ) &&
	!ini_get('zlib.output_compression');


if ( !defined( 'HELPER' ) )
{
	function do_filter_output() {

	    ob_end_flush();
	}

	//function compress_output($output) {
	    //return gzencode($output);
	//}

	function filter_output( $output ) {

		global $compressOutput, $stripOutput;

		if ( $stripOutput ) {
	        $output = html2text($output);
		}

		if ( $compressOutput )
	    	$output = gzencode($output);


	    return $output;
	}

	if ( $compressOutput )
		header("Content-Encoding: gzip");


	ob_start("filter_output");

	register_shutdown_function("do_filter_output");
}

?>