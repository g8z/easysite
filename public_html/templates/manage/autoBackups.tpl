<table border=0 cellpadding=0 cellspacing=5 class=normal>

<tr><td colspan=2>{$logoutLink} {$pathway}</td></tr>
<tr><td colspan="2">[ <a href="backup.php?action=backupScreen">Backup</a> ] [ <a href="backup.php?action=restoreScreen">Restore</a> ] [ <a href="autoBackups.php">Auto-Backup</a> ]</td></tr>
</table>

<p class=subtitle>Auto-Backups</p>

<p class="normal">Auto-Backups are saved system backups, or selective backups, which are sent automatically to your e-mail address on specific days and times as determined by a cronjob.</p>

<div class="normal"><a href="editauto.php">Add New Auto-Backup</a></div>
{foreach from=$backups item=backup}
<p class="normal">
  <b>{$backup.name}</b>
  <br />Cron Job URL: <br /><a href="{$backup.secret_cron}">{$backup.secret_cron}</a><br /><br />
  <input type=button onclick="javascript: document.location.href='editauto.php?id={$backup.id}'" value="Edit"> <input type=button onclick="javascript: if ( confirm( 'Are you really want to delete auto-backup named \'{$backup.name}\'' ) ) document.location.href='editauto.php?id={$backup.id}&action=delete';" value="Delete">
</p>
{foreachelse}
<p class="normal">No Auto-Backups Defined</p>
{/foreach}
