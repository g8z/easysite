<?php

class Category
{
    /**
     * Table with categories.
     * Table must contain id, parenet, _order, level
     */
    var $_table;

    /**
     * PEAR db pointer
     */
    var $_db;

    /**
     * Fields which controls what items to be updated
     */
    var $_add_fields = array();
    var $_add_values = array();

    /**
     * When getting the array of items in <select> lists, this value
     * will be used to distinguish list items
     */

	var $_indent;

    /**
     * Additional fields to be drawn( returned )
     * array( name => fieldname, action => reference to function )
     */
    var $_draw_fields = array();
    var $_lines_array = array();

    /**
     * Ondelete handler. Must be defined( or not ) in the main program
     */
    var $_onDelete;

    function Category( $db, $table, $add_fields, $add_values ) {
        $this->_db = $db;
        $this->_table = $table;
        $this->_add_fields = $add_fields;
        $this->_add_values = $add_values;
        $this->_onDelete = '';
        $this->_indent = '--';
    }


    function setDrawFields( $drawFields ) {
        $this->_draw_fields = $drawFields;
    }

	/**
	 * Returns an array of all children who have this $parent as an ancestor
	 */
    function getDescendents( $ancestor ) {

    	$this->descendents = array();
    	$this->getDescendentsHelper( $ancestor );
		return $this->descendents;
    }

    function getDescendentsHelper( $ancestor ) {
		$children = $this->_db->getAll( "select id from $this->_table where parent = '$ancestor' and " . $this->_addCond() );

		foreach( $children as $index => $row ) {
			$this->descendents [] = $row[id];
			$this->getDescendentsHelper( $row[id] );
		}
    }

	/**
	 * Return a simple array of categories, with levels separated by $indent
	 * (used in conjunction with getCategoryArrayHelper) - reformats the
	 * result from getCategoryArrayHelper to be id => name
	 */
	function getCategoryArray( $parent = 0, $depth = 0, $i = -1 ) {
		$arr = $this->getCategoryArrayHelper( $parent, $depth, $i );
		$result = array();

		foreach( $arr as $index => $value ) {
			$result[ $value[id] ] = $value[name];
		}

		return $result;
	}
	

	/**
	 * Return a simple array of categories, with levels separated by $indent
	 * (used in conjunction with getCategoryArray)
	 */
	function getCategoryArrayHelper( $parent = 0, $depth = 0 ) {
		$cat_array = array();

		$categories = $this->_db->getAll( "select * from $this->_table where parent = '$parent' and " . $this->_addCond() . " order by _order" );

		if ( count( $categories ) ) {
			foreach( $categories as $category ) {
				//$i++;

				// Duplicatate '--' $depth times
 				$name = $category['title'];

				$name = str_pad( $name, strlen( $this->_indent ) * $depth + strlen( $name ), $this->_indent, STR_PAD_LEFT );

				$cat_array[] = array( 'id' => $category[id], 'name' => $name, 'level'=>$depth, 'origName'=>$category['title'] );
				
				$add_cat = $this->getCategoryArrayHelper( $category['id'], $depth + 1 );
				$cat_array = array_append( $cat_array, $add_cat );
			}
		}
		return $cat_array;
	}


    /**
     * Returns additional conditions for select sql query
     */
    function _addCond() {
        $cond = array();
        for ( $i=0, $n=count( $this->_add_fields ); $i<$n; $i++) {
            $cond[] = $this->_add_fields[$i] .' = \''. $this->_add_values[$i]. '\'';
        }
        return count( $cond ) ? implode( " and ", $cond ) : '1';
    }


    /**
     * Returns additional fields list for insert query
     */
    function _getInsertAddFields() {
        for ( $i=0, $n=count( $this->_add_fields ); $i<$n; $i++) {
            $fields[] = $this->_add_fields[$i];
        }
        return count( $fields ) ? implode( ", ", $fields ).',' : '';
    }


    /**
     * Returns additional values list for insert query
     */
    function _getInsertAddValues() {
        for ( $i=0, $n=count( $this->_add_fields ); $i<$n; $i++) {
            $values[] = $this->_add_values[$i];
        }
        return count( $values ) ? '\''.implode( "', '", $values ).'\',' : '';
    }


    /**
     * Assigns ondelete handler
     */
    function onDelete( $handler ) {
        $this->_onDelete = $handler;
    }


    /**
     * Recursively cascade deletion of a parent menu item to all its children.
     */
    function cascadeDelete( $masterid ) {

        // Call ondelete custom function handler
        if ( $this->_onDelete ) {
            $funct = $this->_onDelete;
            $funct( $masterid );
        }

        $cond = $this->_addCond();
        $result = $this->_db->query( "select id from $this->_table where
            parent      = '$masterid' and
            $cond" );

        while( $data = $result->fetchRow() ) {

            $deleteMenuItemId = $data[id];

            $this->_db->query( "delete from $this->_table where
                id          = '$deleteMenuItemId' and
                $cond" );

            $this->cascadeDelete( $deleteMenuItemId );
        }
    }


    // rewritten & optimized
    function bump( $id ) {

        $cond = $this->_addCond();

        $result = $this->_db->query( "select _order, parent from $this->_table where
                    id          = '$id' and
                    $cond" );

        $data = $result->fetchRow();

        $newOrder = $data[_order] - 1;

        if ( $newOrder < 0 )
            return;

        $result = $this->_db->query( "select id from $this->_table where
                    parent      = '$data[parent]' and
                    _order >= '$newOrder' and
                    $cond order by _order, id" );

        // Update current item order
        $this->_db->query( "update $this->_table set _order = '$newOrder' where id='$id'" );

        // Update all item orders that are lower than current
        while( $row = $result->fetchRow() ) {

            if ( $row[id] != $id ) {
                $newOrder++;
                $this->_db->query( "update $this->_table set _order = '$newOrder' where
                            id          = '$row[id]' and
                            $cond" );
            }

        }
    }


    // rewritten & optimized
    function insert( $id ) {

        $cond = $this->_addCond();

        $result = $this->_db->query( "select _order, parent, level from $this->_table where
                    id          = '$id' and
                    $cond" );

        $data = $result->fetchRow();

        $addFields = $this->_getInsertAddFields();
        $addValues = $this->_getInsertAddValues();
        
        $sef_title = getSefTitle( 'New Item', $this->_table, 'sef_title', 0 );
        $parent = intval( $data['parent'] );
        $level = intval( $data['level'] );

        $this->_db->query( "insert into $this->_table (
                    $addFields
                    parent,
                    _order,
                    title, 
                    sef_title, 
                    level
                    ) values (
                    $addValues
                    '$parent',
                    '" .( 2 + $data[_order] ). "',
                    'New Item',
                    '$sef_title',
                    '$level' )" );
        $newId = $this->_db->getOne( "select max(id) from $this->_table" );
        $this->bump( $newId );

    }

    // rewritten & optimized
    function shift( $id ) {

        $cond = $this->_addCond();

        $data = $this->_db->getRow( "select parent, _order, level from $this->_table where
                    id          = '$id' and
                    $cond" );

        $idToBump = $this->_db->getOne( "select id from $this->_table where
                        _order='". ( 1 + $data[_order] ) ."' and
                        parent='$data[parent]' and $cond " );

        $row = $this->_db->getRow( "select parent, _order from $this->_table where
                        id = '$data[parent]' and $cond" );

        // new order + 1 for bump
        $order = $row[_order] + 2;
        $level = $data[level] - 1;

        $this->_db->query( "update $this->_table set _order='$order', parent='$row[parent]', level='$level' where id='$id'" );
        
        $this->_cascadeUpdateLevel( $id, -1 );

        // Update Order

        $this->bump( $id );
        if ( $idToBump )
            $this->bump( $idToBump );

    }

    // rewritten & optimized
    function unshift( $id ) {

        $cond = $this->_addCond();

        $data = $this->_db->getRow( "select _order, parent, level from $this->_table where
                    id          = '$id' and
                    $cond" );

        $idToBump  = $this->_db->getOne( "select id from $this->_table where
                        _order='". ( 1 + $data[_order] ) ."' and
                        parent='$data[parent]' and $cond " );

        $newParent = $this->_db->getOne( "select id from $this->_table where
                        _order='". ( $data[_order] - 1) ."' and
                        parent='$data[parent]' and $cond " );

        $order = $this->_db->getOne( "select max(_order) from $this->_table where parent='$newParent' and $cond" );
        $newOrder = $order + 2;
        
        $level = $data[level] + 1;

        // Set new parent and order
        $this->_db->query( "update $this->_table set _order='$newOrder', parent='$newParent', level='$level' where id='$id'" );
        
        $this->_cascadeUpdateLevel( $id, 1 );
        

        // Update orders

        $this->bump( $id );

        if ( $idToBump )
            $this->bump( $idToBump );


    }
    
    function _cascadeUpdateLevel( $parent, $step ) {
        
        $cond = $this->_addCond();

        $res = $this->_db->query( 'select id, level from '. $this->_table ." where parent='$parent' and $cond" );
        
        while( $row = $res->fetchRow() ) {
            $level = $row[level]+$step;
            $this->_db->query( "update $this->_table set level=$level where id='$row[id]'" );
            $this->_cascadeUpdateLevel( $row[id], $step );
        }

    }

    function delete( $id ) {

        $cond = $this->_addCond();

        $result = $this->_db->query( "select _order, parent from $this->_table where
                    id          = '$id' and
                    $cond" );

        $data = $result->fetchRow();

        $this->_db->query( "delete from $this->_table where
                    id          = '$id' and
                    $cond" );

        $this->cascadeDelete( $id );

        // now, update all the menu items from the group that this was shifted out of

        $result = $this->_db->query( "select id from $this->_table where
                    parent      = '$data[parent]' and
                    $cond order by _order asc" );

        $count = 0;

        while( $row = $result->fetchRow() ) {

            // assign a new order # to this item

            $this->_db->query( "update $this->_table set
                        _order      = '$count' where
                        id          = '$row[id]' and
                        $cond" );

            $count++;
        }

    }

    /**
     * Executes the given $action on the given $id
     */
    function execute( $action, $id="" ) {

        $cond = $this->_addCond();

        if ( $id ) {
        	$sef_title = getSefTitle( $_POST["title_$id"], $this->_table, 'sef_title', $id );
        }
        
        // Update titles
        $this->_db->query( "update $this->_table set
            title      = '" . $_POST["title_$id"] . "',
            sef_title  = '" . $sef_title . "' 
            where id   = '$id' and $cond" );
        
        switch( $action ) {

            case "bump":
                $this->bump( $id );
                break;

            case 'insert':
                $this->insert( $id );
                break;

            // move item to the left
            case 'shift':
                $this->shift( $id );
                break;

            // move menu item to the right
            case 'unshift':
                $this->unshift( $id );
                break;

            case 'kill':
                $this->delete( $id );
                break;
        }
    }


    function _getDrawFields( $data ) {
        
        $drawFields = array();
        
        foreach( $this->_draw_fields as $field => $action ) {
            $drawFields[$field] = $action( $data );
        }
        
        return $drawFields;
    }

    function clearLines() {
        $this->_lines_array = array();
    }


    /**
     * Returns array of items to be outputted
     */
    function draw( $parent, $levelLimit, $noedit )
    {

        $cond = $this->_addCond();
        $query = "select * from $this->_table where
            parent      = '$parent' and
            $cond
            order by _order asc;";

        $result = $this->_db->query( $query );

        $first = true;

        while( $data = $result->fetchRow() )
        {
            $id = $data[id];

            $found = true;  // indicates that at least one item has been found in this menu set

            if ( $data[level] > $levelLimit && $levelLimit )
                continue;

            // determine if this item has children or not (save in $hasChildren variable)
/*            $hasChildren = $this->_db->getOne( "select count(id) from $this->_table where
                parent      = '$data[id]' and
                $cond" );
*/
            $aLine = array();

            // draw the indent
            for ( $i = 0; $i < $data[level]; $i++ ) {
                $aLine[] = '';
            }

            // determine whether or not there is anything higher than this that we can swap
/*            $higherItemExists = $this->_db->getOne( "select id from $this->_table where
                parent      = '$data[parent]' and
                $cond and
                _order < '$data[_order]'" );
*/
            $aLine[] = array(
                'id'        => $id,
                'title'     => $data[title],
                'hidden'    => 0,
                'noedit'    => $noedit,
                //'higherItemExists' => $higherItemExists,
                'higherItemExists' => ( $data[_order] != 0 ),
                'first'     => $first,
                'level'     => $data[level],
                );

            $addDrawFields = $this->_getDrawFields( $data );
            $aLine[count( $aLine )-1] = @array_merge( $aLine[count( $aLine )-1], $addDrawFields );
            $this->_lines_array[] = $aLine;

            if ( $first )
                $first = false;

//            if ( $hasChildren )
                $this->draw( $id, $levelLimit, $noedit );
        }
        return $this->_lines_array;
    }


    function isEmpty() {
        $cond = $this->_addCond();
        return !($this->_db->getOne( "select count(id) as c from $this->_table where $cond" ));
    }

    function span() {
        return $this->getMaxLevel() + 1;
    }

    // gets the deepth of the item
    function getLevel( $id ) {
        
        return $this->_db->getOne( "select id from $this->_table where id='$id'" );

    }


    function getLevelId( $level ) {
        $cond = $this->_addCond();

        $maxLevel = 0;
        $result = $this->_db->query( "select id from $this->_table where $cond" );
        while ( $data = $result->fetchRow() ) {
            $l = $this->getLevel( $data[id] );
            if ( $l == $level ) return $data[id];
        }

        return 0;
    }

    function getMaxLevel() {

        $cond = $this->_addCond();
        
        return $this->_db->getOne( "select max(level) from $this->_table where $cond" );
    }
}
?>