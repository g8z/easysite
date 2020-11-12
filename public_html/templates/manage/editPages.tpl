{* special js functions for this page only *}
{literal}
<script language="Javascript">
<!--

// path to HTMLArea (for wysiwyg editing)
var _editor_url = 'htmlarea/';

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
    	document.location.href='editPages.php';
    else
    	document.editPage.submit();
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
    return true;
}

// the following object & function are for wysiwyg editing, if enabled
var wysiwygFields = new Object();
var allReplaced = false;

function enableWYSIWYG( obj, replaceAll ) {
	
	// obj is the id of the object (a string, not an object type)
	
	obj.match( /[^_]_(.*)/ );
	var id=RegExp.$1;

	toggleT('div_var_'+id,'h');
	
	if ( replaceAll && allReplaced ) {
		alert( 'The editor has already been enabled for all sections.' );
	}
	else if ( eval( wysiwygFields[ obj ] ) && !replaceAll ) {
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


function insertInternalVaraiable( sectionId ) {
    
    var field = eval( 'document.editPage.content_'+sectionId );
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
//-->
</script>
{/literal}

{* only load wysiwyg editor code if wysiwyg editing is allowed
in the site settings AND we are using a wysiwyg-compatible browser *}

{if $settings.wysiwyg eq 'yes' and $wysiwygCompatible}

    {assign var=wysiwyg value=$wysiwygLink}

    <script type="text/javascript" src="{$docroot}{$smarty.const.ADMIN_DIR}/htmlarea/htmlarea.js"></script>
    <script type="text/javascript" src="{$docroot}{$smarty.const.ADMIN_DIR}/htmlarea/easysite_config.js"></script>
	<script>HTMLArea.loadPlugin("CSS");</script>
{/if}


{* loop through all available sections, printing data for each *}
<form action=editPages.php method="POST" enctype="multipart/form-data" name="editPage">

<input type=hidden name=deleteThumbImg value="">
<input type=hidden name=deleteLargeImg value="">
<input type=hidden name=bumpUpSectionVar value="">
<input type=hidden name=deleteSectionVar value="">
<input type=hidden name=page_id value="{$page_id}">
<input type=hidden name=site_key value="{$site_key}">
<input type=hidden name=addNewItem value="">
<input type=hidden name=formIsSubmitted value="">


<table border=0 cellpadding=0 cellspacing=3 class=normal>


    <tr><td class=normal>{$logoutLink} {$pathway}</td></tr>
    
    <tr><td>&nbsp;</td></tr>

        <tr>
        <td>
        <table border=0 cellpadding=0 cellspacing=0 width=100% class=normal>
        <tr><td class=title>Edit Pages</td>
        <td align=right><a href="javascript:launchCentered('message.php',400,400,'scrollbars,resizable');">Did you know...?</a></td>
        </tr>
        </table>
        </td>
        </tr>
        
        {if $pageKeyNotUnique eq 1}<tr><td style="color:red">Page key you entered is not unique, it was not updated.</td></tr>{/if}
        
        <tr>
        <td class=specialOperations>
        
            <table border=0 cellpadding=1 cellspacing=0 class=normal>
            <tr>

                <td align=right>Switch to Page: </td>

                <td>
                
                <select name=switchPageId onChange="javascript:switchPage();">
                
                {if $permissions.add}
                <option value=""> - New Page - </option>
                {/if}
                
                {if $numPages > 0}
                    {if $permissions.edit or $permissions.delete}
                    {html_options values=$page_ids output=$page_titles selected=$page_id}
                    {/if}
                {else}
                    <option value="">(no pages present)</option>
                {/if}
                
                </select>

                {if $numPages > 0 and $page_id neq '' and $permissions.delete}
                    <input type=submit name=deletePage onClick="return confirm('Are you sure? This will delete all data associated with this page!')" value="Delete Page">
                {/if}
                
                </td>
            </tr>
            
            <tr>
            <td align=right>Page Title: </td>
            <td><input type=text name=page_title size=40 value="{$pageTitle}"></td>
            </tr>
            
            {*
            <tr>
            <td align=right>Page Key: </td>
            <td><input type=text name=page_key size=15 value="{$pageKey}">  (optional)  <a href="javascript:launchCentered('{$help.url}?type=page_keys',{$help.width},{$help.height},'{$help.options}');">{$helpSymbol}</a></td>
            </tr>
            *}
            
            <tr><td align=right valign=top>URL: </td><td>
            
            {if $page_id neq ''}
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
            
            <tr>
              <td align="right">Meta Keywords:</td>
              <td><input type="text" name="meta_keywords" value="{$meta_keywords}" size="40"></td>
            </tr>
            <tr>
              <td valign="top">Meta Description:</td>
              <td><textarea name="meta_desc" cols="60" rows="3">{$meta_desc}</textarea></td>
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
            
		<tr><td colspan=2><input type=button name=masterSubmit value="Submit All" onClick="javascript:submitForm();"> 
		</td></tr>
            
        </table>

        </td>
        </tr>

    

{foreach name=sections item=section from=$data}

{* if first iteration, then display the 'Add' title *}

{if $smarty.foreach.sections.iteration eq 1}

    <tr>
    <td class=subtitle>Add a New Page Section</td>
    </tr>
    
{elseif $smarty.foreach.sections.iteration eq 2}
    <tr><td><hr noshade size=5 color="#FF0000"></td></tr>
    <tr>
    <td class=subtitle>Edit Current Page Sections</td>
    </tr>
{/if}
        <tr>
        <td>
        
	        <table border=0 cellpadding=1 cellspacing=0 width=100% class=normal>
	            <tr>
	            <td valign=bottom nowrap width=100%>Content: {$wysiwyg|replace:"[textarea_id]":$section.id}&nbsp;&nbsp;&nbsp;<span id="div_var_{$section.id}" style="visibility:visible;padding:0px"><a href="javascript: insertInternalVaraiable( '{$section.id}' );">Insert Variable...</a></span></td>
 
			<td align=right>Format: </td>
 
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
        <textarea style='width: {$settings.textarea_width|default:"600"}px; height: {$settings.textarea_height|default:"100"}px' name="content_{$section.id}" id="content_{$section.id}">{$section.content}</textarea>
        </td>
        </tr>

        <tr><td>

        {* a table to hold the embedded image & large image paths *}
        <table border=0 cellpadding=0 cellspacing=0><tr><td valign=top>
        
        <table border=0 cellpadding=1 cellspacing=0 class=normal>
        
            <tr>
            <td nowrap align=right>New Line Behavior: </td>
            <td><select name="nl2br_{$section.id}">
		    {html_options options=$newLineCombo selected=$section.nl2br}
		    </select>  <a href="javascript:launchCentered('{$help.url}?type=new_line_behavior',{$help.width},{$help.height},'{$help.options}');">{$helpSymbol}</a>
		    </td>
            </tr>
            
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
            <td align=right>Link Target: </td>
            <td nowrap><select name="link_target_{$section.id}">{html_options options=$linkOptions selected=$section.link_target}</select></td>
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

            <tr>
            <td align=right>Image ALT: </td>
            <td nowrap><input type=text size=40 name="img_alt_{$section.id}" value="{$section.img_alt}"></td>
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

    <tr><td class=normal>{$logoutLink} {$pathway}</td></tr>

</table>                

</form>
