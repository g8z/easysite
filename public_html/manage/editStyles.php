<?php
if ( !defined( 'SMARTY_DIR' ) )
    include_once( '../init.php' );

if ( !hasAdminAccess( 'cm_style' ) ) {
    $t->assign( 'bodyTemplate', 'manage/authError.tpl' );
    exit;
}

$hasAccess = true;

// update the 'last update' setting for the site
include_once( INCLUDE_DIR . 'internal/class.site.php' );
$siteObj = new ES_Site( $db );
$siteObj->setLastUpdate( $site );

if ( $loadFromSkin && !$_POST['formIsSubmitted'] ) {
    
    if ( $hasAccess = hasAdminAccess( 'cm_style_load_from_skin' ) ) {

    	if ( $_SESSION['es_auth']['user_site_key'] == $site ) {
    
    		// we are in a user-specific site
    		// determine the parent site, so that we can copy over the settings
    
    		$parentSite = $_SESSION['es_auth']['site_key'];
    
    		$skin->setParentSite( $parentSite );
    	}
    
    	$skin->makeDefault( $loadFromSkin, STYLES_TABLE );
    	//$t->assign( 'loadFromSkin', $loadFromSkin );
    }
}

// check for form submission
if ( $_POST['formIsSubmitted'] ) {

    $seen = array();

    // save all changed form data first
    foreach( $_POST as $key => $var ) {

        if ( !preg_match( '/([a-zA-Z]*?_)+([0-9]+)/', $key, $matches ) )
            continue;
        else
            $id = $matches[ count( $matches ) - 1 ];
            
        if ( in_array( $id, $seen ) )
            continue;
        else
            $seen[] = $id;
            

        if ( $_POST["user_defined_$id"] > 0 )
    		$_POST["name_$id"] = '.' . $_POST["name_$id"];

		if ( $skin_id )
			$skinClause = " and skin_id = '$skin_id'";

        if ( $hasAccess = hasAdminAccess( 'cm_style_edit' ) ) {
    	    $db->query( "update ".STYLES_TABLE." set
                    name  			= '" . $_POST["name_$id"] . "',
                    bold 			= '" . $_POST["bold_$id"] . "',
                    underline 		= '" . $_POST["underline_$id"] . "',
                    italic 			= '" . $_POST["italic_$id"] . "',
                    font 			= '" . $_POST["font_$id"] . "',
                    size 			= '" . $_POST["size_$id"] . "',
                    color 			= '" . $_POST["color_$id"] . "',
                    bg_color 		= '" . $_POST["bg_color_$id"] . "',
                    user_defined 	= '" . $_POST["user_defined_$id"] . "'
                where id = $id $skinClause"
            );
            
        }
    }
    
    // add new form data, if needed
    if ( $_POST['addNewItem'] ) {
        
        if ( $hasAccess = hasAdminAccess( 'cm_style_add' ) ) {

        extract( $_POST );

		// add a '.' in front of user-defined styles
		$name_NEW = '.' . $name_NEW;

        $db->query( "insert into ".STYLES_TABLE." (
            site_key,
            name,
            bold,
            underline,
            italic,
            font,
            size,
            color,
            bg_color,
            user_defined
            ) values (
            '$site',
            '$name_NEW',
            '$bold_NEW',
            '$underline_NEW',
            '$italic_NEW',
            '$font_NEW',
            '$size_NEW',
            '$color_NEW',
            '$bg_color_NEW',
            '$user_defined_NEW'
            )" );
        }
    
    }
    
}
        
    // determine if we should delete a section
    if ( $_POST['deleteSectionVar'] ) {
        if ( $hasAccess = hasAdminAccess( 'cm_style_add' ) ) {
            $db->query( "delete from ".STYLES_TABLE." where id = '" . $_POST['deleteSectionVar'] . "'" );
        }
    }

	$t->assign( 'font_families', array( '--', 'Arial', 'Verdana', 'Georgia', 'Times', 'Courier' ) );
	//$t->assign( 'font_styles', array( 'normal', 'italic' ) );
	//$t->assign( 'font_weights', array( 'normal', 'bold' ) );

	$fontSizes = array( '--' );

	$inc = 1;

	for ( $sz = 4; $sz <= 72; $sz += $inc ) {

		$fontSizes [] = $sz;

		if ( $sz >= 20 )
			$inc = 2;

		if ( $sz >= 40 )
			$inc = 4;

		if ( $sz >= 60 )
			$inc = 6;
	}

	$t->assign( 'font_sizes', $fontSizes );

	// if a specific skin has been requested, then add an extra query parameter
	if ( $skin_id ) {
		$t->assign( 'skin_id', $skin_id );

		// get the name of this skin, too

		$shared = getSQLShares( 'skin', 'edit' );
		$t->assign( 'skin_name', $db->getOne( 'select if(id in ('.$shared.'), concat(name, \'[shared]\'), name) as name from ' . SKINS_TABLE . " where id = '$skin_id' and (site_key = '$site' or id in ($shared))" ) );

		$skinOptions = " and skin_id = '$skin_id'";
	}
	else
		$skinOptions = " and skin_id < 1";

	$shared = getSQLShares( 'style', 'edit' );
	$data = $db->getAll( "select *, if(id in ($shared), 1, 0) as shared from ".STYLES_TABLE." where (site_key = '$site' or id in ($shared)) $skinOptions order by user_defined, name" );
	//$data = $db->getAll( "select * from $table where site_key = '$site' $skinOptions" );

	// get updated styles

	$system->getStyles();

	
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


// add one item to the beginning of $data for the "new" section
array_unshift( $data, array( 'id' => 'NEW' ) );

$t->assign( 'data', $data );


// populate the list of available skins (for style editor)
$availableSkins = $skin->getAll();

if ( sizeof( $availableSkins ) == 0 )
	$availableSkins = array( '' => '(no skins present)' );

// to populate the skins combo box only
$t->assign( 'availableSkins', $availableSkins );

$permissions = array(
    'add'    => hasAdminAccess( 'cm_style_add' ),
    'edit'   => hasAdminAccess( 'cm_style_edit' ),
    'load_from_skin'   => hasAdminAccess( 'cm_style_load_from_skin' ),
    'delete' => hasAdminAccess( 'cm_style_delete' )
);

$t->assign( 'permissions', $permissions );

if ( !$hasAccess ) {
	$t->assign( 'bodyTemplate', 'manage/authError.tpl' );
}
else {
	$t->assign( 'bodyTemplate', 'manage/editStyles.tpl' );
}

$session->updateLocation( 'style_manager', 'Edit Styles' );	
include_once( '../init_bottom.php' );

$t->display( $templateName );

            
            
            
?>