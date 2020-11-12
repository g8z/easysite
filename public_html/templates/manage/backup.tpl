<form action="backup.php" method="POST" enctype="multipart/form-data" name="backupForm">

<table class="normal">
<tr><td>
<div class="normal">{$logoutLink} {$pathway}</div>
<div class="normal">[ <a href="backup.php?action=backupScreen">Backup</a> ] [ <a href="backup.php?action=restoreScreen">Restore</a> ] [ <a href="autoBackups.php">Auto-Backup</a> ]</div>

<p class=title>System Backup</p>

<p class="normal">You should always backup your database when changes are made using the Content Management tools. You may backup the entire system, or just specific parts of the system, based on what you changed. The backup file may be downloaded as a .zip or .gz archive. Please save the backup file in a safe location. To restore data from an archived file, you must decompress the archive first.</p>


<p class="normal">
  <span class="subtitle">Full Site Backup</span><br />
  <a href="backup.php?action=doBackup&compression=none">Uncompressed Plain Text File</a>
  {if $zip} | <a href="backup.php?action=doBackup&compression=zip">Zip Archive</a>{/if}
  {if $gz} | <a href="backup.php?action=doBackup&compression=gz">GZ Archive</a>{/if}
</p>

<p class="normal">
  <span class="subtitle">Selective Backup</span><br />
  <span class="normal">If you typically only make changes to a specific part of your system, then you can create a custom backup, and save the backup configuration for quick access at later dates.</span><br /><br />
  <span class="normal"><a href="backup.php?action=configureScreen">Add a New Selective Backup</a></span><br />
</p>
</td></tr>
</table>

<table class="normal">
{foreach from=$backups item=backup}
<tr><td width="100" nowrap>{$backup.name}: </td><td>[ <a href="backup.php?action=doBackup&id={$backup.id}">Download</a> ] [ <a href="editauto.php?backup_id={$backup.id}">Set Auto-Backup</a> ] [ <a href="backup.php?action=configureScreen&id={$backup.id}">Edit</a> ] [ <a href="backup.php?action=deleteBackup&id={$backup.id}" onclick="javascript: return confirm( 'Are you really want to delete this backup?' );">Delete</a> ]</td></tr>
{foreachelse}
<tr><td>There are currently no selective backups saved.</td></tr>
{/foreach}
</table>


</form>