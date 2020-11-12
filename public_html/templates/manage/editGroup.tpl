{literal}
<script language="Javascript">
<!--
function doSubmit() {

    var formObj = document.groupForm;
    
    if ( formObj.name.value.trim() == "" ) {
        alert( 'Please input a name for this group.' );
        formObj.name.focus();
        return;
    }
    
    formObj.formIsSubmitted.value = 1;
    formObj.submit();
}
//-->
</script>
{/literal}

{assign var=submitButton value='Submit'}
{assign var=cancelButton value='Cancel'}
{assign var=deleteButton value='Delete Group'}
{assign var=userAdminPage value='usersAndGroups.php'}
{assign var=deleteConfirmation value='Are you sure? This group and all permissions will be permanently removed. The users belonging to this group will not be removed. Upon successful deletion of this group, you will be directed to the group Add screen.'}

<form method=post name=groupForm>

<input type=hidden name=groupID value="{$data.id|default:NEW}">

<table border=0 cellpadding=1 cellspacing=1 width=100% class=normal>

<tr><td colspan=2>{$logoutLink} {$pathway}</td></tr>
<tr><td colspan=2>{$addGroupLink}</td></tr>

{if $groupErr}
<tr><td colspan=2>&nbsp;</td></tr>
<tr><td colspan=2><span class=subtitle>ERROR:</span><br />A group by this name ({$groupErr}) already exists. To preserve your changes or additions, the system has automatically appended a unique integer to this group name. You may wish to change the name of this group.</td></tr>
{/if}

<tr><td colspan=2>&nbsp;</td></tr>

<tr><td colspan=2 class=title>{$type} Group</td></tr>

<tr><td nowrap width=10% align=right>Group Name: </td><td><input type=text name=name size=30 value="{$data.name}"> 
<a href="javascript:launchCentered('{$help.url}?type=groups',{$help.width},{$help.height},'{$help.options}');">{$helpSymbol}</a>
</td></tr>

<tr><td valign=top align=right>Description: </td><td><textarea name=description rows=3 cols=50>{$data.description}</textarea></td></tr>

<tr><td colspan=2>
{if $permissions.add or $permissions.edit}
<input type=button onClick="javascript:doSubmit();" value="{$submitButton}">
{/if}
<input type=button onClick="document.location.href='{$userAdminPage}'" value="{$cancelButton}">

{if $data.id and $permissions.delete}
<input type=submit name=deleteGroup value="{$deleteButton}" onClick="return confirm('{$deleteConfirmation}');">
{/if}

</td></tr>

<tr><td colspan=2>
<table border=0 cellpadding=2 cellspacing=0 class=normal>


<tr><td colspan=2>&nbsp;</td></tr>
{* end of special case for content management tools *}

<tr><td colspan=2 class=subtitle>Site Permissions 
<a href="javascript:launchCentered('{$help.url}?type=permissions',{$help.width},{$help.height},'{$help.options}');">{$helpSymbol}</a>
</td></tr>

<tr><td colspan=2>The following website sections should be restricted to users of this group. Every item that you select will automatically become a restricted area, which will require user login to access. If you are changing the Content Management Tool permissions for the <i>currently logged-in user</i>, then you will not observe the changes until you logout and login again.</td></tr>
<tr><td colspan=2>{include file=manage/editPermissions.tpl}</td></tr>
</table>
</td></tr>

<tr><td colspan=2>

{if $permissions.add or $permissions.edit}
<input type=button onClick="javascript:doSubmit();" value="{$submitButton}">
{/if}
<input type=button onClick="document.location.href='{$userAdminPage}'" value="{$cancelButton}">

{if $data.id and $permissions.delete}
<input type=submit name=deleteGroup value="{$deleteButton}" onClick="return confirm('{$deleteConfirmation}');">
{/if}

</td></tr>

</table>

<input type=hidden name=formIsSubmitted value="">
</form>