<?php

class Form_Redirect extends Ordered_DB_Item
{
    function Form_Redirect( $id=0 ) {
        $this->DB_Item( $id, FORMREDIRECTS_TABLE );

        // init child classes for cascade deletion
        // include_once( INCLUDE_DIR . 'internal/db_items/class.Form_Condition.php' );
        include_once( INCLUDE_DIR . 'internal/db_items/class.Submission.php' );
        // $this->_childClass['condition'] = new Form_Condition();
        $this->_childClass['submission'] = new Submission();
        $this->_parentField['form'] = 'form_id';

	$this->_orderField = 'form_id';
    }

    function isMatch( $condition, $value1, $value2, $case_sensitive=false ) {
        
        if ( !$case_sensitive ) {
            $value1 = strtolower( $value1 );
            $value2 = strtolower( $value2 );
        }

        switch( $condition ) {
			case '>':

				return $value1 > $value2;

			case '<':

				return $value1 < $value2;

			case '=':

				return $value1 == $value2;

			case '!=':

				return $value1 != $value2;

			case '>=':

				return $value1 >= $value2;

			case '<=':

				return $value1 <= $value2;
				
			case 'checked':

				return $value1 == 'checked';
				
			case 'unchecked':

				return $value1 == 'unchecked';
				
		    case 'starts with':
		        
		        return preg_match( '/^'.preg_quote( $value2 ).'/', $value1 );
		    
		    case 'contains':
		        
		        return preg_match( '/'.preg_quote( $value2 ).'/', $value1 );
 
		    case 'ends with':
		        
		        return preg_match( '/'.preg_quote( $value2 ).'$/', $value1 );
		}
		return false;
    }


    /**
     * Finds and returns redirect_id that matches subbmitted form content if any
     */
    function testSubmission( $formContents, $formID ) {

        $redirects = $this->loadCond( array( 'id', 'redirect_type', 'redirect_id', 'condition', 'case_sen', 'value', 'section_id' ), array( 'form_id' => $formID ) );

		// the conditions are now part of the redirect table itself!

        foreach( $redirects as $cond ) {
            $fieldFound = false;

            foreach( $formContents as $fc ) {

                if ( $cond['section_id'] == $fc['field_id'] ) {

					if ( $this->isMatch( $cond[condition], $fc['value'], $cond[value], $cond['case_sen'] ) ) {

						return $cond[id];
					}
                }
            }
        }

        // if no match, then get the default match

        return false;
    }
    

}

?>