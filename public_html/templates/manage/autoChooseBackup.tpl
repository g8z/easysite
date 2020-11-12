<table border=0 cellpadding=0 cellspacing=5 class=normal>

<tr><td colspan=2>{$logoutLink} {$pathway}</td></tr>
<tr><td colspan="2">[ <a href="backup.php?action=backupScreen">Backup</a> ] [ <a href="backup.php?action=restoreScreen">Restore</a> ] [ <a href="autoBackups.php">Auto-Backup</a> ]</td></tr>
</table>


<p class="subtitle">Choose Backup Configuration</p>
{if !$backupIds}
<div class=normal>You have not configured any backups yet. Please <a href="backup.php?action=configureScreen">configure one</a> to continue.</div>
{else}
<form name="backupChoose" method="get">
<div class=normal>Choose already configured backup: <select name="backup_id">{html_options output=$backupTitles values=$backupIds}</select>

<br /><br />or<br /></div><br />
<div class=normal><a href="backup.php?action=configureScreen">Configure One</a></div>
<br />
<input type="submit" name="submitButton" value="Continue"> <input type="button" name="cancelButton" value="Cancel" onclick="javascript: document.location.href='autoBackups.php'">
{/if}
