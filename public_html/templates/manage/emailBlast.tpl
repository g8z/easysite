{literal}
<script language="JavaScript">
<!--

function insertInternalVaraiable() {
    
    var field = eval( 'document.mailForm.body' );
	var width = 400;
	var height = 500;

	var left = ( screen.width - width ) / 2;
	var top = ( screen.height - height ) / 2;

    win = window.open( 'variableChooser.php?mode=email_blast', null, "top="+top+",left="+left+",width="+width+",height="+height+",status=yes,scrollbars=yes,resizable=yes,dependent=yes", true );
    win.opener = window;
    win.opener.field = field;
} 

-->
</script>
{/literal}

<table border=0 cellpadding=1 cellspacing=0 width=100% class=normal>
<tr><td>{$logoutLink} {$pathway}</td></tr>

<tr><td>&nbsp;</td></tr>

<tr><td class=title>E-Mail Blast</td></tr>
<tr><td>&nbsp;</td></tr>

<tr><td>Use this tool to send a mass e-mail to all users of your site, or all members of any particular group of users. You may also send an attachment in your message. Please be aware that some e-mail systems filter attachments for security reasons, so it's usually good practice to also provide a direct download link to your file.</td></tr>

<tr><td>&nbsp;</td></tr>
</table>

<form name="mailForm" method="post">
<input type="hidden" name="mailFormSubmitted" value="1">
<input type="hidden" name="attachmentRemove" value="">

<table width="100%" class="normal">

<tr><td colspan="2" class=subtitle>Your message</td></tr>
<tr><td colspan="2">&nbsp;</td></tr>

<tr><td>From E-Mail: </td><td><input size="40" type="text" name="from_email" value='{$smarty.session.email.from_email|default:$settings.admin_email}'></td></tr>
<tr><td>From Name: </td><td><input size="40" type="text" name="from_name" value='{$smarty.session.email.from_name|default:$settings.admin_name}'></td></tr>

<tr><td colspan="2">&nbsp;</td></tr>

<tr>
	<td valign="top">To: </td>
	<td>
	<input type="radio" name="to" value="all" {if $smarty.session.email.to|default:'all' eq 'all'}checked{/if}> All users<br />
	<input type="radio" name="to" value="mailingList" {if $smarty.session.email.to|default:'all' eq 'mailingList'}checked{/if}> Mailing List {if $listValues}<select name="list_id">{html_options values=$listValues output=$listTitles selected=$smarty.session.email.list_id}</select>{else}(No lists defined){/if} [ <a href="editMailingLists.php">Manage Mailing Lists</a> ]<br />
	<input type="radio" name="to" value="selected" {if $smarty.session.email.to|default:'all' eq 'selected'}checked{/if}> Selected users ({$numSelected|default:0}) [ <a href="#" onclick="javascript: document.mailForm.action='selectUsers.php'; document.mailForm.submit(); return false;">Select</a> ]
	</td>
</tr>

<tr><td colspan="2">&nbsp;</td></tr>

<tr><td>Subject:</td><td><input size="40" type="text" name="subject" value="{$smarty.session.email.subject}"></td></tr>

<tr><td valign="top">Message Body:</td></td><td>[ <a href="javascript: insertInternalVaraiable();">Insert Variable</a> ]<br /><textarea name="body" cols=60 rows=10>{$smarty.session.email.body}</textarea></td></tr>

<tr><td colspan="2">&nbsp;</td></tr>

<tr>
	<td valign="top">Attachments:</td>
	<td>
	{if !$smarty.session.email.attachments}
		No attachments currently made.
	{else}
		{foreach from=$smarty.session.email.attachments item=attachment}
		[ <a href="#" onclick="javascript: document.mailForm.attachmentRemove.value='{$attachment.id}'; document.mailForm.submit(); return false;">Remove</a> ] <b>{$attachment.name} ({$attachment.size|default:'0'} KB)</b><br />
		{/foreach}
	{/if} <br />
	[ <a href="#" onclick="javascript: document.mailForm.action='makeAttachment.php'; document.mailForm.submit(); return false;">Attach Files</a> ]
	</td>
</tr>

<tr><td colspan="2">&nbsp;</td></tr>

<tr>
	<td valign="top">Priority:</td>
	<td>
	<input type="radio" name="priority" value="1" {if $smarty.session.email.priority|default:'3' eq '1'}checked{/if}> High<br />
	<input type="radio" name="priority" value="3" {if $smarty.session.email.priority|default:'3' eq '3'}checked{/if}> Normal<br />
	<input type="radio" name="priority" value="5" {if $smarty.session.email.priority|default:'3' eq '5'}checked{/if}> Low
	</td>
</tr>

<tr><td colspan="2">&nbsp;</td></tr>

<tr><td colspan="2"><input type="submit" name="submitButton" value="Send E-Mail Blast"> <input type="button" name="cancelButton" value="Cancel" onclick="javascript: document.location.href='{$smarty.const.DOC_ROOT}manage/usersAndGroups.php'"></td></tr>

</table>
</form>