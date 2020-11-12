{include file=$navigation}

<br />
<span class=subtitle>Permissions</span>
{if $forTitle}<br /><b><span class=normal>for <font color=red>{$forTitle}</font></span></b>{/if}
<br /><br />

<table class=normal>

<tr><td colspan=2>
Here you can set permissions for the image gallery module for any existing group or user.
<br />
</td></tr>

<tr>
    {if $groups }
    <td>Set permissions for group:</td>
    <td>
        <form action=permissions.php method=post>
        <select name=groupID>
        {html_options options=$groups}
        </select>
        <input type=submit name=go value=' Go '>
        </form>
    {/if}
    </td>
</tr><tr>
    <td>Set permissions for user:</td>
    <td><form action=permissions.php method=post>
        <select name=userID>
        {html_options options=$users}
        </select>&nbsp;
        <input type=submit name=go value=' Go '>
        </form>
   </td>

<tr>
</table>
{if $showPermissions}
    {assign var=resourceExists value=false}
    <form action=permissions.php method=post>
    <table class=normal>
    {foreach item=resourceItem from=$permissions}
        <tr>

        <td valign=top align=left>
        {section name=indent loop=$resourceItem.indent}
        &nbsp;&nbsp;&nbsp;&nbsp;
        {/section}

        <input type=checkbox value=1 name={$resourceItem.id}
        {if $resourceItem.restricted eq 1}checked{/if}>

            {$resourceItem.title|default:"(no title)"}
        </td>
        </tr>

        {assign var=resourceExists value=true}
    {/foreach}
        <tr>
        <td colspan=2><input type=submit name=updatePermissions value=' Update '></td>
        </tr>

    {if $resourceExists eq false}
    <tr><td>(none currently available)</td></tr>
    {/if}
    </table>

    <input type=hidden name=userID value='{$userID}'>
    <input type=hidden name=groupID value='{$groupID}'>

    </form>
{/if}
