<script type="text/javascript" src="{$docroot}manage/picker.js"></script>

{literal}
<script language="javascript">
<!--
function fieldsAreValid() {
	var theForm = document.editSettings;

	var formElements = theForm.elements;
	var numElements = theForm.elements.length;
	
	// determine if valid by comparing size
	// if size = 6 & is not number, then not valid input
	// if size = 10 & is not color code, then not valid input
		
	for ( var i = 0; i < numElements; i++ ) {
		var elemName = theForm.elements[i].name;
		var elemValue = theForm.elements[i].value;
		var elemSize = theForm.elements[i].size;
		var elemType = theForm.elements[i].type;
		
		// all fields are required
		if ( elemType == 'text' ) {
		
			// all fields, except color codes, must have values
			{/literal}
			{if $formID eq ''}
			{literal}
			if ( elemValue.trim() == "" && elemSize != 10 ) {
				alert( 'One or more fields was left empty.' );
				theForm.elements[i].focus();
				return false;
			} else 
			{/literal}
			{/if}
			{literal}
			if ( elemSize == 6 ) {
				// check for numeric input

				if ( !isNumeric( elemValue, '%-' ) ) {
					alert( 'One or more numeric fields contains invalid characters (only 0-9 and % are allowed).' );
					theForm.elements[i].focus();
					return false;
				}
			}
			else if ( elemSize == 10 ) {
				// check for valid date code

				if ( !isColorCode( elemValue ) && elemValue.trim() != '' ) {
					alert( 'One or more color fields contains an invalid color code.' );
					theForm.elements[i].focus();
					return false;
				}
			}
		}
	}
	return true;
}

function doDeleteImage( imgName ) {
	if ( confirm( 'Are you sure? This image will be permanently deleted.' ) ) {
		document.editSettings.deleteImage.value = imgName;
		submitForm();
	}
}

function submitForm() {
	if ( fieldsAreValid() ) {
		document.editSettings.formIsSubmitted.value = 1;
		document.editSettings.submit();
	}
}

function doLoadFromSkin() {

	var skinId = document.editSettings.loadFromSkin.value;
	
	if ( skinId == '' ) {
		alert( 'Please choose a skin to apply.' );
		return false;
	}

	if ( confirm( 'Are you sure? This will permanently overwrite the current settings with the skin settings.' ) ) {

		// redirect with a new GET parameter
		document.location.href = 'editSettings.php?loadFromSkin=' + skinId;
	}
}

function changeImage(select, imageObj ) {

    //alert( {/literal}'{$menuImagesFolder}'{literal} + select.options[select.selectedIndex].value );
    imageObj.src={/literal}'{$menuImagesFolder}'{literal}+select.options[select.selectedIndex].value;
}


function insertInternalVaraiable( name ) {
    
    var field = eval( 'document.editSettings.'+name );
	var width = 400;
	var height = 500;

	var left = ( screen.width - width ) / 2;
	var top = ( screen.height - height ) / 2;

    win = window.open( 'variableChooser.php', null, "top="+top+",left="+left+",width="+width+",height="+height+",status=yes,scrollbars=yes,resizable=yes,dependent=yes", true );
    win.opener = window;
    win.opener.field = field;
}

//-->
</script>
{/literal}


<form action="editSettings.php" method="POST" enctype="multipart/form-data" name="editSettings">

<input type=hidden name=menu_id value="{$menuID}">
<input type=hidden name=reportID value="{$reportID}">
<input type=hidden name=formID value="{$formID}">
<input type=hidden name=formIsSubmitted value="">
<input type=hidden name=site_key value="{$site_key}">
<input type=hidden name=startFields value=1>
<input type=hidden name=skin_id value="{$skin_id}">
<input type=hidden name=area value="{$area}">


<table border=0 cellpadding=0 cellspacing=3 width=100% class=normal>

{* loop through all available sections, printing data for each *}

<tr><td colspan=2 class=normal>{$logoutLink} {$pathway}</td></tr>

{*
{if $returnLink ne ""}
	<tr><td colspan=2 class=normal>{$returnLink}</td></tr>
{/if}
*}

<tr><td colspan=2>&nbsp;</td></tr>


{if $resourceTitle}
<tr><td colspan=2 class=title>{$resourceTitle}</td></tr>
{else}
<tr><td colspan=2 class=title>Edit Site Settings</td></tr>

<tr><td colspan=2>

[ <a href="javascript:launchCentered('{$help.url}?type=site_settings',{$help.width},{$help.height},'{$help.options}');">What do these settings mean?</a> ] [ <a href=editSkins.php onClick="return confirm('Be sure to save any changes to these settings first. If you have not saved your changes, click Cancel, then save the settings. If you have saved your changes, then click OK to continue.')">Save these settings as a skin</a> ]

</td></tr>

{/if}


{if $resourceDesc}
<tr><td colspan=2 class=normal>{$resourceDesc}</td></tr>
{/if}

<tr><td colspan=2>&nbsp;</td></tr>

{if $menuID eq "" and $reportID eq "" and $formID eq ""}
    <tr><td colspan=2>Load Settings from Skin: </td></tr>
    <tr><td colspan=2 nowrap>
    
    <select name=loadFromSkin>
    <option value=""> - choose skin - </option>
    {html_options options=$availableSkins}
    </select>
     <input type=button onClick="javascript:doLoadFromSkin();" value="Ok">
    
    <a href="javascript:launchCentered('{$help.url}?type=load_from_skin',{$help.width},{$help.height},'{$help.options}');">{$helpSymbol}</a>
    
    </td></tr>
    
    <tr><td colspan=2>&nbsp;</td></tr>
{/if}

{assign var=menuType value=$data.type}

{foreach name=settings key=i item=setting from=$structure}

{* determine the size of this field based on type *}

{if $setting[3]|replace:$menuType:"" ne $setting[3] or $menuID eq ""}
{if not ($menuID neq "" and $menuLevel eq -1 and $i eq "override")}

<tr>

{if $setting[1] ne 'comment'}
<td width=40% valign=top>{$setting[0]}</td>
{/if}

<td width=60% nowrap valign=top {if $setting[1] eq 'comment'}colspan=2{/if}>

	{if $setting[1] eq 'textarea'}
		<textarea style='width: 100%' rows=4 name="{$i}">{$data.$i|default:"$setting[2]"}</textarea>
	
	{elseif $setting[1] eq 'insertable_textarea'}
	    <a href="javascript: insertInternalVaraiable( '{$i}' );">Insert Variable...</a><br />
		<textarea style='width: 100%' rows=4 name="{$i}">{$data.$i|default:"$setting[2]"}</textarea>
	
    {* same as style, but allow for none option *}
	{elseif $setting[1] eq 'style2'}
		<select name="{$i}">
		<option value="">(none)</option>
		{html_options values=$styleList output=$styleList selected=$data.$i|default:"$setting[2]"}
	</select>
	
	{elseif $setting[1] eq 'style'}
		<select name="{$i}">
		{html_options values=$styleList output=$styleList selected=$data.$i|default:"$setting[2]"}
		</select>
	{elseif $setting[1] eq 'align'}
		<select name="{$i}">
		{html_options values=$alignCombo output=$alignCombo selected=$data.$i|default:"$setting[2]"}
		</select>
	{elseif $setting[1] eq 'valign'}
		<select name="{$i}">
		{html_options values=$valignCombo output=$valignCombo selected=$data.$i|default:"$setting[2]"}
		</select>
	{elseif $setting[1] eq 'type'}
	
		<select name="{$i}" onchange="javascript:submitForm();">
		{html_options values=$typeCombo output=$typeCombo selected=$data.$i|default:"$setting[2]"}
		</select>
		
	   {if $menuID neq "" and $menuType neq "Tree"}
	   </td></tr>
	   <td width=40% valign=top>Choose level:</td>
        <td width=60% nowrap valign=top>
		<select name="menu_level" onchange="javascript: document.location.href='editSettings.php?menu_id={$menuID}&menu_level='+this.value;">
		{html_options options=$menuLevelCombo selected=$menuLevel}
		</select>
	   {/if}
	   
		
	{elseif $setting[1] eq 'visible_height'}
		<select name="{$i}">
		{html_options options=$visibleHeightCombo selected=$data.$i|default:"$setting[2]"}
		</select>
	{elseif $setting[1] eq 'footer_area'}
		<select name="{$i}">
		{html_options options=$footerAreaCombo selected=$data.$i|default:"$setting[2]"}
		</select>
	{elseif $setting[1] eq 'flyouttype'}
		<select name="{$i}">
		{html_options values=$flyouttypeCombo output=$flyouttypeCombo selected=$data.$i|default:"$setting[2]"}
		</select>
	{elseif $setting[1] eq 'font'}
		<select name="{$i}">
		{html_options values=$fontCombo output=$fontCombo selected=$data.$i|default:"$setting[2]"}
		</select>
	{elseif $setting[1] eq 'weight'}
		<select name="{$i}">
		{html_options values=$weightCombo output=$weightCombo selected=$data.$i|default:"$setting[2]"}
		</select>		
	{elseif $setting[1] eq 'cfolder'}
		<select name="{$i}" onchange="javascript:changeImage(this, document.c_folder)">
		{html_options values=$cfolderImageCombo output=$cfolderImageCombo selected=$data.$i|default:"$setting[2]"}
		</select>&nbsp;&nbsp;<img src="{$menuImagesFolder}{$data.$i|default:"$setting[2]"}" name="c_folder">
	{elseif $setting[1] eq 'ofolder'}
		<select name="{$i}" onchange="javascript:changeImage(this, document.o_folder)">
		{html_options values=$ofolderImageCombo output=$ofolderImageCombo selected=$data.$i|default:"$setting[2]"}
		</select>&nbsp;&nbsp;<img src="{$menuImagesFolder}{$data.$i|default:"$setting[2]"}" name="o_folder">
	{elseif $setting[1] eq 'docimage'}
		<select name="{$i}" onchange="javascript:changeImage(this, document.doc_image)">
		{html_options values=$docimageCombo output=$docimageCombo selected=$data.$i|default:"$setting[2]"}
		</select>&nbsp;&nbsp;<img src="{$menuImagesFolder}{$data.$i|default:"$setting[2]"}" name="doc_image">
		
	{elseif $setting[1] eq 'line'}
		<select name="{$i}" onchange="javascript:changeImage(this, document.line_image)">
		{html_options values=$lineImageCombo output=$lineImageCombo selected=$data.$i|default:"$setting[2]"}
		</select>&nbsp;&nbsp;<img src="{$menuImagesFolder}{$data.$i|default:"$setting[2]"}" name="line_image">
		
	{elseif $setting[1] eq 'join'}
		<select name="{$i}" onchange="javascript:changeImage(this, document.join_image)">
		{html_options values=$joinCombo output=$joinCombo selected=$data.$i|default:"$setting[2]"}
		</select>&nbsp;&nbsp;<img src="{$menuImagesFolder}{$data.$i|default:"$setting[2]"}" name="join_image">
		
	{elseif $setting[1] eq 'join_bottom'}
		<select name="{$i}" onchange="javascript:changeImage(this, document.join_bottom_image)">
		{html_options values=$joinBottomCombo output=$joinBottomCombo selected=$data.$i|default:"$setting[2]"}
		</select>&nbsp;&nbsp;<img src="{$menuImagesFolder}{$data.$i|default:"$setting[2]"}" name="join_bottom_image">
	
	{elseif $setting[1] eq 'minus'}
		<select name="{$i}" onchange="javascript:changeImage(this, document.minus_image)">
		{html_options values=$minusCombo output=$minusCombo selected=$data.$i|default:"$setting[2]"}
		</select>&nbsp;&nbsp;<img src="{$menuImagesFolder}{$data.$i|default:"$setting[2]"}" name="minus_image">
	
	{elseif $setting[1] eq 'plus'}
		<select name="{$i}" onchange="javascript:changeImage(this, document.plus_image)">
		{html_options values=$plusCombo output=$plusCombo selected=$data.$i|default:"$setting[2]"}
		</select>&nbsp;&nbsp;<img src="{$menuImagesFolder}{$data.$i|default:"$setting[2]"}" name="plus_image">
	
	{elseif $setting[1] eq 'minusbottom'}
		<select name="{$i}" onchange="javascript:changeImage(this, document.minus_bottom_image)">
		{html_options values=$minusBottomCombo output=$minusBottomCombo selected=$data.$i|default:"$setting[2]"}
		</select>&nbsp;&nbsp;<img src="{$menuImagesFolder}{$data.$i|default:"$setting[2]"}" name="minus_bottom_image">
		
	{elseif $setting[1] eq 'plusbottom'}
		<select name="{$i}" onchange="javascript:changeImage(this, document.plus_bottom_image)">
		{html_options values=$plusBottomCombo output=$plusBottomCombo selected=$data.$i|default:"$setting[2]"}
		</select>&nbsp;&nbsp;<img src="{$menuImagesFolder}{$data.$i|default:"$setting[2]"}" name="plus_bottom_image">
	
	{elseif $setting[1] eq 'eimage'}
		<select name="{$i}" onchange="javascript:changeImage(this, document.e_image)">
		{html_options values=$eimageCombo output=$eimageCombo selected=$data.$i|default:"$setting[2]"}
		</select>&nbsp;&nbsp;<img src="{$menuImagesFolder}{$data.$i|default:"$setting[2]"}" name="e_image">
		
	{elseif $setting[1] eq 'cimage'}
		<select name="{$i}" onchange="javascript:changeImage(this, document.c_image)">
		{html_options values=$cimageCombo output=$cimageCombo selected=$data.$i|default:"$setting[2]"}
		</select>&nbsp;&nbsp;<img src="{$menuImagesFolder}{$data.$i|default:"$setting[2]"}" name="c_image">
		
	{elseif $setting[1] eq 'fieldList'}
		<select name="{$i}">
		{html_options values=$fieldListValues output=$fieldListTitles selected=$data.$i|default:"$setting[2]"}
		</select>
		
	{elseif $setting[1] eq 'skin'}
       	<select name="{$i}"><option value=''>(use default skin)</option>{html_options options=$skinsCombo selected=$data.$i|default:"$setting[2]"}
    	</select>
   		<a href="javascript:launchCentered('{$help.url}?type=skins',{$help.width},{$help.height},'{$help.options}');">{$helpSymbol}</a>
   		
	{elseif $setting[1] eq 'location'}
		<select name="{$i}"">
		{html_options values=$locationCombo output=$locationCombo selected=$data.$i|default:"$setting[2]"}
		</select>

	{elseif $setting[1] eq 'editableBy'}
		<select name="{$i}"">
		{html_options options=$editableByCombo selected=$data.$i|default:"$setting[2]"}
		</select>
		
	{elseif $setting[1] eq 'restrict_to'}
		{*
		<select name="{$i}[]" multiple size=3>
		{html_options output=$restrictOutput values=$restrictValues selected=$data.$i|default:"$setting[2]" max_len=60}
		</select>
		*}
		<a href="javascript:launchCentered('{$docroot}manage/editRestriction.php?resource_type=menu&resource_id={$menuID}',{$help.width},{$help.height},'{$help.options}');">Edit Visibility</a>
				
    {elseif $setting[1] eq 'boolean'}
		<select name="{$i}">
		{html_options values=$booleanCombo output=$booleanCombo selected=$data.$i|default:"$setting[2]"}
		</select>
		
		{if $i eq 'wysiwyg'}
		<a href="javascript:launchCentered('{$help.url}?type=wysiwyg',{$help.width},{$help.height},'{$help.options}');">{$helpSymbol}</a>
		{/if}

		{if $i eq 'paginate'}
		<a href="javascript:launchCentered('{$help.url}?type=report_pagination',{$help.width},{$help.height},'{$help.options}');">{$helpSymbol}</a>
		{/if}
		
		{if $i eq 'is_default'}
		<a href="javascript:launchCentered('{$help.url}?type=default_page',{$help.width},{$help.height},'{$help.options}');">{$helpSymbol}</a>
		{/if}		
		
		{if $i eq 'generate_report'}
		<a href="javascript:launchCentered('{$help.url}?type=submission_report',{$help.width},{$help.height},'{$help.options}');">{$helpSymbol}</a>
		{/if}		
		
		{if $i eq 'login_form'}
		<a href="javascript:launchCentered('{$help.url}?type=login_form',{$help.width},{$help.height},'{$help.options}');">{$helpSymbol}</a>
		{/if}		

		{if $i eq 'full_textarea'}
		<a href="javascript:launchCentered('{$help.url}?type=full_textarea',{$help.width},{$help.height},'{$help.options}');">{$helpSymbol}</a>
		{/if}		

		{if $i eq 'email_confirmation'}
		<a href="javascript:launchCentered('{$help.url}?type=email_confirmation',{$help.width},{$help.height},'{$help.options}');">{$helpSymbol}</a>
		{/if}
		
	{elseif $setting[1] eq 'comment'}
	
		<table border=0 cellpadding=0 cellspacing=0 width=100% class=normal>
		<tr><td>&nbsp;</td></tr>
		<tr><td>{$setting[2]}</td></tr>
		<tr><td>&nbsp;</td></tr>
		</table>
	
	{elseif $setting[1] eq 'image'}
	
		<table border=0 cellpadding=0 cellspacing=0 class="normal">
		<tr>
		<td nowrap>
		<input type=file name="{$i}">
		</td>
		
		<td nowrap>
		
		{if $data.$i}
			<input type=button onClick="javascript:doDeleteImage('delete_{$i}');" name="delete_{$i}" value="Remove">
		{/if}
		
		<a href="javascript:launchCentered('{$help.url}?type={$i}',{$help.width},{$help.height},'{$help.options}');">{$helpSymbol}</a>
		
		</td>
		
		</tr>
		
		<tr><td colspan=2><small>Current Image Size: {$data.$i|count_characters} bytes</small></td></tr>
		</table>
	{else}	
		<input type=text 

		size=
		{if $setting[1] eq 'text'}
		30
		{elseif $setting[1] eq 'color'}
		10
		{else}
		6
		{/if} 

		name="{$i}" value="{$data.$i|default:$setting[2]}">

		{if $setting[1] eq 'color'}
		<a href="javascript:TCP.popup(document.forms['editSettings'].elements['{$i}'], 1)"><img src="{$docroot}images/color.gif" border=0></a>
		{/if}
		
    	{if $i eq 'rows_per_page'}
		<a href="javascript:launchCentered('{$help.url}?type=report_rows_per_page',{$help.width},{$help.height},'{$help.options}');">{$helpSymbol}</a>
		{/if}

    	{if $i eq 'page_links'}
		<a href="javascript:launchCentered('{$help.url}?type=report_navbar_links',{$help.width},{$help.height},'{$help.options}');">{$helpSymbol}</a>
		{/if}
		
	   	{if $i eq 'title' && $formID}
		<a href="javascript:launchCentered('{$help.url}?type=form_title',{$help.width},{$help.height},'{$help.options}');">{$helpSymbol}</a>
		{/if}
		
		{if $i eq 'form_key' && $formID}
		<a href="javascript:launchCentered('{$help.url}?type=page_keys',{$help.width},{$help.height},'{$help.options}');">{$helpSymbol}</a>
		{/if}

	{/if}
</td>
</tr>
{/if}
{/if}

{/foreach}

<tr><td colspan=2><input type=hidden name=deleteImage value=""><input type=button name=btnSubmit onClick="javascript:submitForm();" value="Submit Updates"></td></tr>

<tr><td colspan=2>&nbsp;</td></tr>

{*
{if $menuID ne ""}
	<tr><td colspan=2 class=normal>{$menuManagerReturnLink}</td></tr>
{/if}
*}

<tr><td colspan=2 class=normal>{$logoutLink} {$pathway}</td></tr>



</table>

</form>