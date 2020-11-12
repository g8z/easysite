<script type="text/javascript" src="{$docroot}{$smarty.const.ADMIN_DIR}/picker.js"></script>

{literal}
<style type='text/css'>
.untoggled, .untoggled:hover {
	font-size: 14px;
	text-decoration: none;
	font-style: normal;
	color: #FFFFFF;
	padding: 4px 4px;
	background-color: #999999;
	font-weight: bold;
}
.toggled, .toggled:hover {
	font-size: 14px;
	text-decoration: none;
	font-style: normal;
	color: #FFFFFF;
	padding: 4px 4px;
	background-color: #333399;
	font-weight: bold;
}
.untoggled2, .untoggled2:hover {
	font-size: 14px;
	text-decoration: none;
	font-style: normal;
	color: #FFFFFF;
	padding: 4px 6px;
	background-color: #999999;
	font-weight: bold;
}
.toggled2, .toggled2:hover {
	font-size: 14px;
	text-decoration: none;
	font-style: normal;
	color: #FFFFFF;
	padding: 4px 6px;
	background-color: #333399;
	font-weight: bold;
}
</style>

<script language="Javascript">
<!--

function toggle( buttonField, hiddenField ) {
 
	if ( buttonField.className == 'toggled' ) {
		buttonField.className = 'untoggled';
		hiddenField.value = '';
	}
	else if ( buttonField.className == 'untoggled' ) {
		buttonField.className = 'toggled';
		hiddenField.value = 1;
	}
	else if ( buttonField.className == 'toggled2' ) {
		buttonField.className = 'untoggled2';
		hiddenField.value = '';
	}
	else if ( buttonField.className == 'untoggled2' ) {
		buttonField.className = 'toggled2';
		hiddenField.value = 1;
	}
}

/*
function select(list, i) 
{
	list.selectedIndex = i;	
}
*/

function selectValue( list, str ) 
{
	var numOptions = list.options.length;
	
	for ( var i = 0; i < numOptions; ++i ) 
	{
		if ( list.options[i].text == str ) 
		{
			list.selectedIndex = i;
			//select( list, i );
			return;
		}
	}
}

function checkApplyAll(listObj) {
	var newFontValue = listObj.value;
	
	if ( confirm( 'You have requested to change a font. It is generally good style to keep a consistent font throughout your website. Would you like to apply this font to all styles?' ) ) {
	    // apply to all
		
	    var theForm = document.forms[0];
	    var numElements = theForm.elements.length;

	    for ( var i = 0; i < numElements; i++ ) {
		var elemName = theForm.elements[i].name;

		// determine if we need to add a new record
		
		if ( elemName.indexOf( "font_" ) != -1 ) {
		
//selectValue( document.forms[formNum]." . $fields[$i] . ", \"$item\" );		
		
			if ( theForm.elements[i].options ) {
				selectValue( theForm.elements[i], newFontValue );
			}
			else {
				theForm.elements[i].value = newFontValue;
			}
		}
	    }
	}
}

function deleteSection(id) {
    if ( confirm( 'Are you sure? This style will be permanently removed. Any text which depends on this style should be changed.' ) ) {
    
        document.editPage.deleteSectionVar.value = id;

        submitForm();
    }
}

function doLoadFromSkin() {

	var skinId = document.editPage.loadFromSkin.value;
	
	if ( skinId == '' ) {
		alert( 'Please choose a skin to apply.' );
		return false;
	}

	if ( confirm( 'Are you sure? This will permanently overwrite the current styles with the styles that are saved with this skin.' ) ) {

		// redirect with a new GET parameter
		document.location.href = 'editStyles.php?styles=1&loadFromSkin=' + skinId;
	}
}

function isValid() {
    var theForm = document.editPage;
    var formElements = theForm.elements;
    var numElements = theForm.elements.length;
    
    for ( var i = 0; i < numElements; i++ ) {
        var elemName = theForm.elements[i].name;
        var elemValue = theForm.elements[i].value.trim();
        var elemSize = theForm.elements[i].size;
        var elemType = theForm.elements[i].type;
        
        // if this is a name field, check to make sure that only alphanumeric characters are used
        
        if ( elemName.indexOf( "name_" ) != -1 && elemType == 'text' ) {
        
        	if ( !isAlphaNumeric( elemValue.trim(), ':' ) ) {
        		alert( 'The style name may contain only letters, numbers, and colons.' );
        		theForm.elements[i].focus();
        		theForm.elements[i].select();
        		return false;
        	}
        }
        else if ( elemValue.trim() != '' && elemName.indexOf( "color_" ) != -1 && elemType == 'text' ) {
        
        	if ( !isColorCode( elemValue.trim() ) ) {
        		alert( elemValue + ' is not a valid color code.' );
        		theForm.elements[i].focus();
        		theForm.elements[i].select();
        		return false;
        	}
        }
        
        // determine if we need to add a new record
        if ( elemName.indexOf( "_NEW" ) != -1 && elemValue != "") {
            // check field type (skip combos, radios, and checkboxes)
    
            if ( elemType != 'select-one' && elemType != 'checkbox' && elemType != 'hidden') {
                theForm.addNewItem.value = 1;
            }
        }
    }
    
    return true;
}
function submitForm() {
    // determine paths for all image uploads
    if ( !isValid() )
        return false;
    
    document.editPage.formIsSubmitted.value = 1;
    
    // submit the form
    document.editPage.submit();
}
//-->
</script>
{/literal}


<form action=editStyles.php method="POST" enctype="multipart/form-data" name="editPage">

<input type=hidden name=deleteSectionVar value="">
<input type=hidden name=bumpUpSectionVar value="">
<input type=hidden name=page_id value="{$page_id}">
<input type=hidden name=site_key value="{$site_key}">
<input type=hidden name=addNewItem value="">
<input type=hidden name=formIsSubmitted value="">
<input type=hidden name=styles value="{$styles}">
<input type=hidden name=commonFont value="">
<input type=hidden name=skin_id value="{$skin_id}">


<table border=0 cellpadding=0 cellspacing=2 width=100% class=normal>

{* loop through all available sections, printing data for each *}

{foreach name=sections item=section from=$data}

{* if first iteration, then display the 'Add' title *}

{if $smarty.foreach.sections.iteration eq 1}

    <tr>
    <td class=normal colspan=7>
    {$logoutLink} {$pathway}
    </td>
    </tr>
    
    {if $skin_name ne ''}
    <tr><td colspan=7>&nbsp;</td></tr>
    <tr><td colspan=7>NOTE: You are editing the styles associated with the skin named "{$skin_name}". You will not be able to observe these styles until the skin is loaded.</td></tr>
    {/if}
    
    <tr><td colspan=7>&nbsp;</td></tr>
    
    <tr><td colspan=7 class=title>Edit Styles</td></tr>
    <tr><td colspan=7>[ <a href=editSkins.php onClick="return confirm('Be sure to save any changes to these styles first. If you have not saved your changes, click Cancel, then save the styles. If you have saved your changes, then click OK to continue.')">Save these styles as a skin</a> ]</td></tr>
    <tr><td colspan=7>&nbsp;</td></tr>

{if $permissions.load_from_skin}
<tr><td colspan=7>Load Styles from Skin: </td></tr>

<tr><td colspan=7 nowrap>

<select name=loadFromSkin>
<option value=""> - choose skin - </option>
{html_options options=$availableSkins}
</select> 
<input type=button onClick="javascript:doLoadFromSkin();" value="Ok">

</td></tr>

<tr><td colspan=2>&nbsp;</td></tr>
{/if}
    
    {assign var=headerRow value="<tr class=normal><td></td><td nowrap>&nbsp;<b>Name</b></td><td nowrap><b>Size</b></td><td><b>Font</b></td><td nowrap><b>Text</b></td><td><b>Background</b></td><td><b>Format</b></td></tr>"}
    
    {if $permissions.add}    
    <tr><td colspan=7 class=subtitle>Add a New User-Defined Style</td></tr>
    {$headerRow}
    {/if}

{/if}


{if $smarty.foreach.sections.iteration eq 2}
    <tr><td colspan=7>&nbsp;</td></tr>
    <tr><td colspan=7 class=subtitle>Built-in Styles</td></tr>
    <tr><td colspan=7 class=normal>Built-in styles may be changed, but not re-named or deleted.</td></tr>
    
    {$headerRow}
    
{/if}

{if $section.user_defined eq 1 and $userDefinedSection eq ""}
    {assign var=userDefinedSection value=true}
    <tr><td colspan=7>&nbsp;</td></tr>
    <tr><td colspan=7 class=subtitle>User-Defined Styles</span></td></tr>
    
    {$headerRow}
{/if}
    
	<tr {if $section.shared eq 1} bgcolor="Blue" {assign var=sharedExists value=true}{/if}>
	<td align=center nowrap>
		{if $section.user_defined and $permissions.delete}
		<input type=button name=delete_{$section.id} value=" X " onClick="javascript:deleteSection({$section.id});"> 
		{/if}
		
		{if ($permissions.edit and $smarty.foreach.sections.iteration neq 1) or ($smarty.foreach.sections.iteration eq 1 and $permissions.add)}
		<input type=button name=masterSubmit value="Ok" onClick="javascript:submitForm();">
		{/if}
	</td>
	
	<td nowrap>
	
	{if ($smarty.foreach.sections.iteration eq 1 and $permissions.add) or $smarty.foreach.sections.iteration neq 1}
	
	{if $section.user_defined eq 0 and $section.id ne 'NEW'}
		&nbsp; {$section.name|replace:'.':''}
		<input type=hidden name="name_{$section.id}" value="{$section.name}">
	{else}
		<input type=text name="name_{$section.id}" size=11 value="{$section.name|replace:'.':''}">
		<input type=hidden name="user_defined_{$section.id}" value="1">
	{/if}
	
	{if 	$section.name eq 'input' or
		$section.name eq 'select' or
		$section.name eq 'textarea'}
		
		*
	{/if}
	
	</td>
	<td>

	<select name="size_{$section.id}">
	{html_options values=$font_sizes output=$font_sizes selected=$section.size|default:10}
	</select>
	
	</td>
	
	<td nowrap>
		<select name="font_{$section.id}" onChange="javascript:checkApplyAll(this);">
		{html_options values=$font_families output=$font_families selected=$section.font}
		</select>
	</td>
	
	<td nowrap>

	<input type=text size=10 name="color_{$section.id}" value="{$section.color}"> 
	<a href="javascript:TCP.popup(document.forms[0].elements['color_{$section.id}'], 1)"><img src="{$docroot}images/color.gif" border=0></a>

	</td>
	<td nowrap>

	<input type=text size=10 name="bg_color_{$section.id}" value="{$section.bg_color}"> 
	<a href="javascript:TCP.popup(document.forms[0].elements['bg_color_{$section.id}'], 1)"><img src="{$docroot}images/color.gif" border=0></a>

	</td>
	<td nowrap>
		
	<a class='{if $section.bold eq 1}toggled{else}untoggled{/if}' href="#" onClick="toggle(this, document.forms[0].bold_{$section.id}); return false;">B</a>  
	 
	<a class='{if $section.italic eq 1}toggled2{else}untoggled2{/if}' href="#" onClick="toggle(this, document.forms[0].italic_{$section.id}); return false;">I</a>  
	 
	<a class='{if $section.underline eq 1}toggled{else}untoggled{/if}' href="#" onClick="toggle(this, document.forms[0].underline_{$section.id}); return false;">U</a>

	<input type=hidden name="bold_{$section.id}" value='{$section.bold}'>
	<input type=hidden name="italic_{$section.id}" value='{$section.italic}'>
	<input type=hidden name="underline_{$section.id}" value='{$section.underline}'>

	</td>
	
	</tr>
	
	{/if}

{/foreach}

    <tr><td colspan=7>&nbsp;</td></tr>

    <tr><td colspan=7>* These styles cannot be fully customized on some web browsers (e.g. Safari).</td></tr>
    {if $sharedExists}
    <tr><td colspan=7><span style="background-color: Blue">&nbsp;&nbsp;&nbsp;</span>&nbsp;&nbsp;These styles are shared.</td></tr>
    {/if}
    
    <tr><td colspan=7>&nbsp;</td></tr>

    <tr>
    <td class=normal colspan=7>
    {$logoutLink} {$pathway}
    </td>
    </tr>


</table>

</form>