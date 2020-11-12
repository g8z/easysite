<?php
if ( !defined( 'SMARTY_DIR' ) )
    include_once( '../init.php' );

include_once( INCLUDE_DIR . 'internal/class.File.php' );

// get the properties for this menu item

$table = MENUITEMS_TABLE;
$settingstable = SETTINGS_TABLE;

$id = intval( $_REQUEST['id'] );

deleteCache( 'menu_js', $menu_id );
deleteCache( 'menu_css', $menu_id );

$menuType = $db->getOne ( 'select ' . SETTINGS_TABLE . '.value from '. SETTINGS_TABLE .', '.$table." where
          $settingstable.site_key      = '$site' and
          $settingstable.property      = 'type' and
          $settingstable.resource_type = 'menu' and
          $settingstable.resource_id      = $table.menu_id and
          $table.id       = '$id'");

if ( $_POST['submitAdvancedPropertiesForm'] ) {

    if ( $menuType == 'Standard' )
    {
    	// default value for unchecked checkboxes

    	if ( !$_POST['sticky_rollover'] ) {
    		 $_POST['sticky_rollover'] = '0';
    	}

		$db->query( " update $table set
			over_style 		= '" . $_POST['over_style'] . "',
			out_style 		= '" . $_POST['out_style'] . "',
			item_width 		= '" . $_POST['item_width'] . "',
			item_height 	= '" . $_POST['item_height'] . "',
			borders			= '" . $_POST['borders'] . "',
			x_offset		= '" . $_POST['x_offset'] . "',
			y_offset		= '" . $_POST['y_offset'] . "',
			out_color		= '" . $_POST['out_color'] . "',
			over_color		= '" . $_POST['over_color'] . "',
			borders			= '" . $_POST['borders'] . "',
			image_width		= '" . $_POST['img_width'] . "',
			image_height	= '" . $_POST['img_height'] . "',
			sticky_rollover	= '" . $_POST['sticky_rollover'] . "',
			target       	= '" . $_POST['target'] . "',
			in_template    	= '" . $_POST['in_template'] . "'
				where id = '$id' and site_key = '$site'" );
   }
   elseif ($menuType == 'Tree')
   {

        $currentlevel = $db->getOne( 'select level from '. $table ." where id='$id'" );

		$db->query("update $table set
			over_style		= '".$_POST['over_style']."',
			out_style		= '".$_POST['out_style']."',
			out_color		= '".$_POST['out_color']."',
			over_color		= '".$_POST['over_color']."'
		where level='$currentlevel' and site_key='$site'");

   }
   elseif ($menuType == 'Outlook')// coming to EasySite 1.5!
   {
          //$level=$db->GetOne( "select level from $table where id='$id' and site_key='$site'" );
          $db->query("update $table set
          over_style        = '".$_POST['over_style']."',
          out_style         = '".$_POST['out_style']."',
          borders           = '".$_POST['borders']."',
          out_color         = '".$_POST['out_color']."',
          over_color        = '".$_POST['over_color']."',
          borders           = '".$_POST['borders']."'
          where id = '$id' and site_key = '$site'");
          if ($level==0) {
              $noimageupload=1;
          }
          else {
              $noimageupload=0;
          }
   }
   elseif ($menuType=='Html')
   {
   }
	// update the images, if uploaded

	$files = array( 'image_out', 'image_over' );
    $conds = array( 'id' => $id );

	foreach ( $files as $num => $name ) {

    	$image = new File( $name );

    	if ( $image->isUploaded() )
    	    $image->updateTable( $table, $name, $conds );
	$image->delete();

    	unset( $image );
	}

	$c->_table = $table;
	$c->_id = $id;

	if ( $_POST['deleteImageType'] == 'image_over' ) {

		$db->query( "update $table set image_over = '' where id = '$id' and site_key = '$site'" );

	}

	if ( $_POST['deleteImageType'] == 'image_out' ) {

		$db->query( "update $table set image_out = '' where id = '$id' and site_key = '$site'" );

	}
    	$c->_field = 'image_over';
    	$c->remove();
		$c->_field = 'image_out';
    	$c->remove();

}

$properties = $db->getRow( 'select * from ' . MENUITEMS_TABLE . " where id = '$id' and site_key = '$site'" );

$properties["menuType"] = $menuType;

$styleList = $system->getStyleList();

//Check to see if we have childreen for this menu item (this is for knowing if we are showing expanded/colapsed image or only one image

$properties["hasChildren"] = $db->getOne( "select id from $table where parent = '$id' limit 1" );

// re-create the menu code so that we can see the changes!

// not needed with 'init_bottom.php
//$t->assign( 'menus', showMenus(0) );

$t->assign( 'targetOptions', array( '_self', '_blank', '_parent' ) );
$t->assign( 'booleanOptions', array( 'yes', 'no' ) );

$t->assign( 'title', 'Advanced Menu Item Properties' );
$t->assign( 'styleList', $styleList );
$t->assign( 'properties', $properties );

// determine which menu we are editing the properties for

$menuItemTitle = $db->getOne( 'select title from ' . MENUITEMS_TABLE . " where id = '$id' and site_key = '$site' limit 1" );
$t->assign( 'menuItemTitle', $menuItemTitle );
$t->assign( 'menuItemType', $db->getOne( 'select resource_type from ' . MENUITEMS_TABLE . " where id = '$id' and site_key = '$site' limit 1" ) );

$t->assign( 'menuManagerReturnLink', "[ <a href=editMenu.php?menu_id=" . $properties[menu_id] . ">Return to the Menu Manager</a> ]" );

if ( !hasAdminAccess( 'cm_menu' ) ) {
    $t->assign( 'bodyTemplate', 'manage/authError.tpl' );
}
else {
    $t->assign( 'bodyTemplate', 'manage/advancedMenuEditor.tpl' );
}

$session->updateLocation( 'advanced_menu_manager', "Advanced Item Properties ($menuItemTitle)", array( 'id' ) );

include_once( '../init_bottom.php' );

$t->display( $templateName );

?>
