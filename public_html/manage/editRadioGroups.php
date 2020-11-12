<?php

if ( !defined( 'SMARTY_DIR' ) )
    include_once( '../init.php' );

$radioGroup = $_REQUEST['radioGroup'];

$t->assign( 'radioGroup', $radioGroup );

// check for form submission
if ( $_POST['formIsSubmitted'] ) {

    $seen = array();

    foreach( $_POST as $key => $var ) {
        $keyParts = explode( '_', $key );

        $id = $keyParts[ sizeof( $keyParts ) - 1 ];

        if ( in_array( $id, $seen ) || !stristr( $key, '_' ) )
        continue;
        else
        $seen [] = $id;

        if ( $id == 'NEW' ) {
            $updateData = true;
            continue;
        }

        if ( !$updateData )
        continue;

        // -----------
        // update info
        // -----------
        
        if ( $id == $_POST['selectedGroupItem'] )
            $selected = '1';
        else
            $selected = '0';

        $db->query( "update ".FORMGROUPS_TABLE." set
                label       = '" . $_POST["label_$id"] . "',
                value       = '" . $_POST["value_$id"] . "',
                orientation = '" . $_POST["orientation_$id"] . "',
                selected    = '$selected'
            where id = $id"
        );
        
    }
    
    // add new form data, if needed
    if ( $_POST['addNewItem'] ) {

        extract( $_POST );
        
        $formID = $db->getOne( 'select form_id from '.FORMSECTIONS_TABLE." where id='$radioGroup'" );

		if ( $user->isShared( 'form', $formID ) )
			$formSiteKey = $user->getSharedSiteKey( 'form', $formID );
		else
			$formSiteKey = $site;        
			
        $_order = 1 + $db->getOne( "select max(_order) from ".FORMGROUPS_TABLE." where _group = '$radioGroup'" );

        if ( $_POST['selectedGroupItem'] == 'NEW' )
            $selected_NEW = '1';
        else
            $selected_NEW = '0';
            

        $db->query( "insert into ".FORMGROUPS_TABLE." (
            site_key,
            _order,
            _group,
            label,
            value,
            orientation,
            selected
            ) values (
            '$formSiteKey',
            '$_order',
            '$radioGroup',
            '$label_NEW',
            '$value_NEW',
            '$orientation_NEW',
            '$selected_NEW' )" );
    }

    
    // determine if we should delete or bump up a section
    if ( $_POST['deleteSectionVar'] ) {

        $db->query( "delete from ".FORMGROUPS_TABLE." where id = '" . $_POST['deleteSectionVar'] . "'" );
    }
    else if ( $_POST['bumpUpSectionVar'] ) {
        // use the _order field to reorder the sections

        $curRecID = $_POST['bumpUpSectionVar'];

        // get the _order of the row immediately above this row
        $curOrder = $db->getOne( "select _order from ".FORMGROUPS_TABLE." where id = '$curRecID'" );

        list( $row ) = $db->getAll( "select id, _order from ".FORMGROUPS_TABLE." where _order < $curOrder and _group = '$radioGroup' order by _order desc limit 1" );

        $prevRecID = $row['id'];
        $prevOrder = $row['_order'];

        if ( !$prevOrder )
            $prevOrder = '0';

        // swap the orders
        $db->query( "update ".FORMGROUPS_TABLE." set _order = '$prevOrder' where id = '$curRecID'" );
        $db->query( "update ".FORMGROUPS_TABLE." set _order = '$curOrder' where id = '$prevRecID'" );
    }

}
    

$t->assign( 'orientationValues', array( 'left', 'right' ) );
$t->assign( 'orientationLabels', array( 'left', 'right' ) );

$data = $db->getAll( "select * from ".FORMGROUPS_TABLE." where _group = '$radioGroup' order by _order" );

$t->assign( 'title', 'Edit Button Group' );

$t->assign( 'disabledElementColor', '#DDDDDD' );

// add one item to the beginning of $data for the "new" section
array_unshift( $data, array( 'id' => 'NEW' ) );

$t->assign( 'data', $data );


$t->assign( 'adminReturnLink', '[ <a href=javascript:close();>Close this Window</a> ]' );
// radio group window is a js popup - no background template needed

include_once( '../init_bottom.php' );

$t->display( 'popupHeader.tpl' );
$t->display( 'manage/editRadioGroups.tpl' );
$t->display( 'popupFooter.tpl' );
    
?>