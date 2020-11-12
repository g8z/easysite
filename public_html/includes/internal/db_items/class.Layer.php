<?php

class Layer extends DB_Item
{
    function Layer( $id=0 ) {
        $this->DB_Item( $id, LAYERS_TABLE );

        // init child classes for cascade deletion
    }
    
    
    /**
     * Check if the given page key is unique 
     */
    function isUnique( $layer_name, $id ) {
        
        $conds = array(
            'id!='     => $id,
            'title' => $layer_name
        );
        
        $ids = $this->loadCond( array( 'id' ), $conds );
        
        return !count( $ids ) || !$layer_name;
    }
}

?>