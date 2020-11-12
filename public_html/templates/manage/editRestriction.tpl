{* special js functions for this page only *}
{literal}
<script language="Javascript">
<!--

//-->
</script>
{/literal}

<form action=editRestriction.php method="POST" enctype="multipart/form-data" name="editRestriction">
<input type=hidden name=resource_type value="{$resource_type}">
<input type=hidden name=resource_id value="{$resource_id}">
<input type=hidden name=formIsSubmitted value="1">

<table border=0 cellpadding=0 cellspacing=3 class=normal>

    <tr><td class=subtitle>Edit Visibility</td></tr>
    <tr><td><b>for the {$resource_type} named "{$resource_name}"</b></td></tr>
    
    <tr><td>&nbsp;</td></tr>
    
    <tr><td>You may specify on what resources the current resource is displayed.</td></tr>
    <tr><td>&nbsp;</td></tr>
    
    <tr><td>
    
    	<table border=0 cellspacing="2" class="normal">
    	
	    <tr><td><input type=checkbox name="cm_tools" value=1 {if $restrict_to.cm_tools}checked{/if}>Display on Content Managenemt Tools
	    <tr><td><input type=checkbox name="module" value=1 {if $restrict_to.module}checked{/if}>Display on modules
	    
    	<tr><td>&nbsp;</td></tr>
    	
    	{foreach from=$resources item=resource key=type}
    	
	    	<tr><td><b>{$resource.title}</b></td></tr>
	    	<tr><td><input type=radio name="{$type}" value="all" {if $restrict_to.$type eq 'all'}checked{/if}>Display on all {$resource.title}</td></tr>
	    	<tr><td><input type=radio name="{$type}" value="none" {if $restrict_to.$type eq 'none'}checked{/if}>Do not display on {$resource.title}</td></tr>
	    	
	    	{if $resource.items}
	    	<tr><td><input type=radio name="{$type}" value="selective" {if $restrict_to.$type|count gt 1}checked{/if}>Display on selected {$resource.title}</td></tr>
	    	
	    	{foreach from=$resource.items item=r}
	    	{assign var=id value=$r.id}
	    		<tr><td><table border=0 cellpadding=0 cellspacing=0 class="normal"><tr><td nowrap>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td><input type=checkbox name="{$type}_{$r.id}" value=1 {if $restrict_to.$type.$id eq 1}checked{/if}>{$r.title}</td></tr></table></td></tr>
	    	{/foreach}
	    	{/if}
	    	
	    	<tr><td>&nbsp;</td></tr>
    	
    	{/foreach}
    	
    	</table>
    </td></tr>
    
    <tr><td><input type=submit name=save value="Save"> <input type=reset name=resetButton value="Reset"> <input type=button name=close value="Close" onclick="javascript: window.close();"></td></tr>

</table>

</form>
