{literal}
<script language="Javascript">
<!--

function mouseOver( id ) {
	
	var elem = document.getElementById( id );
	
	elem.className = 'active';
}

function mouseOut( id ) {
	
	var elem = document.getElementById( id );
	
	if ( id != '{/literal}{$activeResource}{literal}' )
		elem.className = 'inactive';
}


function changeResource( resource ) {
	
	document.location.href='editShares.php?resource='+resource;
}


function changeResourceId( resourceId ) {
	
	var theForm = document.editShares;
	
	theForm.resource_id.value = resourceId;
	theForm.action = 'change_resource';
	
	theForm.submit();
	
	
}

function submitResource() {
	
	var theForm = document.editShares;
	
	theForm.action.value = 'save_resource';
	
	theForm.submit();
	
	
}

//-->
</script>

<style>

.inactive {
	padding: 1px;
	padding-bottom: 0px;
	background-color: '{/literal}{$inactiveColor}{literal}';
	border-bottom:1px solid black;
	height: 30;
	padding-top: 7px;
	display:block;
}

.active {
	border:1px solid black;
	border-bottom: 0px;
	background-color: '{/literal}{$activeColor}{literal}';
	height: 30;
	cursor: hand;
	padding-top: 7px;
	display:block;
}
.content {
	border:1px solid black;
	border-top: 0px;
	width: 100%; 
}

a.menu {
	background-color: {/literal}{$inactiveColor}{literal};
	display:block; 
}

a.menu:hover {
	background-color: {/literal}{$activeColor}{literal};
	display:block; 
}

</style>

{/literal}

<form action=editShares.php method="POST" enctype="multipart/form-data" name="editShares">

<input type=hidden name=action value="change_resource">
<input type=hidden name=resource value="{$activeResource}">
<input type=hidden name=resource_id value="{$resourceId}">

<table border=0 cellpadding=0 cellspacing=0 width=100% class=normal>

<tr><td class=normal colspan="2">{$logoutLink} {$pathway}</td></tr>

<tr><td colspan="2">&nbsp;</td></tr>
	
<tr><td class=title colspan="2">Share Resources</td></tr>

<tr><td colspan="2">&nbsp;</td></tr>

<tr><td colspan="2">Share any system resource with any user or group, and specify the permissions that the recipient should have for the shared resource. <a href="javascript:launchCentered('{$help.url}?type=share_resources',{$help.width},{$help.height},'{$help.options}');">{$helpSymbol}</a></tr>

<tr><td colspan="2">&nbsp;</td></tr>

<tr><td colspan="2">
<table border=0 cellpadding=0 cellspacing=0 width=100% class=normal>
<tr>
{foreach item=resource from=$resources key=type name=sections}
	<td height=30 align=center><a href="#" class="menu" style="text-decoration: none; {if $activeResource eq $type}background-color:{$activeColor}{/if};" onclick="javascript: changeResource( '{$type}' ); return false;"><div id="{$type}" class="{if $type eq $activeResource}active{else}inactive{/if}" onmouseover="javascript: mouseOver( '{$type}' );" onmouseout="javascript: mouseOut( '{$type}' );">{$resource}</div></a></td>
{/foreach}
</tr>
<tr>
	<td colspan="{$countResources}" width="100%">
	<table class="normal content" cellpadding="2" cellspacing="5" border="0">
	<tr><td>&nbsp;</td></tr>
	<tr><td>Select {$activeResource} to share/unshare: <select name="resource_id" onchange="javascript: document.editShares.submit();">{html_options values=$resourceIds output=$resourceValues selected=$resourceId}</select></td></tr>
	<tr><td>&nbsp;</td></tr>
	{if $resourceId}
	<tr><td>Select groups you want to share/unshare selected resource (will apply to all users in the group)</td></tr>
	<tr><td>&nbsp;</td></tr>
	<tr>
		<td align=center>
		<table class=normal width="80%">
		<tr><th align="left">Group</th><th width="5%">View</th>{if $editable}<th width="5%">Edit</th>{/if}</tr>
		{foreach name=groups from=$groups item=group}
	    {if $smarty.foreach.groups.iteration is odd}
	    {*{assign var=rowColor value=$reportSettings.oddRowColor}*}
	   	 {assign var=defaultRowColor value='#EEEEFF'}
	    {else}
	    {*{assign var=rowColor value=$reportSettings.evenRowColor}*}
	   	 {assign var=defaultRowColor value='#DDDDDD'}
	    {/if}

	    <tr bgcolor="{$rowColor|default:$defaultRowColor}"><td>{$group.name}</td><td align=center><input type=checkbox name="view_group_{$group.id}" value=1 {if $group.view}checked{/if}></td>{if $editable}<td align=center><input type=checkbox name="edit_group_{$group.id}" value=1 {if $group.edit}checked{/if}></td>{/if}</tr>
		{/foreach}
		</table>
		</td>
	</tr>
	<tr><td>&nbsp;</td></tr>
	<tr><td>Select users you want to share/unshare selected resource. If no 'override' checkbox selected, then group settings will be applied.</td></tr>
	<tr><td>&nbsp;</td></tr>
	<tr>
		<td align=center>
		<table class=normal width="80%">
		<tr><th align="left">User</th><th align="left">Group</th><th width="5%">Override</th><th width="5%">View</th>{if $editable}<th width="5%">Edit</th>{/if}</tr>
		{foreach name=users from=$users item=user}
		
	    {if $smarty.foreach.users.iteration is odd}
	    {*{assign var=rowColor value=$reportSettings.oddRowColor}*}
	   	 {assign var=defaultRowColor value='#EEEEFF'}
	    {else}
	    {*{assign var=rowColor value=$reportSettings.evenRowColor}*}
	   	 {assign var=defaultRowColor value='#DDDDDD'}
	    {/if}

	    <tr bgcolor="{$rowColor|default:$defaultRowColor}"><td>{$user.login_id}</td><td>{$user.groupname}</td><td align=center><input type=checkbox name="override_user_{$user.id}" value=1 {if $user.override}checked{/if}></td><td align=center><input type=checkbox name="view_user_{$user.id}" value=1 {if $user.view}checked{/if}></td>{if $editable}<td align=center><input type=checkbox name="edit_user_{$user.id}" value=1 {if $user.edit}checked{/if}></td>{/if}</tr>
		{/foreach}
		</table>
		</td>
	</tr>
	<tr><td>&nbsp;</td></tr>
	<tr><td><input type=submit name=submit_button value="Submit" onclick="javascript: submitResource();"> <input type="reset" name=reset_button value="Reset"></td></tr>
	<tr><td>&nbsp;</td></tr>
	{/if}
	</table>
	</td>
</tr>
</table>

</td></tr>

</table>
</form>
