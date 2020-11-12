{literal}
<script language="JavaScript">
<!--

function submitForm() {
	
	var theForm = document.forms[0];
	
	//alert( theForm.groups[0].checked );
	var groupChecked = false;
	var userChecked = false;
	
	if ( theForm.groups.length )

		for( i=0, n=theForm.groups.length; i<n; i++ )
			groupChecked |=  theForm.groups[i].checked;
	else
		groupChecked = theForm.groups.checked;
		
	for( i=0, n=theForm.users.length; i<n; i++ )
		userChecked |=  theForm.users.options[i].selected;
		
		
	if ( groupChecked || userChecked ) {
		theForm.submit();
	}
	else {	
		alert( 'Please choose at least one group or user' );
	}
}

-->
</script>
{/literal}

<table class="normal">

<tr><td><b>Send e-mail to all users with these statuses:</b></td></tr>
<tr><td>
	{foreach from=$statuses key=status item=title}
	<input type="radio" value="{$status}" name="status" {if $status|default:'active' eq $sourceArray.status}checked{/if}> {$title} {if $status eq 'active'}(message will be sent to all active users in all groups){/if}<br />
	{/foreach}
</td></tr>

<tr><td>&nbsp;</td></tr>

<tr><td><b>Send this e-mail to members of these groups.</b><br />For example, to send an email to all Pending users of Groups X and Y, check "Pending" above and then check the Group X and Group Y checkboxes below.</td></tr>
<tr><td>
	{foreach from=$groups item=group}
	<input type="checkbox" value="{$group.id}" name="groups[]" id="groups" {if @$group.id|in_array:$sourceArray.groups}checked{/if}> {$group.name}<br />
	{/foreach}
</td></tr>

<tr><td>&nbsp;</td></tr>

<tr><td>In addition to sending the e-mail to members of the above groups, you may specify individual users to also receive the e-mail. For example, if you wish to send the e-mail to all users in Group Y, and also a few specific members of Groups Y and Z, then you would check the Group Y option above, and choose the individual users from the list below. You may use the CTRL key on your keyboard to select multiple users.</td></tr>

<tr><td>&nbsp;</td></tr>

<tr><td><select name="users[]" multiple id="users" size="8">{html_options values=$userValues output=$userTitles selected=$sourceArray.users}</select></td></tr>

<tr><td>&nbsp;</td></tr>

</table>