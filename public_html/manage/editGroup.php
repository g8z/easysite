<?php
if ( !defined( 'SMARTY_DIR' ) )
    include_once( '../init.php' );

require_once( INCLUDE_DIR . 'internal/editPermissions.php' );

$groupID = intval( $_REQUEST['groupID'] );

if ( !hasAdminAccess( 'cm_users' ) || ( !$groupID && !hasAdminAccess( 'cm_users_gr_add' ))) {
    $t->assign( 'bodyTemplate', 'manage/authError.tpl' );
	$t->display( $templateName );
    exit;
}    

$hasAccess = true;
    
/**
* Determine is the group exists with the given name
*/
function groupExists( $groupName ) {
    global $db, $site;

    $c = $db->getOne( "select count(id) from " . GROUPS_TABLE . " where name like '$groupName' and site_key = '$site'" );

    return $c > 0;
}



if ( $_POST['formIsSubmitted'] ) {

    $name = $_POST['name'];
    $description = $_POST['description'];

    // add/update group name & description
    if ( $groupID == 'NEW' ) {
        
        if ( $hasAccess = hasAdminAccess( 'cm_users_gr_add' ) ) {

            // check to see if a group of this name already exists
    
            if ( groupExists( $name ) ) {
                $t->assign( 'groupErr', $name );
                $name .= '_' . time();
            }
    
            $db->query( 'insert into ' . GROUPS_TABLE . "
            (
                name,
                description,
                site_key
            )
            values
            (
                '$name',
                '$description',
                '$site'
            )" );
    
            // get the new groupID
    
            $groupID = $db->getOne( 'select max(id) from ' . GROUPS_TABLE );
        
        }
        
    }
    else {

        if ( $hasAccess = hasAdminAccess( 'cm_users_gr_edit' ) ) {
        
            $previousGroupName = $db->getOne( 'select name from ' . GROUPS_TABLE . " where id = '$groupID' and site_key = '$site'" );

            if ( groupExists( $name ) && $name != $previousGroupName ) {
                $t->assign( 'groupErr', $name );
                $name .= '_' . time();
            }
    
            $db->query( 'update ' . GROUPS_TABLE . " set
                name = '$name',
                description = '$description'
                where id = '$groupID'" );
            
        }
    }
    
    if ( $hasAccess )
        updatePermissions( GROUP, $groupID, $site );

}
else if ( $_POST['deleteGroup'] ) {

     if ( $hasAccess = hasAdminAccess( 'cm_users_gr_delete' ) ) {
        $deleteGroupID = $_POST['groupID'];
    
        $db->query( 'delete from ' . PERMISSIONS_TABLE . " where
            group_id = '$deleteGroupID' and site_key = '$site'" );
    
        $db->query( 'delete from ' . GROUPS_TABLE . " where
            id = '$deleteGroupID' and site_key = '$site'" );
    
        $groupID = '';
     }
}

$restrictedSections2 = getRestrictedSections( GROUP, $groupID );

// get all available forms, pages, layers, etc. In this version of EasySite, administrators
// may restrict access to entire forms, pages, and files, but not to page/form *sections*
// (possibly add this feature in a later version of EasySite?)

$permissions = array(
    'cm_tools'      => fetchAll( 'cm_tools', $restrictedSections2, $groupID ),
    'form'          => fetchAll( 'form', $restrictedSections2, $groupID ),
    //'form_section'    => fetchAll( 'form_section' ),
    'page'          => fetchAll( 'page', $restrictedSections2, $groupID ),
    //'page_section'    => fetchAll( 'page_section' ),
    //'layer'       => fetchAll( 'layer' ),
    //'menu'            => fetchAll( 'menu' ),
    //'menu_item'   => fetchAll( 'menu_item' ),
    'file'          => fetchAll( 'file', $restrictedSections2, $groupID )
);

// determine if we are adding a new group, or editing an existing group
if ( $groupID ) {
    $t->assign( 'type', 'Edit' );
    $t->assign( 'groupID', $groupID );
}
else
    $t->assign( 'type', 'Add' );

$data = $db->getRow( 'select * from ' . GROUPS_TABLE . " where id = '$groupID' and site_key = '$site'" );

$t->assign( 'data', $data );

$t->assign( 'siteTitle', $db->getOne( "select title from " . SITES_TABLE . " where site_key = '$site' limit 1" ) );

// get a list of all restricted sections, and also a list of all

$t->assign( 'addGroupLink', '[ <a href=editGroup.php>Add a Group</a> ]' );
$t->assign( 'groupReturnLink', '[ <a href=usersAndGroups.php>Edit a Different Group</a> ]' );

$t->assign( 'resources', $permissions );

$permissions = array(
    'add'    => hasAdminAccess( 'cm_users_gr_add' ),
    'edit'   => hasAdminAccess( 'cm_users_gr_edit' ),
    'delete' => hasAdminAccess( 'cm_users_gr_delete' )
);

$t->assign( 'permissions', $permissions );

if ( !$hasAccess ) {
	$t->assign( 'bodyTemplate', 'manage/authError.tpl' );
}
else {
    $t->assign( 'bodyTemplate', 'manage/editGroup.tpl' );
}

if ( $groupID )
	$title = "Edit Group ({$data['name']})";
else
	$title = 'Add Group';
	
$session->updateLocation( 'group_manager', $title, array( 'groupID' ) );

include_once( '../init_bottom.php' );

$t->display( $templateName );

?>