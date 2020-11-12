<?php

/**
 * Requires _order field
 */
class Ordered_DB_Item extends DB_Item
{
    
    var $_orderField = '';

    function Ordered_DB_Item( $id, $table ) {
        $this->DB_Item( $id, $table );
    }

    function create( $fields ) {
        if ( !count( $fields ) )
            return false;
            
        $add = '';
        if ( $this->_orderField ) 
            $add = 'and '. $this->_orderField .' = \''. $fields[$this->_orderField] . '\'';

        $fields[_order] = 1 + $this->_db->getOne( "select max(_order) from ".$this->_table." where site_key = '$this->_site_key' $add" );

        $insertFields = implode( ', ', array_keys( $fields ) );
        $insertValues = '\''.implode( '\', \'', array_values( $fields ) ).'\'';

        $this->fields = $fields;

        $this->_db->query( "insert into $this->_table ($insertFields) values ($insertValues) ");
        $this->fields[id] = $this->_db->getOne( 'select max(id) from '. $this->_table );
    }

    function changeOrder( $id, $step, $orderFieldValue='', $where='1' ) {

        $add = '';
        if ( $orderFieldValue == '' )
        	$orderFieldValue = $this->_db->getOne( 'select '. $this->_orderField .' from '. $this->_table ." where id='$id'" );
        	
        if ( $this->_orderField ) 
            $add = 'and '. $this->_orderField .' = \''. $orderFieldValue . '\'';
            
        $max_order = $this->_db->getOne( 'select max(_order) from ' . $this->_table . " where site_key='$this->_site_key' $add and $where" );
        $min_order = $this->_db->getOne( 'select min(_order) from ' . $this->_table . " where site_key='$this->_site_key' $add and $where" );
        $current_order = $this->_db->getOne( 'select _order from ' . $this->_table . " where site_key='$this->_site_key' and id='$id'" );

        $new_order = $current_order + $step;

        if ( $new_order > $max_order ) $new_order = $max_order;
        if ( $new_order < $min_order ) $new_order = $min_order;

        $from = min( $current_order, $new_order );
        $to = max( $current_order, $new_order );



        $idsToUpdate = $this->_db->getAll( 'select id from ' . $this->_table . " where
                                        site_key='$this->_site_key' and
                                        _order>='$from' and
                                        _order<='$to' $add and $where order by _order" );

        $udStep = ( $step > 0 ) ? '-1' : (( $step < 0 ) ? '+1' : '+0');

        foreach( $idsToUpdate as $udId ) {
            $udId = $udId['id'];
            if ( $udId != $id ) {
                $this->_db->query( 'update ' . $this->_table . " set _order=_order$udStep where id='$udId'" );


            } else {
                $this->_db->query( 'update ' . $this->_table . " set _order='$new_order' where id='$id'" );

            }
        }
    }

    function bumpUp( $id, $orderFieldValue, $where='1' ) {
        $this->changeOrder( $id, -1, $orderFieldValue, $where );
        $this->reorder( $where );
    }
    
    

    function reorder( $where='1' ) {
    
        $groups = array();
        $add = array();
        if ( $this->_orderField ) {
        	$groups = $this->_db->getAll( 'select '.$this->_orderField.' from '. $this->_table.' where site_key=\''.$this->_site_key.'\' and '.$where.' group by '. $this->_orderField );
        	if ( is_array( $groups ) && count( $groups ) )
	        	foreach( $groups as $num=>$group ) {
	        		$add[] = $this->_orderField .'= \''. $group[$this->_orderField] .'\'';
	        	}
	        else 
	        	$add[] = '1=1';
        }
        else 
        	$add[] = '1=1';
        	
        
        foreach ( $add as $num=>$groupItem ) {
	        $ids = $this->_db->getAll( 'select id from ' . $this->_table . " where site_key='{$this->_site_key}' and $groupItem and $where order by _order" );
	        for ($i=0, $n=count( $ids ); $i<$n; $i++) {
	            $id = $ids[$i]['id'];
	            $this->_db->query( 'update ' . $this->_table . " set _order='$i' where id='$id'" );
	        }
        }
    } 
    
    
    function delete( $id ) {
        $this->_db->query( "delete from $this->_table where id='$id'" );
        
        $this->reorder();

        // delete any dependent ('child') records
        if ( is_array( $this->_childClass ) )
            foreach ( $this->_childClass as $key => $child )
                $child->deleteParentDie( get_class( $this ), $id );

        $this->fields = array();
    }    

}

?>