<?php

class Custom_List extends Ordered_DB_Item 
{
    
    function Custom_List( $id=0 ) {
        $this->DB_Item( $id, LISTS_TABLE );
        
        // init child classes for cascade deletion
        include_once( INCLUDE_DIR . 'internal/db_items/class.List_Item.php' );
        $this->_childClass[] = new List_Item();
    }
}

?>