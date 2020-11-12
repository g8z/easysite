<?php

class Page extends DB_Item
{
    function Page( $id=0 ) {
        $this->DB_Item( $id, PAGES_TABLE );

        // init child classes for cascade deletion
        include_once( INCLUDE_DIR . 'internal/db_items/class.Page_Section.php' );
        $this->_childClass[] = new Page_Section();
    }
    
    
    /**
     * Check if the given page key is unique 
     */
    function isUnique( $page_key, $id ) {
        
        $conds = array(
            'id!='     => $id,
            'page_key' => $page_key
        );
        
        $ids = $this->loadCond( array( 'id' ), $conds );
        
        return !count( $ids ) || !$page_key;
    }
}

?>