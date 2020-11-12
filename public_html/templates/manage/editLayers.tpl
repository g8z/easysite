<script type="text/javascript" src="{$docroot}{$smarty.const.ADMIN_DIR}/picker.js"></script>

{* special js functions for this page only *}
{literal}
<script language="Javascript">
<!--

// path to HTMLArea (for wysiwyg editing)
var _editor_url = 'htmlarea/';

function removeImage(type) {
    if ( type == 'thumb' ) {
        document.editPage.deleteThumbImg.value = 1;
    }
    else if ( type == 'large' ) {
        document.editPage.deleteLargeImg.value = 1;
    }
    
    submitForm();
}


function deleteSection() {
    if ( confirm( 'Are you sure? All data in this layer will be PERMANENTLY removed!' ) ) {
        document.editPage.deleteSectionVar.value = 1;
        document.editPage.submit();
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

        
        // check for valid numeric or % input. NOTE: this is also allowing for "," and " " due
        // to the fact that forms with multi-line textboxes can have "#,#" inputs for size
        
        if ( elemSize == 6 && !isNumeric( elemValue, '%, ' ) ) {
        	alert( elemValue + ' is not a valid number, number sequence, or percentage. Please re-input this value.' );
        	formElements[i].focus();
        	formElements[i].select();
        	return false;
        }
        
        if ( elemName.indexOf( "img_large" ) != -1 && elemValue != "" ) {
            // assumes that the hidden input field to hold the 
            // path is IMMEDIATELY after the file field!
            theForm.elements[i + 1].value = elemValue;
            i++
        }
        if ( elemName.indexOf( "img_thumb" ) != -1 && elemValue != "" ) {
            // assumes that the hidden input field to hold the 
            // path is IMMEDIATELY after the file field!
            theForm.elements[i + 1].value = elemValue;
            i++
        }
        if ( elemName.indexOf( "file_data" ) != -1 && elemValue != "" ) {
            // assumes that the hidden input field to hold the 
            // path is IMMEDIATELY after the file field!
            theForm.elements[i + 1].value = elemValue;
            i++
        }
       
        if ( elemType == 'textarea' && eval( wysiwygFields[ elemName ] ) ) {
	    	// check to determine if there is a value in this field

	    	var wysiwygObject = wysiwygFields[ elemName ];
	    	var wysiwygObjectHTML = wysiwygObject.getHTML().trim();
	    	
	    	if ( wysiwygObjectHTML != '' && wysiwygObjectHTML != '<p />' ) {
		theForm.elements[i].value = wysiwygObjectHTML; 	    		
	    	}
	    }
	
    }
    
    if ( theForm.title.value.trim() == '' ) {
        alert( 'Please input a name for this layer.' );
        return false;
    }
    
    return true;
}

// the following object & function are for wysiwyg editing, if enabled
var wysiwygFields = new Object();
var allReplaced = false;

function enableWYSIWYG( obj ) {

	toggleT('div_var','h');


	if ( eval( wysiwygFields[ obj ] ) ) {
		alert( 'The editor has already been enabled for this section.' );
	}
	else {
		var config = new HTMLArea.Config();
		var easysite_config = new EasySite.Config();
		
		{/literal}
		config.width = eval( '{$settings.textarea_width|default:"600"}' )+'px';
		config.height = eval( '{$settings.textarea_height|default:"100"}+70' )+'px';
		{literal}
		
		config.toolbar = easysite_config.toolbar;
		config.btnList = easysite_config.btnList;
		
		if ( !eval( wysiwygFields[ obj ] ) ) {

			//HTMLArea.replace( obj, config );
			
			var htmlareaObj = new HTMLArea( obj, config );
			htmlareaObj.generate();
			
			wysiwygFields[ obj ] = htmlareaObj;
		}
	}
}



function insertInternalVaraiable() {
    
    var field = eval( 'document.editPage.content' );
	var width = 400;
	var height = 500;

	var left = ( screen.width - width ) / 2;
	var top = ( screen.height - height ) / 2;

    win = window.open( 'variableChooser.php', null, "top="+top+",left="+left+",width="+width+",height="+height+",status=yes,scrollbars=yes,resizable=yes,dependent=yes", true );
    win.opener = window;
    win.opener.field = field;
}

function submitForm() {
    // determine paths for all image uploads
    if ( !isValid() )
        return false;
    
    document.editPage.formIsSubmitted.value = 1;
    
    // submit the form
    document.editPage.submit();
}


function settingsOnOff( value ) {
	
	var theForm = document.editPage;
	var i, n;
	
	var relatedFields = new Array( 'format', 'style', 'top', 'left', 'width', 'height', 'align', 'valign', 'zorder', 'padding', 'bgcolor' );
	
	if ( value != '0' && value != '' ) {
		
		for ( index in relatedFields ) {
			eval( 'theForm.'+relatedFields[index]+'.disabled=true' );
		}
	}
	else {
		for ( index in relatedFields ) {
			eval( 'theForm.'+relatedFields[index]+'.disabled=false' );
		}
	}
}


function switchLayer( id ) {
	
	// redirect page without saving current layer
	
	document.location.href="editLayers.php?layer_id="+id;
}

function switchRestrictTo( restrict_to ) {
	
	// redirect page without saving current layer
	
	document.location.href="editLayers.php?choose_restrict_to="+restrict_to;
}



//-->
</script>
{/literal}

{* only load wysiwyg editor code if wysiwyg editing is allowed
in the site settings AND we are using a wysiwyg-compatible browser *}

{if $settings.wysiwyg eq 'yes' and $wysiwygCompatible}

    {assign var=wysiwyg value=$wysiwygLink}

    <script type="text/javascript" src="{$docroot}{$smarty.const.ADMIN_DIR}/htmlarea/htmlarea.js"></script>
    <script type="text/javascript" src="{$docroot}{$smarty.const.ADMIN_DIR}/htmlarea/easysite_config.js"></script>
    
{/if}


<form action=editLayers.php method="POST" enctype="multipart/form-data" name="editPage">

<input type=hidden name=deleteThumbImg value="">
<input type=hidden name=deleteLargeImg value="">
<input type=hidden name=deleteSectionVar value="">
<input type=hidden name=site_key value="{$site_key}">
<input type=hidden name=formIsSubmitted value="">

<table border=0 cellpadding=0 cellspacing=3 class=normal width="{$settings.textarea_width|default:'600'}">

    <tr><td class=normal colspan="3">{$logoutLink} {$pathway}</td></tr>
    
    <tr><td colspan="3">&nbsp;</td></tr>

    <tr><td colspan="3" class="title">Layer Editor</td></tr>
    <tr><td colspan="3">&nbsp;</td></tr>
    
	<tr><td>Edit Layers Visible On:</td><td><select name="choose_restrict_to" onchange="javascript: switchRestrictTo( this.options[this.selectedIndex].value );">{html_options values=$layerRestrictValues output=$layerRestrictOutput selected=$choose_restrict_to max_len=60}</select></td><td>&nbsp;</td></tr>
	<tr><td>Switch To Layer:</td><td><select name="layer_id" onchange="javascript: switchLayer( this.options[this.selectedIndex].value );">{html_options options=$layerList selected=$layer_id}</select></td><td>&nbsp;</td></tr>

    <tr>
    <td>Name:</td><td colspan="2"><input type=text size=50 name="title" maxlength=50 value="{$editLayer.title}"></td>
    </tr>

    <tr>
    <td colspan="3">
    
        <table border=0 cellpadding=1 cellspacing=0 width=100% class=normal>
            <tr>
            <td valign=bottom nowrap>Content: {$wysiwyg}&nbsp;&nbsp;&nbsp;<span id="div_var" style="visibility:visible;padding:0px"><a href="javascript: insertInternalVaraiable();">Insert Variable...</a></span></td>
            
	<td align=right width=50%>Format: </td>

	<td nowrap>
	<select name="format">
	{html_options options=$formatList selected=$editLayer.format}
	</select>
	
	<a href="javascript:launchCentered('{$help.url}?type=section_format',{$help.width},{$help.height},'{$help.options}');">{$helpSymbol}</a>
	
	</td>
            
            <td align=right>Style: </td>

            <td>
            <select name="style">
            {html_options values=$styleList output=$styleList selected=$editLayer.style}
            </select>
            
            </td>
            </tr>
        </table>
    </td>
    </tr>

    <tr>
        <td colspan="3">
        <textarea style='width: {$settings.textarea_width|default:"600"}px; height: {$settings.textarea_height|default:"100"}px' name="content" id="content">{$editLayer.content}</textarea>
        </td>
    </tr>
       
    <!--<tr><td colspan="2">-->

        {* a table to hold the embedded image & large image paths *}
        
		<tr>
	         <td align=right>Override with settings from:</td>
	         <td>
			 <select name="settings_override" onchange="javascript: settingsOnOff( this.options[this.selectedIndex].value );">
			 {html_options options=$layerListSettings selected=$editLayer.settings_override}
			 </select>
			 </td>
			 <td>&nbsp;</td>
	    </tr>

    	<tr>
        	<td align=right nowrap>Top Offset: </td><td><input type=text size=6 name="top" value="{$editLayer.top}"></td>
        </tr>
        
        <tr>
            <td align=right>Left: </td><td><input type=text size=6 name="left" value="{$editLayer._left}"> 
            <a href="javascript:launchCentered('{$help.url}?type=layer_options',{$help.width},{$help.height},'{$help.options}');">{$helpSymbol}</a></td>
            <td>&nbsp;</td>
        </tr>
        
        <tr>
        	<td align=right>Width: </td><td><input type=text size=6 name="width" value="{$editLayer.width}"></td>
        	<td>&nbsp;</td>
        </tr>
        
        <tr>
            <td align=right>Height: </td><td><input type=text size=6 name="height" value="{$editLayer.height}"></td>
            <td>&nbsp;</td>
        </tr>
        
        <tr>
		    <td align=right>Align: </td><td>

		    <select name="align">
		    {html_options values=$align output=$align selected=$editLayer.align}
		    </select>

		    </td>
		    <td>&nbsp;</td>
        </tr>
        
        <tr>
            <td align=right nowrap>V-align: </td><td>
            
            <select name="valign">
            {html_options values=$valign output=$valign selected=$editLayer.valign}
            </select>
            
            </td>
            <td>&nbsp;</td>
        </tr>
        
        <tr>
        	<td align=right nowrap>Z-Order: </td><td><input type=text size=6 name="zorder" value="{$editLayer.zorder}"></td>
        	<td>&nbsp;</td>
        </tr>
        
		<tr>
			<td align=right>Padding: </td><td><input type=text size=6 name="padding" value="{$editLayer.padding}"></td>
			<td>&nbsp;</td>
		</tr>
		
        <tr>
            <td align=right>Color: </td><td nowrap><input type=text size=10 name="bgcolor" value="{$editLayer.bgcolor}">
            <a href="javascript:TCP.popup(document.forms['editPage'].elements['bgcolor'], 1)"><img src="{$docroot}images/color.gif" border=0></a>
            </td>
            <td>&nbsp;</td>
         </tr>

             
       	{if $layer_id}
        <tr>
			<td align=right nowrap valign=top width=20%>Only show this<br />layer on:<br /></td>
			<td valign=top colspan="2">
			
				<a href="javascript:launchCentered('{$docroot}manage/editRestriction.php?resource_type=layer&resource_id={$layer_id}',{$help.width},{$help.height},'{$help.options}');">Edit Visibility</a>
			
			</td>
		</tr>
		{/if}
        	
        
        <tr>
        <td nowrap align=right>New Line Behavior: </td>
        <td><select name="nl2br">
	    {html_options options=$newLineCombo selected=$editLayer.nl2br}
	    </select>
	    </td>
	    <td>&nbsp;</td>
        </tr>

        
        <tr>
        <td nowrap align=right valign="top">Embedded Image: </td>
        <td valign="top">
		        <input type=file name="img_thumb"> 
		        <a href="javascript:launchCentered('{$help.url}?type=img_thumb',{$help.width},{$help.height},'{$help.options}');">{$helpSymbol}</a>

		        
		        <input type=hidden name=img_thumb_path value="">

		        
        </td>
	    {if $editLayer.img_thumb_path ne ""}
	    <td rowspan="2" align="left" nowrap>
	    <p class=small>
    	Embedded Image:<br />
        {img table=$table field=img_thumb id=$editLayer.id maxWidth=100 maxHeight=70}
        </p>
	    </td>
	    {else}
	    <td>&nbsp;</td>
	    {/if}
        
        </tr>
        		        
        {if $editLayer.img_thumb_path ne ""}
    	<tr>
    		<td valign="top" colspan="2"><input type=button onClick="javascript:removeImage('thumb')" value='Remove'> Current: 
	        <i>{$editLayer.img_thumb_path}</i>
	    </tr>
        {/if}



        <tr>
        <td nowrap align=right valign="top">Popup/Large Image: </td>
        <td valign="top">
		        <input type=file name="img_large"> 
		        <a href="javascript:launchCentered('{$help.url}?type=img_large',{$help.width},{$help.height},'{$help.options}');">{$helpSymbol}</a>
		        <input type=hidden name=img_large_path value="">
		        
		        {*
		        {if $editLayer.img_large_path ne ""}
		            <br />
		            <input type=button onClick="javascript:removeImage('large')" value='Remove'>
		        {/if}

		        {if $editLayer.img_large_path ne ""}
		        	<span class="small">Current:<i>{$editLayer.img_large_path}</i></span><br />
		            {img table=$table field=img_large id=$editLayer.id maxWidth=100 maxHeight=70}
		        {else}
		        	&nbsp;
		        {/if}
		        *}
        </td>
        
	    {if $editLayer.img_large_path ne ""}
	    <td rowspan="2" align="left">
	    <p class=small>
    	Popup Image:<br />
        {img table=$table field=img_large id=$editLayer.id maxWidth=100 maxHeight=70}
        {if $editLayer.img_thumb_path eq ''}
        <br />This image will not be viewable unless an embedded image is added.
        {/if}
        </p>
	    </td>
	    {else}
	    <td>&nbsp;</td>
	    {/if}
	    
        </tr>

        {if $editLayer.img_large_path ne ""}
    	<tr>
    		<td valign="top" colspan="2"><input type=button onClick="javascript:removeImage('large')" value='Remove'> Current: 
	        <i>{$editLayer.img_large_path}</i>
	    </tr>
        {/if}

        <tr>
        <td align=right>Image Link: </td>
        <td nowrap><input type=text size=40 name="img_link" value="{$editLayer.img_link}"> <a href="javascript:launchCentered('{$help.url}?type=img_link',{$help.width},{$help.height},'{$help.options}');">{$helpSymbol}</a></td>
        <td>&nbsp;</td>
        </tr>

        <tr>
        <td align=right>Link Target: </td>
        <td nowrap><select name="link_target">{html_options options=$linkOptions selected=$editLayer.link_target}</select></td>
        <td>&nbsp;</td>
        </tr>
        
        <tr>
        <td align=right>Image Anchor: </td>
        <td nowrap>
            <select name=img_anchor>
            {html_options values=$anchorValues output=$anchorNames selected=$editLayer.img_anchor}
            </select>
            <a href="javascript:launchCentered('{$help.url}?type=img_anchor',{$help.width},{$help.height},'{$help.options}');">{$helpSymbol}</a>
        </td>
        <td>&nbsp;</td>
        </tr>

        <tr>
        <td align=right>Image ALT: </td>
        <td nowrap><input type=text size=40 name="img_alt" value="{$editLayer.img_alt}"></td>
        <td>&nbsp;</td>
        </tr>
        
    <tr><td colspan="3">&nbsp;</td></tr>
    <tr>
    <td colspan="2">
    
        <input type=button name=masterSubmit value="Submit" onClick="javascript:submitForm();">
        
        <input type=reset value="Reset">
    
        {if $layer_id and $permissions.delete}
            <input type=button name=delete value="Delete" onClick="javascript:deleteSection();"> 
        {/if}
        

    </td>
    <td>&nbsp;</td>
    </tr>

    <tr><td colspan="3">&nbsp;</td></tr>
    <tr><td class=normal colspan="3">{$logoutLink} {$pathway}</td></tr>


</table>
</form>

<script language="JavaScript">
<!--
settingsOnOff( '{$editLayer.settings_override}' );
-->
</script>
  