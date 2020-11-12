<form name="permissionsForm" method="post">
<input type="hidden" name="formIsSubmitted" value="1">

<table border=0 cellpadding=1 cellspacing=1 width=100% class=normal>

<tr><td colspan=2>{$logoutLink} {$pathway}</td></tr>
<tr><td colspan=2>{$addGroupLink}</td></tr>
<tr><td colspan=2>&nbsp;</td></tr>

<tr><td colspan=2 class=subtitle>Guest Permissions 
<a href="javascript:launchCentered('{$help.url}?type=permissions',{$help.width},{$help.height},'{$help.options}');">{$helpSymbol}</a>
</td></tr>

<tr><td colspan=2>The following site resources should be accessible to guest users - i.e., users who are not logged in.</td></tr>
<tr><td colspan=2>{include file=manage/editPermissions.tpl}</td></tr>
<tr><td colspan=2>&nbsp;</td></tr>

<tr><td colspan=2>
  <input type=submit value="Save Permissions"> 
  <input type=reset value="Reset"> 
  <input type=button value="Cancel" onclick="javascript: document.location.href='usersAndGroups.php'"> 
</td></tr>

</table>
</form>