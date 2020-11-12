{literal}
<script language="Javascript">
function doSubmit() {
	// check for a valid title, and numeric input for width/height of popup window
	
	var formObj = document.details;
	
	if ( formObj.title.value.trim() == '' ) {
		alert( 'Please input a title for this poll.' );
		formObj.title.focus();
		return false;
	}
	
	if ( formObj.pwidth.value == '' || !isNumeric( formObj.pwidth.value.trim() ) ) {
		alert( 'Please input a numeric width' );
		formObj.pwidth.focus();
		return false;
	}
	if ( formObj.pheight.value == '' || !isNumeric( formObj.pheight.value.trim() ) ) {
		alert( 'Please input a numeric height' );
		formObj.pheight.focus();
		return false;
	}
	
	formObj.submit();
}
</script>
{/literal}

{*
<p class=normal>{$navigation}</p>
*}

<span class=normal>{include file=modules/poll/navigation.tpl}</span><br />

<form action=detail.php method=post name=details>

<input type=hidden name=id value="{$data.id}">

<table cellpadding=1 cellspacing=0 border=0 width=100% class=normal>


{* a mini-form to allow the user to change the poll data, or create a new poll *}
<tr><td colspan=4 class=subtitle>
{if $data.id eq ''}New{/if} Poll Properties<br />
<table border=0 cellpadding=2 cellspacing=0 class=normal>

<tr><td>Poll Title: </td><td><input type=text name=title value="{$data.title}" size=40></td></tr>
<tr><td>Form Source: </td><td>

<select name=form_id>
{html_options options=$forms selected=$data.form_id}
</select>

{if $data.form_id}
[ <a target=_blank href="{$docroot}{$smarty.const.ADMIN_DIR}/editForms.php?form_id={$data.form_id}">Edit Form</a> ]
{else}
[ <a target=_blank href="{$docroot}{$smarty.const.ADMIN_DIR}/editForms.php">Form Tool</a> ]
{/if}

</td></tr>

<tr><td>Pop-up Width: </td><td><input type=text name=pwidth value="{$data.width|default:600}" size=10> 

[ <a href="javascript:launchCentered( '../poll.php?id={$data.id}', {$data.width|default:600}, {$data.height|default:400}, 'resizable,scrollbars,status' );">test</a> ]

{*(if poll is a pop-up)*}</td></tr>

<tr><td>Pop-up Height: </td><td><input type=text name=pheight value="{$data.height|default:500}" size=10> {*(if poll is a pop-up)*}</td></tr>

<tr><td>Display To: </td><td>
<select name=group_id>
<option value="all" {if $data.group_id eq 'all'}selected{/if}>All Website Visitors</option>
<option value="auth" {if $data.group_id eq 'auth'}selected{/if}>Authenticated Users Only</option>
{html_options options=$groups selected=$data.group_id}
</select>

{*
<tr><td>Frequency: </td><td>
</td></tr>
*}

{*
<tr><td valign=top>Poll is a:</td><td>

	<input type=radio value=1 name=popup {if $data.popup eq 1}checked{/if}>Pop-up window (default). [ <a href="javascript:launchCentered( '../index.php', {$data.width|default:400}, {$data.height|default:400}, 'resizable,scrollbars' );">test</a> ]<br />
	
	<input type=radio value=0 name=popup {if $data.popup eq 0}checked{/if}>Page that I will link to. [ <a target=_blank href="../index.php">test</a> ]<br />

</td></tr>
*}

<tr><td></td><td><input type=button onClick="javascript:doSubmit();" value="{if $data.id eq ''}Submit New Poll{else}Update Properties{/if}"></td></tr>

<input type=hidden name=formIsSubmitted value=1>

</table>



{if $data.id}
	<tr><td colspan=4>&nbsp;</td></tr>

	<tr><td colspan=4 class=subtitle>Results for "{$data.title}"</td></tr>

	{foreach name=iterator key=label item=polldata from=$results}

	{if $smarty.foreach.iterator.iteration eq 1}

	<tr>
		<td nowrap><b>Poll Question</b></td>
		<td nowrap><b>Answer Given</b></td>
		<td width=50 colspan=2><b>Totals</b></td>
	</tr>
	{else}
	
	<tr><td colspan=4><hr size=1 noshade style="width:100%"></td></td>
	{/if}

	  {foreach key=index name=pollanswer item=subdata from=$polldata}

		{if $index eq 0}

			{*<tr><td>{$label}</td>*}

			{foreach name=subpollanswer key=answer item=count from=$subdata}

			{if $smarty.foreach.subpollanswer.iteration eq 1}
				<tr><td>{$label}</td>
			{else}
				<tr><td>&nbsp;</td>
			{/if}

			{* compute the percentage using $count and $polldata[1] *}

			{if $polldata[1] ne 0}
			{math equation="100 * x/y" x=$count y=$polldata[1] assign=percentage}
			{/if}

			<td>{$answer}</td><td>{$count}</td><td>({$percentage|default:0|number_format}%)</td></tr>

			{/foreach}
		{/if}

	  {/foreach}

	{foreachelse}
	<tr><td colspan=4>There are no results yet for this poll.</td></tr>
	{/foreach}

	<tr><td colspan=4>&nbsp;</td></tr>

{/if}

</table>

</form>