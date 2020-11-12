<?php
if ( !defined( 'SMARTY_DIR' ) )
    include_once( '../init.php' );

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


        if ( $_FILES["file_data_$id"] && $_FILES["file_data_$id"] != 'none' ) {
            if (is_uploaded_file($_FILES["file_data_$id"]['tmp_name'])) {
                $tmp = $_FILES["file_data_$id"][tmp_name];
                // read the binary image information
                $fileData = addslashes( @fread( fopen( $tmp, 'rb' ), @filesize( $tmp ) ) );
                $fileDataPath = $_POST["file_data_path_$id"];

                if ( $id == 'NEW' ) {
                    $file_data_NEW = $fileData;
                    $file_data_path_NEW = $fileDataPath;
                }
                else {
                	$fileUpdate = ",
                        file_data       = '$fileData',
                        file_data_path  = '$fileDataPath'";
                }

                unset( $_FILES["file_data_$id"] );
            }
        }

        if ( $id != 'NEW' ) {
        	$downloadName = $_POST['download_name_'.$id];
	        $db->query( "update ".FILES_TABLE." set download_name='$downloadName' $fileUpdate where id = $id" );

		}

    }

    // add new form data, if needed
    if ( $_POST['addNewItem'] ) {

        extract( $_POST );

        $img_thumb_NEW = $img_large_NEW = $file_data_NEW = '';


		if ( $_FILES["file_data_NEW"][tmp_name]
			&& $_FILES["file_data_NEW"][tmp_name] != 'none' ) {

			$tmp = $_FILES["file_data_NEW"][tmp_name];

			// file info
			if ( $tmp )
				$file_data_NEW = addslashes( @fread( fopen( $tmp, "rb" ), @filesize( $tmp ) ) );
		}


        $db->query( "insert into ".FILES_TABLE." (
            site_key,
            download_name,
            file_data,
            file_data_path
            ) values (
            '$site',
            '$download_name_NEW',
            '$file_data_NEW',
            '$_POST[file_data_path_NEW]'
            )" );

    }

    // determine if we should delete or bump up a section
    if ( $_POST['deleteSectionVar'] ) {
        $db->query( "delete from ".FILES_TABLE." where id = " . $_POST['deleteSectionVar'] );
    }
}

$objectPath = 'http://' . $_SERVER['SERVER_NAME'] . dirname( $_SERVER['PHP_SELF'] ) . '/getObject.php?mode=uploadedFile';
$objectPath = stri_replace( '/' . ADMIN_DIR . '/', '/', $objectPath );

$t->assign( 'objectPath', $objectPath );

$shared = getSQLShares( 'file', 'edit' );
$tdata = $db->getAll( "select *, if(id in ($shared), 1, 0) as shared from ".FILES_TABLE." where (site_key = '$site' or id in ($shared))" );

$data = array();

foreach( $tdata as $row ) {
    $row['menu_id'] = $menuIdFileMapper[$row[id]];

    if ( $user->hasAccess( 'file', $row['id'] ) )
    	$data[] = $row;
}

// add one item to the beginning of $data for the "new" section
array_unshift( $data, array( 'id' => 'NEW' ) );

$t->assign( 'data', $data );

if ( !hasAdminAccess( 'cm_file' ) ) {
	$t->assign( 'bodyTemplate', 'manage/authError.tpl' );
}
else {
	$t->assign( 'bodyTemplate', 'manage/editFiles.tpl' );
}

$session->updateLocation( 'file_manager', 'File Manager' );
include_once( '../init_bottom.php' );
$t->display( $templateName );
?>