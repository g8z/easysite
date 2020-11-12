<?php

$manage = true;
include_once( '../../config.php' );

include_once( INCLUDE_DIR . 'internal/class.File.php' );

$man_id = intval( $_REQUEST['man_id'] );

if ( $_POST['delete'] ) {
	
	$db->query( 'delete from '. MANUFACTURERS_TABLE. " where id='$man_id'" );
	
	// delete cached logo if exists
	$c->_table = MANUFACTURERS_TABLE;
	$c->_field = 'logo';
	$c->_id = $man_id;
	$c->remove();
}

if ( $_POST['formIsSubmitted'] ) {
	
	$logoContents = '';
	
	$logo = new File( 'man_logo' );
	if ( $logo->isUploaded() ) {
		$logoContents = $logo->getContent();
		$file->delete();
	}
	
	if ( !$logoContents ) {
		// do not rewrite logo with the dummy one
		$logoContents = $db->getOne( 'select logo from '. MANUFACTURERS_TABLE." where id='$man_id'" );
	}
	else {
		// we are changing logo
		// remove from cache
		$c->_table = MANUFACTURERS_TABLE;
		$c->_field = 'logo';
		$c->_id = $man_id;
		$c->remove();
	}
	
	if ( $man_id == 0 ) {
		
		// add new manufacturer
		
		$db->query( 'insert into '.MANUFACTURERS_TABLE ." (site_key) values ('$site')" );
		$man_id = $db->getOne( 'select max(id) from '. MANUFACTURERS_TABLE );
	}
	
	$db->query( 'update '. MANUFACTURERS_TABLE." set
		name = '$_POST[man_name]',
		url  = '$_POST[man_url]',
		logo = '$logoContents'
		where id='$man_id'" );
}

$man = $db->getRow( 'select * from '. MANUFACTURERS_TABLE ." where id='$man_id'" );

$t->assign( 'man', $man );

// get list of all manufacturers

$manValues = array( 0 );
$manTitles = array( '- New Manufacturer -' );

$manList = $db->getAll( 'select id, name from '. MANUFACTURERS_TABLE ." where site_key='$site'" );

foreach( $manList as $num=>$manItem ) {
	$manValues[] = $manItem[id];
	$manTitles[] = $manItem[name];
}

$t->assign( 'manValues', $manValues );
$t->assign( 'manTitles', $manTitles );

include_once( FULL_PATH . 'init_bottom.php' );

$t->assign( 'bodyTemplate', 'modules/gallery/manage/ecommerce/manufacturers.tpl' );
$t->display( $templateName );

?>
