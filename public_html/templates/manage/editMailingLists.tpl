<table border=0 cellpadding=1 cellspacing=0 width=100% class=normal>
<tr><td>{$logoutLink} {$pathway}</td></tr>

<tr><td>&nbsp;</td></tr>

<tr><td class=subtitle>Manage Mailing Lists</td></tr>
<tr><td>&nbsp;</td></tr>

<tr><td>Mailing lists allow you to save user and group selections from prior e-mail blasts.</td></tr>
</table>

<form name="mlForm" method="post">
<input type="hidden" name="mlFormSubmitted" value="1">
<input type="hidden" name="deleteList" value="">

<table class="normal">

<tr><td>Switch To List: </td><td><select name="list_id" onchange="javascript: document.mlForm.mlFormSubmitted.value='0'; document.mlForm.submit();">{html_options values=$listValues output=$listTitles selected=$list.id}</select> {if $list.id}<input type="button" value="Delete" onclick="javascript: if (confirm('Are you really want to delete this list?')) {ldelim}document.mlForm.deleteList.value='{$list.id}'; document.mlForm.submit();{rdelim}">{/if}</td></tr>
<tr><td>Name: </td><td><input type="text" name="name" value="{$list.name}"></td></tr>

<tr><td>&nbsp;</td></tr>
</table>

{include file='manage/mailUsersSelection.tpl'}

<input type="button" name="submitButton" value="Save" onclick="javascript: submitForm();">
</form>