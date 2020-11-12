{literal}
<script language="Javascript">
function checkCSV() {
	var formObj = document.importForm;
	
	var fileName = formObj.csv.value;
	
	if ( fileName == '' ) {
		alert( 'Please specify a .CSV file to upload.' );
	}
	else {
		formObj.formIsSubmitted.value = 1;
		formObj.step.value = 'parse_file';
		formObj.submit();
	}
}
function cancelImport() {
	document.location.href='importUsers.php';
}
function mapData() {
	// check to ensure that at least 1 field match has been made
	// also check to ensure that a 'group' has been specified
	var formObj = document.importForm;

	if ( formObj.group_id.value == 0 ) {
	   alert( 'Please specify the group to assign to these new users.' );
	   return false;
	}
	
	var n, elem;
	n = formObj.elements.length;
	
	var filled = false;

	for ( i=0; i<n; i++ ) {
	    elem = formObj.elements[i];
	    if ( elem.type == 'text' && elem.value.length > 0 ) {
            filled = true;
	    }
	}
	
	if ( !filled ) {
	   alert( 'Please specify at leat one relation.' );
	   return false;
	}
	
	formObj.formIsSubmitted.value = 1;
	formObj.step.value = 'map_data';
	return true;
}

function siteCreation() {
	var formObj = document.importForm;
	
	formObj.formIsSubmitted.value = 1;
	formObj.step.value = 'process_import';
	formObj.submit();
}

function applyChild( name, state, parent ) {
    
	var theForm = document.importForm;
	
	if ( parent.checked == state ) {
	    var child = eval( 'theForm.'+name );
	    child.checked = state;
	}

}

function testParent( name, state, child ) {

    var theForm = document.importForm;
    
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


function insertField( fieldName ) {
    var theForm = document.importForm;
    
    theForm.user_site_key.value += '{'+fieldName+'}';
	
}


function errorAction( action ) {
    var theForm = document.importForm;
    
    theForm.step.value=action;
    
    theForm.submit();
    
    return false;
	
}

</script>
{/literal}

<form method=post name=importForm enctype="multipart/form-data">

<input type=hidden name=userID value="{$data.id|default:NEW}">

<!--<input type=hidden name=delimiter value="{$delimiter}">
<input type=hidden name=header value="{$header}">
-->

{foreach from=$postPrevPage key=k item=value}
<input type=hidden name="{$value[0]}" value="{$value[1]}">
{/foreach}

<table border=0 cellpadding=1 cellspacing=1 width=100% class=normal>

<tr><td colspan=2>{$logoutLink} {$pathway}</td></tr>
<tr><td colspan=2>{$addUserLink}</td></tr>
<td><td colspan=2>&nbsp;</td></tr>

<tr><td colspan=2 class=title>Import Users</td></tr>

<tr><td colspan=2 >&nbsp;</td></tr>

<tr><td colspan=2 class=subtitle>{$stepTitle}</td></tr>

<tr><td colspan=2 >&nbsp;</td></tr>

<tr><td colspan=3 >{$stepDesc}</td></tr>

<!--<tr><td colspan=2>After uploading, you will "map" the imported file columns to fields in the user database.</td></tr>
-->

<tr><td colspan=2>&nbsp;</td></tr>

{if $parseError neq ''}

{* parse error message *}
<tr><td colspan=2>
    There were some errors during parsing the file:<br /><br />
    <font color=red><b>{$errors}<br /><br /></b></font>
    <input type=button name=back value=' Go Back ' onclick='javascript:history.go(-1);'>
</td></tr>

{else}

{if $step eq 'file_upload_form'}

	<tr>
		<td nowrap width=20% align=right>Choose File: </td>
		<td><input type=file name=csv size=30></td>
	</tr>
	
	<tr>
		<td align=right><input type=checkbox name=header value=1 checked></td>
		<td>Check here if the first row contains the field names.</td>
	</tr>
	
	<tr>
		<td align=right valign=top>Data Delimiter: </td>
		<td nowrap>
			<input type=radio name=delimiter value=comma checked> CSV (Comma Delimited)<br />
			<input type=radio name=delimiter value=tab> Tab Delimited<br />
			<input type=radio name=delimiter value=semicolon> Semi-Colon Delimited<br />
			<input type=radio name=delimiter value=other>Other Delimiter (specify): <input type=text name=otherDelimiter size=5>
		</td>
	</tr>
	
	<tr><td>&nbsp;</td><td><input type=button onClick="javascript:checkCSV();" value="Next >>"></td></tr>

{elseif $step eq 'map_data'}

	<tr><td nowrap width=20%>Group to use for imported users: </td><td>

	<select name=group_id>
	{html_options options=$groups selected=$data.group_id}
	</select>
	{$addGroupLink} 
	<a href="javascript:launchCentered('{$help.url}?type=groups',{$help.width},{$help.height},'{$help.options}');">{$helpSymbol}</a>
	</td></tr>
	
	<tr><td><td colspan=2>&nbsp;</td></tr>
	
	<tr><td colspan=2>Map the imported columns to the user database fields by inputting the appropriate numbers: </td></tr>
	
	<tr><td colspan=2>&nbsp;</td></tr>
	
	<tr><td align=center colspan=2>
	
	   <table border=0 cellpadding=1 cellspacing=0 class=normal>
	   <tr>
	   <td valign=top>
	   
		<table border=0 cellpadding=1 cellspacing=0 class=normal>

		<tr><td colspan=2 align=center><b>Database Fields</b></td></tr>
		
		{foreach key=field item=display from=$userFields}
		<tr>
			<td nowrap>{$display}</td>
			<td><input type="text" name="{$field}" size="5"></td>
			<td nowrap>&nbsp;&nbsp;&nbsp;</td>
		</tr>
		
		{/foreach}

		</table>
		
	   </td>
	   <td valign=top align=left>
		   <table border=0 cellpadding=1 cellspacing=0 class=normal>
		   
		   	<tr><td colspan=3 nowrap><b>First Row of File</b></td></tr>
		   
			{foreach name=csvIterator from=$data item=dataRow}
			<tr>
				<td>{$smarty.foreach.csvIterator.iteration}.</td>
				<td>&nbsp;</td>
				<td>{$dataRow}</td>
			</tr>
			{/foreach}

		   </table>
	    </td>
	    </tr>
	    </table>
	    
	</td></tr>
	
	
	<tr>
		<td colspan=2 align=center><input type=submit name=process onClick='javascript: return mapData();' value="Next >>">
		<input type=button name=cancel onClick="javascript:cancelImport();" value="Cancel Import">
		</td>
	</tr>


	
{elseif $step eq 'site_creation'}

	<tr><td colspan=2><input type="checkbox" name="create_on_access" value=1> Create user sites on first site access.</td></tr>
	
	<tr><td colspan=2>&nbsp;</td></tr>
	
	<tr><td valign=top align="right">Site Key: </td><td>
	
	<table cellpadding="0" cellpadding="0" class="normal">
	<tr><td>&nbsp;</td><td>Available Fields (Clickable)</td></tr>
	<tr>
		<td valign="top">
		<input type=text name=user_site_key size=20 value="site_{ldelim}Login ID{rdelim}"><a href="javascript:launchCentered('{$help.url}?type=user_websites',{$help.width},{$help.height},'{$help.options}');">{$helpSymbol}</a><br />
		<input type=checkbox name=c_pages    value=1 checked>Copy pages data<br />
		<input type=checkbox name=c_forms    value=1 checked>Copy forms data<br />
		<input type=checkbox name=c_reports    value=1 checked>Copy reports data<br />
		<input type=checkbox name=c_layers   value=1 checked>Copy layers data<br />
		<input type=checkbox name=c_menus    value=1 checked>Copy menus data<br />
		<input type=checkbox name=c_settings value=1 checked>Copy settings data&nbsp;<font color=red>*</font><br />
		<input type=checkbox name=c_styles   value=1 checked onclick="javascript: testParent( 'c_skins', false, this );">Copy styles data&nbsp;<font color=red>*</font><br />
		<input type=checkbox name=c_skins    value=1 checked onclick="javascript: applyChild( 'c_styles', true, this );">Copy skins data<br />
		<input type=checkbox name=c_files    value=1 checked>Copy files data<br />
		<input type=checkbox name=c_lists    value=1 checked>Copy lists data<br />
		<input type=checkbox name=c_module_categories value=1 checked onclick="javascript: testParent( 'c_module_items', false, this );">Copy module categories<br />
		<input type=checkbox name=c_module_items      value=1 checked onclick="javascript: applyChild( 'c_module_categories', true, this );">Copy module items <br />
		</td>
		<td valign="top">
		{foreach from=$userFields item=field}
		<a href="#" onclick="javascript: insertField( '{$field}'); return false;">{$field}</a><br />
		{/foreach}
		</td>
	</tr>
	</table>
	
	</td></tr>
	
	<tr><td>&nbsp;</td><td><font color=red>*</font>  <small>If not selected, default system data will be copied.</small></td></tr>

	
	<tr><td valign=top align="right">Permissions: </td><td>You may give this user access to only specific content-management tools for his or her website. Please specify the tools that this user should be able to access (by default, users do not have a 'Users and Groups' tool):<br />
	
	<table border=0 width=100% cellpadding=1 cellspacing=0 class=normal>
	<tr><td><br />Allow access to these tools:</td></tr>
	<tr><td>
	
	{include file=manage/editPermissions.tpl}
	
	</td></tr>
	
	</table>
	
	
	</td></tr>


	<tr>
		<td colspan=2 align=center><input type=submit name=process onClick='javascript: return siteCreation();' value="Process Import">
		<input type=button name=cancel onClick="javascript:cancelImport();" value="Cancel Import">
		</td>
	</tr>

{elseif $step eq 'error'}
<tr><td colspan="2">{$errorMessage}</td></tr>
<tr><td colspan=2 >&nbsp;</td></tr>
{if $errorUserName}
<tr><td>Enter Login ID</td><td><input type=text name=errorUserName value="{$errorUserName}"></td></tr>
{/if}
{if $errorSiteKey}
<tr><td>Enter Site Key</td><td><input type=text name=errorSiteKey value="{$errorSiteKey}"></td></tr>
{/if}
<tr><td colspan=2 >&nbsp;</td></tr>
<tr><td colspan="2">
	<input type=hidden name="errorIndex" value="{$errorIndex}">
	<input type=button name=contionue onClick="javascript: return errorAction( 'error_continue' );" value="Continue Import">
	<input type=button name=skip onClick="javascript: return errorAction( 'error_skip' );" value="Skip User and Continue Import">
	<input type=button name=cancel onClick="javascript: return cancelImport();" value="Cancel Import">
</td></tr>

{elseif $step eq '3'}

<tr><td colspan=2><b>{$num}</b> users were successfully added! <a href="importUsers.php">Perform another import</a></td></tr>

{/if}

{/if}

<tr><td><td colspan=2>&nbsp;</td></tr>
<tr><td colspan=2>{$addUserLink}</td></tr>
<tr><td colspan=2>{$logoutLink} {$pathway}</td></tr>

</table>
<input type=hidden name=formIsSubmitted value="">
<input type=hidden name=step value="">
</form>