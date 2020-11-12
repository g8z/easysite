{* special js functions for this page only *}
{literal}
<script language="Javascript">
<!--

function submitForm() {
	var theForm = document.editMan;
	
	if ( theForm.man_name.value == '' ) {
		alert( 'Please enter manufacturer name' );
		return false;
	}
	
	theForm.formIsSubmitted.value=1;
	theForm.submit();
	
	return true;
}

function changeMan( value ) {
	
	document.location.href='manufacturers.php?man_id='+value;
	
}

//-->
</script>
{/literal}


{include file=modules/gallery/navigation.tpl}

<br />
<form action=manufacturers.php method="POST" enctype="multipart/form-data" name="editMan">
<input type=hidden name=formIsSubmitted value=0>

<table class=normal cellspacing="2" cellpadding="2">

<tr><td class="subtitle" colspan=2>{if $man.id}Edit{else}Add{/if} Manufacturer</td></tr>
<tr><td colspan=2>&nbsp;</td></tr>

<tr><td>Select Manufacturer</td><td><select name="man_id" onchange="javascript: changeMan( this.value );">{html_options values=$manValues output=$manTitles selected=$man.id}</select></td></tr>
<tr><td align=right>Name</td><td><input type=text name="man_name" value={$man.name}></td></tr>
<tr><td align=right>URL</td><td><input type=text name="man_url" value={$man.url}></td></tr>
<tr><td align=right valign=top>Logo</td><td><input type=file name="man_logo">{if $man.logo}<br /><br />Current Logo:<br /><img src="{imgsrc table=$smarty.const.MANUFACTURERS_TABLE field=logo id=$man.id}">{/if}</td></tr>

<tr><td colspan=2>&nbsp;</td></tr>
<tr><td colspan=2>
    <input type=submit name=save value="Save" onClick="javascript:submitForm();">
    
    {if $man.id}
    <input type=submit name=delete value="Delete" onclick="javascript: return confirm( 'Are you sure want to delete this manufacturer?' );">
    {/if}
    
    <input type=reset value="Reset All">
</td></tr>
        
</table>
</form>