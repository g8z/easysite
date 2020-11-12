<table border=0 cellpadding=1 cellspacing=0 width=100% class=normal>
<tr><td>{$logoutLink} {$pathway}</td></tr>

<tr><td>&nbsp;</td></tr>

<tr><td class=subtitle>Select Users</td></tr>
<tr><td>&nbsp;</td></tr>


{if $selectionFormSubmitted}

<tr><td>You have selected {$numSelected} users.</td></tr>
<tr><td>&nbsp;</td></tr>
<tr><td>[ <a href="emailBlast.php">Return To E-Mail Message</a> ] [ <a href="selectUsers.php">Edit Selection</a> ]</td></tr>
<tr><td>&nbsp;</td></tr>

</table>

{else}

<form name="selectionForm" method="post">
<input type="hidden" name="selectionFormSubmitted" value="1">

<tr><td>On this page you may choose which users, or groups of users, should receive your e-mail blast.</td></tr>

<tr><td>&nbsp;</td></tr>
</table>

{include file='manage/mailUsersSelection.tpl'}

<table class="normal">

<tr><td>
	You may save the above selections as a "mailing list", for use in future e-mail blasts. If the mailing list name already exists, it will be over-written.<br /><br />
	<input type="checkbox" name="save_selection" value="1"> Save this selection as: <input type="text" name="selection_name">
</td></tr>

<tr><td>&nbsp;</td></tr>

<tr><td><input type="button" name="submitButton" value="Make Selection" onclick="javascript: submitForm();"> <input type="button" name="cancelButton" value="Cancel" onclick="javascript: document.location.href='{$smarty.const.DOC_ROOT}/manage/emailBlast.php'"></td></tr>

</table>
</form>
{/if}