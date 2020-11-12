<?php

class Image
{
    var $_image_name;
    var $_image_width;
    var $_image_height;
    var $_image_type;

    // Image resources
    var $_original;
    var $_resized;

    var $_resized_width;
    var $_resized_height;

    var $_error = '';

    var $_types = array( 1 => 'gif',
                         2 => 'jpeg',
                         3 => 'png',
                         4 => 'swf',
                         5 => 'psd' );


    function Image( $image ) {
        $this->_image_name = $image;
        $params = @GetImageSize( $this->_image_name );
        $this->_image_width = $params[0];
        $this->_image_height = $params[1];
        $this->_image_type = $params[2];

        $this->_getOriginal();
    }

    function resize( $new_width, $new_height ) {

        if ( $this->_image_width != 0 && $this->_image_height != 0 )
            $scale = min( $new_width / $this->_image_width, $new_height / $this->_image_height );

        if ( $scale > 1 ) $scale = 1;


        $this->_resized_width = floor( $this->_image_width * $scale );
        $this->_resized_height = floor( $this->_image_height * $scale );

        $this->_createResized();

        @ImageCopyResampled( $this->_resized, $this->_original, 0, 0, 0, 0, $this->_resized_width, $this->_resized_height, $this->_image_width, $this->_image_height );

    }

    function _getOriginal() {
        $readFunction = 'imagecreatefrom'.$this->_getType();
        if ( function_exists( $readFunction ) ) {
            if ( !($this->_original = @$readFunction( $this->_image_name )) )
                $this->_error = 'Can not read image'. $this->_image_name;
        }
        else
            $this->_original = $this->_loadFromFile( $this->_image_name );

     }

    function _createResized() {
        $this->_resized = @imagecreatetruecolor( $this->_resized_width, $this->_resized_height );
    }

    function _getType() {
        return $this->_types[$this->_image_type];
    }

    function saveAs( $filename ) {
        if ( function_exists( 'imagejpeg' ) && $this->_resized )
            @imagejpeg( $this->_resized, $filename );
        else {
            //fwrite( fopen( $filename.'.'.$this->_getType(), 'wb' ), strlen( $this->_original ) );
            @fwrite( @fopen( $filename, 'wb' ), strlen( $this->_original ) );
        }
    }

    function getContents( $filename ) {
        if ( function_exists( 'imagejpeg' ) && $this->_resized ) {
            imagejpeg( $this->_resized, $filename );
            return fread( @fopen( $filename, 'rb' ), filesize( $filename ) );
            @unlink( $filename );
        } else {
            return $this->_original;
        }
    }

    function _loadFromFile( $filename ) {
        $this->_resized = @fread( @fopen( $filename, 'rb' ), @filesize( $filename ) );
    }

    function getError() {
        return $this->_error;
    }

    function getWidth() {
        return $this->_image_width;
    }

    function getHeight() {
        return $this->_image_height;
    }
}

?>