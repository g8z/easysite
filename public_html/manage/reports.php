<?php
if ( !defined( 'SMARTY_DIR' ) )
    include_once( '../init.php' );

include_once( INCLUDE_DIR . 'internal/db_items/class.Report.php' );
include_once( INCLUDE_DIR . 'internal/db_items/class.Form.php' );
include_once( INCLUDE_DIR . 'internal/db_items/class.List_Item.php' );
include_once( INCLUDE_DIR . 'internal/db_items/conditionFunctions.php' );
include_once( INCLUDE_DIR . 'internal/db_items/reportFunctions.php' );
include_once( INCLUDE_DIR . 'internal/db_items/class.Report_Group.php' );
include_once( INCLUDE_DIR . 'internal/db_items/class.Report_Field.php' );

$report = new Report();
$form   = new Form();
$fSect  = new Form_Section();
$rCond  = new Report_Condition();
$rField = new Report_Field();
$fGroup = new Form_Group();
$lItem  = new List_Item();
$rGroup = new Report_Group();
$fSubm  = new Submission();

$reportId = intval( $_REQUEST['id'] );
$resource = $_POST['resource'];

$report->load( $reportId );

if ( $reportId ) {
    if ( $_POST['resource'] )
    	$resource = $_POST['resource'];
   	else
   	    $resource = $report->fields['resource'];
}

$t->assign( 'reportSettings', $report->getSettings( $reportId ) );


// -------------------------
// get available forms array
// -------------------------

$formTitles = array();
$formIds = array();

$titles = $db->getAll( 'select resource_id as id, value as form_title from '.SETTINGS_TABLE." where resource_type='form' and property='title' and site_key='$site' order by value" );
//$titles = $form->loadCond( array( 'form_title'), array( 'site_key'=>$site ), 'form_title' );

foreach ( $titles as $num => $title ) {
    $formTitles[] = 'Form - ' . $title['form_title'];
	$formIds[] = $title['id'];
}

$formTitles[] = 'Users & Groups';
$formTitles[] = 'Gallery Orders General Info';
$formTitles[] = 'Gallery Orders Product Info';
array_unshift( $formTitles, ' - Choose Resource - ' );

$formIds[] = USERS_TABLE;
$formIds[] = DB_PREFIX.'_gallery_orders';
$formIds[] = DB_PREFIX.'_gallery_order_contents';
array_unshift( $formIds, 0 );

$t->assign( 'formTitles', $formTitles );
$t->assign( 'formIds', $formIds );

function saveReport( $id ) {

    global $report, $t, $site, $rCond, $rGroup, $db, $resource, $session;

    $fields = array(
        'name'     =>  $_POST['name'],
        'resource' =>  $_POST['resource'],
        'header'   =>  $_POST['header'],
        'footer'   =>  $_POST['footer'],
        'site_key' =>  $site,
    );

    if ( $report->exists( $id ) )
        $report->updateId( $id, $fields );
    else {

        $report->create( $fields );

        $id = $report->fields[id];

        // save the default settings for this report

        include_once( 'settingsList.php' );

        foreach( $reportSettings as $property=>$setting ) {

            // get the default setting
            $value = $setting[2];

            $db->query( 'insert into ' . SETTINGS_TABLE . " ( site_key, resource_type, resource_id, property, value ) values ( '$site', 'report', '$id', '$property', '$value' )" );
        }
    }
    
    $sef_title = getSefTitle( $_POST['name'], REPORTS_TABLE, 'sef_title', $id );
    $report->updateId( $id, array( 'sef_title'=>$sef_title ) );

    $t->assign( 'report', $report->fields );


    // ---------------------
    // update the conditions
    // ---------------------

    $conds = $rCond->loadByParentId( $report->fields[id], array(), 'report' );

    foreach ( $conds as $cond ) {

        $cid = $cond[id];

		$value = getCondValue( $cid );

        $fields = array(
            'resource'   => $_POST['resource'],
            'report_id'  => $id,
            'section_id' => $_POST['section_id_' . $cid],
            'condition'  => $_POST['condition_' . $cid],
            'case_sen'   => $_POST['case_sensitive_' . $cid],
            'value'      => $value,
            'site_key'   => $site,
        );

        $rCond->updateId( $cid, $fields );
    }


    // -------------------------------------
    // check if we need to add new condition
    // -------------------------------------

    // assign 0 as is to get new condition fields
    $cid = 'NEW';

	$value = getCondValue( $cid );

    $fields = array(
        'resource'   => $_POST['resource'],
        'report_id'  => $id,
        'section_id' => $_POST['section_id_' . $cid],
        'condition'  => $_POST['condition_' . $cid],
        'case_sen'   => $_POST['case_sensitive_' . $cid],
        'value'      => $value,
        'site_key'   => $site,
    );

    if ( $fields[section_id] && $fields[condition] ) {

        $rCond->create( $fields );
    }


    // ------------------
    // update sort groups
    // ------------------

    $groups = $rGroup->loadByParentId( $report->fields[id], array(), 'report' );

    foreach ( $groups as $group ) {

        $gid = $group[id];

        $fields = array(
            'report_id' => $report->fields[id],
            'field_id'  => $_POST['field_id_' . $gid],
            'sum_field_id'  => $_POST['sum_field_id_' . $gid],
            'do_group'  => $_POST['do_group_' . $gid],
            'sort_type' => $_POST['sort_type_' . $gid],
            'position'  => $_POST['position_' . $gid],
            'style'     => $_POST['style_' . $gid],
            'indent'    => $_POST['indent_' . $gid],
            'layout'    => $_POST['layout_' . $gid],
            'site_key'  => $site,
        );

        $rGroup->updateId( $gid, $fields );
    }

    $report->load( $id );

   	$session->updateLocation( 'edit_report', "Edit Report ({$report->fields['name']})", array( 'id' ) );
    $t->assign( 'bodyTemplate', 'manage/reports.tpl' );
}


function viewHTMLReport( $reportId ) {

    global $t, $report, $system, $session, $db;

    include_once( '../init_bottom.php' );

    $report->mode = 'html';
    $content = $report->generate( $reportId );

    $t->assign( 'content', $content );

    $t->display( 'popupHeader.tpl' );
    $t->display( 'pages/reportViewer.tpl' );
    $t->display( 'popupFooter.tpl' );

    exit();
}


function newGroupForm() {

    global $t, $session, $db;

    $session->updateLocation( 'add_report_group', 'Add Report Group', array( 'action', 'id' ) );

    $t->assign( 'bodyTemplate', 'manage/editReportGroups.tpl' );

}


function addNewGroup( $reportId ) {

    global $site, $rGroup, $t, $session, $db;

    $fields = array(
        'report_id' => $reportId,
        'field_id'  => $_POST['field_id'],
        'sum_field_id'  => $_POST['sum_field_id'],
        'do_group'  => $_POST['do_group'],
        'sort_type' => $_POST['sort_type'],
        'position'  => $_POST['position'],
        'style'     => $_POST['style'],
        'indent'    => $_POST['indent'],
        'layout'    => $_POST['layout'],
        'site_key'  => $site,
    );

    $rGroup->create( $fields );

    $session->updateLocation( 'edit_report', 'Edit Report', array( 'id' ) );
    $t->assign( 'bodyTemplate', 'manage/reports.tpl' );
}


function editUserFieldsForm( $reportId ) {

    global $report, $t, $rField, $session, $db;

    $customFields = $rField->loadCond( array(), array( 'report_id'=>$reportId, 'field_id'=>'' ) );
    array_unshift( $customFields, array( 'id'=> 0) );

    $t->assign( 'customFields', $customFields );
    $t->assign( 'reportId', $reportId );
    $t->assign( 'targetOptions', array( '_blank', '_self', '_parent' ) );

    $session->updateLocation( 'edit_report_fields', 'Edit Report Fiels', array( 'action', 'id' ) );
    $t->assign( 'bodyTemplate', 'manage/editReportHeaders.tpl' );
}

function saveUserFields( $reportId ) {

    global $rField, $site, $t, $db;

    $fields = $rField->loadCond( array( 'id' ), array( 'report_id'=>$reportId, 'field_id'=>'', 'site_key'=>$site ) );
    array_unshift( $fields, array( 'id'=> 0 ) );

    foreach ( $fields as $field ) {

        $fid = $field[id];

        if ( $_POST['field_link_type_'.$fid] != 'images' )
            $link = $_POST['field_link_'.$fid];
        else
            $link = 'images';

        $fs = array(
            'report_id'        => $reportId,
            'title'            => $_POST['field_title_'.$fid],
            'content_template' => $_POST['field_content_'.$fid],
            'use_link'         => $_POST['field_use_link_'.$fid],
            'target'           => $_POST['field_target_'.$fid],
            'link'             => $link,
            'visible'          => $_POST['field_visible_'.$fid],
            'site_key'         => $site,
        );

        if ( $fid != 0 )
            $rField->updateId( $fid, $fs );
        else {

            // determine if we should add new field

            if ( $fs['title'] && $fs['content_template'] )
                $rField->create( $fs );
        }
    }


}


function editFieldsForm( $reportId ) {

    global $report, $t, $rField, $resource, $db, $session;

    if ( is_numeric( $resource ) ) {

        // get field types and choice data for form

        $reportFields = $db->getAll( 'select s.id as field_id, s.label as title, s.field_type, r.id as report_field_id, r.display_title, r.visible, r.use_link, r.link, r.target from '.FORMSECTIONS_TABLE.' s left join '.REPORTFIELDS_TABLE." r on s.id=r.field_id and r.report_id='$reportId' where s.form_id='$resource'" );

        //$fieldTypes = $fSect->loadByParentId( $resource, array( 'id', 'field_type', 'list_data' ) );
        //$fields = getFieldsForForm( $fieldTypes );

    }
    else {

        $fieldTitles = $report->getFieldTitles( $resource );
        $fieldIds = $report->getFieldIds( $resource );

        $reportFields = array();

        for ( $i=0, $n=count( $fieldIds ); $i<$n; $i++ ) {

            $field = $rField->loadCond( array( 'id', 'display_title', 'visible', 'use_link', 'link', 'target' ), array( 'field_id'=>$fieldIds[$i], 'report_id'=>$reportId ) );
            $reportFields[] = array( 'title'=>$fieldTitles[$i], 'report_field_id'=>$field[0]['id'], 'field_id'=>$fieldIds[$i], 'display_type'=>$field[0]['display_title'], 'visible'=>$field[0]['visible'] );
        }

    }

    for( $i=0, $n=count( $reportFields ); $i<$n; $i++ ) {
        if ( !$reportFields[$i][report_field_id] )
            $reportFields[$i][visible] = 1;
    }

    //$t->assign( 'customFields', $customFields );
    $t->assign( 'reportId', $reportId );
    $t->assign( 'reportFields', $reportFields );
    $t->assign( 'targetOptions', array( '_blank', '_self', '_parent' ) );

    $session->updateLocation( 'edit_report_fields', 'Edit Report Fiels', array( 'action', 'id' ) );
    $t->assign( 'bodyTemplate', 'manage/editReportFields.tpl' );
}


function saveFields( $reportId ) {

    global $rField, $site, $t, $resource, $report, $db;

    $fieldIds = $report->getFieldIds( $resource );

    $rField->deleteCond( array( 'field_id!='=>'', 'site_key'=>$site ) );

    foreach ( $fieldIds as $num=>$fid ) {

        $link = $_POST['field_link_type_'.$fid];
        if ( $link != 'images' && $link != 'full_image' )
            $link = $_POST['field_link_'.$fid];

        $fs = array(
            'report_id'        => $reportId,
            'field_id'         => $fid,
            'display_title'    => $_POST['field_display_title_'.$fid],
            'visible'          => $_POST['field_visible_'.$fid],
            'use_link'         => $_POST['field_use_link_'.$fid],
            'target'           => $_POST['field_target_'.$fid],
            'link'             => $link,
            'site_key'         => $site,
        );

        $rField->create( $fs );
    }
}

function editLayoutForm( $reportId ) {

    global $report, $t, $session, $db;

    $t->assign( 'layoutOptions', array( 'Simple Column Layout', 'Advanced HTML Layout' ) );

    $session->updateLocation( 'edit_report_layout', 'Edit Report Layout', array( 'action', 'id' ) );
    $t->assign( 'bodyTemplate', 'manage/editReportLayout.tpl' );
}

function saveLayout( $reportId ) {

    global $report, $db;

    if ( $_POST['advanced_layout'] ) {

        $report->updateId( $reportId, array( 'layout_template'=>$_POST['layout_template'] ) );
    }
    else {

        // apply visibility
    }
}


function selectReportFields( $reportId, $addCustom=0 ) {

    global $report, $t, $resource, $db;

    $t->assign( 'report', $report->fields );
    $t->assign( 'reportId', $reportId );

    $t->assign( 'reportFields', $report->getFieldTitles( $resource, $addCustom ) );

    $t->assign( 'title', 'Select Fields' );
    $t->display( 'popupHeader.tpl' );
    $t->display( 'manage/selectReportField.tpl' );
    $t->display( 'popupHeader.tpl' );

    exit();
}

function embedReports( $reportId ) {

    global $report, $t, $resource, $db, $formTitles, $formIds, $site, $session;

    // ------------------------------------------------
    // determine what reports resources can be embedded
    // ------------------------------------------------

    $embeddedTypes = $report->getEmbeddedTypes();
    $t->assign( 'embeddedTypes', $embeddedTypes );

    $resourceIds = array();
    $resourceTitles = array();

    if ( is_array( $embeddedTypes ) && count( $embeddedTypes ) )
    foreach( $embeddedTypes as $resource_id=>$details ) {

    	if ( is_numeric( $resource_id ) ) {
    		$title = $db->getOne( 'select value from '. SETTINGS_TABLE ." where resource_type='form' and resource_id='$resource_id'" );
    	}
    	else {
    		$title = $details['title'];
    	}

    	$resourceTitles[] = $title;
    	$resourceIds[] = $resource_id;
    }

    $t->assign( 'resourceIds', $resourceIds );
    $t->assign( 'resourceTitles', $resourceTitles );

    // --------------------------------------
    // determine reports that can be embedded
    // --------------------------------------

    $allowed = '\'' . implode( '\', \'', $resourceIds ) . '\'';

    $embedableReports = $db->getAll( 'select r.id, r.name, r.resource, e.id as embedded from '. REPORTS_TABLE." r left join ". EMBEDDEDREPORTS_TABLE." e on (r.id=e.source_id and e.into_id='$reportId') where r.resource in ($allowed) and r.site_key='$site'" );

    foreach ( $embedableReports as $idx=>$item ) {
    	$embedableReports[$idx][resource] = $embeddedTypes[$item['resource']]['title'];
    }

    $t->assign( 'embedableReports', $embedableReports );

    $t->assign( 'report', $report->fields );
    $t->assign( 'reportId', $reportId );

    $session->updateLocation( 'edit_embedded_reports', 'Edit Embedded Reports', array( 'action', 'id' ) );
    $t->assign( 'bodyTemplate', 'manage/editEmbedReports.tpl' );

}


function processEmbed( $reportId ) {

	global $db, $site, $report;

	$db->query( 'delete from '. EMBEDDEDREPORTS_TABLE." where into_id='$reportId'" );

	if ( $_POST['name'] ) {
		saveReport(0);
		$db->query( 'insert into '. EMBEDDEDREPORTS_TABLE." (into_id, source_id, site_key) values( '$reportId', '{$report->fields[id]}', '$site' )" );
	}

	$report->load( $reportId );

	$reports = $db->getAll( 'select * from '. REPORTS_TABLE." where site_key='$site'" );

	foreach ( $reports as $idx=>$item ) {
		if ( $_POST['report_'.$item['id']] )
			$db->query( 'insert into '. EMBEDDEDREPORTS_TABLE." (into_id, source_id, site_key) values( '$reportId', '$item[id]', '$site' )" );
	}
}

// ----------------------
// assign report settings
// ----------------------

/*$settings = array();

$sets = $db->getAll( 'select property, value from '. SETTINGS_TABLE . " where report_id='$reportId' and site_key='$site'" );

foreach ( $sets as $num=>$set ) {
    $settings[$set[property]] = $set[value];
}
*/



switch ( $action ) {

    case 'save':
        saveReport( $reportId );
        break;

    case 'viewHtml':
        viewHTMLReport( $reportId );
        break;

    case 'editRecord':
        editRecordForm( $reportId, intval( $_GET['sub_id'] ) );
        $session->updateLocation( 'edit_report_record', 'Edit Report Record', array( 'id', 'sub_id' ) );
        $t->assign( 'bodyTemplate', 'pages/editReportRecord.tpl' );
        break;

    case 'saveRecord':
        saveRecord( intval( $_POST['submission_id'] ) );
        viewHTMLReport( $reportId );
        break;

    case 'deleteRecord':
        deleteRecord( intval( $_GET['sub_id'] ), intval( $_GET['form_id'] ) );
        viewHTMLReport( $reportId );
        break;

    case 'deleteCondition':
        $rCond->delete( intval( $_POST['deleteSectionVar'] ) );
        saveReport( $reportId );
        break;

    case 'editFields':
        editFieldsForm( $reportId );
        break;

    case 'saveFields':
        saveFields( $reportId );
        editFieldsForm( $reportId );
        break;

    case 'editUserFields':
        editUserFieldsForm( $reportId );
        break;

    case 'saveUserFields':
        saveUserFields( $reportId );
        editUserFieldsForm( $reportId );
        break;

    case 'deleteField':
        $rField->delete( intval( $_POST['deleteSectionVar'] ) );
        saveUserFields( $reportId );
        editUserFieldsForm( $reportId );
        break;

    case 'editLayout':
        editLayoutForm( $reportId );
        break;

    case 'changeLayout':
        $report->updateId( $reportId, array( 'advanced_layout'=>$_POST['advanced_layout'] ) );
        $report->fields['advanced_layout'] = $_POST['advanced_layout'];
        editLayoutForm( $reportId );
        break;

    case 'saveLayout':
        saveLayout( $reportId );
        $report->fields['layout_template'] = $_POST['layout_template'];
        editLayoutForm( $reportId );
        break;

    case 'newGroupForm':
        newGroupForm();
        break;

    case 'addNewGroup':
        addNewGroup( $reportId );
        break;

    case 'deleteGroup':
        $rGroup->delete( intval( $_POST['deleteGroupVar'] ) );
        saveReport( $reportId );
        break;

    case 'bumpGroup':
        $rGroup->bumpUp( intval( $_POST['bumpGroupVar'] ), $reportId );
        saveReport( $reportId );
        break;

    case 'fieldSelection':
        selectReportFields( $reportId );
        break;

    case 'embedReports':
    	embedReports( $reportId );
    	break;

    case 'processEmbed':
    	processEmbed( $reportId );
    	embedReports( $reportId );
    	break;

    case 'delete':
        $report->delete( $reportId );
        $db->query( 'delete from ' . SETTINGS_TABLE . " where resource_type='report' and resource_id='$reportId'" );
        $reportId = 0;

    default:
    	$session->updateLocation( 'edit_report', 'Edit Report', array( 'id' ) );
        $t->assign( 'bodyTemplate', 'manage/reports.tpl' );
        break;
}

$t->assign( 'report', $report->fields );

// ---------------------------
// get available reports array
// ---------------------------

$reportTitles = $report->loadColumnCond( 'name', array( 'site_key'=>$site ) );
array_unshift( $reportTitles, '- Create New Report -' );

$reportValues = $report->loadColumnCond( 'id', array( 'site_key'=>$site ) );
array_unshift( $reportValues, 0 );

$t->assign( 'reportTitles', $reportTitles );
$t->assign( 'reportValues', $reportValues );

// -------------------------
// get available form fields
// -------------------------

$fieldTitles = array();
$fieldIds = array();
$clickableFieldTitles = array();

$fieldTitles = $report->getFieldTitles( $resource );
$fieldIds    = $report->getFieldIds( $resource );

$clickableFieldTitles = $report->getFieldTitles( $resource, 1 );

array_unshift( $fieldTitles, ' - Select Field - ' );
array_unshift( $fieldIds, 0 );

$t->assign( 'fieldTitles', $fieldTitles );
$t->assign( 'fieldIds'   , $fieldIds );
$t->assign( 'clickableFieldTitles', $clickableFieldTitles );

if ( !hasAdminAccess( 'cm_report' ) ) {
	$t->assign( 'bodyTemplate', 'manage/authError.tpl' );
}


// ---------------------
// get all report groups
// ---------------------

$reportGroups = $rGroup->loadByParentId( $reportId, array(), 'report', '_order' );
$t->assign( 'reportGroups', $reportGroups );

// --------------------------------------------
// get all available conditions for this report
// --------------------------------------------

$data = $rCond->loadByParentId( $reportId, array(), 'report' );
$data = prepareConditionData( $data );

$t->assign( 'reportConditions', $data );

// -------------------------------------------------
// assign fields and field types for the report form
// -------------------------------------------------

if ( is_numeric( $resource ) ) {

    // get field types and choice data for form

    $fieldTypes = $fSect->loadByParentId( $resource, array( 'id', 'field_type', 'list_data' ) );
    $fields = getFieldsForForm( $fieldTypes );

}
else {

    // get field types and choice data for table

    $fieldTypes = $fields = array();
    $groups = $db->getAll( 'select id, name as label from '.GROUPS_TABLE." where site_key='$site'" );

    $userStatuses = array(
        0 => array( 'id'=>'active', 'label'=>'Active' ),
        1 => array( 'id'=>'pending', 'label'=>'Pending' ),
        2 => array( 'id'=>'suspended', 'label'=>'Suspended' ),
        3 => array( 'id'=>'terminated', 'label'=>'Terminated' )
    );

    $orderStatuses = array(
        0 => array( 'id'=>'initial', 'label'=>'Initial' ),
        1 => array( 'id'=>'pending', 'label'=>'Pending' ),
        2 => array( 'id'=>'completed', 'label'=>'Completed' ),
    );

    foreach( $fieldIds as $title ) {

        if ( !$title )
            continue;

        $choiceTitles = array();
        $choiceValues = array();
        $choices = array();

        $type = getColumnType( $title );

        switch ( $resource ) {

        	case USERS_TABLE:
		        switch( $title ) {

		            case 'country':
		                $choices = $lItem->loadByParentId( 'countries', array( 'label as id', 'label' ), '', 'label' );
		                break;

		            case 'group_id':
		                $choices = $groups;
		                break;

		            case 'status':
		                $choices = $userStatuses;
		                break;
		        }
		        break;

		    case DB_PREFIX.'_gallery_orders':
		        switch( $title ) {
		            case 'shipping_method':
		                $choices = $db->getAll( 'select id, name as label from '. DB_PREFIX."_gallery_shipping_options where site_key='$site'" );
		                break;

		            case 'payment_method':
		                $choices = array( 0=> array( 'id'=>'paypal', 'label'=>'Paypal' ) );
		                break;

		            case 'country':
		                $choices = $lItem->loadByParentId( 'countries', array( 'label as id', 'label' ), '', 'label' );
		                break;

		        	case 'status':
		        		$choices = $orderStatuses;
		        		break;
		        }
		    	break;
        }

        $fieldTypes[] = array( 'id'=>$title, 'field_type'=>$type, 'list_data'=>'' );

        if ( $choices ) {
            foreach ( $choices as $i => $choice ) {
                $choiceTitles[] = addslashes( $choice[label] );
                $choiceValues[] = addslashes( $choice[id] );
            }
        }
        $fields[] = array( 'id'=>$title, 'values'=>'\''.@implode( '\', \'', $choiceValues ).'\'', 'titles'=>'\''.@implode( '\', \'', $choiceTitles ).'\'' );
    }
}


$t->assign( 'fieldTypes', $fieldTypes );
$t->assign( 'fields', $fields );


// -----------------
// assign style list
// -----------------

$styleList = $system->getStyleList();
$t->assign( 'styleList', $styleList );

$t->assign( 'countGroups', $db->getOne( 'select count(id) from ' . REPORTGROUPS_TABLE . " where report_id='$reportId'" ) );

// ----------------------
// assign combobox values
// ----------------------

$t->assign( 'orientationOptions', array( 'left', 'center', 'right' ) );
$t->assign( 'orderValues', array( '0'=>'Ascending Alphabetic', '1'=>'Descending Alphabetic', '2'=>'Ascending Numeric', '3'=>'Descending Numeric' ) );
$t->assign( 'conditions', array( '>', '<', '=', '!=', '>=', '<=', 'starts with', 'contains', 'ends with' ) );
$t->assign( 'positionOptions', array( 'above', 'below' ) );
$t->assign( 'positioOptions', array( 'above', 'below' ) );

$t->assign( 'reportReturnLink', DOC_ROOT . 'manage/reports.php?id='.$reportId );

include_once( '../init_bottom.php' );

$t->display( $templateName );


?>