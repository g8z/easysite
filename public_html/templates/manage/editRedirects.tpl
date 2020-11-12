{* special js functions for this page only *}
{literal}
<script language="Javascript">
<!--

function doBumpUpSection(id) {
	document.editPage.bumpUpSectionVar.value = id;
	submitForm();
}

function submitForm() {
    var theForm = document.editPage;
    
    if ( theForm.default_redirect.value == '' ) {
    	alert( 'Please specify a default redirect page or form.' );
    	return false;
    }
         
    theForm.formIsSubmitted.value = 1;
    
    // submit the form
    theForm.submit();
}

function doDeleteSection(id) {
    if ( confirm( 'Do you really want to delete this condition?' ) ) {
        document.editPage.deleteSectionVar.value = id;
        submitForm();
    }
}

{/literal}
{foreach name=sections item=field from=$fields}
fieldValue{$field.id} = new Array ( {$field.values} );
fieldTitle{$field.id} = new Array ( {$field.titles} );
{/foreach}
{literal}



function setConditionValues( condVal, condId, selCond ) {
    var theForm = document.editPage;
    var opt;
    var item;
    var options = eval( 'theForm.condition_'+condId+'.options' );
    
    options.length = 0;
    for ( var i=0; i<condVal.length; i++ ) {
        opt = new Option( condVal[i], condVal[i] );
        
        options.length++;
        options[i] = opt;

        if ( condVal[i] == selCond ) {
            options[i].selected = true;
        }
            
    }

} 

function activateSelect( condId, fieldId, defaultValue ) {
    var theForm = document.editPage;
    
    toggleT('div_date_'+condId,'h');
    toggleT('div_text_'+condId,'h');
    toggleT('div_multiple_'+condId,'s');
    
    setFieldValues( eval( 'fieldValue'+fieldId ), eval( 'fieldTitle'+fieldId ), condId, defaultValue );
}

function activateEdit( condId, value ) {
    var theForm = document.editPage;
    toggleT('div_date_'+condId,'h');
    toggleT('div_text_'+condId,'s');
    toggleT('div_multiple_'+condId,'h');
}

function activateDate( condId ) {
    var theForm = document.editPage;
    toggleT('div_date_'+condId,'s');
    toggleT('div_text_'+condId,'h');
    toggleT('div_multiple_'+condId,'h');
    
    var date_value = eval( 'theForm.date_value_'+condId+'.value' );
    
    var d = date_value.split( '-' );
    if ( !d[0] ) d[0] = 1;
    if ( !d[1] ) d[1] = 1;
    if ( !d[2] ) d[2] = 1;
    
    
    var objMonth = eval( 'theForm.Date_'+condId+'_Month' );
    var objDay = eval( 'theForm.Date_'+condId+'_Day' );
    var objYear = eval( 'theForm.Date_'+condId+'_Year' );
    
    for ( var i=0; i<objYear.length; i++ ) {
        if ( objYear.options[i].value == d[0] )
            objYear.options[i].selected = true;
    }
    
    objMonth.options[d[1]-1].selected = true;
    objDay.options[d[2]-1].selected = true;

    setDateValues( condId );
} 

function setDateValues( condId ) {
    var theForm = document.editPage;
    var objMonth = eval( 'theForm.Date_'+condId+'_Month' );
    var objDay = eval( 'theForm.Date_'+condId+'_Day' );
    var objYear = eval( 'theForm.Date_'+condId+'_Year' );
    value = objYear.value+'-'+objMonth.value+'-'+objDay.value;
    
    eval( 'theForm.date_value_'+condId+'.value = \'' + value + '\'' );

} 

function changeValue( condId, selCond, defaultValue ) {

    var theForm = document.editPage;    
    var fieldId = eval( 'theForm.section_id_'+condId+'.value' );
    var checkConds = new Array( 'checked', 'unchecked' );
    var commonConds = new Array( '>', '<', '=', '!=', '>=', '<=' );
    var stringConds = new Array( '>', '<', '=', '!=', '>=', '<=', 'starts with', 'contains', 'ends with' ); 
            
    var fieldTypes = new Array();
    {/literal}

    {foreach name=sections item=field from=$fieldTypes}
    fieldTypes['{$field.id}'] = '{$field.field_type}';
    {/foreach}
    
    {literal}
    
    if ( fieldTypes[fieldId] ) {
    if ( fieldTypes[fieldId] == 'checkbox' ) {
        toggleT('div_date_'+condId,'h');
        toggleT('div_text_'+condId,'h');
        toggleT('div_multiple_'+condId,'h');
        setConditionValues( checkConds, condId, selCond );         
    }
    else if ( fieldTypes[fieldId] == 'radio' || fieldTypes[fieldId] == 'select' || fieldTypes[fieldId].match(/^modcat/) ) {
                
	    activateSelect( condId, fieldId, defaultValue );
        setConditionValues( commonConds, condId, selCond ); 

    }
    else if ( fieldTypes[fieldId] == 'date' ) {
    	activateDate( condId );
        setConditionValues( commonConds, condId, selCond );     	
    }
    else {
	    activateEdit( condId, defaultValue );
        setConditionValues( stringConds, condId, selCond ); 	    
    }
  }	   
}

function setFieldValues( condVal, condTitle, condId, selCond ) {
    var theForm = document.editPage;
    var opt;
    var item;
    
    var options = eval( 'theForm.list_values_'+condId+'.options' );
    
    options.length = 0;
    
    for ( var i = 0; i < condVal.length; i++ ) {
        opt = new Option( condTitle[i], condVal[i] );

        options.length++;
        options[i] = opt;

        if ( condVal[i] == selCond ) {
            options[i].selected = true;
        } 
    }
}

function testURL( combo ) {
	
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
<form action=editRedirects.php method="POST" enctype="multipart/form-data" name="editPage">
<input type=hidden name=deleteSectionVar value="">
<input type=hidden name=bumpUpSectionVar value="">
<input type=hidden name=formId value="{$smarty.request.formId}">
<input type=hidden name=formIsSubmitted value="">
<table border=0 cellpadding=0 cellspacing=3 width=100% class=normal>

{foreach name=sections item=section from=$data}

{if $smarty.foreach.sections.iteration eq 1}

    <tr><td class=normal>{$logoutLink} {$pathway}</td></tr>
    
    <tr><td>&nbsp;</td></tr>
    <tr><td class=title>Conditional Redirects</td></tr>
    <tr><td class=subtitle>for the form called "{$formName}"</td></tr>
    <tr><td>When the form is submitted, you may embed some logic in its redirect behavior. For example, you may specify that the form redirects to the Page 1 if one condition is satisfied, or to Page 2 if another condition is satisfied. You may also redirect to other forms, to create multi-step forms. Conditions are applied in the order shown.</td></tr>
   
    <tr><td>&nbsp;</td></tr>
    <tr><td class=subtitle>Default Redirect</td></tr>
    <tr><td class=normal>
	    <table border=0 cellpadding=1 cellspacing=0 class=normal>
	    <tr>
	    <td nowrap>
	    If none of these special conditions are satified,<br />
	    which page or form should the user be sent to?
	    </td>
	    <td>&nbsp;</td>
	    <td>
	    <select name=default_redirect onchange="javascript: testURL(this);">
	    <option value=''> - Select One - </option>
	    {html_options values=$redirectValues output=$redirectOutput selected=$redDefault}
	    </select>
	    </td>
	    </tr>
	    </table>
    </td></tr>
    
  
    <tr><td>&nbsp;</td></tr>
    <tr>
    <td class=subtitle>Add a New Condition</td>
    </tr>

    {elseif $smarty.foreach.sections.iteration eq 2}
    <tr><td><hr width='100%' noshade size=5 color="#FF0000"></td></tr>
    <tr>
    <td class=subtitle>Edit Current Conditions</td>
    </tr>
    {/if}

    <tr>
    <td>
        <table border=0 cellpadding=1 cellspacing=0 class=normal>
            {*
            <tr>
            <td>Field </td><td>Operator</td><td valign=top>Value</td>
            </tr>
            *}
            <tr><td valign=top>

            <select name=section_id_{$section.id} onchange="javascript:changeValue( '{$section.id}', '{$section.condition}', '{$section.value}' );">
            
            {html_options output=$fieldTitles values=$fieldIds selected=$section.section_id}
            
            </select>
            
            </td><td valign=top>
            
            <select name=condition_{$section.id}>
            {html_options output=$conditions values=$conditions selected=$section.condition}
            </select>
            
            </td>
            
            <td valign=top width=300 height="50">
                      
            <div id="div_text_{$section.id}" style="visibility:visible;position:absolute">
            <input type=text name=value_{$section.id} value="{$section.value}" size=30><br />
            <input type=checkbox name="case_sensitive_{$section.id}" {if $section.case_sen}checked{/if} value=1> Case sensitive
            </div>
            
            <div id="div_date_{$section.id}" style="visibility:hidden;position:absolute">
    	    {html_select_date start_year="-60" end_year="+10" time=0000-00-00 prefix="Date_`$section.id`_" all_extra="onchange=\"javascript: setDateValues( '`$section.id`' )\""}
    	    </div>
            
            <div id="div_multiple_{$section.id}" style="visibility:hidden;position:absolute">
            <select name="list_values_{$section.id}">
            </select>
            </div>
            
            <input type=hidden name="date_value_{$section.id}" value='{$section.date_value}'>
            
            </td>

            </tr>
            <tr>
            
            	<td colspan=2 nowrap align=right>If this condition is true, redirect to: </td>
            	<td>
           	
            	<select name="redirect_{$section.id}" onchange="javascript: testURL(this);">
            	{html_options values=$redirectValues output=$redirectOutput selected=$section.redirect}
            	</select></td>
            	
            </tr>
        </table>
    </td>
    </tr>

    <tr> 
    <td>
    
        <input type=submit name={if $smarty.foreach.sections.iteration ne 1}masterSubmit{else}addNewItem{/if} value="Submit All" onClick="javascript:return submitForm();">
        
        {if $smarty.foreach.sections.iteration ne 1}
            <input type=button name=delete_{$section.id} value="Delete" onClick="javascript:doDeleteSection({$section.id});"> 
            
            <input type=button name=bumpup_{$section.id} value="Bump Up" onClick="javascript:doBumpUpSection({$section.id});">
        {/if}        
        
        <input type=reset value="Reset All">
        


    </td>
    </tr>

    <tr><td><hr style='width: 100%' noshade width=1 color="#0000FF"></td></tr>

{/foreach}

    <script language="javascript">
    
    {foreach name=sections item=section from=$data}    
    changeValue( '{$section.id}', '{$section.condition}', '{$section.value}' );
    {/foreach}
    </script>


    <tr>
    <td class=normal>
    {$logoutLink} {$pathway}
    </td>
    </tr>

</form>

</table>