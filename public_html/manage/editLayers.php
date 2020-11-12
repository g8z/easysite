<?php

if ( !defined( 'SMARTY_DIR' ) )
    include_once( '../init.php' );

if ( !hasAdminAccess( 'cm_layer' ) ) {
    $t->assign( 'bodyTemplate', 'manage/authError.tpl' );
    exit;
}

/**
 * Uploads images & updates image paths
 * Function assumes that the image fields are named identically
 * between the pages table and the sections table
 */
function uploadFile( $fieldName ) {

	if ( !$_FILES[$fieldName][name] )
		return;

    global $c, $layer;

    $file = new File( $fieldName );

    if ( $file->isUploaded() ){

        $fields = array(
            $fieldName          => $file->getContent(),
            $fieldName.'_path'  => $file->userName
        );

        $layer->update( $fields );

        $c->_table = LAYERS_TABLE;
        $c->_id = $layer->fields[id];
        $c->_field = $fieldName;
        $c->remove();

	$file->delete();

        // unset this FILES var so that we do not re-add this image
        unset( $_FILES[$fieldName] );
     }

    unset( $file );
}



$hasAccess = true;

// update the 'last update' setting for the site
include_once( INCLUDE_DIR . 'internal/class.site.php' );
$siteObj = new ES_Site( $db );
$siteObj->setLastUpdate( $site );

include_once( INCLUDE_DIR . 'internal/db_items/class.Layer.php' );
include_once( INCLUDE_DIR . 'internal/class.File.php' );

$layer = new Layer();

$layer_id = $_REQUEST['layer_id'];

// determine if we have permissions
if ( $_POST['formIsSubmitted'] ) {

	if ( !$layer_id )
		$hasAccess = hasAdminAccess( 'cm_layer_add' );
	else
		$hasAccess = hasAdminAccess( 'cm_layer_edit' );
}


// check for form submission
if ( $_POST['formIsSubmitted'] && $hasAccess ) {

	    if ( !$layer_id ) {

	        // add new layer

	    	$fields = array( 'site_key' => $site );
	        $layer->create( $fields );
	        $layer_id = $layer->fields[id];
	    }

/*		if ( is_array( $_POST["restrict_to"] ) )
			$_POST["restrict_to"] = implode( ',', $_POST["restrict_to"] );
		else
			$_POST["restrict_to"] = '';
*/
        $fields = array(
                '_left'       => $_POST["left"],
                'top'         => $_POST["top"],
                'width'       => $_POST["width"],
                'height'      => $_POST["height"],
                'align'       => $_POST["align"],
                //'restrict_to'	=> $_POST["restrict_to"],
                'valign'      => $_POST["valign"],
                'zorder'      => intval($_POST["zorder"]),
                'bgcolor'     => $_POST["bgcolor"],
                'padding'     => intval($_POST["padding"]),
                'style'       => $_POST["style"],
                'format'		=> $_POST["format"],
                'content'     => $_POST["content"],
                'nl2br'       => intval($_POST["nl2br"]),
                'title'       => $_POST["title"],
                'settings_override' => intval($_POST["settings_override"]),
                'img_anchor'  => $_POST["img_anchor"],
                'img_link'    => $_POST["img_link"],
                'link_target' => $_POST["link_target"],
                'img_alt'    => $_POST["img_alt"],
        );

        $layer->fields[id] = $layer_id;
        $layer->update( $fields );

        // now handle updates to the images & image paths
        // NOTE: this routine assumes that the image fields are named identically
        // between the layers table and the sections table

        uploadFile( 'img_thumb' );
        uploadFile( 'img_large' );

    }

    // determine if we should delete or bump up a section
    if ( $_POST['deleteSectionVar'] ) {

    	if ( $hasAccess = hasAdminAccess( 'cm_layer_delete' ) ) {
    		$layer->delete( $layer_id );
    	}

    }

    if ( hasAdminAccess( 'cm_layer_edit' ) ) {

    	// set the image caching vars
    	$c->_table = LAYERS_TABLE;
    	$c->_id = $layer_id;

        // determine if we should delete an image
        if ( $_POST['deleteThumbImg'] ) {

            // remove the image content AND the image path
            $db->query( "update ".LAYERS_TABLE." set img_thumb = '', img_thumb_path = '' where id = '$layer_id'" );

    		$c->_field = 'img_thumb';
    		$c->remove();
        }
        else if ( $_POST['deleteLargeImg'] ) {

            // remove the image content AND the image path
            $db->query( "update ".LAYERS_TABLE." set img_large = '', img_large_path = '' where id = '$layer_id'" );

    		$c->_field = 'img_large';
    		$c->remove();
        }

    }

    $layer->load( $layer_id );

	$choose_restrict_to = $_REQUEST['choose_restrict_to'];

	list( $r, $i ) = split( '-', $choose_restrict_to );
	$location = array( 'resource'=>$r, 'id'=>$i );

	$t->assign( 'choose_restrict_to', $choose_restrict_to );

	$shared = getSQLShares( 'layer', 'edit' );
	$data = $db->getAll( "select *, if(id in ($shared), concat(title, '[shared]'), title) as title from ".LAYERS_TABLE." where (site_key = '$site' or id in ($shared)) order by title" );

	// massage the data somewhat to accomodate for the layer restrictions to pages & forms

	$layerList = array( '' => '- Add New Layer -' );
	$layerListSettings = array( '' => '(use specified settings)' );

	foreach( $data as $index => $row ) {

		if ( !trim( $row[title] ) )
			$row[title] = 'layer_' . $row[id];

		if ( !$choose_restrict_to || $system->isVisible( $row[restrict_to], $location ) || $layer_id == $row['id'] )
			$layerList[ "$row[id]" ] = $row[title];

		if ( $layer_id != $row['id'] )
			$layerListSettings[ "$row[id]" ] = $row[title];

		$data[$index] = $row;
	}

	// layerList is used for the "override these options with settings from" combo

	$t->assign( 'layerList', $layerList );
	$t->assign( 'layerListSettings', $layerListSettings );

	$layerRestrictValues = array( 'page-all', 'form-all', 'report-all', 'module-0', 'cm_tools-0' );
	$layerRestrictOutput = array( 'All Pages', 'All Forms', 'All Reports', 'Modules', 'Content Management Tools' );

	// get a list of all forms & pages & reports

	$shared = getSQLShares( 'page' );
	$allPages = $db->getAll( 'select id, if(id in ('.$shared.'), concat(title, \'[shared]\'), title) as title from ' .PAGES_TABLE . " where (site_key = '$site' or id in ($shared)) order by title" );

	$shared = getSQLShares( 'form' );
	$allForms = $db->getAll( 'select resource_id as id, if(resource_id in ('.$shared.'), concat(value, \'[shared]\'), value) as form_title from '.SETTINGS_TABLE." where resource_type='form' and property='title' and (site_key='$site' or resource_id in ($shared)) order by value" );

	$shared = getSQLShares( 'report' );
	$allReports = $db->getAll( 'select id, if(id in ('.$shared.'), concat(name, \'[shared]\'), name) as name from ' . REPORTS_TABLE . " where (site_key = '$site' or id in ($shared)) order by name" );

	foreach( $allPages as $index => $row ) {
		$layerRestrictValues [] = 'page-' . $row[id];
		$layerRestrictOutput [] = 'page - ' . $row[title];
	}
	foreach( $allForms as $index => $row ) {
		$layerRestrictValues [] = 'form-' . $row[id];
		$layerRestrictOutput [] = 'form - ' . $row[form_title];
	}
	foreach( $allReports as $index => $row ) {
		$layerRestrictValues [] = 'report-' . $row[id];
		$layerRestrictOutput [] = 'report - ' . $row[name];
	}

	$t->assign( 'layerRestrictValues', $layerRestrictValues );
	$t->assign( 'layerRestrictOutput', $layerRestrictOutput );


	// which items should be selected (by values, not array indices );
	//$t->assign( 'selectedLayerRestrictOutput', $selectedLayerRestrictOutput );

// add one item to the beginning of $data for the "new" section
//array_unshift( $data, array( 'id' => 'NEW', 'nl2br'=>0 ) );

//$t->assign( 'data', $data );

$t->assign( 'layer_id', $layer_id );
$t->assign( 'editLayer', $layer->fields );

$styleList = $system->getStyleList();

// create the 'format' lists

$formatList = array(
	''			=> '(none)',
	'bullet' 	=> 'Bullets',
	'mbullet'	=> 'Mini-bullets',
	'arrow'		=> 'Arrows',
	'circle'	=> 'Circles',
	'asterisk' 	=> 'Asterisks',
	'dash'		=> 'Dashes',
	'number_1' 	=> '1. 2. 3.',
	'number_2' 	=> '1) 2) 3)',
	'number_3' 	=> '1 2 3',
	);

$t->assign( 'formatList', $formatList );

$anchorValues = array( '', 'left', 'right' );
$anchorNames = array( '---', 'left', 'right' );

$t->assign( 'anchorValues', $anchorValues );
$t->assign( 'anchorNames', $anchorNames );
$t->assign( 'styleList', $styleList );
$t->assign( 'menuItemText', 'Link to Menu Item: ' );
$t->assign( 'specialOperationsBackground', '#DDDDDD' );
$t->assign( 'disabledElementColor', '#DDDDDD' );

// assign 'align' and 'valign' options
$t->assign( 'align', array( 'left', 'center', 'right' ) );
$t->assign( 'valign', array( 'top', 'middle', 'bottom' ) );



// if we are in a "child" website, then assign the $skin->parentSite so that
// we can get any shared skins, shared to us by the parent site administrator

if ( $_SESSION['es_auth']['user_site_key'] == $site ) {

	// we are in a user-specific site
	// determine the parent site, so that we can copy over the settings

	$parentSite = $_SESSION['es_auth']['site_key'];

	$skin->setParentSite( $parentSite );
}

//$allSkins = $skin->getAll();// $skin object is from init.php

//$t->assign( 'skins', $allSkins );

require_once INCLUDE_DIR . 'BrowserInfo.php';

$userBrowser = new BrowserInfo($_SERVER['HTTP_USER_AGENT']);

$wysiwygCompatible = ( $userBrowser->Browser == 'Internet Explorer' && $userBrowser->Browser_Version >= 5.5 ) || ( $userBrowser->Browser == 'Firefox' && $userBrowser->Browser_Version >= 0.7 ) || ( $userBrowser->Browser == 'Mozilla' && $userBrowser->Browser_Version >= 1.3 );

// assign wysiwyg options

if ( $wysiwygCompatible ) {

	$t->assign( 'wysiwygCompatible', $wysiwygCompatible );

	$t->assign( 'wysiwygLink', "<a href=\"javascript:enableWYSIWYG('content');\"><img border=0 src=\"".DOC_ROOT."images/editor.gif\" ALT=\"Use editor for this section only\"></a>" );
}



/*    if ( $_POST['formIsSubmitted'] ) {

    	$currentPageOrForm = 'X';

        // get the updated layer information
        //$layerData = $db->getAll( 'select * from ' . LAYERS_TABLE . " where site_key = '$site'" );

		$layerData = $db->getAll( 'select * from ' . LAYERS_TABLE . " where site_key = '$site' and restrict_to <> 'all' and ( restrict_to = '0' or restrict_to = '' or restrict_to = 'cm_tools' or restrict_to like '%$currentPageOrForm%' )" );

		$layerData = $system->applyFormat( $layerData );

        // fetch all of the layers for this page & for the background template
        $t->assign( 'layerData', $layerData );
    }*/


$t->assign( 'newLineCombo', array( 'Use System Default', 'Convert to Line Breaks', 'Ignore New Lines' ) );
$t->assign( 'linkOptions', array( '_blank'=>'_blank', '_self'=>'_self', '_parent'=>'_parent' ));

// for {img} tag
$t->assign( 'table', LAYERS_TABLE );

$permissions = array(
    'add'    => hasAdminAccess( 'cm_layer_add' ),
    'edit'   => hasAdminAccess( 'cm_layer_edit' ),
    'delete' => hasAdminAccess( 'cm_layer_delete' )
);
$t->assign( 'permissions', $permissions );


if ( !$hasAccess ) {
	$t->assign( 'bodyTemplate', 'manage/authError.tpl' );
}
else {
	$t->assign( 'bodyTemplate', 'manage/editLayers.tpl' );
}

if ( $layer_id )
	$title = "Edit Layer (". cut( $layer->fields[title], 30 ) . ")";
else
	$title = 'Add Layer';

$session->updateLocation( 'layer_manager', $title, array( 'layer_id', 'choose_restrict_to' ) );
include_once( '../init_bottom.php' );

$t->display( $templateName );



?>
