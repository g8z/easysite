<table border=0 cellpadding=1 cellspacing=0 width=100% class=normal>
<tr><td>{$logoutLink} {$pathway}</td></tr>

<tr><td>&nbsp;</td></tr>

<tr><td class=subtitle>Attach Files</td></tr>
<tr><td>&nbsp;</td></tr>

<form name="attachForm" method="post" enctype="multipart/form-data">
<input type="hidden" name="attachFormSubmitted" value="1">
<input type="hidden" name="attachmentRemove" value="">

<tr><td>Attach one or more files to your message.</td></tr>

<tr><td>&nbsp;</td></tr>

</table>

<table class="normal">
{if $smarty.session.email.attachments}
<tr>
	<td valign="top" colspan="2"><b>Current Attachments:</b><br /><br />
	{foreach from=$smarty.session.email.attachments item=attachment}
		[ <a href="#" onclick="javascript: document.attachForm.attachmentRemove.value='{$attachment.id}'; document.attachForm.submit();">Remove</a> ] {$attachment.name} ({$attachment.size} KB)<br />
	{/foreach}
	</td>
</tr>
<tr><td colspan="2">&nbsp;</td></tr>

<tr><td colspan="2">&nbsp;</td></tr>

<tr><td colspan="2"><b>Make New Attachment:</b></td></tr>
{/if}

<tr><td>File:</td><td><input type="file" name="attachment"></td></tr>

<tr><td colspan="2">&nbsp;</td></tr>

<tr><td colspan="2"><input type="submit" name="submitButton" value="Upload"> <input type="submit" name="submitButton" value="Upload and Attach Another"> <input type="button" name="cancelButton" value="Cancel" onclick="javascript: document.location.href='{$smarty.const.DOC_ROOT}manage/emailBlast.php'"></td></tr>
</table>

</form>