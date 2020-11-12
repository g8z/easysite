{* special js functions for this page only *}
{literal}
<script language="Javascript">
<!--

function getEditGroup( groupId ) {
    if ( groupId == "NEW" ) {
        alert( 'Please save this form section before adding radio group data.' );
    }
    else {
        launchCentered( 'editRadioGroups.php?radioGroup=' + groupId,450,500,'scrollbars,resizable' );
    }
}

function getRedirects( formId ) {
    if ( formId == "NEW" ) {
        alert( 'Please save this form section before adding redirects.' );
    }
    else {
        document.location.href= 'editRedirects.php?formId=' + formId;
    }
}


function editReportOverrides( formId ) {
    if ( formId == "NEW" ) {
        alert( 'Please save this form section before adding report condition overrides.' );
    }
    else {
        document.location.href= 'editOverrides.php?form_id=' + formId;
    }
}

function activateElement(element) {
    element.disabled = false; 
    element.style.backgroundColor = '';
}

function deactivateElement(element) {
    element.disabled = true; 
    //element.style.backgroundColor = '{/literal}{$disabledElementColor}{literal}';
}

function enableFields( sectId, fieldType, csvValues, buttonObj, fieldSizeObj, validatorObj, requiredObj, errMsgObj ) {

    // check to see if the CSV text field should be enabled
    if ( fieldType.value == 'select' ) {
        activateElement( csvValues );
        toggleT('div_page_section_'+sectId,'h');
        toggleT('div_list_'+sectId,'s');
    }
    else if ( fieldType.value == 'page_section' ) {
        deactivateElement( csvValues );
        deactivateElement( fieldSizeObj );
        deactivateElement( validatorObj );
        toggleT('div_page_section_'+sectId,'s');
        toggleT('div_list_'+sectId,'h');
    } 
    else {
        deactivateElement( csvValues );
        toggleT('div_page_section_'+sectId,'h');
        toggleT('div_list_'+sectId,'h');
    }
    
    if ( fieldType.value == 'date' ) {
        deactivateElement( csvValues );
        deactivateElement( fieldSizeObj );
        deactivateElement( validatorObj );
    }

    if ( fieldType.value == 'image' ) {
        deactivateElement( fieldSizeObj );
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

function isValid() {
    var theForm = document.editPage;
    var elem;
    var condSelected = true;
            
    if ( eval( theForm.form_title ) ) {
        if ( theForm.form_title.value.trim() == '' ) {
            alert( 'Please input a title for this form.' );
            return false;
        }
    }

    return true;
}



function switchForm() {
    document.editPage.form_id.value = document.editPage.switchFormId.value;
    
    if ( document.editPage.form_id.value == '' ) 
    	document.location.href='editForms.php?add_form=1';
    else
    	document.editPage.submit();
}

function submitForm() {
    // determine paths for all image uploads
    if ( !isValid() )
        return false;
    
    document.editPage.formIsSubmitted.value = 1;
    
    // submit the form
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

function testSearchForm() {
	var checkObj = document.editPage.is_search_form;
	var buttonObj = document.editPage.overrides;


	if ( checkObj.checked ) {
		buttonObj.disabled = false;
		buttonObj.style.color = '#000000';

	}
	else {
		buttonObj.disabled = true;
		buttonObj.style.color = '#888888';
	}
}

function testCond( combo ) {
	/*
	var theForm = document.editPage;
	theForm.add_cond.disabled = ( obj.value != 'condition-0' );
	
	if ( theForm.add_cond.disabled ) {
	
	}
	*/

	var buttonObj = document.editPage.add_cond;


	if ( combo.value == 'condition-0' ) {
		buttonObj.disabled = false;
		buttonObj.style.color = '#000000';

	}
	else {
		buttonObj.disabled = true;
		buttonObj.style.color = '#888888';
	}
	
	
	
    var url = combo.options[combo.selectedIndex].value.match(/^url$/);    

    if ( url ) {
        var i=0;
        var index = combo.options.length;
        var oldURL = '';
        
        for ( i =0; i < combo.options.length; i++) {
            if ( combo.options[i].value.match(/^(url)(.+)$/) ) {
                index = i;
                
            oldURL = RegExp.$1;
            break;
        }
    }
    
    var promptText = '';
    var urltype = '';
    var urltype2 = '';
    
    if ( url ) {
        promptText = "You have requested to link this menu item to a URL. Please specify the full URL path here (http://...):";
        
        urltype = 'url';
        urltype2 = 'url';
    }
    
    // action cancelled by user
    if ( url == null )
        return;
        
    var url = prompt( promptText, oldURL );    

    while( url != null && !isValidURL( url ) && !isValidEmail( url ) ) {
        url = prompt( "The URL you have specified contains invalid syntax. Please try again.", url );
    }

    if ( url != null ) {
        if (index == combo.options.length)
            combo.options.length++;
            
        var title = (url.length > 25) ? url.substr( 0, 22 ) + '...' : url;    
        combo.options[index] = new Option( urltype + ' - ' + title, urltype2 +'-'+ url );
        combo.options.selectedIndex = index;
    }
    else
        combo.options.selectedIndex = 0;
    }
}

//-->
</script>
{/literal}



{* loop through all available sections, printing data for each *}
<form action=editForms.php method="POST" enctype="multipart/form-data" name="editPage">
<input type=hidden name=form_id value="{$form_id}">
<input type=hidden name=bumpUpSectionVar value="">
<input type=hidden name=deleteSectionVar value="">
<input type=hidden name=radioGroup value="{$radioGroup}">
<input type=hidden name=formIsSubmitted value="">
<input type=hidden name=site_key value="{$site_key}">
<table border=0 cellpadding=0 cellspacing=3 width=100% class=normal>

{foreach name=sections item=section from=$data}

{if $smarty.foreach.sections.iteration eq 1}

    <tr><td class=normal>{$logoutLink} {$pathway}</td></tr>
    
    <tr><td>&nbsp;</td></tr>

    <tr>
    <td>
    <table border=0 cellpadding=0 cellspacing=0 width=100%>
    <tr><td class=title>Edit Forms Sections</td>

    </tr>
    </table>
    </td>
    </tr>
    <tr>
    <td class=specialOperations>

        <table border=0 cellpadding=1 cellspacing=0 class=normal>
        <tr>

        <td align=right>Switch to Form: </td>

    <td>
    
    <select name=switchFormId id="switchFormId" onChange="javascript:switchForm();">
    
    {if $permissions.add}
    <option value=''> - New Form - </option>
    {/if}
    
    {if $numForms > 0}
        {if $permissions.edit or $permissions.delete}
        {html_options values=$form_ids output=$form_titles selected=$form_id}
        {/if}
    {else}
        <option value="">(no forms present)</option>
    {/if}
    
    </select>

    {if $numForms > 0 and $form_id ne 'NEW' and $permissions.delete}
        <input type=submit name=deleteForm onClick="return confirm('Are you sure? This will delete all data associated with this form!')" value="Delete Form">
        <input type=button name=deleteForm onClick="document.location.href= 'editSettings.php?formID={$form_id}';" value="Edit Settings">
    {/if}
    
    </td>

<!--	<tr><td colspan=2><input type=button name=masterSubmit value="Submit All" onClick="javascript:submitForm();"> 
	</td></tr>
-->

		<tr><td colspan="2">&nbsp;</td></tr>
        </table>

    </td>
    </tr>

    
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
        {*
        <tr>
            <td align=right valign=top><input type=checkbox name=is_default value=1 {if $isDefault}checked{/if}></td>
            <td colspan=3>Make this the default page for this website. <a href="javascript:launchCentered('{$help.url}?type=default_page',{$help.width},{$help.height},'{$help.options}');">{$helpSymbol}</a></td>
        </tr>
        
	    <tr>
	    <td align=right valign=top><input type=checkbox name=generate_report value=1 {if $generate_report}checked{/if}></td>
	    <td colspan=3>Generate submission reports for this form. {if $form_id neq 'NEW'}[ 
	    <a target=_blank href="viewReport.php?form_id={$form_id}">View Report</a>
	     ]{/if} <a href="javascript:launchCentered('{$help.url}?type=submission_report',{$help.width},{$help.height},'{$help.options}');">{$helpSymbol}</a></td>
	    </tr>
	    
	    <tr>
	    <td align=right valign=top><input type=checkbox name=is_login_form value=1 {if $isLoginForm}checked{/if}></td>
	    <td colspan=3>Make this the login form for the content management tools. <a href="javascript:launchCentered('{$help.url}?type=login_form',{$help.width},{$help.height},'{$help.options}');">{$helpSymbol}</a></td>
	    </tr>
	    *}

	    <tr>
	    <td align=right valign=top><input type=checkbox name=is_search_form value=1 {if $isSearchForm}checked{/if} onclick="javascript: testSearchForm();"></td>
	    <td colspan=3>Use as a search form for this report: <select name="search_report_id">{html_options output=$reportTitles values=$reportValues selected=$searchReportId}</select>  <a href="javascript:launchCentered('{$help.url}?type=report_search_forms',{$help.width},{$help.height},'{$help.options}');">{$helpSymbol}</a><br />
	    <input type=button name=overrides value="Report Filter Overrides" onclick="javascript: editReportOverrides( '{$form_id}' )"  {if $isSearchForm neq 1}disabled style="color:#888888;"{/if}></td>
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
            
<!--            <tr>
            <td align=right nowrap>Skin: </td>
            <td nowrap colspan=3>
            	<select name="skin_id">
            	<option value=''>(use default skin)</option>
            	{html_options options=$skins selected=$skin_id}
            	</select>
            	
    		<a href="javascript:launchCentered('{$help.url}?type=skins',{$help.width},{$help.height},'{$help.options}');">{$helpSymbol}</a>
    
            </td>
            </tr>-->
        
        <tr>
             <td align=right>Counters: </td>
             <td colspan=3>{$numViews|default:"0"} Views / {$numSubmissions|default:"0"} Submissions</td>
        </tr>
        
        {*
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
        *}
        {*
        <tr>
            <td align=right>To: </td>
            <td><input size=20 type=text name=form_to value="{$form_to}"></td>
            <td align=right>CC:</td>
            <td><input size=20 type=text name=form_cc  value="{$form_cc}"></td>
            
        </tr>*}
        <tr>
        	{*<td align=right>Subject: </td><td><input size=20 type=text name=form_subject value="{$form_subject}"></td>*}
            <td nowrap align=right>Redirect To:</td><td nowrap colspan=3>
            <select name=form_redirect onChange='javascript:testCond(this);'>
            
            {html_options values=$redirectValues output=$redirectOutput selected=$form_redirect}
                        
            </select>
            <a href="javascript:launchCentered('{$help.url}?type=form_redirect',{$help.width},{$help.height},'{$help.options}');">{$helpSymbol}</a>
            </td>
        </tr>
         
        <tr><td>&nbsp;</td>
            <td colspan=4>
            <input type=button {if $form_redirect ne 'condition-0'}disabled style="color:#888888;"{/if} name=add_cond value='Edit Conditional Redirects' onClick="javascript:getRedirects('{$form_id}');">
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
    
    <tr>
    <td class=subtitle>Add a New Form Section</td>
    </tr>

    {elseif $smarty.foreach.sections.iteration eq 2}
    <tr><td><hr width='100%' noshade size=5 color="#FF0000"></td></tr>
    <tr>
    <td class=subtitle>Edit Current Form Sections</td>
    </tr>
    {/if}
     



    <tr>
    <td>
        <table border=0 cellpadding=2 cellspacing=0 class=normal>
            <tr>
            <td align=right>Field Type:</td>
            <td>
            <select onChange="javascript:enableFields('{$section.id}', document.editPage.field_type_{$section.id}, document.editPage.list_data_{$section.id}, document.editPage.radioList{$section.id}, document.editPage.field_size_{$section.id},document.editPage.validator_{$section.id}, document.editPage.required_{$section.id}, document.editPage.err_msg_{$section.id});" name=field_type_{$section.id}>
            {html_options values=$fieldTypeValues output=$fieldTypeLabels selected=$section.field_type}
            </select>
            </td>
            <td align=right>Field Size:</td>
            <td><input type=text size=6 name=field_size_{$section.id} value="{if $section.field_size neq '0'}{$section.field_size}{/if}"
            
            {if $section.field_type neq 'text' and $section.field_type neq 'password' and $section.field_type neq 'textarea' and $smarty.foreach.sections.iteration neq 1}
            disabled 
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
            
            {if $section.field_type neq 'text' and $section.field_type neq 'textarea' and $section.field_type neq 'password' and $smarty.foreach.sections.iteration neq 1}
            disabled 
            {/if}
            
            >
            {html_options values=$validatorTypeValues output=$validatorTypeLabels selected=$section.validator}
            </select>
   
            </td>
            <td align=right>Error Msg:</td>
            <td><input type=text size=15 name=err_msg_{$section.id} value="{$section.err_msg}">
            </td>
            </tr>
            
            <tr>
                        
            <td colspan=5 align=left valign=top>
            
    		
    		<div id="div_page_section_{$section.id}" style="visibility:{if $section.field_type eq 'page_section'}visible{else}hidden{/if};position:absolute">
    		{* Page Section: *}
    		<select name="page_section_{$section.id}">{html_options output=$pageSectionsOutput values=$pageSectionsValues selected=$section.page_section}</select>
    		</div>

    		
    		<div id="div_list_{$section.id}" style="visibility:{if $section.field_type eq 'select'}visible{else}hidden{/if};position:absolute">
            [ <a target=_blank href=listIndex.php>My Lists</a> ] Use This List: 
            
            <select name=list_data_{$section.id}
          
            {if $section.field_type neq 'select' or $smarty.foreach.sections.iteration eq 1}
            disabled 
            {/if}
            >
            
            <option value=""> - Choose List - </option>
            {html_options options=$listData selected=$section.list_data}
            
            </select>
            
    		<a href="javascript:launchCentered('{$help.url}?type=lists',{$help.width},{$help.height},'{$help.options}');">{$helpSymbol}</a></b></td>
    		</div>


            
            
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

    
    <tr>
    <td>
    
        <input type=submit name={if $smarty.foreach.sections.iteration ne 1}masterSubmit{else}addNewItem{/if} value="Submit All" onClick="javascript:submitForm();">
        
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
    {$logoutLink} {$pathway}
    </td>
    </tr>

</table>    

</form>