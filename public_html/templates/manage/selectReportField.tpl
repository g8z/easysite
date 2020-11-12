{* special js functions for this page only *}
{literal}
<script language="Javascript">
<!--

function sendValues( title ) {
    
    opener.field.value += '{' + title + '}';
}


// -->
</script>
{/literal}

<table cellpadding="0" cellspacing="0" border="0" class="normal">

<tr><td class="title">Select Field</td></tr>
<tr><td class="subtitle">of report named "{$report.name}"</td></tr>

<tr><td>&nbsp;</td></tr>

<tr><td>Click on any field below to add it's name to the target. When report will be displayed the content of this field will be outputted instead of name. You can add as much fields as you like. Click on 'Close Window' when finished. </td></tr>

<tr><td>&nbsp;</td></tr>

<tr><td class="subtitle">Available Fields (Clickable)</td></tr>
{foreach name=sections item=section from=$reportFields}
    <tr><td><a href="#" onclick="javascript: sendValues( '{$section|addslashes}' ); return false;">{ldelim}{$section}{rdelim}</a></td></tr>
{foreachelse}
    <tr><td>No fields currently present in this report.</td></tr>
{/foreach}

<tr><td>&nbsp;</td></tr>

<tr><td><input type=button name=close_button value='Close Window' onclick="javascript: window.close();"></td></tr>

</table>