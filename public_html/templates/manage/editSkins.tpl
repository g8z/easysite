{literal}
<script language="Javascript">
<!--
function isValid() {
	var theForm = document.editSkins;
	var formElements = theForm.elements;
	var numElements = theForm.elements.length;

	return true;
}

function selectAll( allBox, prefix ) {
    
	var el = document.editSkins.elements;
	var length = el.length;

	for ( i=0; i<length; i++) {
		if ( el[i].name.substr(0, 2) == prefix ) {
			el[i].checked = allBox.checked;
		}
	}
}

function switchSkin() {
	document.editSkins.skin_id.value = document.editSkins.switchSkinId.value; 
	document.editSkins.makeDefault.value = 1;
	// contents of form will not be saved, since we are not calling submitForm() here
	document.editSkins.submit();
}

function isValid() {
	// check for valid skin title
	
	var formObj = document.editSkins;
	var nameField = formObj.skinName;
	
	if ( nameField.value.trim() == '' ) {
		alert( 'Please input a name for this skin.' );
		nameField.focus();
		nameField.select();
		return false;
	}
	return true;
}

/** this is used when a user does not have full admin access over skins */
function loadSkin() {
	document.editSkins.load_skin_id.value = document.editSkins.switchSkinId.value;

	// contents of form will not be saved, since we are not calling submitForm() here
	document.editSkins.submit();
}

/**
 * temporarily disabled - new method is to make any loaded skin the default skin
 */
/*
function makeDefault() {
	// determine paths for all image uploads
	if ( !isValid() )
		return false;
    
	if ( confirm( 'Are you sure? These skin settings will be permanently loaded as the default skin for this website! This will affect all users viewing your site. Any settings that you have not already saved as a skin will be lost.' ) ) {

		document.editSkins.formIsSubmitted.value = 1;
		document.editSkins.makeDefault.value = 1;
		document.editSkins.submit();
	}
}
*/

function deleteSkin() {

	if ( confirm( 'Are you sure? Any pages or forms which are associated with this skin will be re-associated with the default skin.' ) ) {
		document.editSkins.deleteSkinId.value = 1;

		// no need to validate data, since we are deleting
		document.editSkins.submit();
	}
}

function submitForm() {
	// determine paths for all image uploads
	if ( !isValid() )
		return false;

	document.editSkins.formIsSubmitted.value = 1;
	document.editSkins.submit();
}
//-->
</script>
{/literal}

<form action=editSkins.php method="POST" enctype="multipart/form-data" name="editSkins">

<input type=hidden name=formIsSubmitted value="">
<input type=hidden name=skin_id value="{$data.id}">
<input type=hidden name=makeDefault value="">
<input type=hidden name=deleteSkinId value="">



<table border=0 cellpadding=0 cellspacing=2 width=100% class=normal>


{* only used when the user does not have access to styles/settings CM Tools *}
<input type=hidden name=load_skin_id value="">

	<tr>
	<td class=normal>
	{$logoutLink} {$pathway}
	</td>
	</tr>

	<tr><td>&nbsp;</td></tr>
	
	{if $stylesAccess or $settingsAccess}
	
		<tr><td class=title>{$type} Skin</td></tr>

		<tr><td>
			<a href="javascript:launchCentered('{$help.url}?type=about_skins',{$help.width},{$help.height},'{$help.options}');">I don't understand how to use this feature. Please explain it.</a>
		</td></tr>

		<tr><td>&nbsp;</td></tr>

		<tr><td><b>Important:</b> The skin settings will that you select draw from the currently-loaded settings{*, and from the menu that you select below*}. Thus, you should ensure that you have the look you want before saving it as a skin. If the skin is <b>shared</b> by another user, then you may not edit/delete the skin.{*[ <a href=editSettings.php>Change the current settings</a> ]*} </td></tr>
	{else}
		<tr><td class=title>Choose Skin</td></tr>
		<tr><td>&nbsp;</td></tr>
		<tr><td>The skins listed below have been pre-defined for you by the system administrator. If no skins are present, then you must contact your system administrator to permit access to at least one skin.</td></tr>
	{/if}
	
	<tr><td>&nbsp;</td></tr>
	
	<tr><td>
		
		{* display a jump menu of all available skins, with edit/add options *}
		
		<table border=0 cellpadding=1 cellspacing=0 class=normal>
		
		{*
		{if $data.id}
		<tr>
			<td colspan=2>			
			[ <a href=editSkins.php>Add New</a> ]			
			
			[ <a target=_blank href="{$docroot}index.php?preview_skin={$data.id}">Preview</a> ]
			[ <a href="javascript:makeDefault();">Make Default</a> ]
			</td>
		</tr>
		<tr><td colspan=2>&nbsp;</td></tr>		
		{/if}
		*}
		
		<tr>
			<td align=right>Switch To: </td>
			<td>
			<select name=switchSkinId {if $stylesAccess or $settingsAccess}onChange="javascript:switchSkin();"{/if}>
			{if $noSkins}
				<option value=''>(no skins present)</option>
			{else}
				{if $stylesAccess or $settingsAccess}
					<option value=''> - New Skin - </option>
					{html_options options=$allSkins selected=$data.id}
				{else}
					{html_options options=$allSkins selected=$loadedSkin}
				{/if}
			{/if}
			</select>
			
			{if $stylesAccess eq '' and $settingsAccess eq '' and $noSkins eq ''}
			<input type=button onClick="javascript:loadSkin();" value="Load">
			{/if}
			
			</td>
		</tr>
		
		{if $stylesAccess or $settingsAccess}
		
			<tr><td align=right>Skin Name: </td>
				<td><input type=text size=30 name=skinName value="{$data.name}">

			<a href="javascript:launchCentered('{$help.url}?type=skin_name',{$help.width},{$help.height},'{$help.options}');">{$helpSymbol}</a>


				</td></tr>

			<tr><td align=right valign=top>Description: </td>
				<td><textarea rows=3 cols=40 name=description>{$data.description}</textarea></td></tr>

		{/if}

			</table>
	
	</td></tr>

	{if $stylesAccess or $settingsAccess}

	{* display options for skin sharing, and skin restrictions for users *}
	
	<tr><td><input type=button onClick="javascript:submitForm();" value="Submit"> <input type=reset value="Reset"> {if $data.id}<input type=button onClick="javascript:deleteSkin();" value="Delete Skin">{/if}</td></tr>
	
	
	
		<tr><td>&nbsp;</td></tr>

		<tr><td><b>Which properties should this skin include?</b></td></tr>

		<tr><td>The following three lists show all of the options which affect the look of a website - including styles, global site settings, and global menu settings. When a skin is applied these values will override any existing values.</td></tr>
	
	{/if}
	
	{* display list of checkboxes for global settings, menu settings, and styles *}
	
	{if $stylesAccess}
	
		<tr><td>&nbsp;</td></tr>
		<tr><td><b>Styles</b> [ <a href="editStyles.php?styles=1&skin_id={$data.id}">edit styles</a> ]</td></tr>

		{* note: first checkbox in all/none selector *}

		<tr><td>
			<table border=0 cellpadding=1 cellspacing=0 class=normal>
			<tr><td>&nbsp;</td><td>

			{* note: second parameter of selectAll must be a 2-letter prefix *}
			<input type=checkbox name=all value=1 onClick="javascript:selectAll(this,'st');" checked>select/de-select all<br />
			<hr noshade size=1>

			{html_checkboxes name=styles options=$skinStyles separator="<br />" selected=$skinStylesSelected}

			</td></tr>
			</table>
		</td></tr>

		<tr><td>&nbsp;</td></tr>
	
	{/if}
	
	{* check to ensure that the logged-in user has access to the settings tool *}

	{if $settingsAccess}

		<tr><td><b>Template Sections</b> [ <a href="editLayout.php?skin_id={$data.id}">edit settings</a> ]</td></tr>

		<tr><td>

			<table border=0 cellpadding=1 cellspacing=0 class=normal>
			<tr><td>&nbsp;</td><td>

			{* note: second parameter of selectAll must be a 2-letter prefix *}
			<input type=checkbox name=all value=1 onClick="javascript:selectAll(this,'se');" checked>select/de-select all<br />
			<hr noshade size=1>

			{html_checkboxes name=sections options=$templateSections separator="<br />" selected=$templateSectionSelected}

			</td></tr>
			</table>

		</td></tr>
	
	{/if}
	
	{*
	NOTE: Skin menu settings have been temporarily disabled in 1.4.1

	<tr><td>&nbsp;</td></tr>

	<tr><td><b>Global Menu Settings</b></td></tr>
	
	<tr><td>Please specify which menu's properties you would like to use for this skin. When the skin is applied, this menu's settings will be automatically applied to all existing menus (this will result in the loss of any settings specified by the user!).</td></tr>
	
	<tr><td>
		<table class=normal cellspacing=0 cellpadding=0 border=0>
			<tr><td colspan=2>&nbsp;</td></tr>
			<tr>
			<td nowrap>Use settings from this menu: &nbsp;</td>
			<td>
				<select name=menu_id>
				{html_options options=$menuSkins selected=$menu_id}
				</select>
				
          <a href="javascript:launchCentered('{$help.url}?type=skin_menu',{$help.width},{$help.height},'{$help.options}');">{$helpSymbol}</a>

			</td>
			</tr>
			<tr><td colspan=2>&nbsp;</td></tr>
		</table>
	</td></tr>
	
	<tr><td>
	
		<table border=0 cellpadding=1 cellspacing=0 class=normal>
		<tr><td>&nbsp;</td><td>
		
		<input type=checkbox name=all value=1 onClick="javascript:selectAll(this,'me');" checked>select/de-select all<br />
		<hr noshade size=1>
	
		{html_checkboxes name=menuSettings options=$skinMenuSettings separator="<br />" selected=$skinMenuSettingsSelected}
		
		</td></tr>
		</table>
		
	</td></tr>
	
	*}
	
	{if $stylesAccess or $settingsAccess}
		<tr><td><input type=button onClick="javascript:submitForm();" value="Submit"> <input type=reset value="Reset"> {if $data.id}<input type=button onClick="javascript:deleteSkin();" value="Delete Skin">{/if}</td></tr>
	{/if}

	<tr><td>&nbsp;</td></tr>
	

	<tr>
	<td class=normal>
	{$logoutLink} {$pathway}
	</td>
	</tr>
	
	<tr><td>&nbsp;</td></tr>

</table>

</form>