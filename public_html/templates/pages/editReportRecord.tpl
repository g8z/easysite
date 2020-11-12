<script language="Javascript">
<!--
function validate() {ldelim}
	var formObj = document.theForm;
	var dateFilled;
	
	// check for required fields
	{foreach name=sections item=section from=$data}
	{if $section.required}
	
	    {if $section.field_type eq 'date'}
	       dateFilled = formObj["field_name_{$section.id}_Month"].value!=0 && formObj["field_name_{$section.id}_Day"].value!=0 && formObj["field_name_{$section.id}_Year"].value!=0;
	       if ( !dateFilled ) {ldelim}
		      alert( "{$section.err_msg|default:'A required item has been left blank.'}" );
              formObj["field_name_{$section.id}_Month"].focus();
              return false;
		   {rdelim}
	    {else}
	
		if ( formObj["field_name_{$section.id}"].value == '' ) {ldelim}
			alert( "{$section.err_msg|default:'A required item has been left blank.'}" );
			formObj["field_name_{$section.id}"].focus();
			return false;
		{rdelim}
		{/if}
	{/if}

	{if $section.validator eq 'numeric'}
		
		if ( !isNumeric( formObj["field_name_{$section.id}"].value ) ) {ldelim}
			alert( "{$section.err_msg|default:'This value must be numeric.'}" );
			formObj["field_name_{$section.id}"].focus();
			formObj["field_name_{$section.id}"].select();
			return false;
		{rdelim}
		
	{elseif $section.validator eq 'alpha-numeric'}
	
		if ( !isAlphaNumeric( formObj["field_name_{$section.id}"].value ) ) {ldelim}
			alert( "{$section.err_msg|default:'This value must be alphanumeric.'}" );
			formObj["field_name_{$section.id}"].focus();
			formObj["field_name_{$section.id}"].select();
			return false;
		{rdelim}
	
	{elseif $section.validator eq 'alphabetic'}
	
		if ( !isAlphabetic( formObj["field_name_{$section.id}"].value ) ) {ldelim}
			alert( "{$section.err_msg|default:'This value must be alphabetic.'}" );
			formObj["field_name_{$section.id}"].focus();
			formObj["field_name_{$section.id}"].select();
			return false;
		{rdelim}
	
	{elseif $section.validator eq 'website'}
	
		if ( !isValidURL( formObj["field_name_{$section.id}"].value ) ) {ldelim}
			alert( "{$section.err_msg|default:'This web address contains invalid syntax.'}" );
			formObj["field_name_{$section.id}"].focus();
			formObj["field_name_{$section.id}"].select();
			return false;
		{rdelim}
	
	{elseif $section.validator eq 'email'}
	
		if ( !isValidEmail( formObj["field_name_{$section.id}"].value ) ) {ldelim}
			alert( "{$section.err_msg|default:'This e-mail address contains invalid syntax.'}" );
			formObj["field_name_{$section.id}"].focus();
			formObj["field_name_{$section.id}"].select();
			return false;
		{rdelim}

	{/if}
	
	{/foreach}
	
	
	formObj.submit();
{rdelim}

function deleteRecord() {ldelim}
    return confirm( 'Are you sure want to delete this record?' );
{rdelim}
//-->
</script>

<form action="{$action}" method=post name="theForm" enctype="multipart/form-data">

<input type=hidden name="id" value="{$reportId}">
<input type=hidden name="submission_id" value="{$submission_id}">
<input type=hidden name="user_id" value="{$user_id}">
<input type=hidden name="redirect_id" value="{$redirect_id}">
<input type=hidden name="formID" value="{$formID}">

<input type=hidden name="start" value="{$start}">
<input type=hidden name="set" value="{$set}">

<input type=hidden name=startFormFields value=1>

{* loop through all available sections, printing data for each *}
<table border=0 cellpadding="{$settings.cellpadding}" cellspacing="0" class=normal>

	<tr><td colspan=2 class=title>Edit Record</td></tr>
	<tr><td colspan=2><a href="#" onclick="javascript: document.location.href='{$backURL}';">Back to the Report</a></td></tr>
	<tr><td>&nbsp;</td></tr>

	{foreach name=sections item=section from=$data}

		<tr>
			<td width=30% align={if $section.field_type neq 'page_section'}right{else}left{/if}
			
			{if $section.field_type eq 'textarea' or $section.field_type eq 'radio' or $section.field_type eq 'checkbox'}valign=top{/if}
			
			valign="top">{$section.label}</td>
		
			<td> 
			{if $section.field_type eq 'text'}
			
				<input type=text name="field_name_{$section.id}" size="{$section.field_size}" value="{$section.value}">
			
			{elseif $section.field_type eq 'textarea'}
			
				<textarea name="field_name_{$section.id}" cols="{$section.field_cols}" rows="{$section.field_rows}">{$section.value}</textarea>
			
			{elseif $section.field_type eq 'password'}
			
				<input type=password name="field_name_{$section.id}" size="{$section.field_size}" value="{$section.value}">
			
			{elseif $section.field_type eq 'select'}
			
				{list name=field_name_`$section.id` key=$section.list_data selected=$section.value}
			
			{elseif $section.field_type eq 'checkbox'}
			
				<input type=checkbox name="field_name_{$section.id}" value="checked" {if $section.value eq 'checked'}checked{/if}>
			
		  {elseif $section.field_type eq 'modcat'}
		      <select name="field_name_{$section.id}">	
				{html_options options=$categories selected=$section.value}
			  </select>
			
		  {elseif $section.field_type eq 'page_section'}
		  
		  </td><tr><td colspan=2>
		  
                <table class=normal>
        		<tr><td class="{$section.page_section_data.style}">
        
        		{if $section.page_section_data.img_thumb ne "" and $section.page_section_data.img_thumb ne 'none'}
        			
        			{if $section.page_section_data.img_large eq "" or $section.page_section_data.img_large eq 'none'}
        			
        				{if $section.page_section_data.img_link}<a {if $section.page_section_data.img_link|replace:"javascript:":"" eq $section.page_section_data.img_link}target=_blank{/if} href="{$section.page_section_data.img_link}">{/if}<img border=0 align="{$section.page_section_data.img_anchor|default:left}" src=
        
        				"{imgsrc table=$smarty.const.SECTIONS_TABLE field=img_thumb id=$section.page_section_data.id}"
        				
        				{* image.php?id={$section.page_section_data.id}&type=page_small" *}
        				
        				{* style information for this embedded image - this is not in <style> tag because whether we choose right or left pad depends on the image anchor *}
        				
        				style="margin-bottom:{$settings.image_pad_bottom};
        				margin-top:{$settings.image_pad_top};
        				{if $section.page_section_data.img_anchor eq 'right'}margin-left:{$settings.image_pad_left};{/if}
        				{if $section.page_section_data.img_anchor eq 'left' or $section.page_section_data.img_anchor eq ''}margin-right:{$settings.image_pad_right}px;{/if}">{if $section.page_section_data.img_link}</a>{/if}
        			
        			{else}
        		
        				<a href="javascript:getLargeImage({$section.page_section_data.id});">
        				<img alt="Click to Enlarge" align="{$section.page_section_data.img_anchor|default:left}" border=0 
        				src=
        				
        				"{imgsrc table=$smarty.const.SECTIONS_TABLE field=img_thumb id=$section.page_section_data.id}"
        				
        				{* style information for this embedded image - this is not in <style> tag because
        				whether we choose right or left pad depends on the image anchor *}
        				
        				style="margin-bottom:{$settings.image_pad_bottom};
        				margin-top:{$settings.image_pad_top};
        				{if $section.page_section_data.img_anchor eq 'right'}margin-left:{$settings.image_pad_left};{/if} 
        				{if $section.page_section_data.img_anchor eq 'left' or $section.page_section_data.img_anchor eq ''}margin-right:{$settings.image_pad_right}px;{/if}">
        				</a>
        			
        			{/if}
        		{/if}
        		
        		{if $section.page_section_data.content ne ""}
        		{$section.page_section_data.content|replace:"<!--numvisitors-->":$numvisitors|replace:"<!--lastupdate-->":$lastupdate|replace:"<!--admin-->":$adminPath}
        		{/if}
        		
        		</td></tr>
        		</table>
        		
        		

		 
			{elseif $section.field_type eq 'radio'}
				
				<table border=0 cellpadding=2 cellspacing=0 class=normal>
				
					{foreach name=templist item=radios from=$section.radio_list}
					<tr>
						{if $radios.orientation eq 'left'}
							<td><input name="field_name_{$section.id}" type=radio value="{$radios.value}" 
							{if $radios.selected eq 1}checked{/if}></td>
							<td>{$radios.label}</td>
						{else}
							<td>{$radios.label}</td>
							<td><input name="field_name_{$section.id}" type=radio value="{$radios.value}" 
							{if $radios.selected eq 1}checked{/if}></td>
						{/if}
						
					</tr>
					{/foreach}
				
				</table>
			{elseif $section.field_type eq 'date'}
			    {html_select_date prefix=field_name_`$section.id`_ day_value_format="%02d" time="`$section.year`-`$section.month`-`$section.day`" start_year="-60" end_year="+10" day_extra='><option value=0 selected>Day</option' month_extra='><option value=0 selected>Month</option' year_extra='><option value=0 selected>Year</option'}
			    
			{elseif $section.field_type eq 'image'}
			
    			{if $section.blob_value}
    			Current Image ({$section.file_data_path}):<br /><br />
    			<img src="{imgsrc table=$smarty.const.FORMSUBMISSIONS_TABLE field=blob_value id=$section.unique_id}"><br />
    			<br />
    			Change Image<br />
    			{/if}
    			
			    <input type='file' size=30 name="field_name_{$section.id}">
			    
    			{if $section.blob_value}
    			<br /><br />OR<br /><br /><input type='checkbox' size=30 name="custom_remove_file_{$section.id}">Delete Image
    			{/if}
			    
			{elseif $section.field_type eq 'file'}
    			{if $section.blob_value}
    			Current File ({$section.file_data_path}):<br /><br />
    			Change File<br />
    			{/if}
    			
			    <input type='file' size=30 name="field_name_{$section.id}">
			    
    			{if $section.blob_value}
    			<br /><br />OR<br /><br /><input type='checkbox' size=30 name="custom_remove_file_{$section.id}">Delete File
    			{/if}
    			
			{/if}
			</td>
		</tr>
	{/foreach}
	
	<tr><td></td><td>

	
	<input type=hidden name=endFormFields value=1>
	<input type=button value="Save" onClick="javascript:validate()">  
	<input type=button value="Delete" onClick="javascript: if (confirm( 'Are you sure want to delete this record?' )) document.location.href='?action=deleteRecord&sub_id={$submission_id}&id={$reportId}&form_id={$formID}&start={$start}&set={$set}'">  
	<input type=reset value="Reset">
	</td></tr>

</table>

</form>
