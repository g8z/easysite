{* special js functions for this page only *}
{literal}
<script language="Javascript">
<!--

// -->
</script>
{/literal}

<table border=0 cellpadding=0 cellspacing=3 width=100% class=normal>
<form action=reports.php method="POST" enctype="multipart/form-data" name="embedReports">
<input type=hidden name=action value="processEmbed">
<input type=hidden name=id value="{$reportId}">

<tr><td class=normal colspan=2 >{$logoutLink} {$pathway}</td></tr>

<tr><td colspan=2>&nbsp;</td></tr>

<tr><td class=title colspan=2>Embed Reports</td></tr>
<tr><td class=subtitle colspan=2>for the report called "{$report.name}"</td></tr>
<tr><td colspan=2>&nbsp;</td></tr>
<tr><td colspan=2>You may embed reports into the existing report. If the report resource is a form submission, then you may also have conditionally-redirect reports embedded. If the report resource is a database table, then the reports which are connected to those database tables can be embedded. To see the embedded reoprt contents, you should insert it into the report layout.</td></tr>
    
<tr><td colspan=2>&nbsp;</td></tr>

{if $embeddedTypes}
	<tr><td colspan=2>
	Reports with the following resources can be embed to the current report:
	<ul>
	{foreach from=$embeddedTypes key=resource item=type}
	<li>{$type.title}</li>
	{/foreach}
	</ul>
	</td></tr>
	
	<tr><td colspan=2><b>Embed Existing Reports:</b></td></tr>
	{foreach from=$embedableReports item=report}
	<tr><td colspan=2><input type=checkbox name="report_{$report.id}" {if $report.embedded}checked{/if}> {$report.name} (resource - {$report.resource})</td></tr>
	{foreachelse}
	<tr><td colspan=2>There are no existing reports to embed.</td></tr>
	{/foreach}
	
	<tr><td colspan=2>&nbsp;</td></tr>

	<tr><td colspan=2><b>Create New Embedded Report:</b></td></tr>
	<tr><td>Name: </td><td><input type=text name="name"></td></tr>
	<tr><td>Resource: </td><td><select name="resource">{html_options output=$resourceTitles values=$resourceIds}</select></td></tr>
{else}
<tr><td colspan=2>No reports can be embedded into the currently-loaded report.</td></tr>
{/if}

<tr><td colspan=2>&nbsp;</td></tr>

<tr><td colspan=2><input type=submit name=submitButton value="Submit"> <input type=reset name=resetButton value="Reset"></td></tr>

</table>