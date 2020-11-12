{literal}
<script language="Javascript">
<!--
function validateForm() {

	// check for valid user_site_key
	
	var theForm = document.userForm;
	
	var userSiteKey = theForm.user_site_key.value.trim();
	
	if ( userSiteKey == '' && theForm.currentKey.value != '' ) {
		if ( !confirm( 'Clearing the site key will permanently delete this user\'s website. Are you sure that you want to do this?' ) ) {
			return;
		}
	}
	
	if ( userSiteKey == '{/literal}{$defaultSite}{literal}' ) {
		alert( 'You cannot use "{/literal}{$defaultSite}{literal}" as the site key because this key is reserved.' );
		theForm.user_site_key.focus();
		theForm.user_site_key.select();
		return;	
	}
	
	if ( userSiteKey != '' && !isAlphaNumeric( userSiteKey ) ) {
		alert( 'The site key for a user website must contain only letters and/or numbers.' );
		theForm.user_site_key.focus();
		theForm.user_site_key.select();
		return;
	}
	
	// trim site key value
	theForm.user_site_key.value = userSiteKey;
	
	theForm.formIsSubmitted.value = 1;
	theForm.submit();
}

function applyChild( name, state, parent ) {
    
	var theForm = document.userForm;
	
	if ( parent.checked == state ) {
	    var child = eval( 'theForm.'+name );
	    child.checked = state;
	}

}

function testParent( name, state, child ) {

    var theForm = document.userForm;
    
    var message;
    
    switch ( name ) {
        case 'c_skins':
            message = 'skins';
            break;
            
        case 'c_module_items':
            message = 'module items';
            break;
    }
    
    var stateChild  = ( state == true ) ? 'enable' : 'disable';
    var stateParent = ( state == true ) ? 'enabled' : 'disabled';
    
    message = 'You can not '+stateChild+' this item because of copy '+message+' data should be '+stateParent;
    
    //alert( child.checked+'   '+eval( 'theForm.'+name+'.checked' ) );
    
    if ( child.checked == state && eval( 'theForm.'+name+'.checked' ) != state ) {
        child.checked = !state;
        alert( message );
    }
}

{/literal}

{if $configurations}

{* Generate configuration arrays *}

{foreach from=$configurations item=cf}

	var cf{$cf.id} = new Array();
	
	{foreach from=$cf.state key=formElement item=value name=iteratior}
		cf{$cf.id}['{$formElement}'] = '{$value}';
	{/foreach}
	
	
{/foreach}
{/if}

{literal}


function applyConfiguration( name ) {
	
    var theForm = document.userForm;
    
    var cfg = eval( name );
    var field;
    
    for ( formElement in cfg ) {
    	if ( field = eval( 'theForm.'+formElement ) ) {
    		
    		if ( field.type == 'checkbox' )
    			field.checked = cfg[formElement] ? 1 : 0;
    		else
    			field.value = cfg[formElement];
    			
    	}
    }
    
}

//-->
</script>
{/literal}

{assign var=deleteSiteConfirmString value="Are you sure you want to delete user website?"}

<form method=post name=userForm>

<input type=hidden name=userID value="{$data.id}">
<input type=hidden name=formIsSubmitted value="">
<input type=hidden name=currentKey value="{$data.user_site_key}">
<input type=hidden name=cmPermissions value="">

<table border=0 cellpadding=1 cellspacing=1 width=100% class=normal>

<tr><td colspan=2>{$logoutLink} {$pathway}</td></tr>
<td><td colspan=2>&nbsp;</td></tr>

<tr><td colspan=2 class=subtitle>Give this user a website!</td></tr>

<tr><td colspan=2>You may give every user his or her own website, which can be managed using these same content-management tools. To give this user a website, input a site key in the field below. The site key should contain only alpha-numeric characters.</td></tr>

<tr><td colspan=2>&nbsp;</td></tr>

{if $siteExistsError}
<tr><td colspan=2>

<span class=subtitle>Error</span><br />
<span class=normal>The site key that you have chosen for this user's website is taken. Please choose another.</span>

</td></tr>

<tr><td colspan=2>&nbsp;</td></tr>
{/if}

<tr><td valign=top>Site Key: </td><td><input type=text name=user_site_key size=20 value="{$data.user_site_key}">
	<a href="javascript:launchCentered('{$help.url}?type=user_websites',{$help.width},{$help.height},'{$help.options}');">{$helpSymbol}</a></td></tr>
	

{if $userSiteUrl eq ''}
<tr><td colspan=2>&nbsp;</td></tr>

<tr>
	<td valign="top">Use Site Configuration:</td>
	<td>
	{if $cValues}<select name="configuration" onchange="javascript: applyConfiguration( this.options[this.selectedIndex].value );">{html_options values=$cValues output=$cTitles selected=$cSelected}</select>{else}No Configurations Defined{/if}<br /><br />
	Each time you create a user site, the last-created configuration is auto-saved and auto-loaded. You may save and use multiple configurations by checking the "Save current configuration..." checkbox at the bottom of this screen, and providing a name for the configuration.
	</td>
</tr>

<tr><td colspan=2>&nbsp;</td></tr>

<tr>
	<td valign="top">Copy Parent Data:</td>
	<td>
	<input type=checkbox name=c_pages    value=1 checked>Copy pages data<font color=red>*</font><br />
	<input type=checkbox name=c_forms    value=1 checked>Copy forms data<font color=red>*</font><br />
	<input type=checkbox name=c_reports    value=1 checked>Copy reports data<br />
	<input type=checkbox name=c_layers   value=1 checked>Copy layers data<br />
	<input type=checkbox name=c_menus    value=1 checked>Copy menus data<font color=red>*</font><br />
	<input type=checkbox name=c_settings value=1 checked>Copy settings data&nbsp;<font color=red>*</font><br />
	<input type=checkbox name=c_styles   value=1 checked onclick="javascript: testParent( 'c_skins', false, this );">Copy styles data&nbsp;<font color=red>*</font><br />
	<input type=checkbox name=c_skins    value=1 checked onclick="javascript: applyChild( 'c_styles', true, this );">Copy skins data<br />
	<input type=checkbox name=c_files    value=1 checked>Copy files data<br />
	<input type=checkbox name=c_lists    value=1 checked>Copy lists data<br />
	<input type=checkbox name=c_module_categories value=1 checked onclick="javascript: testParent( 'c_module_items', false, this );">Copy module categories<br />
	<input type=checkbox name=c_module_items      value=1 checked onclick="javascript: applyChild( 'c_module_categories', true, this );">Copy module items <br />
	<br />
	<font color=red>*</font>  If not selected, default system data will be copied.
	</td>
</tr>
{/if}

</td></tr>

<tr><td colspan=2>&nbsp;</td></tr>

<tr><td valign=top>Site URL: </td><td>{if $userSiteUrl}<a target=_blank href="{$userSiteUrl}">{$userSiteUrl}</a><br /><input type=submit name=deleteSite value=' Remove User Site ' onclick="javascript: return confirm('{$deleteSiteConfirmString}');"><br />(clicking this link will log you out){else}(no site key defined yet){/if}</td></tr>

<tr><td colspan=2>&nbsp;</td></tr>

{if $userSiteUrl eq ''}
<tr><td valign=top>Skin: </td><td><select name="skin_id"><option value="0">- Choose Skin -</option>{html_options options=$skins}</select><br />Leave un-selected to use this site's default skin as the default skin for the new user website.</td></tr>

<tr><td colspan=2>&nbsp;</td></tr>
{/if}

<tr><td valign=top>Permissions: </td><td>You may give this user access to only specific content-management tools for his or her website. Please specify the tools that this user should be able to access (by default, users do not have a 'Users and Groups' tool):<br />

<table border=0 width=100% cellpadding=1 cellspacing=0 class=normal>
<tr><td><br />Allow access to these tools:</td></tr>
<tr><td>

{include file=manage/editPermissions.tpl}

</td></tr>

</table>

{if $userSiteUrl eq ''}
<tr><td colspan=2>&nbsp;</td></tr>

<tr><td colspan="2">
	Current site configuration will be saved with the 'Last Created' name. However it will be rewritten next site creation. You may save this one with different name and use in the future.  If you will enter existing configuration name, it will be rewritten.<br /><br />
	<input type="checkbox" name="save_configuration" value="1"> Save current configuration: <input type="text" name="configuration_name" value="">
</td></tr>

<tr><td colspan=2>&nbsp;</td></tr>
{/if}


</td></tr>

<tr><td colspan=2><input type=button onClick="validateForm();" value='Submit All'></td></tr>

</table>

<script language="JavaScript">
	{if $userSiteUrl eq '' and $configurations}
	applyConfiguration( document.userForm.configuration.value );
	{/if}
</script>