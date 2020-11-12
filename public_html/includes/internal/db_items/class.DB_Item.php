<?php


/**
 * Base class for any db item
 */
class DB_Item
{

    var $_db;
    var $_table;
    var $_id;
    var $_site_key;

    // used for cascade deletion
    var $_parentField;
    var $_childClass;

    var $fields;
    var $permissions;
    
    var $resource_type;

    function DB_Item( $id, $table ) {

        global $db, $site;

        $this->_db = $db;
        $this->_table = $table;
        $this->_site_key = $site;

        if ( $id )
            $this->load( $id );
        else
            $this->_id = 0;
    }

    function create( $fields ) {

        if ( !count( $fields ) )
            return false;

        $insertFields = implode( ', ', array_keys( $fields ) );
        $insertValues = '\''.implode( '\', \'', array_values( $fields ) ).'\'';

        $this->fields = $fields;

        $this->_db->query( "insert into $this->_table ($insertFields) values ($insertValues) ");
        $this->fields[id] = $this->_db->getOne( 'select max(id) from '. $this->_table );

    }

    function load( $id, $fields=array() ) {

        $selectFields = $this->_getSelectFields( $fields );

        $this->fields = $this->_db->getRow( "select $selectFields from $this->_table where id='$id' " );
        $this->fields[id] = $id;

        return $this->fields;
    }

    function loadCond( $fields=array(), $conds=array(), $order='', $add='' ) {

        if ( $order )
            $order = 'order by '.$order;

        $selectFields = $this->_getSelectFields( $fields );
        $where = $this->_getWhereCond( $conds );

        return $this->_db->getAll( "select $selectFields from $this->_table where $where $order $add" );
    }

    function loadByParentId( $parentId, $fields=array(), $parentName='', $order='' ) {

        if ( $conds = $this->_getParentConds( $parentId, $parentName ) )
            return $this->loadCond( $fields, $conds, $order );
        else
            return false;
    }

    function loadColumnCond( $colName, $conds, $order='', $add='' ) {
        $r = $this->loadCond( array( $colName ), $conds, $order, $add );
        $result = array();
        foreach ( $r as $index => $item ) {
            $result[] = $item[$colName];
        }

        return $result;
    }

    function loadColumnByParent( $colName, $parentId, $parentName='', $order='', $add='' ) {
        if ( $conds = $this->_getParentConds( $parentId, $parentName ) )
            return $this->loadColumnCond( $colName, $conds, $order, $add );
    }

    function _getParentConds( $parentId, $parentName='' ) {

        $conds = array();

        if ( count( $this->_parentField ) == 1 ) {

            // if the only parent
            $a = array_values( $this->_parentField );
            $field = $a[0];

        } else {
            $field = $this->_parentField[$parentName];
        }

        if ( $field )
            $conds = array( $field => $parentId );

        return $conds;
    }

    function _getSelectFields( $fields=array() ) {

        if ( count( $fields ) )
            $selectFields = implode( ', ', $fields );
        else
            $selectFields = '*';

        return $selectFields;

    }

    function update( $fields=array() ) {

        $updateFields = $this->_getUpdateFields( $fields );

        if ( !$updateFields || !$this->fields[id] )
            return false;

        $id = $this->fields[id];

        return $this->_db->query( "update $this->_table set $updateFields where id='$id'" );
    }

    function updateCond( $fields, $conds ) {

        $updateFields = $this->_getUpdateFields( $fields );
        $where = $this->_getWhereCond( $conds );

        return $this->_db->query( "update $this->_table set $updateFields where $where" );
    }

    function updateId( $id, $fields ) {
        
        if ( $this->exists( $id ) ) {
            
            $this->updateCond( $fields, array( 'id'=> $id ) );
            //$this->fields = $fields;
            $this->fields[id] = $id;
            
            return $this->fields;
            
        } else {
            return false;
        };
    }

    function delete( $id ) {
        $this->_db->query( "delete from $this->_table where id='$id'" );

        // delete any dependent ('child') records
        if ( is_array( $this->_childClass ) )
            foreach ( $this->_childClass as $key => $child )
                $child->deleteParentDie( get_class( $this ), $id );

        $this->fields = array();
    }

    function deleteCond( $conds ) {

        $where = $this->_getWhereCond( $conds );

        if ( is_array( $this->_childClass) )
            foreach ( $this->_childClass as $key => $child ) {


                $parents = $this->loadCond( array( 'id' ), $conds );

                foreach ( $parents as $parent ) {
                    $child->deleteParentDie( get_class( $this ), $parent[id] );
                }

        }

        return $this->_db->query( "delete from $this->_table where $where" );
    }


    // deletes the item because of 'parent' deletion
    function deleteParentDie( $parentClass, $parentId ) {

		if ( $this->_parentField[$parentClass] ) {
			$conds = array( $this->_parentField[$parentClass] => $parentId );
			$this->deleteCond( $conds );
		}
    }


    function _getQueryStatement( $fields, $implodeItem ) {
        if ( !count( $fields ) )
            return false;

        if ( !function_exists( 'keyVal' ) ) {
            function keyVal( $key, $value ) {

                $condition = '=';

                // check to see if there is no condition specified
                $lastChar = $key[ strlen( $key )-1 ];
                if ( $lastChar == '>' || $lastChar == '<' || $lastChar == '=' )
                    $condition = '';
                if ( ereg( " +in", $key ) ) {
                    return $key.$value;
                } else
                    return $key.$condition.'\''.$value.'\'';
            }
        }

        return implode( $implodeItem, array_map( 'keyVal', array_keys( $fields), array_values( $fields ) ) );
    }

    function _getUpdateFields( $fields ) {

        return $this->_getQueryStatement( $fields, ', ' );
    }

    function _getWhereCond( $conds ) {

        return $this->_getQueryStatement( $conds, ' and ' );
    }
    
    
    function exists( $id ) {
        return $this->_db->getOne( 'select id from ' . $this->_table . ' where id=\''.$id.'\'' );
    }
    
    
    function getKeyTitle( $key, $title, $permission='' ) {
    	
    	global $user;
    	
    	// get arrays for smarty html_options
    	
    	$keys = array();
    	$titles = array();
    	
    	$shared = getSQLShares( $this->resource_type, $permission );
    	
    	$list = $this->_db->getAll( "select $key, if(id in ($shared), concat($title, '[shared]'), $title) as $title from ".$this->_table." where (site_key='{$this->_site_key}' or $key in ($shared)) order by $title" );
    	
    	foreach ( $list as $idx=>$item ) {
    		$keys[] = $item[$key];
    		$titles[] = $item[$title];
    	}
    	
    	array_unshift( $keys, 0 );
    	array_unshift( $titles, '- Select One -' );
    	
    	return array( $keys, $titles );
    }
}

?>
