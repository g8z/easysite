<?php

class Navigation {

    var $_total;
    var $_itemsPerPage;
    var $_pagesPerScreen;
    var $_requestVars;
    var $_start;
    var $_url;
    var $_separator;
    var $_set;
    var $_startPage;
    var $_endPage;
    var $_totalPages;
    var $_nextSymbol;
    var $_prevSymbol;
    var $_currentPage;

    function Navigation() {
        $this->_url = $_SERVER['PHP_SELF'] . '?';

	  $vars = array_merge( $_POST, $_GET );// do not add cookies

        foreach( $vars as $name => $value ) {
            if ( $name != 'start' && $name != 'set' )
                $this->_url .= $name . '=' . $value . '&';
        }
        
        
        $this->_url = str_replace( ' ', '%20', $this->_url );

        // total number of items
        $this->_total = 100;

        // number of items to display per page
        $this->_itemsPerPage = 10;

        // the number of links to show in the navigation bar (in case > 10)
        $this->_pagesPerScreen = 10;

        $this->_start = 0;  // the current 'start' index

        $this->_separator = ' &nbsp; ';

        $this->_nextSymbol = '>';
        $this->_prevSymbol = '<';
        $this->_firstSymbol = '<<';
        $this->_lastSymbol = '>>';

        $this->_set = '0';

        $this->_currentPage = ( $_POST['current'] > 0 ) ? $_POST['current'] : 1;
        if ( $this->_currentPage > $this->_pagesCount ) $this->_currentPage = $this->_pagesCount;

    }

    // return some HTML to represent the navigation bar
    function output() {

        // check to see if any defaults should be set

        if ( !$this->_set )
            $this->_set = '0';

        // determine the link sequence

        $this->_totalPages = ( $this->_total - 1 ) / $this->_itemsPerPage;
        $this->_startPage = $this->_set * $this->_pagesPerScreen;
        $this->_endPage = min( $this->_startPage + $this->_pagesPerScreen - 1, $this->_totalPages );

        $arr = array();

        // if the start page > 1, then show a "<< " link

        if ( $this->_set > 0 ) {

            $arr [] = '<a href=' . $this->_url . 'start=' . ( $this->_start - $this->_itemsPerPage ) . '&set=' . ( $this->_set - 1 ) . '>' . $this->_prevSymbol . '</a>';
        }

        for ( $i = $this->_startPage + 1; $i <= $this->_endPage + 1; $i++ ) {

            $index = ( $i - 1 ) * $this->_itemsPerPage;

            if ( $this->_start >= ( $i - 1 ) * $this->_itemsPerPage && $this->_start < ( $i * $this->_itemsPerPage ) )
                $arr [] = $i;

            else
                $arr [] = '<a href=' . $this->_url . 'start=' . $index . '&set=' . $this->_set . '>' . $i . '</a>';
        }

        // the 'next page' link
        if ( $this->_endPage < $this->_totalPages ) {
            $arr [] = '<a href=' . $this->_url . 'start=' . ( $index + $this->_itemsPerPage ) . '&set=' . ( $this->_set + 1 ) . '>' . $this->_nextSymbol . '</a>';

            // if there is a 'next page' link, then there is a 'last page'
        }

        // if there are navigable results, then return nothing
        if ( sizeof( $arr ) > 1 )
            return implode( $this->_separator, $arr );
        else
            return '';
    }


}

?>