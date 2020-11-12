<br />

<table width="100%" style="border:1px solid #000000" bgcolor="#EEEEEE" class=normal>

<tr><td>

{if $upgradeSuccessull eq 1}
<font color=green>The database tables were successfully upgraded.</font>
<input type=submit name=submit value="Finish" onclick="javascript:document.location.href='index.php'">
{/if}

{if $upgradeSuccessull eq 0}
<font color="red"><b>ERROR: </b> One or more database tables could not be upgraded. Check the appropriate SQL file to make sure there are no syntax errors in it, and be sure that you have the correct prior version of EasySite.</font>

There were some problems with the upgrade.<br /><br />Please check to ensure that there are no errors in the upgrade file, and that you have not attempted to repeat the upgrade on an already-upgraded system.
<br /><br />

<input type="button" name="start" onclick="javascript:document.location.href='?step=2'" value="<< Back">
{/if}

</td></tr>
</table>
