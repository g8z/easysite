<?php

if ( !defined( 'SMARTY_DIR' ) )
	include_once( '../init.php' );

if ( !hasAdminAccess( 'cm_users' ) || !hasAdminAccess( 'cm_users_import' ) ) {
    $t->assign( 'bodyTemplate', 'manage/authError.tpl' );
	$t->display( $templateName );
    exit;
}

function getPostData( $except ) {
	
	$postPrevPage = array();
	
	foreach( $_POST as $key=>$value ) {
		
		if ( !in_array( $key, $except ) ) {
			$postPrevPage[] = array( $key, $value );
		}
	}
	
	return $postPrevPage;
}


$userFields = array(
	'login_id' 		=> 'Login ID',
	'login_pass' 	=> 'Login Pass',
	'first_name' 	=> 'First Name',
	'last_name'		=> 'Last Name',
	'email'			=> 'E-Mail',
	'url'			=> 'URL',
	'company'		=> 'Company',
	'fax'			=> 'Fax',
	'phone'			=> 'Phone',
	'address_1'		=> 'Address, Line 1',
	'address_2'		=> 'Address, Line 2',
	'city'			=> 'City',
	'state'			=> 'State/Province',
	'country'		=> 'Country',
	'comments'		=> 'Comments',
	'status'		=> 'Status',
	'member_id'		=> 'Member ID'
);

// include the necessary PHP classes for CSV file parsing here!

require_once( INCLUDE_DIR . 'internal/class.File.php' );
require_once( INCLUDE_DIR . 'internal/class.csv_file.php' );

// upload file into the temp dir
$filePath = FULL_PATH . TEMP_DIR . '/import.csv';

switch( $_POST['delimiter'] ) {
	case 'comma':
		$delim = ',';
		break;

	case 'tab':
		$delim = "\t";
		break;

	case 'semicolon':
		$delim = ';';
		break;

	case 'other':
		$delim = $otherDelimiter;
		break;
}
	
	
function parseFile() {
	
	global $db, $t, $delim, $userFields, $site, $filePath;

	$parseError = '';

    $csv_file = new Csv_File( 'csv', $delim, '"', $_POST['header'] );

    if ( $csv_file->isUploaded() ) {

		$csv_file->parse();

		if( $csv_file->isOK() ) {
			$csv_file->saveAs( $filePath );

		} else {
			$parseError = 1;
			$t->assign( 'errors', $csv_file->getErrors() );
		}
		
		if ( $_POST['header'] ) 
            $data = $csv_file->colNames;
        else
            $data = $csv_file->rows[0];

		$t->assign( 'data', $data );
    };

	$t->assign( 'step', 'map_data' );
	$t->assign( 'stepTitle', 'Step 2: Map User Data' );
	$t->assign( 'stepDesc', 'You can what field should contain what data from the uploaded file.' ); 


	//$users = $db->getAll( "select id, login_id from " . USERS_TABLE . " where site_key = '$site' order by login_id" );
	$groups = $db->getAll( "select id, name from " . GROUPS_TABLE . " where site_key = '$site' order by name" );

	$selectOne = '- Select One -';

	$userList = array( $selectOne );
	$groupList = array( $selectOne );


/*	foreach( $users as $user ) {
		$userList[$user[id]] = $user[login_id];
	}
*/
	foreach( $groups as $group ) {
		$groupList[$group[id]] = $group[name];
	}

	//$t->assign( 'users', $userList );
	$t->assign( 'groups', $groupList );

	// process the import

	// get all of the fields in the USER database
	
	$t->assign( 'parseError', $parseError );
	$t->assign( 'userFields', $userFields );

}


function mapData() {
	
	global $t, $userFields;
	
	require_once( INCLUDE_DIR . 'internal/editPermissions.php' );

	$restrictedSections2 = getRestrictedSections( USER, $userID );

	$permissions = array(
	    'cm_tools'      => fetchAll( 'cm_tools', $restrictedSections2, $userID, USER ),
	);
	
	$t->assign( 'resources', $permissions );
	$t->assign( 'userFields', $userFields );
	
	$t->assign( 'step', 'site_creation' );
	$t->assign( 'stepTitle', 'Step 3. Give imported users a website.' );
	$t->assign( 'stepDesc', 'You may give every user his or her own website, which can be managed using these same content-management tools. To give imported users a website, input a site key mask below. The site key mask should contain at least one user field and can contain non user specific data. You may specify field data in {} branches. <br />For example, site_key mask defined as <b>site_{Login ID}</b> will have a value <b>site_jon</b> for user with <b>jon</b> Login ID.<br /><br />Creating user sites can take much time to complete for big number of users to be imported. You can specify to create sites not during the import, but in first site access attempt. Parent data in this case will be copied at the site access moment.' );
}


function processImport( $errorStep='', $errorIndex=array(), $errorUserName='', $errorSiteKey='' ) {
	
	global $db, $t, $delim, $userFields, $site, $filePath, $system, $templateName, $session;

	// adding users

	$csv_file = new Csv_File( $filePath, $delim, '"', $_POST['header'] );//from temp dir
	$csv_file->parse();

    $insertFields = implode( ',', array_keys( $userFields ) ) . ',group_id,site_key,user_site_key';
    
    $currentUsers = $db->getAll( 'select login_id from '. USERS_TABLE." where site_key='$site'" );
    
    $currentSites = $db->getAll( 'select site_key from '. SITES_TABLE." where site_key='$site'" );
    $curTempSites = $db->getAll( 'select user_site_key as site_key from '. TEMPSITES_TABLE );
    $currentSites = array_append( $currentSites, $curTempSites );
    
    foreach ( $currentUsers as $num=>$item ) {
    	$currentUsers[$num] = $item['login_id'];
    }
    
    foreach ( $currentSites as $num=>$item ) {
    	$currentSites[$num] = $item['site_key'];
    }
    
    // find to login_id field index
    // for determining if the user with this login_id exists
    $fieldIdInd = array_search( 'login_id', array_keys( $userFields ) );
    
    $index = 0;
    $loginExists = false;
    $siteExists = false;

    foreach( $csv_file->rows as $row ) {
    	
        $index++;
        
        if ( $index < $errorIndex || ( $index == $errorIndex && $errorStep=='error_skip' ) )
        	continue;
        
		$values = array();
        $replaceFields = array();
        
        $user_site_key = $_POST['user_site_key'];

        foreach ( $userFields as $key => $field ) {
        	
        	$value = $row[$_POST[$key]-1];
            
	        if ( $key == 'login_id' ) {
	        	
		        // check if this user have overriden username in case of previous error
	        
		        if ( $errorStep == 'error_continue' && $errorUserName && $errorIndex == $index ) {
		        	$value = $errorUserName;
		        }
	        
	        	if ( in_array( $value, $currentUsers )  ) {
		        	$loginExists = true;
		        	$errorMessage = '<font color=red>User with Login ID <b>'. $value .'</b> is already exists. Please choose another Login ID.</font>';
		        	$errorUserName = $value;
		        }
		        else 
		        	$currentUsers[] = $value;
        	}
            
	        if ( $_POST['user_site_key'] ) {
	        	
	        	$user_site_key = str_replace( '{'.$field.'}', $value, $user_site_key );
	        }
	        
            $values[] = "'" . addslashes( $value ). "'";
	        
		}
		
		if ( $_POST['user_site_key'] ) {
			
		        // check if this user have overriden username in case of previous error
	        
		        if ( $errorStep == 'error_continue' && $errorSiteKey ) {
		        	$user_site_key = $errorSiteKey;
		        }

		        if ( in_array( $user_site_key, $currentSites ) ) {
				$siteExists = true;
		       	$errorMessage = '<font color=red>Site with site_key <b>'. $user_site_key .'</b> is already exists. Please choose another site_key.</font>';
		       	$errorSiteKey = $user_site_key;
			}
			else
				$currentSites[] = $user_site_key;
		}

		
		if ( !$loginExists && ( (!$siteExists && $user_site_key) || ( !$user_site_key ) ) ) {
			
			// insert values 
				
			$values[] = intval( $_POST[group_id] );
			$values[] = "'$site'";
			$values[] = "'$user_site_key'";
	
	        $insertValues = implode( ',', $values );
	
	        $db->query( 'insert into '. USERS_TABLE ."($insertFields) values ($insertValues)" );
	        
	        $insertedUser = $db->getOne( 'select max(id) from '. USERS_TABLE );
	        
	        // determine if we should create sites 
	        // and if so, do we need to do this immediately or on first site access
	        
	        if ( $user_site_key ) {
	        	
				require_once( INCLUDE_DIR . 'internal/editPermissions.php' );
				
	            updatePermissions( USER, $insertedUser, $user_site_key );
    
    			$parentData = array(
        			'c_pages'  => $_POST['c_pages'],
                    'c_forms'  => $_POST['c_forms'],
                    'c_reports'=> $_POST['c_reports'],
                    'c_layers' => $_POST['c_layers'],
                    'c_menus'  => $_POST['c_menus'],
                    'c_settings' => $_POST['c_settings'],
                    'c_styles' => $_POST['c_styles'],
                    'c_skins'  => $_POST['c_skins'],
                    'c_files'  => $_POST['c_files'],
                    'c_lists'  => $_POST['c_lists'],
                    'c_module_categories' => $_POST['c_module_categories'],
                    'c_module_items'      => $_POST['c_module_items'],
    			);
	    			
	    		if ( $_POST['create_on_access'] ) {
		        		
		        		// store in the temp sites table
		        		$parentData = serialize( $parentData );
		        		$db->query( 'insert into '.TEMPSITES_TABLE." (user_site_key,owner,parent_data,site_key) values ('$user_site_key', '$insertedUser', '$parentData', '$site' )" );
		        }
		        else {
		        	
					include_once( INCLUDE_DIR . 'internal/class.site.php' );
					$siteObj = new ES_Site( $db, DEFAULT_SITE );
		        	
	    			$siteObj->create( $user_site_key, $insertedUser, $parentData );
		        }
	        }
	        
	        //echo 'insert into '. USERS_TABLE ."($insertFields) values ($insertValues)<br>\r\n";
		}
		else {
			//print_r( $currentUsers );
			// display error
			
			$except = array( 'step', 'skippedUsers' );
			$prevPost = getPostData( $except );
			
			if ( $errorStep == 'error_skip' ) {
				$prevPost[] = array( 'skippedUsers', $_POST['skippedUsers'] + 1 );
			}
			else {
				$prevPost[] = array( 'skippedUsers', $_POST['skippedUsers'] );
			}
			
			$t->assign( 'postPrevPage', $prevPost );
			
			$t->assign( 'step', 'error' );
			$t->assign( 'errorMessage', $errorMessage );
			$t->assign( 'errorUserName', $errorUserName );
			$t->assign( 'errorSiteKey', $errorSiteKey );
			$t->assign( 'errorIndex', $index );
			$t->assign( 'stepTitle', 'Error Importing User' );
			$t->assign( 'stepDesc', 'There was an error inserting user #'. $index );
			
			$session->updateLocation( 'import_users', 'Import User List' );
			include_once( '../init_bottom.php' );
			$t->assign( 'bodyTemplate', 'manage/importUsers.tpl' );
			
			$t->display( $templateName );
			
			exit();
			
		}
		
    }
    
	$skippedUsers = $errorStep == 'error_skip' ? $_POST['skippedUsers'] + 1 : $_POST['skippedUsers'];
	
	$t->assign( 'num', $csv_file->numRows() - $skippedUsers );
	$t->assign( 'step', '3' );

}


$step = $_REQUEST['step'];

$skippedUsers = $_POST['skippedUsers'];

switch ( $step ) {
	
	case 'parse_file':
		parseFile();
		break;
		
	case 'map_data':
		mapData();
		break;
		
	case 'process_import':
		processImport( '', -1 );
		break;
		
	case 'error_continue':
		processImport( 'error_continue', $_POST['errorIndex'], $_POST['errorUserName'], $_POST['errorSiteKey'] );
		
	case 'error_skip':
		processImport( 'error_skip', $_POST['errorIndex'], $_POST['errorUserName'], $_POST['errorSiteKey'] );
		break;
		
	default:
		$t->assign( 'step', 'file_upload_form' );
		$t->assign( 'stepTitle', 'Step 1: Specify file to imort from' );
		$t->assign( 'stepDesc', 'Upon import, new user records will be automatically created for every row of your file.  The <b>FIRST</b> row of your file will be used in the mapping process; this may be either a row of field names, or the first row of your dataset.' );
		break;
}


$except = array( 'step' );
$t->assign( 'postPrevPage', getPostData( $except ) );


if ( !hasAdminAccess( 'cm_users' ) ) {
	$t->assign( 'bodyTemplate', 'manage/authError.tpl' );
}
else {
	$t->assign( 'bodyTemplate', 'manage/importUsers.tpl' );
}

$session->updateLocation( 'import_users', 'Import User List' );
include_once( '../init_bottom.php' );

$t->display( $templateName );

?>