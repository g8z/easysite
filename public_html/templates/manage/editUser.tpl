{literal}
<script type="text/javascript">
<!--
function validateForm() {

	var theForm = document.userForm;

	// check for required fields:
	// user id, password, group

	if ( theForm.login_id.value.trim() == '' ) {
		alert( 'Login ID is a required field.' );
		theForm.login_id.focus();
		theForm.login_id.select();
		return;
	}
	{/literal}
	{if !$data.id}
	else if ( theForm.login_pass.value.trim() == '' ) {ldelim}
		alert( 'Password is a required field.' );
		theForm.login_pass.focus();
		theForm.login_pass.select();
		return;
	{rdelim}
	{/if}
	{literal}
	else if ( theForm.group_id.value == '' ) {
		alert( 'Please choose a group for this user. If no groups exist, then you must define at least one group first.' );
		theForm.group_id.focus();
		//theForm.group_id.select();
		return;
	}


	theForm.formIsSubmitted.value = 1;
	theForm.submit();
}
//-->
</script>
{/literal}

{assign var=deleteConfirmString value="Are you sure? This user\'s information will be permanently removed. Any website that this user has created will also be removed! If you wish to keep this user\'s website, but dis-allow access to restricted areas that you have created, then you should simply expire the user rather than delete the user altogether."}
{assign var=deleteSiteConfirmString value="Are you sure you want to delete user website?"}

<form action="" method=post name=userForm>

<input type=hidden name=formIsSubmitted value="">
<input type=hidden name=userID value="{$data.id|default:NEW}">

<table border=0 cellpadding=1 cellspacing=1 width=100% class=normal>

<tr><td colspan=2>{$logoutLink} {$pathway}</td></tr>
<tr><td colspan=2>{$addUserLink} {if $data.id}{$userWebsiteLink}{/if}</td></tr>
<tr>
<td><td colspan=2>&nbsp;</td></tr>



{if $loginExistsError}

<tr><td colspan=2>

<span class=subtitle>Error</span><br />
<span class=normal>The login ID that you have specified already exists. Please choose another.</span>

</td></tr>

<tr><td colspan=2>&nbsp;</td></tr>

{/if}

<tr><td colspan=2 class=title>{$type} User</td></tr>

<tr><td colspan=2 class=normal>Required items: Login ID, Password, Group/Dept.</td></tr>

<tr><td colspan=2>&nbsp;</td></tr>

<tr><td nowrap width=10%>Login ID: </td><td><input type=text name=login_id size=15 value="{$data.login_id}"></td></tr>
<tr><td>{if $data.id}New {/if}Password: </td><td><input type=text name=login_pass size=15>{if $data.id} Leave blank for no change{/if}</td></tr>

<tr><td>Group/Dept: </td><td>

<select name=group_id>
{html_options options=$groups selected=$data.group_id}
</select>
{$addGroupLink}
<a href="javascript:launchCentered('{$help.url}?type=groups',{$help.width},{$help.height},'{$help.options}');">{$helpSymbol}</a>
</td></tr>

<tr><td nowrap>First Name: </td><td><input type=text name=first_name size=20 value="{$data.first_name}"></td></tr>

<tr><td>Last Name: </td><td><input type=text name=last_name size=20 value="{$data.last_name}"></td></tr>

<tr><td>E-Mail: </td><td><input type=text name=email size=30 value="{$data.email}">
{if $data.email}[ <a href="mailto:{$data.email}">mail</a> ]{/if}
</td></tr>

<tr><td>URL: </td><td><input type=text name=url size=30 value="{$data.url}">
{if $data.view_url}[ <a target=_blank href="{$data.view_url}">view</a> ]{/if}</td></tr>

<tr><td>Company/Organization: </td><td><input type=text name=company size=30 value="{$data.company}"></td></tr>

<tr><td>Fax: </td><td><input type=text name=fax size=30 value="{$data.fax}"></td></tr>

<tr><td>Phone: </td><td><input type=text name=phone size=30 value="{$data.phone}"></td></tr>

<tr><td>Address, Line 1: </td><td><input type=text name=address_1 size=30 value="{$data.address_1}"></td></tr>

<tr><td nowrap>Address, Line 2: </td><td><input type=text name=address_2 size=30 value="{$data.address_2}"></td></tr>


<tr><td nowrap>City: </td><td><input type=text name=city size=30 value="{$data.city}"></td></tr>

<tr><td nowrap>State: </td><td><input type=text name=state size=30 value="{$data.state}"></td></tr>

<tr><td nowrap>Zip: </td><td><input type=text name=zip size=30 value="{$data.zip}"></td></tr>

<tr><td nowrap>Country: </td><td>{list name=country key=countries selected=$data.country}{*<input type=text name=country size=30 value="{$data.country}">*}</td></tr>


<tr><td valign=top>Comments: </td><td><textarea name=comments rows=3 cols=50>{$data.comments}</textarea></td></tr>

<tr><td nowrap>Member ID: </td><td><input type=text name=member_id size=30 value="{$data.member_id}"></td></tr>

<tr><td>Expires? </td><td><input type=checkbox name=use_expiration value=1 {if $data.use_expiration eq 1}checked{/if}>
<a href="javascript:launchCentered('{$help.url}?type=expires',{$help.width},{$help.height},'{$help.options}');">{$helpSymbol}</a>
</td></tr>

<tr><td>Expiration Date: <br /><small>(if expires)</small></td><td>{html_select_date time=$time end_year="+10"}
<a href="javascript:launchCentered('{$help.url}?type=exp_date',{$help.width},{$help.height},'{$help.options}');">{$helpSymbol}</a>
</td></tr>

{* some companies that are using this as an employee database may require a status option *}
<tr><td valign=top>Status: </td><td>
<select name=status>
{html_options options=$statusOptions selected=$data.status}
</select>
<a href="javascript:launchCentered('{$help.url}?type=user_status',{$help.width},{$help.height},'{$help.options}');">{$helpSymbol}</a>

</td></tr>

{* end of demo mode condition *}

<tr><td colspan=2>
{if $permissions.add or $permissions.edit}
<input type=button onClick="validateForm();" value='Submit All'>
{/if}

{if $data.id and $permissions.delete}
	<input type=submit name=deleteUser value="Delete User" onClick="return confirm('{$deleteConfirmString}');">

{/if}

</td></tr>

{if $data.id and $permissions.delete}
	<tr><td colspan=2 class=small>NOTE: Deleting a user will <b>permanently</b> remove any website that has been defined for the user. If you wish to keep the user's website, but simply deny access to a restricted area, then you should expire the user rather than delete the user altogether.</td></tr>
{/if}
<tr>
<td><td colspan=2>&nbsp;</td></tr>
<tr><td colspan=2>{$addUserLink} {if $data.id}{$userWebsiteLink}{/if}</td></tr>
<tr><td colspan=2>{$logoutLink} {$pathway}</td></tr>

</table>
<input type=hidden name=currentLoginName value="{$data.login_id}">

</form>
