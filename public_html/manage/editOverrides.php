<?php
if ( !defined( 'SMARTY_DIR' ) )
    include_once( '../init.php' );
    
include_once( INCLUDE_DIR . 'internal/db_items/class.Filter_Override.php' );
include_once( INCLUDE_DIR . 'internal/db_items/class.Form.php' );
include_once( INCLUDE_DIR . 'internal/db_items/class.Report.php' );

$fOver = new Filter_Override();
$form = new Form();
$report = new Report();


// $form - form object
function updateFilterOverrides( $form ) {
    
    global $fOver, $report, $site, $user;
    
    // ------------------------
    // update current overrides
    // ------------------------
    
	if ( $user->isShared( 'form', $_POST['form_id'] ) )
		$formSiteKey = $user->getSharedSiteKey( 'form', $_POST['form_id'] );
	else
		$formSiteKey = $site;

	$overrides = $fOver->loadByParentId( $form->fields[id], array( 'id' ), 'form' );
    
    foreach ( $overrides as $override ) {
        
        $oid = $override[id];
        
        $fields = array(
            'form_id'         => $_POST['form_id'],
            'section_id'      => $_POST['section_id_' . $oid],
            'report_field_id' => $_POST['report_field_id_' . $oid],
            'condition'       => $_POST['condition_' . $oid],
            'skip_empty'      => $_POST['skip_empty_' . $oid],
            'allow_case'      => $_POST['allow_case_' . $oid],
            'site_key'        => $formSiteKey,
        );
        
        $fOver->updateId( $oid, $fields );
    }
    
    
    // ------------------------------------
    // check if we need to add new override
    // ------------------------------------
    
    // assign 0 as is to get new condition fields
    $oid = '0'; 
    
    $fields = array(
        'form_id'         => $_POST['form_id'],
        'section_id'      => $_POST['section_id_' . $oid],
        'report_field_id' => $_POST['report_field_id_' . $oid],
        'condition'       => $_POST['condition_' . $oid],
        'skip_empty'      => $_POST['skip_empty_' . $oid],
        'allow_case'      => $_POST['allow_case_' . $oid],
        'site_key'        => $formSiteKey,
    );
    
    if ( $fields[section_id] && $fields[condition] && $fields[report_field_id] ) {
        
        $fOver->create( $fields );
    }

}

$formId = intval( $_REQUEST['form_id'] );

$shared = getSQLShares( 'form', 'edit' );
$form->load( $formId, array( 'id', 'search_report_id' ) );
$formTitle = $db->getOne( 'select value from '.SETTINGS_TABLE." where resource_type='form' and resource_id='$formId' and property='title' and (site_key='$site' or resource_id in ($shared))" );
$t->assign( 'formTitle', htmlentities( $formTitle ) );

// ---------------------------
// determine what we should do
// ---------------------------

switch ( $_POST[action] ) {
    
    case 'update':
        updateFilterOverrides( $form );
        break;

    case 'delete':
        $fOver->delete( intval( $_POST['deleteSectionVar'] ) );
        updateFilterOverrides( $form );
        break;

}

$report->load( $form->fields['search_report_id'] );

$reportFieldTitles = $report->getFieldTitles( $report->fields['resource'] ); 
$reportFieldIds    = $report->getFieldIds( $report->fields['resource'] ); 
array_unshift( $reportFieldTitles, ' - Select Field - ' );
array_unshift( $reportFieldIds, 0 );
$t->assign( 'reportFieldTitles', $reportFieldTitles );
$t->assign( 'reportFieldIds', $reportFieldIds );

$filterOverrides = $fOver->loadByParentId( $form_id, array(), 'form' );
array_unshift( $filterOverrides, array( 'id'=>0 ) );
$t->assign( 'filterOverrides', $filterOverrides );

$formFieldTitles = $form->getFieldTitles( $formId, 1 );
$formFieldIds = $form->getFieldIds( $formId, 1 );
$t->assign( 'formFieldTitles', $formFieldTitles );
$t->assign( 'formFieldIds', $formFieldIds );

$t->assign( 'formId', $formId );

$t->assign( 'formReturnLink', '[ <a href="'.DOC_ROOT . 'manage/editForms.php?form_id='.$formId.'">Return to Form Manager</a> ]' );

$t->assign( 'conditions', array( '>', '<', '=', '!=', '>=', '<=', 'starts with', 'contains', 'ends with' ) );

$t->assign( 'bodyTemplate', 'manage/editOverrides.tpl' );

$session->updateLocation( 'edit_overrides', "Edit Form Overrides ({$formTitle})", array( 'form_id' ) );
include_once( '../init_bottom.php' );

$t->display( $templateName );


?>