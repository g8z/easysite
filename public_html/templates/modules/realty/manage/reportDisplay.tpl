<title>Realty Report</title>

{if $smarty.get.type eq 'summary'}

	{* display the group summary report by status *}

	<p class=subtitle>Membership Summary - by Membership Status</p>

	<table border=0 cellpadding=2 cellspacing=2 class=normal>
	<tr><th>Membership Type</th><th>Active</th><th>Pending</th><th>Suspended</th><th>Terminated</th></tr>
	<tr><td>&nbsp;</td></tr>

	{foreach item=data from=$reportData}
	<tr><td>{$data.name}</td><td>{$data.active}</td><td>{$data.pending}</td><td>{$data.suspended}</td><td>{$data.terminated}</td></tr>
	{/foreach}

	{* display the summary data *}

	<tr><td>&nbsp;</td></tr>

	<tr><td>Totals</td><td>{$totalActive}</td><td>{$totalPending}</td><td>{$totalSuspended}</td><td>{$totalTerminated}</td></tr>

	</table>

{elseif ($smarty.get.type eq 'detail' and $smarty.get.sort eq 'last_name') or $smarty.get.type eq 'roster'}

	<p class=subtitle>Membership Detail - by Last Name</p>
	
	<table border=0 cellpadding=2 cellspacing=2 class=normal>
	
	<tr><th>Name</th><th>Status</th><th>Membership Type</th><th>Company Name</th></tr>
	
	<tr><td>&nbsp;</td></tr>
	
	{foreach item=data from=$reportData}
	<tr>
		<td>{if $data.last_name}{$data.last_name}, {/if}{$data.first_name}</td>
		<td>{$data.status}</td>
		<td>{$data.group_name}</td>
		<td>{$data.company}</td>
	</tr>
	{/foreach}
	
	</table>

{elseif $smarty.get.type eq 'detail' and $smarty.get.sort eq 'company'}

	<p class=subtitle>Membership Detail - by Last Name</p>
	
	<table border=0 cellpadding=2 cellspacing=2 class=normal>
	
	<tr><th>Company</th><th>Name</th><th>Membership Type</th><th>Status</th></tr>
	
	<tr><td>&nbsp;</td></tr>
	
	{foreach item=data from=$reportData}
	<tr>
		<td>{$data.company}</td>
		<td>{if $data.last_name}{$data.last_name}, {/if}{$data.first_name}</td>
		<td>{$data.group_name}</td>
		<td>{$data.status}</td>
	</tr>
	{/foreach}
	
	</table>
	
{elseif $smarty.get.type eq 'detail' and $smarty.get.sort eq 'status'}

	<p class=subtitle>Membership Detail - by Status</p>
	
	<table border=0 cellpadding=2 cellspacing=2 class=normal>
	
	<tr><th>Status</th><th>Membership Type</th><th>Name</th><th>Company</th></tr>
	
	<tr><td>&nbsp;</td></tr>
	
	{foreach item=data from=$reportData}
	<tr>
		<td>{$data.status}</td>
		<td>{$data.group_name}</td>
		<td>{if $data.last_name}{$data.last_name}, {/if}{$data.first_name}</td>
		<td>{$data.company}</td>
	</tr>
	{/foreach}
	
	</table>
	
{elseif $smarty.get.type eq 'detail' and $smarty.get.sort eq 'group'}

	<p class=subtitle>Membership Detail - by Membership Type ('Group')</p>
	
	<table border=0 cellpadding=2 cellspacing=2 class=normal>
	
	<tr><th>Membership Type</th><th>Status</th><th>Name</th><th>Company</th></tr>
	
	<tr><td>&nbsp;</td></tr>
	
	{foreach item=data from=$reportData}
	<tr>
		<td>{$data.group_name}</td>
		<td>{$data.status}</td>
		<td>{if $data.last_name}{$data.last_name}, {/if}{$data.first_name}</td>
		<td>{$data.company}</td>
	</tr>
	{/foreach}
	
	</table>

{elseif $smarty.get.type eq 'roster'}

{/if}