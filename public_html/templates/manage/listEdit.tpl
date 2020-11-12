{literal}
<script type="text/javascript">
/* <[CDATA[ */
function doSubmit() {
	// check for key, valid inputs

	var formObj = document.listForm;

	if ( formObj.title.value.trim() == '' ) {
		alert( 'Please input a non-blank descriptive title for this list.' );
		formObj.title.focus();
		return false;
	}
	// list key must be alpha-numeric, but _ is allowed

	if ( formObj.list_key.value.trim() == '' || !isAlphaNumeric( formObj.list_key.value , '_' ) ) {
		alert( 'Please input a valid, unique list key. The list key should not contain spaces or special symbols.' );
		formObj.title.focus();
		return false;
	}
	{/literal}
	{if $list.id}
	if ( formObj.list_key.value.trim() != formObj.current_key.value.trim() ) {ldelim}
		if ( !confirm( 'You have requested to change the List Key. This *may* cause some modules to fail in some cases, since some modules use pre-defined lists, like "states" and "countries". Are you sure that you want to do this?' ) ) {ldelim}
			return false;
		{rdelim}
	{rdelim}
	{/if}
	{literal}

	formObj.formIsSubmitted.value = 1;
	formObj.submit();
}
/* ]]> */
</script>
{/literal}

<form method=post action="" name=listForm>
<table border=0 cellpadding=0 cellspacing=2 class=normal>

<tr>
<td class=normal>
{$logoutLink} {$pathway}
</td>
</tr>

<tr><td>&nbsp;</td></tr>

<tr><td class=title>{if $list.id gt 0}Edit List: {$list.list_key}{else}Add a New List{/if}</td></tr>

<tr><td>&nbsp;</td></tr>

<tr><td>[ <a href=listIndex.php>
Return to the List Index</a> ]{if $permissions.add} [ <a href=listEdit.php>Create a New List</a> ]{/if} [ <a href="javascript:launchCentered('{$help.url}?type=lists',{$help.width},{$help.height},'{$help.options}');">What's a list?</a>
 ]</td></tr>

{if $listKeyExists}
<tr><td>&nbsp;</td></tr>
<tr><td>NOTE: The list key that you have requested is already in use. Your list *has* been added or updated, but with the revised list key shown below. Please choose a unique list key for this list.</td></tr>
{/if}

<tr><td>&nbsp;</td></tr>

<tr><td>

{* table of all our lists *}
<table class=normal cellpadding=3 cellspacing=1>

<tr><td colspan=2>

<table class=normal cellpadding=2 cellspacing=0>
<tr><td align=right>Descriptive Title: </td><td><input type=text size=40 name=title value="{$list.title}"></td></tr>
<tr><td align=right>List Key (must be unique): </td><td nowrap>

<input type=text size=20 name=list_key value="{$list.list_key}">
<input type=hidden name=current_key value="{$list.list_key}">

<a href="javascript:launchCentered('{$help.url}?type=list_key',{$help.width},{$help.height},'{$help.options}');">{$helpSymbol}</a>

</td></tr>

{if $list.id}
<tr><td align=right>List Preview: </td><td>{list key=$list.list_key}</td></tr>

{/if}

</table>

</td></tr>

<tr><td colspan=2><b>How does it work?</b> For each item in the left column, there should be a label in the right column. Extra spaces before or after the list contents are removed. If you want a "- Select One -" label, add it to the top of the List Labels column, with a 0 or "N/A" value in the data row.</td></tr>

<tr><td colspan=2>

<table class=normal>

<tr>
<td align=center>List Data<br /><small>(what the user does not see)</small></td>
<td align=center>List Labels<br /><small>(what the user sees)</small></td>
</tr>

<tr>

{* note: $listData and $listLabels MUST be on new lines for this to work *}

<td align=center><textarea rows=20 cols=30 name=listData>
{$listData}</textarea></td>

<td align=center><textarea rows=20 cols=40 name=listLabels>
{$listLabels}</textarea></td>

</tr>

<tr><td colspan=2 align=center><input type=button onClick="javascript:doSubmit();" value="{if $list.id gt 0}Update List{else}Add New List{/if}"></td></tr>

</table>

</td></tr>

</table>

</td></tr>

<tr><td>&nbsp;</td></tr>

<tr>
<td class=normal>
{$logoutLink} {$pathway}
</td>
</tr>

</table>

<input type=hidden name=id value="{$list.id}">
<input type=hidden name=formIsSubmitted value="">
</form>
