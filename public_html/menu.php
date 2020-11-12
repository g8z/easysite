<?php

require( INCLUDE_DIR . 'internal/menuFunctions.php' );


function buildCache( $menuId, $startid ) {

	global $t;

	$menu = new Menu();

	// Read menu settings from the DB
	$menuSettings = $menu->getMenuSettings( $menuId );

	$menu->settings[ALL_LEVELS]['one_branch'] = $menu->settings[ALL_LEVELS]['one_branch'] == 'yes' ? true : false;
	$menu->settings[ALL_LEVELS]['show_images'] = $menu->settings[ALL_LEVELS]['show_images'] == 'yes' ? true : false;
	//$node_opens_branch = $node_opens_branch == 'yes' ? true : false;
	$menu->settings[ALL_LEVELS]['explorer_tree'] = $menu->settings[ALL_LEVELS]['explorer_tree'] == 'yes' ? true : false;
	$menu->settings[ALL_LEVELS]['show_folder_image'] = $menu->settings[ALL_LEVELS]['show_folder_image'] == 'yes' ? true : false;
	$menu->settings[ALL_LEVELS]['use_bg_color'] = $menu->settings[ALL_LEVELS]['selected_bg_color'] === '' ? false : true;

	extract( $menu->settings[ALL_LEVELS] );

	// get styles

	if ( $type == 'Standard' ) {

		$styles = '';

		for ( $i=-1, $n=count( $menuSettings ); $i<$n; $i++ ) {

			if ( @count( $menu->settings[$i] ) )
				$t->assign_by_ref( 'ms', $menu->settings[$i]);

			$t->assign( 'menu_level', $i );
			$styles .= $t->fetch( 'menus/coolmenu_style.css' );

		};


		// get script

		if ($type == 'Horizontal') {
			$leveloff = array( 'dy'=>strval($menu_h - $border_size + $sub_menu_x), 'dx'=>strval($sub_menu_y) );
			$itemoff  = array( 'dy'=>0, 'dx'=>strval($menu_w - $border_size) );
		}
		else {
			$leveloff = array( 'dy'=>strval($sub_menu_x), 'dx'=>strval($menu_h - $border_size + $sub_menu_y) );
			$itemoff  = array( 'dy'=>strval($menu_h - $border_size), 'dx'=>0 );
		}

		$t->assign( 'leveloff', $leveloff );
		$t->assign( 'itemoff', $itemoff );


		list( $menuScript, $menuLinks ) = $menu->drawDHTMLMenu( $startid, $menuId );

		$t->assign_by_ref( 'ms', $menu->settings[ALL_LEVELS]);
		$t->assign_by_ref( 'menuScript', $menuScript );
		$script = $t->fetch( 'menus/coolmenu_script.js' );

	}
	else {

		$t->assign_by_ref( 'ms', $menu->settings[ALL_LEVELS]);
		$styles .= $t->fetch( 'menus/treemenu_style.css' );

		list( $menuScript, $menuLinks ) = $menu->drawTreeMenu( $startid, $menuId );

		$t->assign( 'menuScript', $menuScript );
		$script = $t->fetch( 'menus/treemenu_script.js' );

	}

	unset( $menu );

	writeCache( 'menu_js', $script, $menuId );
	writeCache( 'menu_css', $styles, $menuId );
	$t->assign( 'menuLinks', $menuLinks );
	$t->assign( 'menuId', $menuId );
	writeCache( 'menu_links', $t->fetch( 'menus/menu_links.tpl' ), $menuId );
}

$startid = 0;

$menu = new Menu();

$shared = getSQLShares( 'menu' );
$query = "select id, restrict_to from ". MENUS_TABLE. " where (site_key='$system->site' or id in ($shared))";
$menuIds = $db->getAll( $query );

foreach( $menuIds as $key => $val ) {

	if ( !$system->isVisible( $val['restrict_to'] ) )
		continue;

	$menuId = $val[id];
	$script = "";

	$t->assign( 'menuId', $menuId );

	// If no items then skip this menu
	$query = "select id from ". MENUITEMS_TABLE ." where menu_id='$menuId' limit 1";
	$res = $db->getOne( $query );

	if ( !$res )
		continue;

	// check if we need to generate styles and js
	$settingsLastChange = $db->getOne( 'select max(UNIX_TIMESTAMP(last_change)) from '.SETTINGS_TABLE." where (resource_type='menu' and resource_id='$menuId') or (resource_type='site' and site_key='{$system->site}')" );
	$itemsLastChange = $db->getOne( 'select max(UNIX_TIMESTAMP(last_change)) from '.MENUITEMS_TABLE ." where menu_id='$menuId'" );
	$menuLastChange = time(); //max( $settingsLastChange, $itemsLastChange );

	$cacheLastChange = min( @filemtime(getCacheFileName( 'menu_js', $menuId )), @filemtime(getCacheFileName( 'menu_css', $menuId )) );

	if ( $menuLastChange<1 || ($cacheLastChange < $menuLastChange) )
		buildCache( $menuId, $startid );

	$type = $db->getOne( 'select value from '.SETTINGS_TABLE ." where site_key='$site' and property='type' and resource_type='menu' and resource_id='$menuId' and (param=-1 or param='')" );

	$menuLinks = getCache( 'menu_links', $menuId );
	switch( $type ) {

		// ---------
		// tree menu
		// ---------
		case 'Tree':

			$menudata = '
			var t' . $menuId . '=new COOLjsTreePRO("t' . $menuId . '", TREE' . $menuId . '_NODES, TREE' . $menuId . '_FORMAT);
			t' . $menuId . '.init();';

			$t->assign( 'treeMenuExists', 1 );
			break;


		// ------------
		// coolmenu pro
		// ------------
		case 'Standard':
		default:


			$menudata = 'var m' . $menuId . ' = new COOLjsMenuPRO("menu' . $menuId . '", MENU_ITEMS_BORDERSANDSHADOW' . $menuId . ');
			m' . $menuId . '.initTop();
			m' . $menuId . '.init();
			m' . $menuId . '.show();';

			$t->assign( 'coolMenuExists', 1 );
			break;

}

$menus[] = array( 'id'=>$menuId, 'data'=>$menudata, 'links'=>$menuLinks, 'lastChange'=>$menuLastChange );
}

?>