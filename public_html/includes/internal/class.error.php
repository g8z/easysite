<?php

class Error
{
	
	var $_message;
	
	function Error( $title, $message ) {
		
		$this->_template = 'pages/generalError.tpl';
		
		$this->_title = $title;
		$this->_message = $message;
	}
	
	function messageDie() {
		
		global $t, $templateName;
		
		//include_once( ROOT_DIR . 'init_bottom.php' );
		
		$t->assign( 'errorTitle', $this->_title );
		$t->assign( 'errorMessage', $this->_message );
		$t->display( $this->_template );
		
		die();
	}
}

?>