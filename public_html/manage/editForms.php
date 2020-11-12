<?php

if ( !defined( 'SMARTY_DIR' ) )
    include_once( '../init.php' );

if ( !hasAdminAccess( 'cm_form' )  ) {
    $t->assign( 'bodyTemplate', 'manage/authError.tpl' );
    exit;
}

$hasAccess = true;

include_once( INCLUDE_DIR . 'internal/db_items/class.Form.php' );
include_once( INCLUDE_DIR . 'internal/db_items/class.Form_Section.php' );
include_once( INCLUDE_DIR . 'internal/db_items/class.Form_Group.php' );
include_once( INCLUDE_DIR . 'internal/db_items/class.Form_Redirect.php' );
include_once( INCLUDE_DIR . 'internal/class.moduleManager.php' );

$formID = intval( $_REQUEST['form_id'] );

if ( !$user->hasAccess( 'form', $formID ) )
	loginError( ACCESS_DENIED );

$form   = new Form();

$bsite = $site;

if ( $user->isShared( 'form', $formID, 'edit' ) )
	$site = $user->getSharedSiteKey( 'form', $formID );

$fSect  = new Form_Section();
$fGroup = new Form_Group();
$fRedir = new Form_Redirect();

$site = $bsite;

$table = FORMSECTIONS_TABLE;

if ( !$_REQUEST['add_form'] ) {
    // if a new form, then add it and get the new id

    if ( $formID == 'NEW' ) {

        if ( $hasAccess = hasAdminAccess( 'cm_form_add' ) ) {

            $fields = array( 'site_key' => $site );
            $form->create( $fields );
            $formID = $form->fields[id];

            // save form settings

            include_once( 'settingsList.php' );

	        foreach( $formSettings as $property=>$setting ) {

	            // get the default setting
	            $value = $setting[2];

	            if ( $property == 'title' || $property == 'description' ) {
	            	$value = 'Form_'.$form->fields[id];
	            	if ( $property == 'title' ) {
			            $sef_title = getSefTitle( $value, FORMS_TABLE, 'sef_title', $formID );
			            $form->updateId( $formID, array( 'sef_title'=>$sef_title ) );
	            	}
	            }

	            $value = addslashes( $value );

	            $db->query( 'insert into ' . SETTINGS_TABLE . " ( site_key, resource_type, resource_id, property, value ) values ( '$site', 'form', '$formID', '$property', '$value' )" );
	        }

        }
    } else if ( $_POST['formIsSubmitted'] ) {
            $hasAccess = hasAdminAccess( 'cm_form_edit' );
        }

}
else {
    $formID = 'NEW';
}

if ( $_POST['deleteForm'] && $formID != 'NEW' ) {

    if ( $hasAccess = hasAdminAccess( 'cm_form_delete' ) ) {

        // DB_Item class takes care about cascade deletion
    	$form->delete( $formID );

    	// delete settings
    	$db->query( 'delete from '. SETTINGS_TABLE." where resource_type='form' and resource_id='$formID'" );

        header( 'Location: '.$session->prevLocation['url'] );
        exit;

    }
}


// check for form submission
if ( $_POST['formIsSubmitted'] && $hasAccess ) {

    // update active field
    //$fRedir->updateCond( array( 'active'=>0 ), array( 'form_id'=>$formID ) );

    $seen = array();

    foreach( $_POST as $key => $var ) {

        if ( !preg_match( '/([a-zA-Z]*?_)+([0-9]+)/', $key, $matches ) )
            continue;
        else {

            $id = $matches[ count( $matches ) - 1 ];
        }

        if ( in_array( $id, $seen ) )
            continue;
        else
            $seen[] = $id;


        $fields = array(
            'field_type'  => $_POST["field_type_$id"],
            'field_name'  => $_POST["field_name_$id"],
            'field_size'  => $_POST["field_size_$id"],
            'required'    => intval($_POST["required_$id"]),
            'validator'   => $_POST["validator_$id"],
            'err_msg'     => $_POST["err_msg_$id"],
            'label'       => $_POST["label_$id"],
            'list_data'   => $_POST["list_data_$id"],
            'page_section'=> intval($_POST["page_section_$id"])
        );

        $fSect->updateCond( $fields, array( 'id'=>$id ) );

    }

    // add new form data, if needed
    if ( $_POST['addNewItem'] ) {

        extract( $_POST );

	    if ( $user->isShared( 'form', $formID, 'edit' ) )
	    	$insertSiteKey = $user->getSharedSiteKey( 'form', $formID );
	    else
	    	$insertSiteKey = $site;

        $fields = array(
            'site_key'      => $insertSiteKey,
            'form_id'       => $formID,
            'field_type'    => $field_type_NEW,
            'field_name'    => $field_name_NEW,
            'field_size'    => $field_size_NEW,
            'required'      => intval($required_NEW),
            'validator'     => $validator_NEW,
            'err_msg'       => $err_msg_NEW,
            'label'         => $label_NEW,
            'list_data'     => $list_data_NEW,
            'page_section'  => intval($page_section_NEW)
        );

        $fSect->create( $fields );
    }

    // we must update the to, cc, subject, redirect variables
    list( $_POST['redirect_type'], $_POST['redirect_id'] ) = explode( '-', $_POST['form_redirect'] );
    list( $_POST['other_redirect_type'], $_POST['other_redirect_id'] ) = explode( '-', $_POST['other_redirect'] );

    $fields = array(
        //'form_title'    => $_POST['form_title'],
        //'form_desc'     => $_POST['form_desc'],
        //'form_to'       => $_POST['form_to'],
        //'form_cc'       => $_POST['form_cc'],
        //'form_subject'  => $_POST['form_subject'],
        'redirect_type' => $_POST['redirect_type'],
        'redirect_id'   => $_POST['redirect_id'],
        'other_redirect_type' => $_POST['other_redirect_type'],
        'other_redirect_id'   => $_POST['other_redirect_id'],
        'is_search_form'      => intval($_POST['is_search_form']),
        'search_report_id'    => $_POST['search_report_id'],
        //'skin_id'       => $_POST['skin_id']
    );

    $form->updateCond( $fields, array( 'id'=>$formID ) );

    // determine if we should delete or bump up a section

    if ( $_POST['deleteSectionVar'] ) {

        $fSect->deleteCond( array( 'id' => $_POST['deleteSectionVar'] ) );
    }
    else if ( $_POST['bumpUpSectionVar'] ) {

        $fSect->bumpUp( $_POST['bumpUpSectionVar'], $formID );
    }

    $defaultId = $formID;
    $defaultType = 'form';

}

/*    $defaultID = $db->getOne( 'select default_resource_id from ' . SITES_TABLE . " where site_key = '$site' and default_resource_type = 'form' limit 1" );

    if ( $defaultID == $formID )
        $t->assign( 'isDefault', true );

    $loginFormID = $db->getOne( 'select login_form_id from ' . SITES_TABLE . " where site_key = '$site'" );

    if ( $loginFormID == $formID )
        $t->assign( 'isLoginForm', true );*/

if ( $formID == 'NEW' ) {
	$data = array();
}
else {
	//$conds = array( 'site_key' => $site, 'form_id'=>$formID );
	$data = $fSect->loadByParentId( $formID, array(), 'form', '_order' );
	//loadCond( array(), $conds, '_order' );
}



// list of all pages, so that we can specify the redirect page for the form

$shared = getSQLShares( 'page' );
$allPages = $db->getAll( 'select id, if(id in ('.$shared.'), concat(title, \'[shared]\'), title) as title from ' .PAGES_TABLE . " where (site_key = '$site' or id in ($shared)) order by title" );

$pageSectionsValues = array();
$pageSectionsOutput = array();

$redirectValues = array();
$redirectOutput = array();

// create two arrays from $allPages that we can use in {html_options}
foreach( $allPages as $somePage ) {

	$redirectTitle = $somePage['title'];

	// so that the names in the combo box do not spill over too much
	if ( strlen( $redirectTitle ) > 30 )
		$redirectTitle = substr( $redirectTitle, 0, 27 ) . '...';

	$redirectValues [] = 'page-' . $somePage['id'];
	$redirectOutput [] = 'page - ' . $redirectTitle;

    // -------------------------
	// get page sections content
	// -------------------------

	$pss = $db->getAll( 'select id, content from '. SECTIONS_TABLE ." where page_id='$somePage[id]' order by _order" );

    foreach( $pss as $ps ) {

        $sectContent = stripHTMLTags( $ps[content] );

        if ( !$sectContent ) {

            // if section contains only html tags,
            // then convert and dislay them

            $sectContent = htmlentities( $ps[content] );
        }

        $pageSectionsOutput[] = $somePage[title] .' - '. cut( $sectContent, 40 );
        $pageSectionsValues[] = $ps[id];
    }

}

array_unshift( $pageSectionsOutput, '- Select Page Section -' );
array_unshift( $pageSectionsValues, '- Select Page Section -' );

$t->assign( 'pageSectionsOutput', $pageSectionsOutput );
$t->assign( 'pageSectionsValues', $pageSectionsValues );


$t->assign( 'form_id', $formID );

if ( $formID != 'NEW' ) {

	$form->load( $formID );

	$currentFormFields = $form->fields;

	//$t->assign( 'form_title', $form->settings['title'] );
	//$t->assign( 'form_desc', $form->settings['form_desc'] );
	//$t->assign( 'form_to', $form->fields['form_to'] );
	//$t->assign( 'form_cc', $form->fields['form_cc'] );
	//$t->assign( 'form_subject', $form->fields['form_subject'] );
	//$t->assign( 'skin_id', $form->fields['skin_id'] );
	$t->assign( 'searchReportId', $form->fields['search_report_id'] );
	$t->assign( 'isSearchForm', $form->fields['is_search_form'] );

	$t->assign( 'form_redirect', $form->fields['redirect_type'] . '-' . $form->fields['redirect_id'] );
	$t->assign( 'other_redirect', $form->fields['other_redirect_type'] . '-' . $form->fields['other_redirect_id'] );

	//$t->assign( 'generate_report', $form->fields['generate_report'] );

	$t->assign( 'numViews', $form->fields[counter] );
	$t->assign( 'numSubmissions', $form->fields[counter_submit] );
}




$basePath = 'http://' . $_SERVER['SERVER_NAME'] . dirname( $_SERVER['PHP_SELF'] );

// path to test forms
$formPath = $basePath . '/getForm.php' . '?form_id=' . $form->fields['id'];
$formPath = stri_replace( '/' . ADMIN_DIR . '/', '/', $formPath );

$t->assign( 'formPath', $formPath );

// get a list of all available "lists"

$tempListData = $db->getAll( 'select * from ' . LISTS_TABLE . " where site_key = '$site' order by _order" );
$listData = array();

foreach( $tempListData as $index => $row ) {

	if ( strlen( $row[title] ) > 27 )
	$row[title] = substr( $row[title], 0, 25 ) . '...';

	$listData[ "$row[list_key]" ] = $row[title];
}

$t->assign( 'listData', $listData );

$modMan = new Module_Manager();

$modules = $modMan->getModules();

$moduleTypeLabels = array();
$moduleTypeValues = array();

foreach( $modules as $module ) {
	$moduleTypeLabels[] = ucfirst( $module[module_key] ) .' Categories';
	$moduleTypeValues[] = 'modcat_'.$module[id];
}


$fieldTypeValues = array_merge( array( 'text', 'textarea', 'radio', 'checkbox', 'select', 'password', 'date', 'image', 'file', 'page_section', 'user_groups' ), $moduleTypeValues );
$fieldTypeLabels = array_merge( array( 'Single-line Text', 'Multi-line Text', 'Radio Group', 'Checkbox', 'Select/List', 'Password', 'Date', 'Image Upload', 'File Upload', 'Page Section', 'User Groups Combo' ), $moduleTypeLabels );

$t->assign( 'fieldTypeValues', $fieldTypeValues );
$t->assign( 'fieldTypeLabels', $fieldTypeLabels );

$t->assign( 'validatorTypeValues', array( '', 'email', 'website', 'numeric', 'alphabetic', 'alpha-numeric' ) );
$t->assign( 'validatorTypeLabels', array( '(none)', 'E-Mail', 'Website', 'Numeric', 'Alphabetic', 'Alphanumeric' ) );

$t->assign( 'title', 'Edit Form Sections' );

// get all available forms for purposes of the jump menu
//$fields = array( 'id', 'form_title' );
//$conds = array( 'site_key' => $site );

$sharedForms = getSQLShares( 'form' );
$forms = $db->getAll( 'select resource_id as id, property, if(resource_id in ('.$sharedForms.'), concat(value, \'[shared]\'), value) as form_title from '.SETTINGS_TABLE." where resource_type='form' and property='title' and (site_key='$site' or resource_id in ($sharedForms) ) order by value" );

$formIds = array();
$formNames = array();

foreach( $forms as $index => $form ) {

	$formIds [] = $form['id'];

	if ( $form['id'] == $formID )
		$formTitle = $form['form_title'];

	$redirectTitle = $form['form_title'];

	$formNames [] = $form['form_title'];

	// add the forms to the redirect options

	$redirectValues [] = 'form-' . $form['id'];
	$redirectOutput [] = 'form - ' . $redirectTitle;
}

// get all available reports as possible redirects from a form

$shared = getSQLShares( 'report' );
$reports = $db->getAll( 'select id, if(id in ('.$shared.'), concat(name, \'[shared]\'), name) as name from ' . REPORTS_TABLE . " where (site_key = '$site' or id in ($shared)) order by name" );

$reportIds = array();
$reportNames = array();

foreach( $reports as $index => $report ) {
	$reportIds [] = $report['id'];

	$redirectTitle = $report['name'];

	// so that the names in the combo box do not spill over too much
	if ( strlen( $redirectTitle ) > 30 )
		$redirectTitle = substr( $redirectTitle, 0, 27 ) . '...';

	// add the forms to the redirect options

	$redirectValues [] = 'report-' . $report['id'];
	$redirectOutput [] = 'report - ' . $redirectTitle;
}

$redirectValues[] = 'url';
$redirectOutput[] = 'new url';

if ( $currentFormFields[redirect_type] == 'url' ) {
    $redirectValues[] = 'url-'.$currentFormFields[redirect_id];
    $redirectOutput[] = 'url - '. cut( $currentFormFields[redirect_id], 30 );
}

// selection redirect if any of the conditions was not met
//$otherRedValues = $redirectValues;
//$otherRedOutput = $redirectOutput;

$redirectValues[] = 'condition-0';
$redirectOutput[] = 'Conditional';

// get the available 'redirect' options


$t->assign( 'redirectValues', $redirectValues );
$t->assign( 'redirectOutput', $redirectOutput );

$t->assign( 'numForms', sizeof( $formNames ) );

$t->assign( 'form_ids', $formIds );
$t->assign( 'form_titles', $formNames );



$resourceType = 'form';
$resourceId = $formID;

$menuData = $db->getAll( 'select * from ' . MENUITEMS_TABLE . " where resource_type = '$resourceType' and resource_id = '$resourceId' and resource_id !='' " );

$t->assign( 'menuLinkageDegree', sizeof( $menuData ) );
$t->assign( 'menuData', $menuData );


$t->assign( 'disabledElementColor', '#DDDDDD' );

// add one item to the beginning of $data for the "new" section
array_unshift( $data, array( 'id' => 'NEW' ) );

$t->assign( 'data', $data );




// if we are in a "child" website, then assign the $skin->parentSite so that
// we can get any shared skins, shared to us by the parent site administrator

if ( $_SESSION['es_auth']['user_site_key'] == $site ) {

    // we are in a user-specific site
    // determine the parent site, so that we can copy over the settings

    $parentSite = $_SESSION['es_auth']['site_key'];

    $skin->setParentSite( $parentSite );
}

$allSkins = $skin->getAll();// $skin object is from init.php

$t->assign( 'skins', $allSkins );

$t->assign( 'form_id', $formID );

$redirects = $fRedir->loadByParentId( $formID );

$t->assign( 'condRedirects', $redirects );


// ---------------------------
// get available reports array
// ---------------------------

include_once( INCLUDE_DIR . 'internal/db_items/class.Report.php' );

$report = new Report();

/*$sharedReports = getSQLShares( 'report' );
$reportTitles = $report->loadColumnCond( "if(id in ($sharedReports), concat(name, '[shared]'), name) as name", array( 'site_key'=>$site ) );
array_unshift( $reportTitles, '- Select Report -' );

$reportValues = $report->loadColumnCond( 'id', array( 'site_key'=>$site ) );
array_unshift( $reportValues, 0 );
*/

list( $reportValues, $reportTitles ) = $report->getKeyTitle( 'id', 'name' );

$t->assign( 'reportTitles', $reportTitles );
$t->assign( 'reportValues', $reportValues );

$permissions = array(
    'add'    => hasAdminAccess( 'cm_form_add' ),
    'edit'   => hasAdminAccess( 'cm_form_edit' ),
    'delete' => hasAdminAccess( 'cm_form_delete' )
);

$t->assign( 'permissions', $permissions );

if ( !$hasAccess ) {
    $t->assign( 'bodyTemplate', 'manage/authError.tpl' );
}
else {
    $t->assign( 'bodyTemplate', 'manage/editForms.tpl' );
}

if ( $formID != 'NEW' )
	$title = "Edit Form ({$formTitle})";
else
	$title = 'Create New Form';

$session->updateLocation( 'form_manager', $title, array( 'formID', 'form_id', 'add_form' ) );

include_once( '../init_bottom.php' );

$t->display( $templateName );


?>
