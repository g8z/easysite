<?php

if ( !defined( 'SMARTY_DIR' ) )
    include_once( '../init.php' );


include_once( INCLUDE_DIR . 'internal/db_items/class.Form.php' );
include_once( INCLUDE_DIR . 'internal/db_items/class.Form_Section.php' );
include_once( INCLUDE_DIR . 'internal/db_items/class.Form_Group.php' );
include_once( INCLUDE_DIR . 'internal/db_items/class.Form_Redirect.php' );
//include_once( INCLUDE_DIR . 'internal/db_items/class.Form_Condition.php' );
include_once( INCLUDE_DIR . 'internal/db_items/class.List_Item.php' );

$form   = new Form();
$fSect  = new Form_Section();
$fGroup = new Form_Group();
$fRedir = new Form_Redirect();
//$fCond  = new Form_Condition();
$lItem  = new List_Item();

//if ( $condId != 'NEW' )
//$fCond->load( $condId, array() );

$fieldId = $_GET['fieldId'];

$fSect->load( $fieldId, array() );
$fieldType = $fSect->fields[field_type];

switch ( $fieldType ) {

    case 'radio':
        // get all radio groups id and titles as possible choices
        $choices = $fGroup->loadByParentId( $fSect->fields[id], array( 'id', 'label' ) );
        $type = 'list';
        break;

    case 'select':
        // get all select list choices
        $choices = $lItem->loadByParentId( $fSect->fields[list_data], array( 'label as id', 'label' ) );
        $type = 'list';
        break;

    case 'date':
        $selTime = $fRedir->fields[value];
        $type = 'date';
        break;

}

if ( $type == 'list' ) {

    // assign multiple items data

    $choiceTitles = array();
    $choiceIds = array();
    foreach ( $choices as $i => $choice ) {
        $choiceTitles[] = $choice[label];
        $choiceIds[] = $choice[id];
    }

    $t->assign( 'choiceTitles', $choiceTitles );
    $t->assign( 'choiceIds', $choiceIds );
}

$t->assign( 'condId', $condId );

//$t->assign( 'delimiter', '|||' );
$t->assign( 'type', $type );

include_once( '../init_bottom.php' );
$t->display( 'popupHeader.tpl' );
$t->display( 'manage/valueSelect.tpl' );
$t->display( 'popupFooter.tpl' );

?>