<table border=0 cellpadding=2 cellspacing=2 class=normal>

<tr><td colspan=2>{$logoutLink} {$pathway}</td></tr>
<tr><td colspan=2>&nbsp;</td></tr>

<tr><td class=title colspan=2>Modules</td></tr>

<tr><td colspan=2>

<table border=0 class=normal cellpadding=1 cellspacing=0>
<form action="" method=post>
<tr><td colspan=2>Make the selected module the default page for this website:</td></tr>

<tr><td colspan=2 nowrap>

<select name=isDefault>
<option value=''>(none)</option>
{html_options options=$moduleList selected=$defaultModuleId}
</select> 
<input type=submit value=Ok>

<a href="javascript:launchCentered('{$help.url}?type=default_page',{$help.width},{$help.height},'{$help.options}');">{$helpSymbol}</a>

</td>
</tr>

</form>
</table>

</td></tr>

<form action='' method=post>

{foreach item=module from=$modules}

<tr>
    <td width=5%><img src="{$module.logo}"></td>
    <td valign=top>
        <table cellpadding=1 cellspacing=0 border=0 class=normal>
            <tr><td class=subtitle>{$module.title} {$module.version}</td></tr>
            
            <tr><td>
            
            	<a target=_blank href="{$docroot}modules/{$module.module_key}/index.php">View</a> |
            
            	<a href="{$docroot}modules/{$module.module_key}/manage/index.php">Manage</a>
            	
            </td></tr>
            
            <tr><td>Author(s): {$module.author}</td></tr>
            <tr><td>Skin: 
            
            <select name=module_skin_{$module.id}>
            {if $useDefaultSkin}
            {* determines whether the 'use default' option is available *}
            <option value='0'>{$useDefaultSkin}</option>
            {/if}
            {html_options options=$skins selected=$module.skin_id}
            </select>
            
            <input type=submit name="submit_{$module.id}" value="Ok">
            
            <a href="javascript:launchCentered('{$help.url}?type=skins',{$help.width},{$help.height},'{$help.options}');">{$helpSymbol}</a>
            
            </td></tr>
        </table>
    </td>
</tr>
{assign var=hasOneModuleAccess value=1}

{foreachelse}
<tr><td colspan=2>There are no modules currently installed.</td></tr>
{/foreach}

</form>

{if $hasOneModuleAccess ne 1}
<tr><td colspan=2>You have not been granted access to any current modules. Please contact your system administrator if you feel that you should have access to one or more modules.</td></tr>
{/if}

</table>