{literal}
<script language="Javascript">
<!--

function validateBackup() {

	var theForm = document.backupForm;
	var theElements = theForm.elements;
	var numElements = theElements.length;
	
	if ( !theForm.backup_name.value ) {
		alert( 'Please enter a configuration name' );
		return false;
	}
	
	// we need to ensure that at least one checkbox is checked
	for( var i = 0; i < numElements; i++ ) {
		if ( theElements[i].type == 'checkbox' ) {
			if ( theElements[i].checked ) {
				return true;
			}
		}
	}
	alert( 'You must select at least one item to backup!' );
	return false;
}
//-->
</script>
{/literal}

<form name="backupForm" method="post">
<input type="hidden" name="id" value="{$backup.id}">
<table border=0 cellpadding=0 cellspacing=5 class=normal>

<tr><td colspan=2>{$logoutLink} {$pathway}</td></tr>
<tr><td colspan="2">[ <a href="backup.php?action=backupScreen">Backup</a> ] [ <a href="backup.php?action=restoreScreen">Restore</a> ] [ <a href="autoBackups.php">Auto-Backup</a> ]</td></tr>
<tr><td colspan=2>&nbsp;</td></tr>

<tr><td class=subtitle colspan=2>Selective Backup</td></tr>
<tr><td colspan=2>Selective backups can be saved and quickly accessed from the Backup page. They can also be used for auto-backups using cronjobs. <a href="javascript:launchCentered('{$help.url}?type=auto_backups',{$help.width},{$help.height},'{$help.options}');">More about cronjob auto-backups</a>.</td></tr>
<tr><td colspan=2>&nbsp;</td></tr>
<tr><td colspan=2>Backup Name: <input type=text name="backup_name" value="{$backup.name}"></td></tr>
<tr><td class=normal>&nbsp;</td><td>

<tr><td colspan=2>Backup these specific items...</td></tr>

<tr><td class=normal>&nbsp;</td><td>
<table border=0 cellpadding=2 cellspacing=0 width=100% class=normal>

<tr><td width=25><input type=checkbox name="resources[]" value=menus {if @'menus'|in_array:$backup.resources}checked{/if}></td><td>Menus</td></tr>
<tr><td><input type=checkbox name="resources[]" value=layers {if @'layers'|in_array:$backup.resources}checked{/if}></td><td>Layers</td></tr>
<tr><td><input type=checkbox name="resources[]" value=settings {if @'settings'|in_array:$backup.resources}checked{/if}></td><td>Settings</td></tr>
<tr><td><input type=checkbox name="resources[]" value=styles {if @'styles'|in_array:$backup.resources}checked{/if}></td><td>Styles</td></tr>
<tr><td><input type=checkbox name="resources[]" value=skins {if @'skins'|in_array:$backup.resources}checked{/if}></td><td>Skins</td></tr>
<tr><td><input type=checkbox name="resources[]" value=forms {if @'forms'|in_array:$backup.resources}checked{/if}></td><td>Forms</td></tr>
<tr><td><input type=checkbox name="resources[]" value=reports {if @'reports'|in_array:$backup.resources}checked{/if}></td><td>Reports</td></tr>
<tr><td><input type=checkbox name="resources[]" value=pages {if @'pages'|in_array:$backup.resources}checked{/if}></td><td>Pages</td></tr>
<tr><td><input type=checkbox name="resources[]" value=files {if @'files'|in_array:$backup.resources}checked{/if}></td><td>Files</td></tr>
<tr><td><input type=checkbox name="resources[]" value=lists {if @'lists'|in_array:$backup.resources}checked{/if}></td><td>Lists</td></tr>
<tr><td><input type=checkbox name="resources[]" value=sites {if @'sites'|in_array:$backup.resources}checked{/if}></td><td>Sites & Default Settings</td></tr>
<tr><td><input type=checkbox name="resources[]" value=users_groups {if @'users_groups'|in_array:$backup.resources}checked{/if}></td><td>Users, Groups, & Permissions</td></tr>
<tr><td><input type=checkbox name="resources[]" value=shares {if @'shares'|in_array:$backup.resources}checked{/if}></td><td>Shares</td></tr>

<tr><td colspan=2>Modules...</td></tr>
<tr><td>&nbsp;</td><td>
	<table border=0 cellpadding=2 cellspacing=0 class=normal>

    {foreach from=$modules item=module}
	<tr><td><input type=checkbox name="resources[]" value="{$module.module_key}" {if @$module.module_key|in_array:$backup.resources}checked{/if}></td><td>{$module.title}</td></tr>
    {foreachelse}
    <tr><td colspan=2>No modules currently installed.</td></tr>
    {/foreach}
	</table>
</td></tr>

</table>

</td></tr>

<tr><td colspan=2 class=normal>Or...</td></tr>

<tr><td class=normal>&nbsp;</td><td>
<table border=0 cellpadding=2 cellspacing=0 class=normal width=100%>

<tr><td width=25><input type=checkbox name="resources[]" value=everything {if @'everything'|in_array:$backup.resources}checked{/if}></td><td>Backup Everything! (this will override the above choices)</td></tr>

{if $zip or $gz}
<tr><td colspan=2><hr noshade size=1 style="width:50%" align=left></td></tr>
<tr><td width=25><input type=radio name=compression {if !$backip.id || !$backup.compression || $backup.compression eq 'none'}checked{/if} value=""></td><td>Do not compress downloaded backup file</td></tr>	
{/if}

{if $zip}
<tr><td width=25><input type=radio name=compression value=zip {if $backup.compression eq 'zip'}checked{/if}></td><td>Compress backup file with ZIP</td></tr>
{/if}

{if $gz}
<tr><td width=25><input type=radio name=compression value=gz {if $backup.compression eq 'gz'}checked{/if}></td><td>Compress backup file with GZIP</td></tr>
{/if}

</table>

</td></tr>

<tr><td colspan=2 class=normal><input type=submit name=action onClick="javascript:return validateBackup();" value="Save Configuration"></td></tr>
</table>
</form>