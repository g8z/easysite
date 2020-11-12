<?php

if ( !defined( 'SMARTY_DIR' ) )
  include_once( '../init.php' );

include_once( INCLUDE_DIR . 'internal/db_items/class.Report.php' ); 

$report = new Report();

$menu = new Menu();

function linkFunction( $row ) {
    return "javascript: writePath(t0.getSelectedNode(), '$row[resource_type]', '$row[resource_id]' );";
}

$pages = $db->getAll( 'select id as resource_id, title, \'page\' as resource_type from '. PAGES_TABLE." where site_key='$site' order by title" );
//$forms = $db->getAll( 'select id as resource_id, form_title as title, \'form\' as resource_type from '. FORMS_TABLE." where site_key='$site' order by form_title" );
$forms = $db->getAll( 'select resource_id, value as title, \'form\' as resource_type from '.SETTINGS_TABLE." where resource_type='form' and property='title' and site_key='$site' order by value" );
$reports = $db->getAll( 'select id as resource_id, name as title, \'report\' as resource_type from '. REPORTS_TABLE." where site_key='$site' order by name" );
$files = $db->getAll( 'select id as resource_id, download_name as title, \'file\' as resource_type from '. FILES_TABLE." where site_key='$site' order by download_name" );

$tempUserFields = array(
	'id' 		    =>'Unique ID',
	'login_id' 		=>'Login ID',
	'login_pass'	=>'Password',
	'first_name'	=>'First Name',
	'last_name'		=>'Last Name',
	'email'			=>'E-Mail',
	'url'			=>'URL',
	'phone'			=>'Phone',
	'address_1'		=>'Address, Line 1',
	'address_2'		=>'Address, Line 2',
	'group_id'		=>'Group',
	'comments'		=>'Comments',
	'date_created'	=>'Creation Date',
	'last_login'	=>'Last Login Date',
	'date_expires' 	=>'Expiration Date',
	'use_expiration'=>'Expires?',
	'user_site_key'	=>'User Site',
	'status'		=>'Status',
	'company'		=>'Company/Organization',
	'fax'			=>'Fax',
	'city'			=>'City',
	'state'			=>'State',
	'zip'			=>'Zip',
	'country'		=>'Country',
	'member_id'		=>'Member ID',
);

$userFields = array();
$recipientFields = array();

foreach ( $tempUserFields as $key=>$title ) {
    $userFields[] = array( 'title'=>htmlentities( $title ), 'resource_type'=>'user_info', 'resource_id'=>$key );
    $recipientFields[] = array( 'title'=>htmlentities( $title ), 'resource_type'=>'recipient', 'resource_id'=>$key );
}

$menuArray = array(
    0 => array( 
        'title' => 'Link To Internal Resource',
        'child' => array( 
            0 => array( 'title'=>'Page',   'child'=>$pages ),
            1 => array( 'title'=>'Form',   'child'=>$forms ),
            2 => array( 'title'=>'File',   'child'=>$files ),
            3 => array( 'title'=>'Report', 'child'=>$reports ),
            4 => array( 'title'=>'Admin Section', 'resource_type'=>'link', 'resource_id'=>'admin' ),
        ),
        
    ),
    1 => array( 'title'=>'Logged User Info', 'child'=>$userFields ),
    2 => array( 'title'=>'Number of Site Visits', 'resource_type'=>'variable', 'resource_id'=>'numvisits' ),
    3 => array( 'title'=>'Last Site Update', 'resource_type'=>'variable', 'resource_id'=>'lastupdate' ),
    4 => array( 'title'=>'Admin Path Box', 'resource_type'=>'path_box', 'resource_id'=>'admin' ),
    5 => array( 'title'=>'Timestamp', 'resource_type'=>'timestamp', 'resource_id'=>'' ),
);


// add 'mode-specific' variables

switch( $_GET['mode'] ) {
	case 'email_blast':
		// add emailing user info
		$menuArray[] = array( 'title'=>'E-Mail Recipient Info', 'child'=>$recipientFields );
		break;
		
}

$nodes = $menu->getTreeNodes( $menuArray, 'linkFunction' );


if ( strlen( $nodes ) )
    $found = 1;

$nodes = 'var TREE_NODES = [' . $nodes . '];';


$t->assign( 'found', strlen( $nodes ) );
$t->assign( 'count', $count );
$t->assign( 'nodes', $nodes );

include_once( '../init_bottom.php' );

$t->assign( 'fromHtmlarea', $_GET['htmlarea'] );
$t->display( 'manage/variableChooser.tpl' );

?>