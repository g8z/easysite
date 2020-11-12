<?php

define( LOCAL_FILE, 1 );
define( FORM_FILE,  2 );

class File {
    
    var $userName;
    var $serverName;
    
    var $path;
    
    // type can be 
    // - file uploaded from form or
    // - file on the server's hard drive
    var $_type;
    
    var $_db;
    
    function File( $name ) {
        
        global $db;
        
        $this->_db = $db;
        
        if ( array_key_exists( $name, $_FILES ) ) {
            $this->_type = FORM_FILE;
            $this->userName = $_FILES[$name][name];
            $this->serverName = $_FILES[$name][tmp_name];
            $this->path = FULL_PATH . TEMP_DIR . '/uploaded'.time().'.file';
			move_uploaded_file( $this->serverName, $this->path );
        } else {
            $this->type = LOCAL_FILE;
            $this->userName = $this->path = $name;
        }
            
    }
    
    function getSize() {
        return @filesize( $this->path );
    }
    
    function getContent() {
        return addslashes( @fread( @fopen( $this->path, "rb" ), $this->getSize() ) );
    }
    
    function isUploaded() {
        if ( $this->_type = FORM_FILE ) {
            return ( !empty( $this->serverName ) && $this->serverName != 'none' && file_exists( $this->path ) );
        } else
            return false;
    }
    
    function saveAs( $filename ) {
        @fwrite( @fopen( $filename, 'wb' ), stripslashes( $this->getContent() ) );
    }
    
    function delete() {
    	return @unlink( $this->path );
    }
    
    /**
     * Updates $table.$field with $cond conditions
     */
    function updateTable( $table, $field, $conds = array() ) {
        
        $sqlWhere = array();
        $where = '';
        
        foreach( $conds as $column => $value ) {
            $sqlWhere[] = $column .'=\''. $value .'\'';
        }
        
        if ( count( $sqlWhere ) ) 
            $where = 'where '. implode( ' and ', $sqlWhere );
            
        $content = $this->getContent();
            
        $this->_db->query( "update $table set $field='$content' $where" );
    }
}

?>