<?php


/**
 * Class for install/uninstall(may be in future ?) 
 * and calling module api functions
 */
class Module_Manager
{
    
    var $_db;
    var $_siteKey;
    
    function Module_Manager() {
        
        global $db, $site;
        
        $this->_db = $db;
        $this->_siteKey = $site;
        
    }
    
    function setSite( $siteKey ) {
        $this->_siteKey = $siteKey;
    }
    
    function getModules() {
        return $this->_db->getAll( 'select * from ' . MODULES_TABLE . " where site_key='$this->_siteKey'" );
    }
    
    function callFunction( $moduleKey, $function, $params = array() ) {
        include_once( FULL_PATH . MODULES_DIR . '/' . $moduleKey .'/api.php' );
    
        $function = $moduleKey.'_'.$function;
        
        return $function( $params );
    }
    
    function getCategories( $moduleId ) {
        return $this->_db->getAll( 'select * from ' . MODULECATEGORIES_TABLE . " where site_key='$this->_siteKey' and id='$moduleId'" );
    }
}
?>