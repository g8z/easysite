<?php

$manage = true;

require '../config.php';

$id = intval( $id );

// get all of the polls by title

$pollsTable = POLLS_TABLE;
$formsTable = FORMS_TABLE;
$pollResultsTable = POLLRESULTS_TABLE;

$hasAccess = hasAdminAccess( 'cm_module' ) && hasAdminAccess( 'cm_'.$moduleKey ) &&
           ( hasAdminAccess( 'cm_'.$moduleKey.'_add_polls' ) || hasAdminAccess( 'cm_'.$moduleKey.'_edit_polls' ) );


if ( $hasAccess ) {

// check for a change to the active state for this poll

if ( $id ) {

    $hasAccess = 0;

	if ( $reset && hasAdminAccess( 'cm_'.$moduleKey.'_reset_polls' ) ) {

        $hasAccess = 1;

		// remove any result data for this poll

		$db->query( "delete from $pollResultsTable where poll_id = '$id' and site_key = '$site'" );

		$t->assign( 'pollReset', $db->getOne( "select title from $pollsTable where id = '$id' and site_key = '$site'" ) );
	}
	else if ( $drop && hasAdminAccess( 'cm_'.$moduleKey.'_delete_polls' ) ) {

        $hasAccess = 1;

		$db->query( "delete from $pollResultsTable where poll_id = '$id' and site_key = '$site'" );

		$db->query( "delete from $pollsTable where id = '$id' and site_key = '$site'" );
	}
	else if ( hasAdminAccess( 'cm_'.$moduleKey.'_reset_polls' ) ) {
		$hasAccess = 1;
		$db->query( "update $pollsTable set active = '$active' where site_key = '$site' and id = '$id'" );
	}
}

$polls = $db->getAll( "select a.id, a.active, a.title, s.value as form_title, s.resource_id as form_id, a.added_on from $pollsTable a, ".SETTINGS_TABLE." s where a.form_id = s.resource_id and a.site_key = '$site' and s.site_key = '$site' and s.resource_type='form' and s.property='title' order by a.added_on desc" );

// for each poll, check to see if any poll data exists...
// if no data, then the 'reset' option will be a non-link

foreach( $polls as $index => $row ) {
	$numResults = $db->getOne( "select count(id) from $pollResultsTable where poll_id = '$row[id]' and site_key = '$site'" );

	$row['results'] = $numResults;
	$polls[$index] = $row;
}

$t->assign( 'polls', $polls );

if ( $hasAccess )
    $t->assign( 'bodyTemplate', 'modules/poll/manage/index.tpl' );
}

if ( !$hasAccess )
    noAccessMessage( 'modules/poll/navigation.tpl' );


$session->updateLocation( 'poll_index', 'Poll Index' );
include_once( FULL_PATH . 'init_bottom.php' );

$t->display( $templateName );


?>