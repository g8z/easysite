
<script type="text/javascript" src="{$docroot}manage/popupCat/pickCategory.js"></script>

{literal}
<script language="Javascript">
<!--
function del( theKillButton ) {
    var theForm = document.mainForm;
    if ( confirm( "Are you SURE?? This will permanently delete this menu item AND ALL ITS DECENDENTS!" ) ) {
        theForm.killid.value = theKillButton.name;
        theForm.submit();
    }
}
function jumpMenu() {
    document.mainForm.menu_id.value = document.mainForm.currentMenuId.value;
    document.mainForm.submit();
}
function editMenuSettings() {
    document.mainForm.action = 'editSettings.php';
    document.mainForm.menu_id.value = document.mainForm.currentMenuId.value;
    
    document.mainForm.submit();
}

function removeOption( combo, index ) {
    var i=0;
    for (i=index; i<combo.options.length-1; i++) {
        combo.options[i] = combo.options[i+1];
    }
    combo.options.length--;
}

function testURL(combo) {

    // Test for module category

    var modcat = combo.options[combo.selectedIndex].value.match(/^modcat$/);

    if ( modcat )
        C.popup( combo, combo.options[combo.selectedIndex] );

    // we do not need to execute until category is not selected
    if ( modcat && C.title.length==0 )
        return;

    var url = combo.options[combo.selectedIndex].value.match(/^url$/);    
    var frmurl = combo.options[combo.selectedIndex].value.match(/^frmurl$/);

    if ( url || frmurl || modcat ) {
        var i=0;
        var index = combo.options.length;
        var oldURL = '';
        
        for ( i =0; i < combo.options.length; i++) {
            if ( combo.options[i].value.match(/^(url|frmurl|modcat)(.+)$/) ) {
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
    else if ( frmurl ) {
        promptText = "You have requested to embed this URL within an iframe (inline frame). Please specify the full URL path here (http://...):";
        
        urltype = 'frm url';
        urltype2 = 'frmurl';
    }
    else if ( modcat ) {
        promptText = "You have requested to link this menu item to a URL which will be embedded in your website design.  Please specify the full URL path here (http://...):";
        
        urltype = 'mod cat';
        urltype2 = 'modcat';
    }
    
    if ( oldURL == 'url' || oldURL == 'frmurl' || oldURL == 'modcat' )
        oldURL = '';
    
    if ( !modcat )
        var url = prompt( promptText, oldURL );
    else {
        url = C.value;
        var temp = C.title;
        title = (temp.length > 25) ? temp.substr( 0, 22 ) + '...' : temp;

        C.title = '';
        C.value = '';
    }

    // action cancelled by user
    if ( url == null )
        return;

    while( url != null & !isValidURL( url ) && !isValidEmail( url ) && !modcat ) {
        url = prompt( "The URL you have specified contains invalid syntax. Please try again.", url );
    }

    if ( url != null ) {
        if (index == combo.options.length)
            combo.options.length++;
        
        if ( !modcat )
            var title = (url.length > 25) ? url.substr( 0, 22 ) + '...' : url;

        // add this new url to our <select> list
        if ( !modcat )
            combo.options[index] = new Option( urltype + ' - ' + title, urltype2 + url );
        else
            combo.options[index] = new Option( title, urltype2 + url );

        combo.options.selectedIndex = index;

	document.getElementById ("mainForm").submit ();
    }
    else
        combo.options.selectedIndex = 0;
        }
}

//-->
</script>
{/literal}


<form id="mainForm" name=mainForm method=post action=editMenu.php>
<input type=hidden name=killid value=''>

<table border=0 cellspacing=0 cellpadding=1 class=normal width=100%>

<tr><td colspan=2>{$logoutLink} {$pathway}</td></tr>

<tr><td colspan=2>&nbsp;</td></tr>

<tr><td colspan=2 class=title>Menu Manager</td></tr>

<tr>
<td nowrap class=specialOperations>

    <table border=0 cellpadding=1 width=100% cellspacing=0 class=normal>
    <tr>
        <td width=10% nowrap>Current Menu: </td>

        <td>
        <select name=currentMenuId onChange="jumpMenu();">
        
        {if $menu_id > 0}
        {html_options values=$menuIds output=$menuTitles selected=$menu_id}
        {else}
        <option value="">(no menus defined)</option>
        {/if}
        
        </select>
        </td>
    </tr>
    {if $permissions.add}
    <tr>
        <td nowrap>
        Add New Menu: 
        </td>
        
        <td>
        <input type=text name=newMenuName size=30> 
        <input type=submit name=addMenu value="Add">
        </td>
    
    </tr>
    {/if}
    
    {* check to ensure that at least one menu is loaded *}
    {if $menu_id > 0}
    <tr>
        {if $permissions.edit_settings}
        <td>
        <input type=button name=editSettings value="Edit Settings" onClick="return editMenuSettings();">
        </td>
        {else}
        &nbsp;
        {/if}
        
        <td>
        {if $permissions.delete}
        <input type=submit name=deleteMenu value="Delete Menu" onClick="return confirm('Are you SURE?? This will permanently delete ALL LEVELS of this menu!')">
        {else}
        &nbsp;
        {/if}
        </td>
    </tr>
    {/if}
    {* end of check to ensure that at least one menu is loaded *}
    
    </table>
</td>
</tr>

<tr><td colspan=2>&nbsp;</td></tr>

{* check to ensure that at least one menu is loaded *}
{if $menu_id > 0 and $permissions.edit_structure}

<tr><td colspan=2 class=subtitle>Menu Content & Structure 

<a href="javascript:launchCentered('{$help.url}?type=menu_manage',{$help.width},{$help.height},'{$help.options}');">{$helpSymbol}</a>

</td></tr>

<tr><td colspan=2><span class=normal>Use the <img border=0 src="{$docroot}images/editor.gif" alt="Advanced Menu Editor (Standard Menu Only)"> image to edit special options for single menu items, such as background image, style, and dimensions. To edit global menu properties, like colors and positioning, click "Edit Settings" above. Please note that double-quotes (") should not be used, although single-quotes are allowed.</span></td></tr>

<tr><td colspan=2>&nbsp;</td></tr>

<tr><td colspan=2>
    <table border=0 width=100% cellpadding=1 cellspacing=0>

    {foreach from=$lines_array item=aLine}
        <tr>
        {foreach from=$aLine item=aCell}
            {if $aCell == ""}
                <td></td>
            {else}
                <td class=normal nowrap>
                <table cellpadding=0 cellspacing=0 border=0>
                <tr>
        <td nowrap class=normal>
            {if $aCell.hidden}
                HIDDEN: 
            {/if}
            <input type=text size=15  name="title_{$aCell.id}" value="{$aCell.title}">
            {if !$aCell.noedit}
                <input onClick="javascript:del( this );" type=button name=kill_{$aCell.id} value=" x ">
            {/if}
            {if $aCell.higherItemExists}
                <input type=submit name=bump_{$aCell.id} value=" ^ ">
            {/if}
            {if !$aCell.noedit}
                {if $aCell.level > $startLevel}
                <input type=submit name=shift_{$aCell.id} value=" < ">
                {/if}
                {if !$aCell.first}
                <input type=submit name=unshift_{$aCell.id}  value=" > ">
                {/if}
                <input type=submit name=insert_{$aCell.id}  value=" * ">
            {/if}
                </td></tr>
                <tr><td>
                
            <table border=0 cellpadding=0 cellspacing=0>
            <tr><td nowrap>
            <small>
                &nbsp;&nbsp;Link to: 
                <select name=link_{$aCell.id} style="font-size:9px" onchange="javascript: testURL(this);">
                {html_options options=$allObjects[$aCell.id] selected=$aCell.linkedResource}
                </select>
            </small>
            </td><td>
            
            &nbsp;<a href="advancedMenuEditor.php?id={$aCell.id}"><img border=0 src="{$docroot}images/editor.gif" alt="Advanced Menu Editor (Standard Menu Only)"></a>
            
            {*
            <a href="javascript:launchCentered('advancedMenuEditor.php?id={$aCell.id}',450,500,'resizable,scrollbars');"><img border=0 src="{$docroot}images/editor.gif" alt="Advanced Menu Editor"></a>
            *}
            
            </font>
            </td></tr>
            </table>
                
                </td>
                </tr>
                </table>
            {/if}
        {/foreach}
        </tr>
    {/foreach}
	<tr>
		<td>
			<input type=hidden name=formIsSubmitted value="1">
			<input type=hidden name=noedit value="{$noedit}">
			<input type=hidden name=levelLimit value="{$levelLimit}">
			<input type=hidden name=startid value="{$startid}">
			<input type=hidden name=menu_id value="{$menu_id}">
		</td>
	</tr>
    
    {if $found}
        <tr><td colspan={$span}><input type=submit name=ok value="Update All Items"></td></tr>
    {else}
        <tr><td colspan={$span}><input type=submit name=insert_ value="Add The First Row"></td></tr>
    {/if}

    </table>
</td></tr>

{else}

<tr><td colspan=2>There are no menus defined at the present time. Please use the form above to create your first menu. Then, return to this tool to edit the content, structure, and settings of your new menu.</td></tr>

{/if}

<tr><td colspan=2>&nbsp;</td></tr>

{* end of check to ensure that at least one menu is loaded *}

<tr><td colspan=2>{$adminReturnLink} {$logoutLink} {$userGuideLink}</td></tr>

</table>
</form>
