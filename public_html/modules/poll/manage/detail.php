<?php

$manage = true;

require '../config.php';

// get all of the polls by title

$pollsTable = POLLS_TABLE;
$formsTable = FORMS_TABLE;
$pollResultsTable = POLLRESULTS_TABLE;

$hasAccess = hasAdminAccess( 'cm_module' ) && hasAdminAccess( 'cm_'.$moduleKey ) &&
           ( hasAdminAccess( 'cm_'.$moduleKey.'_add_polls' ) || hasAdminAccess( 'cm_'.$moduleKey.'_edit_polls' ) );


// check if we are adding poll and we have permissions for add
if ( $hasAccess && !isset( $_GET[id] ) && !isset( $_POST[id] ) && !$formIsSubmitted )
    $hasAccess = hasAdminAccess( 'cm_'.$moduleKey.'_add_polls' );

if ( $hasAccess ) {

// check for a change to the active state for this poll

if ( $formIsSubmitted ) {

    $hasAccess = 0;

    //print_r( $_POST );

	if ( !$id ) {

        if ( $hasAccess = hasAdminAccess( 'cm_'.$moduleKey.'_add_polls' ) ) {
        	// add a new record

    		$db->query( "insert into $pollsTable ( id, site_key, added_on ) values ( NULL, '$site', now() )" );

	    	$id = $db->getOne( "select max(id) from $pollsTable where site_key = '$site'" );
        }
	}

    if ( $id && ( $hasAccess || hasAdminAccess( 'cm_'.$moduleKey.'_edit_polls' ) ) ) {

        $hasAccess = 1;

        // update record with an existing id value

    	$db->query( "update $pollsTable set
    		title 		= '$title',
    		form_id 	= '$form_id',
    		popup 		= '1',
    		width 		= '$pwidth',
    		height 		= '$pheight',
    		group_id 	= '$group_id'
    			where id = '$id'" );
    }
}

// get the data for the currently selected poll

$data = $db->getRow( "select * from $pollsTable where id = '$id' and site_key = '$site'" );

$t->assign( 'data', $data );

$formId = $data[form_id];

// get the result data for this poll

$resultData = $db->getAll( "select * from $pollResultsTable where poll_id = '$id' and site_key = '$site' order by label, data" );

// compute the poll summaries...

$summaries = array();

$formLabels = array();

foreach( $resultData as $index => $row ) {

	if ( !$formLabels[ $row[label] ] ) {

		/*
		$label = $db->getOne( 'select label from ' . FORMSECTIONS_TABLE . " where form_id = '$formId' and id = '$row[form_section]' and site_key = '$site'" );
		*/

		$formLabels[ $row[label] ] = $row[label];
	}

	if ( !is_array( $summaries[ $formLabels[ $row[label] ] ] ) )
		$summaries[ $formLabels[ $row[label] ] ] = array();

	$summaries[ $formLabels[ $row[label] ] ] [$row[data]]++;
}


$groups = $db->getAll( "select id, name from " . GROUPS_TABLE . " where site_key = '$site' order by name" );

foreach( $groups as $group ) {
	$groupList[$group[id]] = 'Group - ' . $group[name];
}

$t->assign( 'groups', $groupList );

// loop through the summary data one more time to totals
// (will be used in the template to get the percentage)

foreach( $summaries as $key => $row ) {

	$total = 0;

	foreach( $row as $subkey => $subtotal ) {
		$total += $subtotal;
	}

	$summaries[ $key ] = array( $row, $total );
}

// get all available forms for purposes of the jump menu
//$forms = $db->getAll( 'select id, form_title as title from ' . FORMS_TABLE . " where site_key = '$site' order by form_title" );
$forms = $db->getAll( 'select resource_id as id, value as title from '.SETTINGS_TABLE." where resource_type='form' and property='title' and site_key='$site' order by value" );

$formData = array();

foreach( $forms as $index => $form ) {
	$formData[$form['id']] = $form['title'];
}

$t->assign( 'forms', $formData );

$t->assign( 'results', $summaries );


if ( $hasAccess )
    $t->assign( 'bodyTemplate', 'modules/poll/manage/detail.tpl' ); // insert the body content

} // if has access

if ( !$hasAccess )
    noAccessMessage( 'modules/poll/navigation.tpl' );

$session->updateLocation( 'edit_poll', 'Poll Details', array( 'id' ) );
include_once( FULL_PATH . 'init_bottom.php' );

$t->display( $templateName );

?>