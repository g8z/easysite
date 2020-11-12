<script type="text/javascript">
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
	
	{* if this is a login form, then copy the values of fields 1 & 2 into the hidden username and password vars *}
	
	var elements = formObj.elements;
	var numElements = elements.length;
/*	for( var i = 0; i < numElements; i++ ) {ldelim}
	   if ( elements[i].type == 'checkbox' ) {ldelim}
	       if ( elements[i].checked == true ) 
	           elements[i].value = 'checked'
	       else {ldelim}
	           elements[i].checked = true;
	           elements[i].value = 'unchecked';
	       {rdelim}
	   {rdelim}
	{rdelim}*/

	{if $isLoginForm}
	
	for( var i = 0; i < numElements; i++ ) {ldelim}
		if ( elements[i].name == 'startFormFields' ) {ldelim}
			formObj.username.value = formObj.elements[i + 1].value;
			formObj.password.value = formObj.elements[i + 2].value;
			break;
		{rdelim}
	{rdelim}

	{/if}
	
	
	formObj.submit();
{rdelim}
//-->
</script>

{* some special server-side checks for the login form *}

{if $isLoginForm}

	{if $login_error eq $smarty.const.LOGIN_NOT_FOUND}

		<p class=normal>
		The username / password combination that you entered could not be found.
		<br />
		<br />
		Please check to ensure that both the username and password are spelled correctly, and that you are accessing a page that you have the necessary permissions to access. Also, be sure to check that your CAPS LOCK and NUM LOCK keys are not activated.
		</font>
		</p>

	{* the login was found, but it does not give access to this part of the website *}

	{elseif $login_error eq $smarty.const.ACCESS_DENIED}

		<p class=normal>
		{if $smarty.post.username || $smarty.session.es_auth.login_id}
		  The username 

		  {if $smarty.post.username}
			  "{$smarty.post.username}"
		  {else}
			  "{$smarty.session.es_auth.login_id}"
		  {/if}

		  does not provide access to this section of the website.
		  <br />
		  <br />
		  If you believe that you should have access to this area, please contact your system administrator to grant access.
		{else}
		  You have not access to this area.
		{/if}
		</p>

	{elseif $login_error eq $smarty.const.LOGIN_EXPIRED}

		<p class=normal>
		The username 

		{if $smarty.post.username}
			"{$smarty.post.username}"
		{else}
			"{$smarty.session.es_auth.login_id}"
		{/if}

		was found, but it has expired. To renew this login name & password, please contact your system administrator.
		</p>
	{/if}
{/if}


<form {if !$isLoginForm}action="{$action}"{else}action=""{/if} method=post name="theForm" enctype="multipart/form-data">

{if $isLoginForm}
	<input type=hidden name=username value="">
	<input type=hidden name=password value="">
	<input type=hidden name=es_login value=1>
{elseif $poll}
	{* the unique id of the poll *}
	<input type=hidden name=poll value="{$poll}">
{/if}

<input type=hidden name=form_redirect value="{$form_redirect}">
<input type=hidden name=first_form value="{$first_form}">
<input type=hidden name=form_to value="{$form_to}">
<input type=hidden name=form_cc value="{$form_cc}">
<input type=hidden name=form_bcc value="{$form_bcc}">
<input type=hidden name=form_subject value="{$form_subject}">
<input type=hidden name="submission_id" value="{$submission_id}">
<input type=hidden name="redirect_id" value="{$redirect_id}">

<input type=hidden name=startFormFields value=1>

{* loop through all available sections, printing data for each *}
<table border=0 cellpadding="{if !$settings.cellpadding}0{else}$settings.cellpadding{/if}" cellspacing="0" class=normal width="100%">

	<tr><td colspan=2 class="{$formSettings.title_style|default:'title'}" align="{$formSettings.title_align|default:'left'}">{$form_title|nl2br}</td></tr>
	<tr><td colspan=2>{$form_desc|nl2br}</td></tr>
	<tr><td colspan="2">&nbsp;</td></tr>
	
	<tr><td colspan="2" align='{$formSettings.form_alignment|default:"center"}' width="100%">
	<table class=normal cellpadding="{if !$settings.cellpadding}0{else}$settings.cellpadding{/if}" width="100%" border=0>

	{foreach name=sections item=section from=$data}

		<tr>
			<td align={if $section.field_type neq 'page_section'}{$formSettings.labels_alignment|default:"right"}{else}left{/if}
			
			{if $section.field_type eq 'textarea' or $section.field_type eq 'radio' or $section.field_type eq 'checkbox'}valign=top{/if}
			
			{if $formSettings.full_textarea eq 'yes' and  $section.field_type eq 'textarea'}
				colspan="2"
			{elseif $formSettings.labels_width}
				width="{$formSettings.labels_width}%"
			{/if}
			{if $formSettings.wrap_labels eq 'no'}nowrap{/if}
			>{$section.label}</td>
		
			{if $formSettings.full_textarea eq 'yes' and  $section.field_type eq 'textarea'}
			</td></tr>
			{else}
			<td> 
			{/if}
			
			{if $section.field_type eq 'text'}
			
				<input type=text name="field_name_{$section.id}" size="{$section.field_size}" value="">
			
			{elseif $section.field_type eq 'textarea'}
			
				{if $formSettings.full_textarea eq 'yes'}
				<tr><td colspan=2>
				{/if}
				
				<textarea name="field_name_{$section.id}" {if $formSettings.full_textarea eq 'yes'}style='width: 100%'{else}cols="{$section.field_cols}"{/if} rows="{$section.field_rows}"></textarea>
			
			{elseif $section.field_type eq 'password'}
				<input type=password name="field_name_{$section.id}" size="{$section.field_size}" value="">
			
			{elseif $section.field_type eq 'select'}
				{list name=field_name_`$section.id` key=$section.list_data}
			
			{elseif $section.field_type eq 'checkbox'}
				<input type=checkbox name="field_name_{$section.id}" value="checked">
			
		  {elseif $section.field_type eq 'user_groups'}
		      <select name="field_name_{$section.id}">	
				{html_options values=$groupIds output=$groupTitles}
			  </select>

		   {elseif $section.field_type eq 'modcat'}
		      <select name="field_name_{$section.id}">	
				{html_options options=$categories}
			  </select>
			
		  {elseif $section.field_type eq 'page_section'}
		  
		  </td></tr><tr><td colspan=2 width="100%">
		  
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
			    {html_select_date prefix=field_name_`$section.id`_ day_value_format="%02d" time=$time start_year="-106" end_year="+10" time=0000-00-00 day_extra='><option value="00" selected>Day</option' month_extra='><option value="00" selected>Month</option' year_extra='><option value="0000" selected>Year</option'}
			{elseif $section.field_type eq 'image' or $section.field_type eq 'file'}
			    <input type='file' size=30 name="field_name_{$section.id}">
			{/if}
			
			{if $section.allow_case} <input type="checkbox" name="case_sen_{$section.id}" value=1> Case sensitive{/if}
			
			</td>
		</tr>
	{/foreach}
	
	<tr><td colspan="2">

	
	<input type=hidden name=endFormFields value=1>
	
	<div align='{$formSettings.buttons_alignment|default:"center"}'><input type=submit value="{$formSettings.submit_caption|default:'Submit'}" onClick="javascript: return validate();">
	{if $formSettings.show_reset eq 'yes'}<input type=reset value="{$formSettings.reset_caption|default:'Reset'}">{/if}</div>
	
	<input type=hidden name=form_id value="{$form_id}">
	<input type=hidden name=es_form_continuation value="{$es_form_continuation}">
	</td></tr>
	
	
	</table></td></tr>
	

</table>

</form>

{literal}
<script type="text/javascript">

// autofocus to the first field of any form

var formObj = document.forms[0];
var formElements = formObj.elements;
var numElements = formElements.length;

for ( var i = 0; i < numElements; i++ ) {
	if ( formElements[i].type != 'hidden' ) {
		formElements[i].focus();
		break;
	}
}
</script>
{/literal}

{* if this form is a poll, then it's a popup window, so we should force focus to the front *}

{if $poll}
<script language="Javascript">
window.focus();
</script>
{/if}