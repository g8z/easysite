<?php

if ( !defined( 'SMARTY_DIR' ) )
    include_once( '../init.php' );

include_once( INCLUDE_DIR . 'internal/db_items/class.Form.php' );
include_once( INCLUDE_DIR . 'internal/db_items/class.Form_Section.php' );
include_once( INCLUDE_DIR . 'internal/db_items/class.Form_Group.php' );
include_once( INCLUDE_DIR . 'internal/db_items/class.Form_Redirect.php' );
include_once( INCLUDE_DIR . 'internal/db_items/class.List_Item.php' );
include_once( INCLUDE_DIR . 'internal/db_items/conditionFunctions.php' );

$form   = new Form();
$fSect  = new Form_Section();
$fGroup = new Form_Group();
$fRedir = new Form_Redirect();
$lItem  = new List_Item();


// check for form submission
if ( $_POST['formIsSubmitted'] ) {

    $seen = array();

    foreach( $_POST as $key => $var ) {

        if ( !preg_match( '/([a-zA-Z]*?_)+([0-9]+)/', $key, $matches ) )
            continue;
        else
            $id = $matches[ count( $matches ) - 1 ];

        if ( in_array( $id, $seen ) )
            continue;
        else
            $seen[] = $id;

		list( $redirectType, $redirectId ) = explode( '-', $_POST["redirect_$id"] );

		$value = getCondValue( $id );

		$fields = array(
			'site_key'   	=> $site,
			'section_id'  	=> $_POST["section_id_$id"],
			'condition'   	=> $_POST["condition_$id"],
			'case_sen'   	=> $_POST["case_sensitive_$id"],
			'value'       	=> $value,
			'redirect_type' => $redirectType,
			'redirect_id' 	=> $redirectId,
			'form_id'		=> $formId,
		);

        $fRedir->updateId( $id, $fields );
    }

    // add new form data, if needed
    if ( $_POST['addNewItem'] && $section_id_NEW && $condition_NEW ) {

		list( $redirectType, $redirectId ) = explode( '-', $_POST["redirect_NEW"] );

		$value = getCondValue( 'NEW' );

        $fields = array(
            'site_key'   	=> $site,
            'section_id' 	=> $_POST['section_id_NEW'],
            'condition'  	=> $_POST['condition_NEW'],
            'case_sen'  	=> $_POST['case_sensitive_NEW'],
            'value'      	=> $value,
            'redirect_type'	=> $redirectType,
            'redirect_id' 	=> $redirectId,
            'form_id'	 	=> $formId,
        );

        $fRedir->create( $fields );
    }

    // update 'default redirect' for this form

    list( $_POST['redirect_type'], $_POST['redirect_id'] ) = explode( '-', $_POST['default_redirect'] );

    $fields = array(
        'other_redirect_type' => $_POST['redirect_type'],
        'other_redirect_id'   => $_POST['redirect_id']
    );

    $form->updateId( $formId, $fields );

	// determine if we should delete or bump up a section
	if ( $_POST['deleteSectionVar'] ) {
		$fRedir->delete( $_POST['deleteSectionVar'] );
	}

	if ( $_POST['bumpUpSectionVar'] ) {
		$fRedir->bumpUp( $_POST['bumpUpSectionVar'], $formId );
	}
}

$form->load( $formId );

$currentFormFields = $form->fields;
$redDefault = $form->fields['other_redirect_type'] . '-' . $form->fields['other_redirect_id'];
$t->assign( 'redDefault', $redDefault );

$shared = getSQLShares( 'page' ); 
$allPages = $db->getAll( 'select id, if(id in ('.$shared.'), concat(title, \'[shared]\'), title) as title from ' .PAGES_TABLE . " where (site_key = '$site' or id in ($shared)) order by title" );

$redirectValues = array();
$redirectOutput = array();

// create two arrays from $allPages that we can use in {html_options}
foreach( $allPages as $somePage ) {

	$redirectTitle = $somePage['title'];

	$redirectValues [] = 'page-' . $somePage['id'];
	$redirectOutput [] = 'page - ' . $redirectTitle;
}



// get all available forms for purposes of the jump menu

$fields = array( 'id', 'form_title', 'other_redirect_type', 'other_redirect_id' );
$conds = array( 'site_key' => $site );

$shared = getSQLShares( 'form' ); 
$forms = $db->getAll( 'select f.id, other_redirect_type, f.other_redirect_id, if(f.id in ('.$shared.'), concat(s.value, \'[shared]\'), s.value) as form_title from '. FORMS_TABLE.' f left join '.SETTINGS_TABLE." s on f.id=s.resource_id and s.resource_type='form' and s.property='title' where (f.site_key='$site' or f.id in ($shared)) order by s.value" );

// only a single iteration of this loop is done

foreach( $forms as $index => $f ) {

	$redirectTitle = $f['form_title'];

	// so that the names in the combo box do not spill over too much
	if ( strlen( $redirectTitle ) > 30 )
		$redirectTitle = substr( $redirectTitle, 0, 27 ) . '...';

	// add the forms to the redirect options

	$redirectValues [] = 'form-' . $f['id'];
	$redirectOutput [] = 'form - ' . $redirectTitle;

	
}

$redirectValues[] = 'url';
$redirectOutput[] = 'new url';

$t->assign( 'redirectValues', $redirectValues );
$t->assign( 'redirectOutput', $redirectOutput );

$fRedir->load( $formId );

/*
$t->assign( 'redRedirect', $fRedir->fields['other_redirect_type'] . '-' . $fRedir->fields['redirect_id'] );
*/

// maybe unnecessary??
$conds = array( 'form_id'=>$formId, 'field_type!='=>'image\' and field_type!=\'file\' and field_type!=\'page_section' );
$fieldTitles = $fSect->loadColumnCond( 'label', $conds, '_order' );
$fieldIds    = $fSect->loadColumnCond( 'id', $conds, '_order' );
array_unshift( $fieldTitles, ' - Select Field - ' );
array_unshift( $fieldIds, 0 );
$t->assign( 'fieldTitles', array_values( array_values( $fieldTitles ) ) );
$t->assign( 'fieldIds'   , array_values( $fieldIds ) );

$fieldTypes = $fSect->loadByParentId( $formId, array( 'id', 'field_type', 'list_data' ) );
$t->assign( 'fieldTypes', $fieldTypes );

$fields = getFieldsForForm( $fieldTypes );

$t->assign( 'fields', $fields );

$data = $fRedir->loadByParentId( $formId, array(), 'form', '_order' );
$data = prepareConditionData( $data );

$t->assign( 'data', $data );


$conditions = array( '>', '<', '=', '!=', '>=', '<=', 'starts with', 'contains', 'ends with' );
$t->assign( 'conditions', $conditions );

$formName = htmlentities( $db->getOne( 'select value as form_title from '. SETTINGS_TABLE." where resource_type='form' and property='title' and resource_id='$formId'" ) );
$t->assign( 'formId', $formId );
$t->assign( 'formName', $formName );

$t->assign( 'formReturnLink', '[ <a href="'.DOC_ROOT . 'manage/editForms.php?form_id='.$formId.'">Return to Form Manager</a> ]' );

if ( !hasAdminAccess( 'cm_form' ) ) {
    $t->assign( 'bodyTemplate', 'manage/authError.tpl' );
}
else {
    $t->assign( 'bodyTemplate', 'manage/editRedirects.tpl' );
}

$session->updateLocation( 'edit_redirects', "Edit Form Redirects ({$formName})", array( 'formID', 'formId' ) );
include_once( '../init_bottom.php' );

$t->display( $templateName );


?>