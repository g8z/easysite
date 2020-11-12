<?php

class Backup extends DB_Item
{
	var $_db;
	var $crlf;
	
	function Backup( $id=0 ) {
		
		$this->DB_Item( $id, BACKUPS_TABLE );
		
		global $crlf; // from init.php
		$this->crlf = $crlf;
		
		// init child classes for cascade deletion
		include_once( INCLUDE_DIR . 'internal/db_items/class.Auto_Backup.php' );
		$this->_childClass[] = new Auto_Backup();
		
	}
	
	function create( $fields, $databases ) {
		$fields['resources'] = serialize( $fields['resources'] );
		DB_Item::create( $fields );
	}
	
	function updateId( $id, $fields, $databases ) {
		$fields['resources'] = serialize( $fields['resources'] );
		DB_Item::updateId( $id, $fields );
	}
	
	function load( $id ) {
		DB_Item::load( $id );
		$this->fields['resources'] = @unserialize( $this->fields['resources'] );
		return $this->fields;
	}
	
	function backupId( $id ) {
		
		$this->load( $id );
		
    	// configured backup
    	
	    $matches = array (
            menus    => array( MENUS_TABLE, MENUITEMS_TABLE ),
            layers   => array( LAYERS_TABLE ),
            settings => array( SETTINGS_TABLE ),
            styles   => array( STYLES_TABLE ),
            skins	 => array( SKINS_TABLE ),
            forms    => array( FORMS_TABLE, FORMGROUPS_TABLE, FORMSECTIONS_TABLE, FORMREDIRECTS_TABLE, FORMSUBMISSIONS_TABLE, FILTEROVERRIDES_TABLE ),
            reports  => array( REPORTS_TABLE, REPORTCONDITIONS_TABLE, REPORTFIELDS_TABLE, REPORTGROUPS_TABLE, EMBEDDEDREPORTS_TABLE ),
            pages    => array( PAGES_TABLE, SECTIONS_TABLE ),
            files    => array( FILES_TABLE ),
            sites    => array( SITES_TABLE ),
            users_groups => array( PERMISSIONS_TABLE, USERS_TABLE, GROUPS_TABLE ),
            lists    => array( LISTS_TABLE, LISTITEMS_TABLE ),
            shares   => array( SHARES_TABLE ),
         );
         
         foreach ( $matches as $key=>$tables ) {
         	foreach( $tables as $i=>$table )
         		$matches[$key][$i] = array( 'name'=>$table, 'where'=>"site_key='{$this->_site_key}'" );
         }
	
	    $matches = array_merge( $matches, $this->_getModuleTables() );
	
	    $tables = array ();
	
	    foreach ( $matches as $key => $value ) {
	    	
	       if ( @in_array( $key, $this->fields['resources'] ) || @in_array( 'everything', $this->fields['resources'] ) ) {
	       	
	       		foreach ( $value as $idx=>$table ) {
	       			$tables[] = array( 'name'=>$table['name'], 'where'=>$table['where'] );
	       		}
	        }
	    }

		$databases[DB_NAME] = array(
			'mode' => 'selected',
			'tables' => $tables, // list tables here if mode != 'full'
		);
		
	    return $this->backupDatabases( $databases, $this->fields['compression'], ALLOW_FULL_BACKUP );
	}
	
	function backupDatabases( $databases, $compression='', $structure='1' ) {
		
	    $backup = '';
	    $mime_type = '';
	    
	    foreach( $databases as $name=>$info ) {
	    	$info['name'] = $name;
	    	$backup .= $this->backupDatabase( $info, $structure );
	    }
	    
		$now_date = date ( "m_d_y", time() );
		
		if ( $this->fields['name'] ) {
			$cr = str_replace( array('.',' ','\'','"','\'','@','!','~','#','$','%','^','&','*','(',')','+','=' ), '', $this->fields['name'] ) . '_';
		}
		else
			$cr = '';
		
		$filename = $cr . "backup_" . $now_date . '.sql';
		
		// If dump is going to be compressed, set correct mime_type and add
		// compression to extension
		
		if ( $compression == 'zip' ) {
			
			include_once ( INCLUDE_DIR ."zip.lib.php" );
			
			$tempfilename .= $filename;
			$filename .= '.zip';
			$mime_type = 'application/x-zip';
			$zipfile = new zipfile();
			$zipfile->addFile( $backup, $tempfilename );
			$backup = $zipfile->file();
		}
		elseif ( $compression == 'gz' ) {
			
			$filename .='.gz';
			$mime_type = 'application/x-gzip';
			$backup = gzencode( $backup );
		}
		
		return array( $filename, $mime_type, $backup );
	}
	
	function backupDatabase( $database, $structure='1' ) {
	
		@set_time_limit(120);
	
	    $tables = ( $database['mode'] == 'full' ? $this->_getDBTables( $database['name'] ) : $database['tables'] );
	    
	    $backup = '';
	    
	    foreach ( $tables as $id => $table ) {
	
            // include table structure id we are simply dackuping the database
            // or if checked in configured backup
            
	    	if ( $structure )
	        	$backup .= $this->_getTableStructure( $table['name'] );
	
	        $backup .= $this->_getTableContent( $table );
	    }
	
		return $backup;
	}
	
	
	function _getTableStructure( $table ) {
		
		$out = '';
		$crlf = $this->crlf ? $this->crlf : "\r\n";
		
	    $out = $crlf."#".$crlf."# Data structure of ".$table." table".$crlf."#".$crlf;
	    $out .= "DROP TABLE IF EXISTS `$table`;$crlf";
	    $row = $this->_db->getRow( "SHOW CREATE TABLE `$table`" );
	
        $out .= str_replace( "\n", $crlf, $row['Create Table'] );
	
	    return $out.";".$crlf;
	}
	
	
	function _getTableContent( $table )
	{
	    $out = '';
		$crlf = $this->crlf ? $this->crlf : "\r\n";
		
		$where = $table['where'];
		$table = $table['name'];
		
		if ( !$where )
			$where = 1;
	
	    $rows = $this->_db->getAll( "SELECT * FROM `$table` where $where" );
	
	    if ( @count( $rows ) ) {
	    	
	    	// get column list
	    	
			$columnList = array();
	    	
	    	$cols = array_keys( $rows[0] );
	    	foreach ( $cols as $idx=>$col )
	    		if ( !is_numeric( $col ) )
					$columnList[] = $col;
		    
	        $cols = $columnList;
	        $columnList = implode( ', ', $columnList );
	    	
	        $out .= $crlf."#".$crlf."# Data of ".$table." table".$crlf."#".$crlf;
	
	    }
	
	    foreach ( $rows as $idx=>$row ) {
	
	        $out .= "INSERT INTO `".$table."` ( ".$columnList." ) VALUES (";
	        
	        $sqlRow = array();
	        
	        foreach ( $cols as $cidx=>$name ) {
	        	
                $search_array = array('\\', '\'', "\x00", "\x0a", "\x0d", "\x1a" );
                $replace_array = array('\\\\', '\\\'', '\0', '\n', '\r', '\Z');
                $row[$name] = str_replace($search_array, $replace_array, $row[$name] );
		        
	            if ( !isset( $row[$name] ) ) {
	                $sqlRow[] = "NULL";
	            }
	            else 
	                $sqlRow[] = "'".$row[$name]."'";
	            
	        }
            $sqlRow = implode( ', ', $sqlRow );
	        $out .= $sqlRow . ');';
	        $out .= $crlf;
	    }
	    $out .= $crlf;
	
	    return $out;
	}
	
	function _getDBTables( $name ) {
	
		$tbl = $this->_db->getAll( 'show tables' );
		$tables = array();
		
		foreach ( $tbl as $tidx=>$table ) {
			if ( strpos( $table[key($table)], DB_PREFIX ) === 0 ) {
				$w = "site_key='{$this->_site_key}'";
				//$w = '1';
			//else 
				//$w = '1';
			$tables[] = array( 'name'=>$table[key($table)], 'where'=>$w );
			}
		}
			
		unset( $tdb1 );
		
		return $tables;
	}
 
	function _getModuleTables() {
		
	    global $db;
	    
	    $site = $this->_site_key;
	
	    // -----------------------------------------
	    // get tables for modules by calling
	    // 'module_key'_getTableList for each module
	    // -----------------------------------------
	    
	    include_once( INCLUDE_DIR . 'internal/class.moduleManager.php' );
	    
	    $modMan = new Module_Manager();
	
	    $modules = $modMan->getModules();
	    
	    $moduleTables = array();
	    
	    $commonTables = array( MODULES_TABLE, MODULEOBJECTS_TABLE, MODULECATEGORIES_TABLE, MODULESETTINGS_TABLE );
	
	    foreach ( $modules as $module ) {
	        
		    foreach ( $commonTables as $idx=>$table )
		    	$tables[$idx] = array( 'name'=>$table, 'where'=>"site_key='{$this->_site_key}' and module_key='{$module['module_key']}'" );
	    	
	        $modTables = $modMan->callFunction( $module[module_key], 'getTableList' );
		    foreach ( $modTables as $idx=>$table )
		    	$modTables[$idx] = array( 'name'=>$table, 'where'=>"site_key='{$this->_site_key}'" );
	
	        $moduleTables[$module[module_key]] = array_append( $tables, $modTables );
	    }
	
	    return $moduleTables;
	}	
}

?>