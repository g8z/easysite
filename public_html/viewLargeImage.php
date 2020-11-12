<?php
if ( !defined( 'SMARTY_DIR' ) )
    include_once( 'init.php' );
    
$mode = $_GET['mode'];

$newWindow = isset( $_GET['newWindow'] ) ? $_GET['newWindow'] : 1;

switch ( $mode ) {
    case 'page':
        $table = SECTIONS_TABLE;
        $field = 'img_large';
        $canAccess = true;
        break;
        
    case 'layer':
        $table = LAYERS_TABLE;
        $field = 'img_large';
        $canAccess = true;
        break;
        
    case 'report':
        $table = FORMSUBMISSIONS_TABLE;
        $field = 'blob_value';
        $canAccess = true;
        break;
        
    default:
        $canAccess = false;
        break;
}

$t->assign( 'canAccess', $canAccess );

if ( $canAccess ) {
    
    $id = intval( $_GET['id'] );
    
    if ( $mode != 'report' ) {
    
        $link = trim( $db->getOne( "select img_link from $table where id = '$id' limit 1" ) );
        
        if ( $link ) {
        
        	if ( stristr( $link, 'javascript:' ) )
        		$target = '';
        	else
        		$target = 'target=_blank';
        
        }
        $t->assign( 'target', $target );
        $t->assign( 'link', $link );
    
    }
    
    
    $t->assign( 'table', $table );
    $t->assign( 'field', $field );
    $t->assign( 'id', $id );

}
    
$t->assign( 'newWindow', $newWindow );

include_once( 'init_bottom.php' );

if ( $newWindow ) {
    $t->assign( 'title', 'Image Viewer' );    
    $t->display( 'popupHeader.tpl' );
    $t->display( 'pages/largeImage.tpl' );
    $t->display( 'popupFooter.tpl' ); 
}
else {
    $t->assign( 'bodyTemplate', 'pages/largeImage.tpl' );
    $t->display( $templateName );
}

$db->disconnect();

?>
