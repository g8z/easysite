<?php

error_reporting( 0 );

$version = '3.2.7';

session_start();

// -------------------------------------------
// firstly check for some very important files
// -------------------------------------------

$root = dirname(__FILE__) . '/';
@ini_set( 'magic_quotes_runtime', 'Off' );
$fileList = array(
	'install_files',
	'sql',
	'sql/sample_data.sql',
	'sql/tables.sql',
	'cache',
	'includes',
	'includes/internal/Functions.php',
	'manage',
	'modules',
	'templates',
	'libs',
	'libs/Smarty',
	'libs/Pear',
	'config.php',
	'index.php',
	'getForm.php',
	'init.php',
	'init_bottom.php',
	'login.php'
);


foreach ( $fileList as $idx=>$file ) {
	if ( !file_exists( $root . $file ) ) {
		echo '<h1>EasySite Installation is not complete or is corrupted!</h1>';
		$resource = ( count( explode( '.', $file ) ) > 1 ) ? 'file' : 'directory';
		echo "<b>/$file</b> $resource should be present on your server.<br /><br />Please ensure that you have uploaded all the nesessary files and <a href='install.php'>run the installer</a> again.";
		die();
	}
}

include 'install_files/consts.php'; 	// Constants
include 'install_files/funcs.php';		// Needed functions
include 'install_files/header.tpl';		// HTML Header

if ( (!isset( $_GET['step'])) || ($_GET['step'] < 1 || $_GET['step'] > 10) )
	$step = 1;
else
	$step = $_GET['step'];


if ($step > 1)
{
	// Can use templates
	$full_path = dirname(__FILE__) . '/';

	define ( 'SMARTY_DIR', $full_path . 'libs/Smarty/' );
	define ( 'TEMPLATE_DIR', $full_path . 'templates/' );
	define ( 'TEMPLATE_C_DIR', $full_path . 'templates_c/' );
	define ( 'PEAR_DIR', $full_path . 'libs/Pear/' );
	define ( 'CACHE_DIR', $full_path . 'cache/' );
	define ( 'INCLUDE_DIR', $full_path . 'includes/' );
	define ( 'DOC_ROOT', $full_path );

	require_once SMARTY_DIR . 'Config_File.class.php';
	require_once SMARTY_DIR . 'Smarty.class.php';
	require_once INCLUDE_DIR . 'internal/Functions.php';

	//ini_set( 'include_path', PEAR_DIR );

	require_once( PEAR_DIR . 'DB.php' );

	//	PEAR::setErrorHandling( PEAR_ERROR_CALLBACK, 'errhndl' );

	$t = new Smarty;
	$t->force_compile = true;

	$t->template_dir = TEMPLATE_DIR;
	$t->compile_dir = TEMPLATE_C_DIR;
	$t->cache_dir = CACHE_DIR;
}


/**
 ** FTP step - Changing file permissions automatically by the installer.
 ** This step comes before step 1 so it can modify $step and recheck settings.
 **/
if ( $step == 1.5 ) {

	if ( !$_POST['ftpHost'] ) {
		/**
		 ** Display FTP form information.
		 **/
		include 'install_files/step1.5.tpl';
	} else {
		/**
		 ** Do Chmod change and return to step 1.
		 **/

		@set_time_limit(150);

		include 'install_files/ftp.inc';

		$f = new FTP;

		if ( $f->connect ( $_POST['ftpHost'] ) == false )
			FTPerrhndl ( 'Unable to connect to FTP host.<br />Please check your FTP login information and click continue.' );

		if ( !$f->authenticate ( $_POST['ftpUser'] , $_POST['ftpPassword'] ) )
			FTPerrhndl ( 'Invalid username or password.<br />Please check your FTP login information and click continue.' );

		if ( !$f->chdir ( $_POST['ftpPath'] ) )
			FTPerrhndl ( 'Invalid FTP path.<br />Please check your FTP path and click continue.' );

		if ( !$f->chmod ( 'config.php' , '0666' ) || !$f->chmod ( 'temp/' , '0777' ) ||
			 !$f->chmod ( 'templates_c' , '0777' ) || !$f->chmod ( 'cache' , '0777' ) )
			FTPerrhndl ( 'The FTP login information that you provided does not allow sufficient access to change the necessary file permissions.<br />Please check the file ownership settings, change the permissions manually with an FTP client, or contact your website hosting support.' );

		if ( !$FTPerr )
			$step = 1;
	}
}

// First step - general check

if ($step == 1) {

	$_SESSION = array();

	include 'install_files/step_1.tpl';

	$canContinue = 1;

	$canContinue = isWriteable ( $canContinue, 'config.php', 0666, 'config.php file' );
	$canContinue = isWriteable ( $canContinue, 'temp/', 0777, 'temp folder' );
	$canContinue = isWriteable ( $canContinue, 'templates_c/', 0777, 'templates_c folder' );
	$canContinue = isWriteable ( $canContinue, 'cache/', 0777, 'cache folder' );



	// check if there are file uploads enabled

	$good = ini_get( 'file_uploads' );
	Message ( 'File Uploads Allowed:', $good );
	$canContinue = $canContinue && $good;

	$ftpCan = $canContinue;

	$good = function_exists( 'mysql_connect' ) ? 1 : 0;
	$canContinue = $canContinue && $good;

	Message ( 'MySQL support exists: ', $good );

	$good = phpversion() >= '4.1.2' ? 1 : 0;
	$canContinue = $canContinue && $good;

	Message ( 'PHP version >= 4.1.2: ', $good );

	echo '</table>';

	if ( $canContinue) {
		echo  '<tr><td colspan="2" align="right"><br />Congratulations! You may continue the installation.<br /><br /><input type="button" name="continue" value="Continue >>" onclick="javascript:document.location.href=\'?step=2\'">';
	} else {
		echo  '<tr><td colspan="2" ><br />The installer has detected some problems with your server environment, which will not allow EasySite to operate correctly.<br /><br />Please correct these issues and then refresh the page to re-check your environment.<br /><br />';

		if ( function_exists ( 'ftp_connect' ) ) {
			echo 'Some of the problems that have been detected are file permission problems. You may allow the installer to correct these problems automatically via FTP.<br /><br />If you want the installer to make the necessary corrections, <a href="?step=1.5">click here</a>. Otherwise, you must correct these issues manually, and then refresh this page after the corrections have been made. Most FTP programs, like WS_FTP, AbsoluteFTP, and CuteFTP, allow users to change the permissions of files and folders on the server.<br /><br />';
		}
		echo '<br /><input type="button" name="continue" value="Continue >>" onclick="javascript:alert(\'Please correct the above problems before continuing.\')"></td></tr>';
	}
}

// Second step - database login information

if ( $step == 2 )
{

	$t->assign( 'errorConnection', 0 );
	include 'install_files/step_2.php';
}

// Third step - test connection

if ( $step == 3 )
{
	include 'install_files/step_3.php';
}


// Fourth Step - admin account

if ( $step == 4 ) {
	include 'install_files/step_4.php';
}

// Fifth step - mail settings

if ($step == 5)
{
	$sendMailPath = @ini_get( sendmail_path );

	// if sendmail is found, then parse it to remove sendmail options

	if ( $sendMailPath ) {
		$sendMailPathParts = explode( ' ', $sendMailPath );
		$sendMailPath = $sendMailPathParts[0];
	}

	$t->assign( 'sendMailPath', $sendMailPath );

    $t->assign( 'formatValues', array( 'text', 'html') );
    $t->assign( 'formatNames', array(  'Text', 'HTML' ) );
    $t->assign( 'typeValues', array( 'mail', 'sendmail', 'smtp' ) );
    $t->assign( 'typeNames', array(  'Standard Mail', 'Sendmail', 'SMTP' ) );

    $t->display( 'pages/install5.tpl' );
}

// Fifth step - writing mail settings
if ($step == 6)
{
	// clear the cache & template_c before starting EasySite

	$t->clear_all_cache();
	$t->clear_compiled_tpl();

	// remove all files from image cache
	include_once( 'includes/internal/class.cacher.php' );
	$c = new Cacher();
	$c->clear();

    extract ($_POST);

    if (!isset($smtpAuth))
		$smtpAuth = 0;

	// Replacing config variables

	$replace = array(
		'MAIL_FORMAT'		=> $mailFormat,
		'MAIL_TYPE'		=> $mailType,
		'SMTP_HOST'		=> $smtpHost,
		'SMTP_PORT'		=> $smtpPort,
		'SMTP_AUTH'		=> $smtpAuth,
		'SMTP_USER'		=> $smtpUser,
		'SMTP_PASS'		=> $smtpPassword,
		'SM_PATH'		=> $smPath );

	$configData = getConfigData( $replace );

	$t->assign( 'configCreated', writeConfig( $configData ) );
	$t->display( 'pages/install6.tpl' );
}

unset( $db );

include 'install_files/footer.tpl'; //HTML Footer.
?>