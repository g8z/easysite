{literal}
<script language=Javascript>
<!--

function getRadioValue( obj )
{
	var numItems = obj.length;

	for ( var i = 0; i < numItems; i++ ) {
		if ( obj[i].checked == true )
			return obj[i].value;
	}
}

function downloadContacts() {
	var formObj = document.downloadContactsForm;
	
	formObj.target = '_self';
	
	if ( getRadioValue( formObj.delimiter ) == 'other' ) {
	
		// check for valid delimiter, if 'other' is specified
	
		if ( formObj.other.value == '' ) {
			alert( 'Please specify a separation value.' );
			formObj.other.focus();
			formObj.other.select();
			return false;
		}
		else if ( formObj.other.value.trim() == '' ) {
			alert( 'Please sepecify a valid separator (space is not a valid sparator).' );
			formObj.other.focus();
			formObj.other.select();
			return false;
		}
	}
	else if ( getRadioValue( formObj.delimiter ) == 'web' ) {
		formObj.target = '_blank';
	}
	
	formObj.submit();
}
//-->
</script>
{/literal}


<form action=editUser.php method=post>
<table border=0 cellpadding=1 cellspacing=0 width=100% class=normal>
<tr><td colspan=2>{$logoutLink} {$pathway}</td></tr>



<tr><td colspan=2>&nbsp;</td></tr>

<tr><td colspan=2 class=title>User & Group Admin</td></tr>
<tr><td colspan=2>&nbsp;</td></tr>

{if $permissions.user_add or $permissions.user_edit or $permissions.user_delete}
    <tr><td colspan=2 class=subtitle>Users & User Websites</td></tr>
    
    <tr><td colspan=2>You may define user logins for restricted sections of your website. Which sections of your website a user has access to is defined by the user's group membership. Each user can also have his or her own website, controlled by these same content-management tools. [ <a href="javascript:launchCentered('{$help.url}?type=user_websites',{$help.width},{$help.height},'{$help.options}');">more about user websites</a> ]</td></tr>
    
    <tr><td colspan=2>&nbsp;</td></tr>
    <tr><td nowrap colspan=2>
    
    {if $permissions.user_add}
    <input type=button onClick="document.location.href='editUser.php'" value="Create New User"> 
    {/if}
    
    {if $permissions.user_add and ($permissions.user_edit or $permissions.user_delete)}
    <b>- OR -</b> 
    {/if}
    
    {if $permissions.user_edit or $permissions.user_delete}
    Edit this User:
    <select name=userID>
    {html_options options=$users}
    </select>
    <input type=submit value=Go>
    {/if}
    
    {if $permissions.user_import}
    &nbsp; <a href=importUsers.php>Import User List</a>
    {/if}
    
    </td></tr>
</table>
    </form>
{/if}



{if $permissions.user_add or $permissions.user_edit or $permissions.user_delete}
<table border=0 cellpadding=1 cellspacing=0 width=100% class=normal>
<tr><td colspan=2>&nbsp;</td></tr>
<tr><td colspan=2 class=subtitle>E-Mail Blast</td></tr>
<tr><td colspan=2>Send an e-mail to all users in your system, or all users belonging to any particular group, or any set of groups. <a href="emailBlast.php">Compose your e-mail.</a></td></tr>
</table>

{/if}


{if $permissions.group_add or $permissions.group_edit or $permissions.group_delete}
    <form action=editGroup.php method=post>
    <table border=0 cellpadding=1 cellspacing=0 width=100% class=normal>
    
    <tr><td colspan=2>&nbsp;</td></tr>
    <tr><td colspan=2 class=subtitle>Groups</td></tr>
    
    
    <tr><td colspan=2>This is a way to 'categorize' users, so that they may 
    share similar access permissions. This tool is also used to determine which sections of the website should be restricted to this group.
    </td></tr>
    
    <tr><td colspan=2>&nbsp;</td></tr>
    <tr><td colspan=2 nowrap>
    
    {if $permissions.group_add}
    <input type=button onClick="document.location.href='editGroup.php'" value="Create New Group"> 
    {/if}
    
    {if $permissions.group_add and ($permissions.group_edit or $permissions.group_delete)}
    <b>- OR -</b> 
    {/if}
    
    {if $permissions.group_edit or $permissions.group_delete}
    Edit this Group: 
    <select name=groupID>
    {html_options options=$groups}
    </select>
    <input type=submit value=Go>
    {/if}
    </td></tr>
    
    <tr><td colspan=2>&nbsp;</td></tr>
    <tr><td colspan="2" class="subtitle">Guest User Permissions</td></tr>
    <tr><td colspan=2>Specify the resources that are accessible by users not logged-in to the system. <a href="guestPermissions.php">Specify Permissions</a></td></tr>
    
    </table>
    </form>
{/if}



{if $permissions.user_download}

<form action=downloadUsers.php method=post name=downloadContactsForm target="_self">
<table border=0 cellpadding=1 cellspacing=0 width=100% class=normal>

<tr><td colspan=2>&nbsp;</td></tr>
<tr><td colspan=2 class=subtitle>Download User Information</td></tr>

<tr><td colspan=2>You may download all of the information about your users as a single tab- or comma-delimited file; for example, to import into Excel to into a Personal Information Manager.
</td></tr>

<tr><td colspan=2>&nbsp;</td></tr>

<tr><td colspan=2><b>Display Options</b></td></tr>

<tr><td width=10%><img src={$docroot}images/spacer.gif width=20 height=1></td><td>

	<input checked type=radio value=comma name=delimiter> File with items separated by commas<br />
	<input type=radio value=tab name=delimiter> File with items separated by tabs<br />
	<input type=radio value=web name=delimiter> Web table (displayed in web browser, not downloaded)<br />
	<input type=radio value=other name=delimiter> File separated by this value <input type=text name=other value="" size=3><br />
	<hr size=1 noshade>
	<input type=checkbox name=headers value=1 checked> Include header row (for example: First Name, Last Name)<br />
	<input type=checkbox name=quotes value=1> Surround values with quotes (for example: "Bill", "Gates")
</td></tr>

<tr><td colspan=2>&nbsp;</td></tr>

<tr><td nowrap colspan=2><input type=button onClick="javascript:downloadContacts();" value="Download All"> <b>- OR -</b> Only Download this Group of Users:

<select name=groupID>
{html_options options=$groups}
</select>
<input type=button onClick="javascript:downloadContacts();" value=Go>
</td></tr>

<tr><td colspan=2>&nbsp;</td></tr>


<tr><td colspan=2>{$logoutLink} {$pathway}</td></tr>

</table>
</form>
{/if}


