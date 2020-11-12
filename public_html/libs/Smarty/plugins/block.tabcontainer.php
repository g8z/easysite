<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


function smarty_block_tabcontainer($params, $content, &$smarty)
{
    if (is_null($content)) {
        return;
    }
    
    $_output = 
    	'<script type="text/javascript" src="'.HTTP_DIR.'js/tabpane.js"></script>
    	<script type="text/javascript" src="'.HTTP_DIR.'js/tabpane.css"></script>
		<div class="tab-page" id="modules-cpanel">
		<script type="text/javascript">
		   var tabPane1 = new WebFXTabPane( document.getElementById( "modules-cpanel" ), 1 )
		</script>' .
    	
    	$content . 
    	
    	'</div>';
    	
    return $_output;

}

/* vim: set expandtab: */

?>
