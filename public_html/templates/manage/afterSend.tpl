<table border=0 cellpadding=1 cellspacing=0 width=100% class=normal>
<tr><td>{$logoutLink} {$pathway}</td></tr>

<tr><td>&nbsp;</td></tr>

<tr><td class=subtitle>E-Mails Sending Results</td></tr>
<tr><td>&nbsp;</td></tr>

{if $sent}
{foreach from=$sent item=success key=user}
<tr><td>{if $success}E-Mail to <b>{$user}</b> was successfully sent{else}<font color="red">E-Mail sending to <b>{$user}</b> failed.</font>{/if}</td></tr>
{/foreach}
<tr><td>&nbsp;</td></tr>
<tr><td>[ <a href="emailBlast.php">Send Another E-Mail</a> ] [ <a href="usersAndGroups.php">Return to Users & Groups Index</a> ]</td></tr>
{else}
<tr><td>There were no users to send mail to.</td></tr>
<tr><td>&nbsp;</td></tr>
<tr><td>[ <a href="emailBlast.php">Return To Your Message</a> ]</td></tr>
{/if}

<tr><td>&nbsp;</td></tr>
</table>