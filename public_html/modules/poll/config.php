<?php

if ( $manage ) {
	require '../../../init.php';
    include_once '../../include/functions.php';
} else {
	require '../../config.php';
	require '../../init.php';
    include_once '../include/functions.php';
}

// these links are common to all admin pages for the realty module

$myPolls = '[ <a href=' . DOC_ROOT . MODULES_DIR . '/poll/manage/index.php>My Polls</a> ]';
$newPoll = '[ <a href=' . DOC_ROOT . MODULES_DIR . '/poll/manage/detail.php>New Poll</a> ]';

//$modView = '[ <a target=_blank href=' . DOC_ROOT . MODULES_DIR . '/poll/index.php>My Polls</a> ]';

$t->assign( 'myPolls', $myPolls );
$t->assign( 'newPoll', $newPoll );

// determine the skin for this module

$moduleKey = 'poll';

$moduleInfo = $db->getRow( 'select skin_id from ' . MODULES_TABLE . " where module_key = '$moduleKey' and site_key = '$site'" );

if ( $moduleInfo[skin_id] > 0 ) {
	$skin->loadAll( $moduleInfo[skin_id] );
}

// get the category list (used in all forms, search + manage forms)

$add_fields = array( 'site_key' );
$add_values = array( $site );

?>