{literal}
<script language="Javascript">
<!--

function doRestore() {

	{/literal}
	{if $smarty.const.DEMO_MODE}
	alert( 'The restore tool is disabled in this demo.' );
	return false;
	{/if}
	{literal}

	var formObj = document.backupForm;
	var fileName = formObj.restoreFile.value.trim();
	
	var valid = true;
	
	if ( fileName == '' ) {
		alert( 'Please specify a source .SQL file to restore from.' );
		valid = false;
	}
	else if ( fileName.indexOf( '.zip' ) != -1 || fileName.indexOf( '.gz' ) != -1 ) {
		alert( 'The file to restore from must be an uncompressed SQL file.' );
		valid = false;
	}
	else if ( fileName.toLowerCase().indexOf( '.sql' ) == -1 ) {
		alert( 'The file to restore from must be a plain-text SQL file.' );
		valid = false;
	}
	return valid;
}
//-->
</script>
{/literal}

<form action="backup.php" method="POST" enctype="multipart/form-data" name="backupForm">

<table border=0 cellpadding=0 cellspacing=5 class=normal>
<tr><td colspan=2>{$logoutLink} {$pathway}</td></tr>
<tr><td colspan="2">[ <a href="backup.php?action=backupScreen">Backup</a> ] [ <a href="backup.php?action=restoreScreen">Restore</a> ] [ <a href="autoBackups.php">Auto-Backup</a> ]</td></tr>
<tr><td colspan=2>&nbsp;</td></tr>

<tr><td colspan=2 class=subtitle>Restore System Data</td></tr>

<tr><td colspan=2 class=normal>Use this tool to restore a backup to your site. The system will detect what you are restoring based on the SQL data in the backup file. Please be sure to only restore files that were created using the above backup tool. The file to restore must be an uncompressed SQL file. You may restore full system backups or selective backups.</td></tr>

<tr><td colspan=2 align="center">
{if $success eq 1}<b>Your data was successfully restored!</b>{/if}
{if $success eq 2}<b>ERROR: Part or all your data was NOT successfully restored! You may wish to try the data restoration again, and check your server environment to ensure that you have the necessary permissions to restore data to MySQL.</b>{/if}
</td></tr>

<tr><td colspan=2 class=normal><input type=file size=30 name=restoreFile> <input type=submit onClick="javascript:return doRestore();" name=action value="Restore"></td></tr>

<tr><td colspan=2>&nbsp;</td></tr>
<tr><td colspan=2>{$logoutLink} {$pathway}</td></tr>

</table>

</form>