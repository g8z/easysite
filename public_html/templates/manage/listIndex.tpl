<form method=post>
<table border=0 cellpadding=0 cellspacing=2 class=normal>
<tr>
<td class=normal>
{$logoutLink} {$pathway}
</td>
</tr>

<tr><td>&nbsp;</td></tr>

<tr><td class=title>List Manager</td></tr>

<tr><td>{if $permissions.add}[ <a href=listEdit.php>Create a New List</a> ] {/if}[ <a href="javascript:launchCentered('{$help.url}?type=lists',{$help.width},{$help.height},'{$help.options}');">What's a list?</a>
 ]</td></tr>

<tr><td>&nbsp;</td></tr>

<tr><td>

{* table of all our lists *}
<table class=normal cellpadding=3 cellspacing=1>

{foreach name=iterator item=list from=$listData}

	{if $smarty.foreach.iterator.iteration eq 1}
	<tr>
		<td><b>Descriptive Title</b></td>

		<td><b>Key <a href="javascript:launchCentered('{$help.url}?type=list_key',{$help.width},{$help.height},'{$help.options}');">{$helpSymbol}</a></b></td>

		<td>{*<b>Controls</b>*}</td>

		<td><b>Sample</b></td>
	</tr>
	{/if}

	<tr>
		<td>{$list.title}</td>

		<td nowrap>{$list.list_key}</td>

		<td nowrap align=center>{if $permissions.edit}<a href="listEdit.php?id={$list.id}"><img alt="Edit List" src="{$docroot}images/edit.png" border=0></a>{/if}  {if $permissions.delete}<a onClick="return confirm( 'Are you sure? This action will delete the list and all items within it.' );" href="listIndex.php?del={$list.id}"><img alt="Drop List" src="{$docroot}images/drop.png" border=0></a>{/if}  
		
		{if $smarty.foreach.iterator.iteration ne 1}
		{if $permissions.edit}<a href="listIndex.php?bump={$list.id}"><img alt="Bump Up List" src="{$docroot}images/bump.png" border=0></a>{/if}
		{/if}
		
		</td>

		{* call the Smarty function to create this list *}

		<td>{list key=$list.list_key}</td>
	</tr>

{foreachelse}

	<tr><td colspan=4>No lists have been defined yet. Why not <a href="listEdit.php">create one</a>?</td></tr>

{/foreach}

</table>

</td></tr>

<tr><td>&nbsp;</td></tr>

<tr>
<td class=normal>
{$logoutLink} {$pathway}
</td>
</tr>
</table>
</form>