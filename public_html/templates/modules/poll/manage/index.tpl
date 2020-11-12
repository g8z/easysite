<span class=normal>{include file=modules/poll/navigation.tpl}</span><br />

{if $pollReset}
<p class=normal>The poll named "{$pollReset}" has been reset. All user data submitted for this poll has been permanently deleted.</p>
{/if}

<table border=0 cellpadding=2 cellspacing=0 class=normal>

<tr><td><span class=subtitle>{$sectionTitle|default:"Polls"}</span></td></tr>

<tr><td>Click on the title of any poll to view the poll results, or to edit the poll properties. Click on the Form Souce links to edit the form which forms the basis of the poll. It is recommended that you limit to one the number of active polls.

<a href="javascript:launchCentered('{$help.url}?type=polls',{$help.width},{$help.height},'{$help.options}');">Tell me more about polls.</a>

</td></tr>

<tr><td>&nbsp;</td></tr>

<tr><td>

<table border=0 cellpadding=2 cellspacing=0 class=normal width=100%>

{if $polls}
<tr>
	<td valign=bottom><b>Title</b></td>
	<td valign=bottom><b>Form Source</b></td>
	<td valign=bottom><b>Date Added</b></td>
	<td valign=bottom><b>Active?</b></td>
	<td colspan=2><b>Controls</b></td>
</tr>


{foreach item=poll from=$polls}

<tr>
	<td><a href="detail.php?id={$poll.id}">{$poll.title}</a></td>
	
	<td><a href="{$docroot}manage/editForms.php?form_id={$poll.form_id}">{$poll.form_title|htmlentities}</a></td>
	
	<td>{$poll.added_on|date_format:"%B %d, %Y"}</td>
	
	<td><a href="?id={$poll.id}&active={if $poll.active}0{else}1{/if}">{if $poll.active eq 1}Yes{else}No{/if}</a></td>
	
	<td>
	
	{if $poll.results}
		<a href="?id={$poll.id}&reset=1" onClick="return confirm('Are you sure? This will delete any existing result data for this poll!');">Reset</a>
	{else}
		Reset
	{/if}
	
	</td>
	
	<td>
		{if $smarty.session.cm_auth.cm_module and $smarty.session.cm_auth.cm_poll and $smarty.session.cm_auth.cm_poll_delete_polls}
        <a href="?id={$poll.id}&drop=1" onClick="return confirm('Are you sure? This will permanently remove the poll named \'{$poll.title}\'');"><img src="{$docroot}images/drop.png" border=0></a>
        {else}
        &nbsp;
        {/if}
	</td>
</tr>

{/foreach}
{else}
<tr><td colspan="5">There is no polls currently present. <a href="{$smarty.const.DOC_ROOT}{$smarty.const.MODULES_DIR}/poll/manage/detail.php">Create a New Poll</a></td></tr>
{/if}

</table>

</td></tr>

</table>