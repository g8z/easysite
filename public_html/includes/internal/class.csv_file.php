<?php

// error defenitions
define( 'DUMMY_FILE', 'The file you have specified is dummy.' );

class Csv_File extends File
{
    
    var $delimiter = ',';
    var $quote = '"';
    var $firstRowAsNames;
    var $colNames;
    var $rows;
    
    var $_errors;
    
    function Csv_File( $fileName, $delimiter=',', $quote='"', $firstRowAsNames=1 ) {
        $this->File( $fileName );
        
        $this->delimiter = $delimiter;
        $this->quote = $quote;
        $this->firstRowAsNames = $firstRowAsNames;
        
        $this->_errors = array();
        
    }
    
    function parse() {
        
        @set_time_limit( 0 );
        
        $content = split( "\n", stripslashes( $this->getContent() ) );
        
        if ( !$this->getSize() ) {
            $this->_errors[] = DUMMY_FILE;
            return false;
        }
        
        $count = count( $content );
        $ind = -1;
        $row = '';
        
        do {
            
            $ind++;
            $line = $content[$ind] . "\n";
        
            $row .= $line;
            
            // check for multiline field
            // if so, then add the next row
            if ( substr_count( $row, '"' ) % 2 ) {
                continue;
            }
            
            // these cols can contain part of the whole field
            // because of delimiter can be insibe the field
            $tempCols = split( $this->delimiter, $row );
            
            // real columns
            $cols = array();
            
            $n = count( $tempCols );
            $tempInd = -1;
            $i=0;
            $col = '';
            
            do {
                $tempInd++;
                $col .= $tempCols[$tempInd] . $this->delimiter;
                
                if ( substr_count( $col, '"' ) % 2 ) {
                    continue;
                }
                
                $cols[$i++] = $col;
                
                $col = '';
                
            }
            while ( $tempInd < $n-1 );

            $parsedRow = array();
            $i = 0;
            $emptyRow = true;
            foreach( $cols as $col ) {
                
                $col = trim( $col, $this->delimiter."\r\n" );
                
                // remove quotes from the start and end of the field
                if ( $col[0] == $this->quote && $col[ strlen( $col )-1 ] == $this->quote ) {
                    $col = str_replace( '""', '"', $col );
                    $col = substr( $col, 1, strlen( $col )-2 );
                }
                
                $emptyRow = $emptyRow && empty( $col );

                $parsedRow[] = $col;
            }
            
            if ( !$emptyRow ) {
            
                if ( $this->firstRowAsNames && !count( $this->colNames ) )
                    // store column names
                    $this->colNames = $parsedRow;
                else
                    // store content
                    $this->rows[] = $parsedRow;
                
            }
                
            $row = '';
            
        }
        while ( $ind < $count - 1 );
        
    }
    
    function isOk() {
        return count( $this->_errors ) == 0;
    }
    
    function getErrors() {
        return implode( '<br />', $this->_errors );
    }
    
    function numRows() {
        return count( $this->rows );
    }
}

?>