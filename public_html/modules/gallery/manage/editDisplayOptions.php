<?php

require_once '../config.php';

// full | cat_thumb | pr_thumb
$type = $_REQUEST['type'];

switch( $type ) {
	
	case 'full':
		$title = 'Product Image/Details Layout';
		$desc = 'Use the options below to change the layout of the product details relative to the product image. This is what the user sees AFTER clicking on the thumbnail of the product. This product image is therefore usually much larger, and more room is available to display the product options and description. The action arrows re-order the product details relative to one another.';
		break;
		
	case 'cat_thumb':
		$title = 'Category Image/Details Layout';
		$desc = 'Use the options below to change the layout of the text relative to the category image. For example, if "Category Title" is in a green row, then that information will be displayed immediately below the category image.';
		break;
		
	case 'pr_thumb':
	default:
		$title = 'Thumbnail Image/Details Layout';
		$desc = 'Use the options below to change the layout of the product details relative to the thumbnail image. For example, if "Price is in a green row, then the price will be displayed immediately below the thumbnail image of the product. The action arrows re-order the product details relative to one another.';
		break;
}

$t->assign( 'title', $title );
$t->assign( 'description', $desc );

if ( !$type ) {
	$t->assign( 'bodyTemplate', 'modules/gallery/manage/editDisplayOptions.tpl' );
	
	$session->updateLocation( 'display_options', 'Display Options', array( 'type' ) );
	include_once( FULL_PATH . 'init_bottom.php' );
	
	$t->display( $templateName );
	die();
}

if ( $type != 'cat_thumb' && $type != 'pr_thumb' ) 
	$type = 'full';
	
if ( $_POST['isFormSubmitted'] && $editFieldId ) {
	
	// basic update all fields here
	//$fields = $db->getAll( 'select * from '. DISPLAYOPTIONS_TABLE." where site_key='$site' and type='$type'" );
	$row = $db->getOne( 'select max(row) from '.DISPLAYOPTIONS_TABLE." where site_key='$site' and type='$type' and section='{$_POST['section_'.$editFieldId]}'" );
	
	//foreach( $fields as $num=>$field ) {
		$db->query( 'update '. DISPLAYOPTIONS_TABLE.' set
			visible = \''.$_POST['visible_'.$editFieldId].'\',
			section = \''.$_POST['section_'.$editFieldId].'\',
			layout = \''.$_POST['layout_'.$editFieldId].'\',
			style = \''.$_POST['style_'.$editFieldId].'\',
			align = \''.$_POST['align_'.$editFieldId].'\'
			where id=\''.$editFieldId.'\' and site_key=\''.$site.'\' and type=\''.$type.'\'' );
	//}
	changeRow( $editFieldId, 0 );
	normalizePosition( $editFieldId );
	
	//$odbi->reorder( "type='full'" );
	//$odbi->reorder( "type='cat_thumb'" );
	//$odbi->reorder( "type='pr_thumb'" );
	

}

function changeRow( $id, $step ) {
	
	global $db, $site, $type;
	
	//$row = $db->getOne( 'select row from '.DISPLAYOPTIONS_TABLE." where id='$editFieldId'" );
	
	$section = $db->getOne( 'select section from '.DISPLAYOPTIONS_TABLE." where id='$id'" );
	
	$newRowPos = $db->getOne( 'select row_position from '. DISPLAYOPTIONS_TABLE." where row=row+(".$step.") and visible=1 and type='$type' and section='$section' and site_key='$site'" );
	$newRowPos++; 
	
	$db->query( 'update '.DISPLAYOPTIONS_TABLE." set row=row+(".$step."), row_position='$newRowPos' where id='$id'" );
	
	$db->query( 'update '.DISPLAYOPTIONS_TABLE." set row=row+100 where section='$section' and type='$type' and site_key='$site'" );
	
	// update row order
	
	$rows = $db->getAll( 'select row from '.DISPLAYOPTIONS_TABLE." where section='$section' and visible=1 and type='$type' and site_key='$site' group by row order by row" );
	
	$i=0;
	foreach( $rows as $idx=>$row ) {
		$i++;
		$db->query( 'update '.DISPLAYOPTIONS_TABLE." set row=$i where row='{$row['row']}' and visible=1 and type='$type' and section='$section' and site_key='$site'" );
	}


}


function changeRowPosition( $id, $step ) {
	
	global $db, $site, $type;
	
	$item = $db->getRow( 'select section, row, row_position from '.DISPLAYOPTIONS_TABLE." where id='$id'" );
	$newRowPosition = $item['row_position'] + $step;
	
	$relationSign = ( $step > 0 ) ? '<=' : '>=';
	
	$db->query( 'update '.DISPLAYOPTIONS_TABLE." set row_position=row_position-(".$step.") where row_position $relationSign $newRowPosition and section='$item[section]' and visible=1 and type='$type' and row='$item[row]' and site_key='$site'" );
	$db->query( 'update '.DISPLAYOPTIONS_TABLE." set row_position='$newRowPosition' where id='$id'" );
}


function normalizePosition( $id ) {
	
	global $db, $site, $type;
	
	// reorder all options in the section
	
	$section = $db->getOne( 'select section from '.DISPLAYOPTIONS_TABLE." where id='$id'" );

	$rows = $db->getAll( 'select row from '.DISPLAYOPTIONS_TABLE." where section='$section' and visible=1 and type='$type' and site_key='$site' group by row" );
	
	foreach ( $rows as $idx=>$row ) {
		$ids = $db->getAll( 'select id from '.DISPLAYOPTIONS_TABLE." where row='$row[row]' and section='$section' and visible=1 and type='$type' and site_key='$site' order by row_position" );
		
		$i=0;
		foreach( $ids as $iidx=>$id ) {
			$i++;
			$db->query( 'update '.DISPLAYOPTIONS_TABLE." set row_position='$i' where id='$id[id]'" );
		}
	}
}

// --------------------------------------------
// determine if we should move or edit field
// --------------------------------------------

$action = $_POST['action'];
preg_match( '/^(left|right|up|down|edit)_field_(.*?)$/', $action, $matches );

if ( @count( $matches ) ) {
	
	$editFieldId = $matches[2];
	
	if ( $matches[1] == 'up' ) {
		changeRow( $editFieldId, -1 );
	}
	elseif ( $matches[1] == 'down' ) {
		changeRow( $editFieldId, 1 );
	}
	elseif ( $matches[1] == 'left' ) {
		changeRowPosition( $editFieldId, -1 );
	}
	elseif ( $matches[1] == 'right' ) {
		changeRowPosition( $editFieldId, 1 );
	}
	
	normalizePosition( $editFieldId );
	
}

$items = getFields( $type );

// -----------------------------------------
// construct field structure for output
// and deternine what side they can be moved
// -----------------------------------------

$fields = array();
foreach( $items as $num=>$item ) {
	
	if ( $item[visible] ) {
		
		$fields[$item[section]][$item[row]][] = $item;
		
		$fields[$item[section]][$item[row]][canDown] = 0;
		//$fields[$item[section]][$item[row]][canUp] = 0;
		
		if ( $item[row] == 1 && count( $fields[$item[section]][$item[row]] ) == 2 ) {
			$fields[$item[section]][$item[row]][canUp] = 0;
		}
		else {
			$fields[$item[section]][$item[row]][canUp] = 1;
			
			if ( $item[row] != 1 )
				$fields[$item[section]][$item[row]-1][canDown] = 1;
		}
		
		if ( count( $fields[$item[section]][$item[row]] ) > 3 ) {
			$fields[$item[section]][$item[row]][canDown] = 1;
			//$fields[$item[section]][$item[row]][canUp] = 1;
		}
		
	}
	else {
		$fields[invisible][] = $item;
	}
	
	if ( $item[id] == $editFieldId ) {
		$t->assign( 'editField', $item );
	}
	
}

$styleList = $system->getStyleList();
$t->assign( 'styleValues', $styleList );

$t->assign( 'type', $type );
$t->assign( 'fields', $fields );

$t->assign( 'sectionValues', array( 'left', 'top', 'right', 'bottom' ) );
$t->assign( 'alignValues', array( 'left', 'center', 'right' ) );

$t->assign( 'visibleValues', array( '1', '0' ) );
$t->assign( 'visibleTitles', array( 'yes', 'no' ) );

$session->updateLocation( 'display_options', 'Display Options', array( 'type' ) );
$t->assign( 'bodyTemplate', 'modules/gallery/manage/editDisplayOptions.tpl' );

include_once( FULL_PATH . 'init_bottom.php' );

$t->display( $templateName );
?>