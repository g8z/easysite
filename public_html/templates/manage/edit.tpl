<script type="text/javascript" src="{$docroot}{$smarty.const.ADMIN_DIR}/picker.js"></script>

{* special js functions for this page only *}
{literal}
<script language="Javascript">
<!--

// path to HTMLArea (for wysiwyg editing)
var _editor_url = 'htmlarea/';

function removeImage(id,type) {
    if ( type == 'thumb' ) {
        document.editPage.deleteThumbImg.value = id;
    }
    else if ( type == 'large' ) {
        document.editPage.deleteLargeImg.value = id;
    }
    
    submitForm();
}

function switchPage() {
    document.editPage.page_id.value = document.editPage.switchPageId.value; 
    
    if ( document.editPage.page_id.value == '' )
    	document.location.href='edit.php?add_page=1';
    else
    	document.editPage.submit();
}

function switchForm() {
    document.editPage.form_id.value = document.editPage.switchFormId.value;
    
    if ( document.editPage.form_id.value == '' ) 
    	document.location.href='edit.php?add_form=1';
    else
    	document.editPage.submit();
}

function getEditGroup( groupId ) {
    if ( groupId == "NEW" ) {
        alert( 'Please save this form section before adding radio group data.' );
    }
    else {
        launchCentered( 'edit.php?radioGroup=' + groupId,400,500,'scrollbars,resizable' );
    }
}

function activateElement(element) {
    element.disabled = false; 
    element.style.backgroundColor = '#ffffff';
}

function deactivateElement(element) {
    element.disabled = true; 
    element.style.backgroundColor = '{/literal}{$disabledElementColor}{literal}';
}

function enableFields( fieldType, csvValues, buttonObj, fieldSizeObj, validatorObj ) {

    // check to see if the CSV text field should be enabled
    if ( fieldType.value == 'select' ) {
        activateElement( csvValues );
    }
    else {
        deactivateElement( csvValues );
    }
    
    if ( fieldType.value == 'text' || fieldType.value == 'textarea' || fieldType.value == 'password' ) {
        activateElement( fieldSizeObj );
        activateElement( validatorObj );
    }
    else {
        deactivateElement( fieldSizeObj );
        deactivateElement( validatorObj );
    }
    
    // check to see if the "edit group" button should be enabled
    if ( fieldType.value == 'radio' ) {
        buttonObj.disabled = false;
        buttonObj.style.color = '#000000';

    }
    else {
        buttonObj.disabled = true;
        buttonObj.style.color = '#888888';
    }
    
}

function deleteSection(id) {
    if ( confirm( 'Are you sure? All of the items in this section will be PERMANENTLY removed!' ) ) {
        document.editPage.deleteSectionVar.value = id;
        submitForm();
    }
}

function bumpUpSection(id) {
    document.editPage.bumpUpSectionVar.value = id;
    submitForm();
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
        
        /*
        if ( elemSize == 10 && elemType == 'text' && trim( elemValue ) != '' ) {
            if ( !isColorCode( elemValue ) ) {
                alert( elemValue + ' is not a valid color code. Please re-input this value.' );
                return false;
            }
        }
        */
        
        // check for valid numeric or % input. NOTE: this is also allowing for "," and " " due
        // to the fact that forms with multi-line textboxes can have "#,#" inputs for size
        
        if ( elemSize == 6 && !isNumeric( elemValue, '%, ' ) ) {
        	alert( elemValue + ' is not a valid number, number sequence, or percentage. Please re-input this value.' );
        	formElements[i].focus();
        	formElements[i].select();
        	return false;
        }
        
        if ( elemName.indexOf( "img_large_" ) != -1 && elemValue != "" ) {
            // assumes that the hidden input field to hold the 
            // path is IMMEDIATELY after the file field!
            theForm.elements[i + 1].value = elemValue;
            i++
        }
        if ( elemName.indexOf( "img_thumb_" ) != -1 && elemValue != "" ) {
            // assumes that the hidden input field to hold the 
            // path is IMMEDIATELY after the file field!
            theForm.elements[i + 1].value = elemValue;
            i++
        }
        if ( elemName.indexOf( "file_data_" ) != -1 && elemValue != "" ) {
            // assumes that the hidden input field to hold the 
            // path is IMMEDIATELY after the file field!
            theForm.elements[i + 1].value = elemValue;
            i++
        }
       
        // determine if we need to add a new record
        if ( elemName.indexOf( "_NEW" ) != -1 ) {
            // check field type (skip combos, radios, and checkboxes)
    
    	    if ( elemType == 'textarea' && eval( wysiwygFields[ elemName ] ) ) {
    	    	// check to determine if there is a value in this field
  
    	    	var wysiwygObject = wysiwygFields[ elemName ];
    	    	var wysiwygObjectHTML = wysiwygObject.getHTML().trim();
    	    	
    	    	if ( wysiwygObjectHTML != '' && wysiwygObjectHTML != '<p />' ) {
    	    		theForm.addNewItem.value = 1;
			theForm.elements[i].value = wysiwygObjectHTML; 	    		
    	    	}
    	    }
            else if ( elemType != 'select-one' && elemType != 'checkbox' && elemValue != "" ) {
                theForm.addNewItem.value = 1;
            }
        }else{
	        if ( elemType == 'textarea' && eval( wysiwygFields[ elemName ] ) ) {
    	    	// check to determine if there is a value in this field
  
    	    	var wysiwygObject = wysiwygFields[ elemName ];
    	    	var wysiwygObjectHTML = wysiwygObject.getHTML().trim();
    	    	
    	    	if ( wysiwygObjectHTML != '' && wysiwygObjectHTML != '<p />' ) {
			theForm.elements[i].value = wysiwygObjectHTML; 	    		
    	    	}
    	    }
	}
    }
    
    // check for special fields
    if ( eval( theForm.page_title ) ) {
        if ( theForm.page_title.value.trim() == '' ) {
            alert( 'Please input a name for this page.' );
            return false;
        }
    }
    else if ( eval( theForm.form_title ) ) {
        if ( theForm.form_title.value.trim() == '' ) {
            alert( 'Please input a title for this form.' );
            return false;
        }
    }
    
    return true;
}

// the following object & function are for wysiwyg editing, if enabled
var wysiwygFields = new Object();
var allReplaced = false;

function enableWYSIWYG( obj, replaceAll ) {

	// obj is the id of the object (a string, not an object type)

	if ( replaceAll && allReplaced ) {
		alert( 'The editor has already been enabled for all sections.' );
	}
	else if ( eval( wysiwygFields[ obj ] ) && !replaceAll ) {
		alert( 'The editor has already been enabled for this section.' );
	}
	else {
		var config = new HTMLArea.Config();

		config.height = '200px';

		if ( replaceAll ) {
			var tas = document.getElementsByTagName("textarea");

			for (var i = tas.length - 1; i >= 0; i-- ) {

				var htmlareaObj;

				if ( !eval( wysiwygFields[ tas[i].name ] ) ) {

					// name = id for all textarea objects
					htmlareaObj = new HTMLArea( tas[i].name, config );
					
					htmlareaObj.generate();
				
					//(new HTMLArea( tas[i].name, config )).generate();
				}

				wysiwygFields[ tas[i].name ] = htmlareaObj;
			}
			allReplaced = true;
		}
		else if ( !eval( wysiwygFields[ obj ] ) ) {

			//HTMLArea.replace( obj, config );
			
			var htmlareaObj = new HTMLArea( obj, config );
			htmlareaObj.generate();
			
			wysiwygFields[ obj ] = htmlareaObj;
		}
	}
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

{* only load wysiwyg editor code if wysiwyg editing is allowed
in the site settings AND we are using a wysiwyg-compatible browser *}

{if $settings.wysiwyg eq 'yes' and $wysiwygCompatible}

    {assign var=wysiwyg value=$wysiwygLink}

    <script type="text/javascript" src="{$docroot}{$smarty.const.ADMIN_DIR}/htmlarea/htmlarea.js"></script>
{/if}


{* loop through all available sections, printing data for each *}
<form action=edit.php method="POST" enctype="multipart/form-data" name="editPage">

<input type=hidden name=deleteThumbImg value="">
<input type=hidden name=deleteLargeImg value="">
<input type=hidden name=deleteSectionVar value="">
<input type=hidden name=page_id value="{$page_id}">
<input type=hidden name=site_key value="{$site_key}">
<input type=hidden name=layers value="{$layers}">
<input type=hidden name=files value="{$files}">
<input type=hidden name=form_id value="{$form_id}">
<input type=hidden name=radioGroup value="{$radioGroup}">
<input type=hidden name=addNewItem value="">
<input type=hidden name=formIsSubmitted value="">
<input type=hidden name=skin_id value="{$skin_id}">

<table border=0 cellpadding=0 cellspacing=3 width=100% class=normal>

{foreach name=sections item=section from=$data}

{* if first iteration, then display the 'Add' title *}

{if $smarty.foreach.sections.iteration eq 1}

    <tr>
    <td class=normal>
    {$adminReturnLink} {$logoutLink} {$userGuideLink}
    </td>
    </tr>
    
    <tr><td>&nbsp;</td></tr>

    {if $radioGroup eq ""}
        <tr>
        <td>
        <table border=0 cellpadding=0 cellspacing=0 width=100%>
        <tr><td class=title>Edit {$pageTypePlural}</td>
        
        {if $layers or $page_id}
        <td align=right><a href="javascript:launchCentered('message.php',400,400,'scrollbars,resizable');">Did you know...?</a></td>
        {/if}
        
        </tr>
        </table>
        </td>
        </tr>
        
        {if $files ne ""}
        
		<tr><td>Depending on your server's settings, there may be a maximum upload size limit in place. For most servers, the size limit is about 1000 KB (1 MB). You may increase this by using a larger max_allowed_packet setting in your database configuration.</td></tr>

		<tr><td>&nbsp;</td></tr>
        
        {/if}
        
        {if $layers ne ""}
        
        {/if}
        
        {if $files eq "" and $layers eq ""}
        <tr>
        <td class=specialOperations>
        
            <table border=0 cellpadding=1 cellspacing=0 class=normal>
            <tr>
            {if $page_id neq ""}

                <td align=right>Switch to Page: </td>

                <td>
                <select name=switchPageId onChange="javascript:switchPage();">
                
                <option value=""> - New Page - </option>
                
                {if $numPages > 0}
                    {html_options values=$page_ids output=$page_titles selected=$page_id}
                {else}
                    <option value="">(no pages present)</option>
                {/if}
                
                </select>

                {if $numPages > 0 and $page_id ne 'NEW'}
                    <input type=submit name=deletePage onClick="return confirm('Are you sure? This will delete all data associated with this page!')" value="Delete Page">
                {/if}
                
                </td>


            {elseif $form_id neq ""}

                <td align=right>Switch to Form: </td>

                <td>
                <select name=switchFormId onChange="javascript:switchForm();">
                
                <option value=''> - New Form - </option>
                
                {if $numForms > 0}
                    {html_options values=$form_ids output=$form_titles selected=$form_id}
                {else}
                    <option value="">(no forms present)</option>
                {/if}
                
                </select>

                {if $numForms > 0 and $form_id ne 'NEW'}
                    <input type=submit name=deleteForm onClick="return confirm('Are you sure? This will delete all data associated with this form!')" value="Delete Form">
                {/if}
                
                {*
                <input type=button name=newForm onClick="document.location.href='edit.php?add_form=1'" value="New Form">
                *}
                
                </td>

            {/if}
            </tr>

            {if $page_id neq ""}
            <tr>
            <td align=right>Page Name: </td>
            <td><input type=text name=page_title size=40 value="{$pageTitle}"></td>
            </tr>
            
            <tr><td align=right valign=top>URL: </td><td>
            
            {if $page_id neq 'NEW'}
                <a target=_blank href="{$pagePath}">{$pagePath}</a>
            {else}
                (no URL is available until the page is saved)
            {/if}
            
            </td></tr>
            
            <tr>
            <td align=right><input type=checkbox name=is_default value=1 {if $isDefault}checked{/if}></td>
            <td>Make this the default page for this website. <a href="javascript:launchCentered('{$help.url}?type=default_page',{$help.width},{$help.height},'{$help.options}');">{$helpSymbol}</a>
    </td>
            </tr>
            
            <tr>
            <td align=right><input type=checkbox name=is_logout_page value=1 {if $isLogoutPage}checked{/if}></td>
            <td>Make this the content management tools logout page. <a href="javascript:launchCentered('{$help.url}?type=logout_page',{$help.width},{$help.height},'{$help.options}');">{$helpSymbol}</a>
    </td>
            </tr>
            
            <tr>
            <td align=right nowrap>Menu Linkage: </td>
            <td>
            
            {foreach item=menuData from=$menuData}
            	[ <a href="editMenu.php?currentMenuId={$menuData.menu_id}">{$menuData.title}</a> ] 
            {/foreach}
            
            {if $menuLinkageDegree eq 0}
            	<table border=0 cellpadding=0 cellspacing=0 class=normal><tr><td nowrap>
            	Not currently linked to a menu [ <a href="editMenu.php">Menu Tool</a> ]
            	</td></tr></table>
            {/if}
           
            </td>
            </tr>
            
            {* skin options... this might not be available to individual users *}
            
            <tr>
            <td align=right nowrap>Skin: </td>
            <td nowrap>
            	<select name="skin_id">
            	<option value=''>(use default skin)</option>
            	{html_options options=$skins selected=$skin_id}
            	</select>
            	
    		<a href="javascript:launchCentered('{$help.url}?type=skins',{$help.width},{$help.height},'{$help.options}');">{$helpSymbol}</a>
    
            </td>
            </tr>
            
            <tr>
            <td align=right># Views: </td>
            <td>{$counter|default:"0"}</td>
            </tr>
            
            {/if}
            
		<tr><td colspan=2><input type=button name=masterSubmit value="Submit All" onClick="javascript:submitForm();"> 
		</td></tr>
            
            </table>

        </td>
        </tr>
        {/if}

    {/if}


    {* begin form-specific tags *}
    {if $form_id ne "" and $smarty.foreach.sections.iteration eq 1}
    <tr><td class=specialOperations>
        <table cellpadding=2 cellspacing=0 border=0 class=normal>
        <tr>
            <td align=right>URL: </td>
            
            {if $form_id neq 'NEW'}
            <td colspan=3><a target=_blank href="{$formPath}">{$formPath}</a></td>
            {else}
            <td colspan=3>(no URL is available until the form is saved)</td>
            {/if}
        
        </tr>
        <tr>
            <td align=right valign=top><input type=checkbox name=is_default value=1 {if $isDefault}checked{/if}></td>
            <td colspan=3>Make this the default page for this website. <a href="javascript:launchCentered('{$help.url}?type=default_page',{$help.width},{$help.height},'{$help.options}');">{$helpSymbol}</a>
    </td>
        </tr>
        
	    <tr>
	    <td align=right valign=top><input type=checkbox name=is_login_form value=1 {if $isLoginForm}checked{/if}></td>
	    <td colspan=3>Make this the login form for the content management tools. <a href="javascript:launchCentered('{$help.url}?type=login_form',{$help.width},{$help.height},'{$help.options}');">{$helpSymbol}</a>
    </td>
	    </tr>
	    
	<tr>
		<td align=right nowrap>Menu Linkage: </td>
		<td colspan=3>

			{foreach item=menuData from=$menuData}
			[ <a href="editMenu.php?currentMenuId={$menuData.menu_id}">{$menuData.title}</a> ] 
			{/foreach}

			{if $menuLinkageDegree eq 0}
			<table border=0 cellpadding=0 cellspacing=0 class=normal><tr><td nowrap>
			Not currently linked to a menu [ <a href="editMenu.php">Menu Tool</a> ]
			</td></tr></table>
			{/if}

		</td>
	</tr>
        
            {* skin options... this might not be available to individual users *}
            {* NOTE: this is repeated in the page editor template data *}
            
            <tr>
            <td align=right nowrap>Skin: </td>
            <td nowrap colspan=3>
            	<select name="skin_id">
            	<option value=''>(use default skin)</option>
            	{html_options options=$skins selected=$skin_id}
            	</select>
            	
    		<a href="javascript:launchCentered('{$help.url}?type=skins',{$help.width},{$help.height},'{$help.options}');">{$helpSymbol}</a>
    
            </td>
            </tr>
        
        <tr>
             <td align=right>Counters: </td>
             <td colspan=3>{$numViews|default:"0"} Views / {$numSubmissions|default:"0"} Submissions</td>
        </tr>
        <tr>
            <td align=right>Title: </td>
            <td colspan=3><input size=40 type=text name=form_title value="{$form_title}">
            
            <a href="javascript:launchCentered('{$help.url}?type=form_title',{$help.width},{$help.height},'{$help.options}');">{$helpSymbol}</a>
    
            </td>
        </tr>
        <tr>
            <td align=right valign=top>Description: </td>
            <td colspan=3><textarea name=form_desc rows=3 cols=60>{$form_desc}</textarea></td>
        </tr>
        <tr>
            <td align=right>To: </td>
            <td><input size=20 type=text name=form_to value="{$form_to}"></td>
            <td align=right>CC:</td>
            <td><input size=20 type=text name=form_cc  value="{$form_cc}"></td>
            
        </tr>
        <tr>
            <td align=right>Subject: </td><td><input size=20 type=text name=form_subject value="{$form_subject}"></td><td nowrap align=right>Redirect To:</td><td nowrap>
            <select name=form_redirect>
            
            {html_options values=$redirectValues output=$redirectOutput selected=$form_redirect}
            
            
            </select>
            <a href="javascript:launchCentered('{$help.url}?type=form_redirect',{$help.width},{$help.height},'{$help.options}');">{$helpSymbol}</a>
            </td>
        </tr>
        <tr>
        <td colspan=4>
        
        <input type=button name=masterSubmit value="Submit All" onClick="javascript:submitForm();">

        </td>
        </tr>
        </table>
    </td></tr>
    <tr><td>&nbsp;</td></tr>
    {/if}
    
    <tr>
    <td class=subtitle>Add a New {$pageTypeSingular}</td>
    </tr>
{elseif $smarty.foreach.sections.iteration eq 2}
    <tr><td><hr width='100%' noshade size=5 color="#FF0000"></td></tr>
    <tr>
    <td class=subtitle>Edit Current {$pageTypePlural}</td>
    </tr>
{/if}
  
    {* form options specifically for layers *}
    {if $layers ne ""}
    <tr>
    <td>
        <table border=0 cellpadding=1 width=100% cellspacing=0 class=normal>
            <tr><td align=right>Top Offset: </td><td><input type=text size=6 name="top_{$section.id}" value="{$section.top}"></td>
                <td align=right>Left: </td><td><input type=text size=6 name="left_{$section.id}" value="{$section._left}"> 
                <a href="javascript:launchCentered('{$help.url}?type=layer_options',{$help.width},{$help.height},'{$help.options}');">{$helpSymbol}</a></td>
                <td align=right>Height: </td><td><input type=text size=6 name="height_{$section.id}" value="{$section.height}"></td>
                <td colspan=2 align=right><span class=small>override with settings from:</span></td>
            </tr>
            <tr><td align=right>Z-Order: </td><td><input type=text size=6 name="zorder_{$section.id}" value="{$section.zorder}"></td>
		<td align=right>Padding: </td><td><input type=text size=6 name="padding_{$section.id}" value="{$section.padding}"></td>
		    <td align=right>Align: </td><td>

		    <select name="align_{$section.id}">
		    {html_options values=$align output=$align selected=$section.align}
		    </select>

		    </td>
		 <td colspan=2 align=right>
		 <select name="settings_override_{$section.id}">
		 {html_options options=$layerList selected=$section.settings_override}
		 </select>
		 </td>
            </tr>
                <tr><td align=right>Width: </td><td><input type=text size=6 name="width_{$section.id}" value="{$section.width}"></td>
                    <td align=right>Color: </td><td><input type=text size=10 name="bgcolor_{$section.id}" value="{$section.bgcolor}">
                    <a href="javascript:TCP.popup(document.forms['editPage'].elements['bgcolor_{$section.id}'], 1)"><img src="{$docroot}images/color.gif" border=0></a>
                    </td>
                    <td align=right>V-align: </td><td>
                    
                    <select name="valign_{$section.id}">
                    {html_options values=$valign output=$valign selected=$section.valign}
                    </select>
                    
                    </td>
                    {* the layer title (for identification purposes only!) *}
                    <td align=right>Name:</td><td><input type=text size=10 name="title_{$section.id}" maxlength=18 value="{$section.title}"></td>
                 </tr>
        </table>
    </td>
    </tr>
    {/if}
    {* end of options specifically for layers *}
    
    
    {* form options specifically for files management *}
    {if $files ne ""}
    <tr><td>
    <table border=0 cellpadding=1 cellspacing=0 class=normal>
        <tr>
        <td nowrap align=right>File to Upload: </td>
        <td nowrap><input type=file name="file_data_{$section.id}"> 
        <a href="javascript:launchCentered('{$help.url}?type=file',{$help.width},{$help.height},'{$help.options}');">{$helpSymbol}</a>
        <input type=hidden size=30 name=file_data_path_{$section.id} value=""> {* ({$max} KB max) *}
        </td>
        </tr>
        
        <tr>
        <td nowrap align=right>Download Name: </td>
        <td><input size=30 type=text name="download_name_{$section.id}" value="{$section.download_name}"> 
        <a href="javascript:launchCentered('{$help.url}?type=download_name',{$help.width},{$help.height},'{$help.options}');">{$helpSymbol}</a>
        </td>
        </tr>
        
        {* only display the following items if we actually have a file to download *}
        {if $smarty.foreach.sections.iteration ne 1}
            <tr>
            {* this is automatically generated *}
            <td align=right>Download Link: </td>
            <td><a target=_blank href="{$objectPath}?id={$section.id}">{$objectPath}?id={$section.id}</a></td>
            </tr>

            <tr>
            <td align=right valign=top>Local File Path: </td>
            <td><span class=small><i>{$section.file_data_path|default:"(no file present)"}</span></i>
            </td>
            </tr>
            
             <tr>
             <td align=right valign=top># Downloads: </td>
             <td>{$section.counter|default:"0"}</i>
             </td>
            </tr>
        {/if}

        </table>
    
    </td>
    </tr>
    {/if}
    {* end of options specifically for file management *}
     
    
    {* options for radio button groups *}
    {if $radioGroup ne ""}
    <tr>
    <td>
        <table border=0 cellpadding=1 cellspacing=0 class=normal>
            <tr>
            <td align=right>Label: </td><td><input type=text size=15 name=label_{$section.id} value="{$section.label}"></td>
            <td align=right>Value: </td><td><input type=text size=10 name=value_{$section.id} value="{$section.value}"></td>
            </tr>
            
            <tr>
            <td align=right>Orientation: </td>
            
            <td>
            <select name=orientation_{$section.id}>
            {html_options values="$orientationValues" output="$orientationLabels" selected=$section.orientation}
            </select>
            </td>
            
            <td align=right>Selected?</td><td><input type=radio name=selectedGroupItem value={$section.id} {if $section.selected eq 1}checked{/if}></td>
            </tr>
        </table>
    </td>
    </tr>
    {/if}

    {* begin form-specific tags *}
    {if $form_id ne ""}
    
    <tr>
    <td>
        <table border=0 cellpadding=2 cellspacing=0 class=normal>
            <tr>
            <td align=right>Field Type:</td>
            <td>
            <select onChange="javascript:enableFields(document.editPage.field_type_{$section.id}, document.editPage.list_data_{$section.id}, document.editPage.radioList{$section.id}, document.editPage.field_size_{$section.id},document.editPage.validator_{$section.id});" name=field_type_{$section.id}>
            {html_options values=$fieldTypeValues output=$fieldTypeLabels selected=$section.field_type}
            </select>
            </td>
            <td align=right>Field Size:</td>
            <td><input type=text size=6 name=field_size_{$section.id} value="{if $section.field_size neq '0'}{$section.field_size}{/if}"
            
            {if $section.field_type neq 'text' and $section.field_type neq 'password' and $section.field_type neq 'textarea' and $smarty.foreach.sections.iteration neq 1}
            style='background-color: {$disabledElementColor}' disabled 
            {/if}
            
            >
            
            <a href="javascript:launchCentered('{$help.url}?type=field_size',{$help.width},{$help.height},'{$help.options}');">{$helpSymbol}</a>
    
            
            </td>
            <td align=right>Required?</td>
            <td><input type=checkbox value=1 name=required_{$section.id} {if $section.required eq 1}checked{/if}>
            <a href="javascript:launchCentered('{$help.url}?type=required_fields',{$help.width},{$help.height},'{$help.options}');">{$helpSymbol}</a>
            </td>
            </tr>
            
            <tr>
            <td align=right>Label:</td><td><input type=text size=15 name=label_{$section.id} value='{$section.label}'></td>
            <td align=right>Validator:</td><td>
            
            <select name=validator_{$section.id}
            
            {if $section.field_type neq 'select' or $smarty.foreach.sections.iteration eq 1}
            style='background-color: {$disabledElementColor}' disabled 
            {/if}
            
            >
            {html_options values=$validatorTypeValues output=$validatorTypeLabels selected=$section.validator}
            </select>
   
            </td>
            <td align=right>Error Msg:</td><td><input type=text size=15 name=err_msg_{$section.id} value="{$section.err_msg}"></td>
            </tr>
            
            <tr>
                        
            <td colspan=2 align=right>[ <a target=_blank href=listIndex.php>My Lists</a> ] Use This List: </td><td colspan=3>
            
            <select name=list_data_{$section.id}
          
            {if $section.field_type neq 'select' or $smarty.foreach.sections.iteration eq 1}
            style='background-color: {$disabledElementColor}' disabled 
            {/if}
            >
            
            <option value=""> - Choose List - </option>
            {html_options options=$listData selected=$section.list_data}
            
            </select>
            
		<a href="javascript:launchCentered('{$help.url}?type=lists',{$help.width},{$help.height},'{$help.options}');">{$helpSymbol}</a></b></td>

            
            {*
            <input type=text size=30 name=list_data_{$section.id} value="{$section.list_data}" 
            
            {if $section.field_type neq 'select' or $smarty.foreach.sections.iteration eq 1}
            style='background-color: {$disabledElementColor}' disabled 
            {/if}
            >
            *}
            
            
            </td>
            
            <td>
            <input name=radioList{$section.id} type=button 

            {if $section.field_type neq 'radio' or $smarty.foreach.sections.iteration eq 1}
            style='color: #888888' disabled 
            {/if}
            
            value="Edit Group" onClick="javascript:getEditGroup('{$section.id}');">
            
            <a href="javascript:launchCentered('{$help.url}?type=form_options',{$help.width},{$help.height},'{$help.options}');">{$helpSymbol}</a>
            </td>
            </tr>
        </table>
    </td>
    </tr>
    {/if}
    
    {if $radioGroup eq "" and $form_id eq "" and $files eq "" and $styles eq ""}
    {* beginning of non-form section *}
        {*
        <tr>
        <td>
            <table border=0 cellpadding=1 cellspacing=0 class=normal>
            <tr>
	
            <td>Style: </td>

            <td>
            <select name="style_{$section.id}">
                {html_options values=$styleList output=$styleList selected=$section.style}
            </select>
            </td>
            
            <td>
                {if $layers eq true }
                    Layer ID:
                {else}
                    Section ID: 
                {/if}
            </td>

            <td>
            <input type=text width=15 name="title_{$section.id}" value="{$section.title}"> 
            </td>

            <td>
            <a href="javascript:launchCentered('{$help.url}?type=title',{$help.width},{$help.height},'{$help.options}');">{$helpSymbol}</a>
            </td>

            </tr>
            </table>
        </td>
        </tr>
        *}
        
        <tr>
        <td>
        
            <table border=0 cellpadding=1 cellspacing=0 width=100% class=normal>
                <tr>
                <td valign=bottom nowrap>Content: {$wysiwyg|replace:"[textarea_id]":$section.id}</td>
                
		<td align=right width=50%>Format: </td>

		<td nowrap>
		<select name="format_{$section.id}">
		{html_options options=$formatList selected=$section.format}
		</select>
		
		<a href="javascript:launchCentered('{$help.url}?type=section_format',{$help.width},{$help.height},'{$help.options}');">{$helpSymbol}</a>
		
		</td>
                
                <td align=right>Style: </td>

                <td>
                <select name="style_{$section.id}">
                {html_options values=$styleList output=$styleList selected=$section.style}
                </select>
                
                </td>
                </tr>
            </table>
        </td>
        </tr>

        <tr>
        <td>
        <textarea rows=4 style='width: 100%;' name="content_{$section.id}" id="content_{$section.id}">{$section.content}</textarea>
        </td>
        </tr>

        <tr><td>

        {* a table to hold the embedded image & large image paths *}
        <table border=0 cellpadding=0 cellspacing=0><tr><td valign=top>
        
        <table border=0 cellpadding=0 cellspacing=0 class=normal>
        
		{if $layers}
			<tr>
			<td align=right nowrap valign=top width=20%>Only show this<br />layer on:<br /></td>
			<td valign=top>
			
			<table border=0 cellpadding=0 cellspacing=0><tr><td>
			<select size=3 name="restrict_to_{$section.id}[]" multiple>

			
			{html_options values=$layerRestrictValues output=$layerRestrictOutput selected=$section.restrict_to}

			</select>
			</td><td valign=top>
			
			<a href="javascript:launchCentered('{$help.url}?type=layer_restrict',{$help.width},{$help.height},'{$help.options}');">{$helpSymbol}</a>
			
			</td></tr></table>
			
			</td>
			</tr>
		{/if}
        	
            <tr>
            <td nowrap align=right>Embedded Image: </td>
            <td><input type=file name="img_thumb_{$section.id}"> 
            <a href="javascript:launchCentered('{$help.url}?type=img_thumb',{$help.width},{$help.height},'{$help.options}');">{$helpSymbol}</a>
            <input type=hidden name=img_thumb_path_{$section.id} value="">
            </td>
            </tr>

            {if $section.img_thumb_path ne ""}
                <tr>
                <td class=normal nowrap><input type=button onClick="javascript:removeImage({$section.id},'thumb')" value='Remove'> Current: </td>
                <td class=small><i>{$section.img_thumb_path}</i></td>
                </tr>
            {/if}

            <tr>
            <td nowrap align=right>Popup/Large Image: </td>
            <td><input type=file name="img_large_{$section.id}"> 
            <a href="javascript:launchCentered('{$help.url}?type=img_large',{$help.width},{$help.height},'{$help.options}');">{$helpSymbol}</a>
            <input type=hidden name=img_large_path_{$section.id} value="">
            </td>
            </tr>

            {if $section.img_large_path ne ""}
                <tr>
                <td class=normal nowrap><input type=button onClick="javascript:removeImage({$section.id},'large')" value='Remove'> Current: </td>
                <td class=small><i>{$section.img_large_path}</i></td>
                </tr>
            {/if}


            <tr>
            <td align=right>Image Link: </td>
            <td nowrap><input type=text size=40 name="img_link_{$section.id}" value="{$section.img_link}"> <a href="javascript:launchCentered('{$help.url}?type=img_link',{$help.width},{$help.height},'{$help.options}');">{$helpSymbol}</a></td>
            </tr>

            <tr>
            <td align=right>Image Anchor: </td>
            <td nowrap>
                <select name=img_anchor_{$section.id}>
                {html_options values=$anchorValues output=$anchorNames selected=$section.img_anchor}
                </select>
                <a href="javascript:launchCentered('{$help.url}?type=img_anchor',{$help.width},{$help.height},'{$help.options}');">{$helpSymbol}</a>
            </td>
            </tr>

            </table>
            
            </td><td valign=top>
            
            {if $section.img_thumb_path}
            <p class=small>
            Embedded Image:<br />
            {img table=$table field=img_thumb id=$section.id maxWidth=100 maxHeight=70}
            </p>
            {/if}
            
            {if $section.img_large_path}
            <p class=small>
            Pop-up Image:<br />
            {img table=$table field=img_large id=$section.id maxWidth=100 maxHeight=70}
            {if $section.img_thumb_path eq ''}
            <br />This image will not be viewable unless an embedded image is added.
            {/if}
            </p>
            {/if}
            
            </td></tr></table>

        </td>
        </tr>
    {/if}
    {* end of non-form section *}
    <tr>
    <td>
    
        <input type=button name=masterSubmit value="Submit All" onClick="javascript:submitForm();">
        
        <input type=reset value="Reset All">
    
        {if $smarty.foreach.sections.iteration ne 1}
            <input type=button name=delete_{$section.id} value="Delete" onClick="javascript:deleteSection({$section.id});"> 
                    
            {if $files eq "" and $layers eq "" and $smarty.foreach.sections.iteration > 2}
                <input type=button name=bump_{$section.id} value="Bump Up" onClick="javascript:bumpUpSection({$section.id});">
            {/if}
        
        {/if}
        

    </td>
    </tr>

    <tr><td><hr style='width: 100%' noshade width=1 color="#0000FF"></td></tr>

{/foreach}

    <tr>
    <td class=normal>
    {$adminReturnLink} {$logoutLink} {$userGuideLink}
    </td>
    </tr>

</form>

</table>